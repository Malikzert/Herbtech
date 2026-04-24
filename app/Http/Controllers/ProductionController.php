<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Production::with('product', 'user');
        
        if (!auth()->user()->can('admin')) {
            $query->where('user_id', auth()->id());
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }
        
        $productions = $query->latest()->paginate(10)->appends($request->query());
        
        $view = auth()->user()->can('admin') ? 'admin.productions.index' : 'operator.productions.index';
        return view($view, compact('productions'));
    }

    public function create()
    {
        $products = Product::all();
        $rawMaterials = \App\Models\RawMaterial::all();
        return view('operator.productions.create', compact('products', 'rawMaterials'));
    }

    public function store(Request $request)
    {
        if ($request->has('materials')) {
            $materials = array_filter($request->input('materials'), function ($mat) {
                return !empty($mat['raw_material_id']) && !empty($mat['quantity']);
            });
            $request->merge(['materials' => $materials]);
        }

        $validated = $request->validate([
            'batch_number' => 'required|string|max:100|unique:productions,batch_number',
            'product_id' => 'required|exists:products,id',
            'target_quantity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'pic_name' => 'nullable|string|max:255',
            'materials' => 'nullable|array',
            'materials.*.raw_material_id' => 'required|exists:raw_materials,id',
            'materials.*.quantity' => 'required|numeric|min:0.1',
        ]);

        $status = $request->input('action') === 'start' ? 'in_progress' : 'draft';

        $production = Production::create([
            'batch_number' => $validated['batch_number'],
            'product_id' => $validated['product_id'],
            'target_quantity' => $validated['target_quantity'],
            'start_date' => $validated['start_date'],
            'pic_name' => $validated['pic_name'],
            'user_id' => auth()->id(),
            'status' => $status,
        ]);

        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                \App\Models\ProductionMaterial::create([
                    'production_id' => $production->id,
                    'raw_material_id' => $mat['raw_material_id'],
                    'quantity_used' => $mat['quantity'],
                ]);
            }
        }

        return redirect()->route('operator.productions.show', $production->id)
            ->with('success', 'Produksi berhasil ' . ($status === 'in_progress' ? 'dimulai.' : 'disimpan sebagai draft.'));
    }

    public function show(string $id)
    {
        $production = Production::with('product', 'user', 'productionMaterials.rawMaterial', 'qualityControls')
            ->findOrFail($id);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        $view = auth()->user()->can('admin') ? 'admin.productions.show' : 'operator.productions.show';
        return view($view, compact('production'));
    }

    public function edit(string $id)
    {
        $production = Production::findOrFail($id);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($production->status, ['draft', 'in_progress'])) {
            return redirect()->route('operator.productions.index')
                ->with('error', 'Produksi tidak dapat diedit pada status ini.');
        }

        $products = Product::all();
        return view('operator.productions.edit', compact('production', 'products'));
    }

    public function update(Request $request, string $id)
    {
        $production = Production::findOrFail($id);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'batch_number' => 'required|string|max:100|unique:productions,batch_number,' . $id,
            'product_id' => 'required|exists:products,id',
            'target_quantity' => 'required|integer|min:1',
            'actual_quantity' => 'nullable|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'pic_name' => 'nullable|string|max:255',
            'status' => ['nullable', Rule::in(['draft', 'in_progress', 'qc_check', 'completed', 'cancelled'])],
        ]);

        $production->update($validated);

        return redirect()->route('operator.productions.show', $production->id)
            ->with('success', 'Produksi berhasil diperbarui.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $production = Production::findOrFail($id);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['draft', 'in_progress', 'qc_check', 'completed', 'cancelled'])],
        ]);

        if ($validated['status'] === 'in_progress' && $production->status === 'draft') {
            $production->update(['start_date' => now()]);
        }

        if ($validated['status'] === 'qc_check' && $production->status !== 'in_progress') {
            return back()->with('error', 'Produksi harus dalam status in_progress untuk QC.');
        }

        if ($validated['status'] === 'completed' && $production->status !== 'qc_check') {
            return back()->with('error', 'Produksi harus melalui QC terlebih dahulu.');
        }

        $production->update(['status' => $validated['status']]);

        if (in_array($validated['status'], ['completed', 'cancelled'])) {
            $production->update(['end_date' => now()]);
        }

        return back()->with('success', 'Status produksi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $production = Production::findOrFail($id);

        if (!auth()->user()->can('admin') && $production->user_id !== auth()->id()) {
            abort(403);
        }

        if ($production->status !== 'draft') {
            return back()->with('error', 'Hanya produksi dengan status draft yang dapat dihapus.');
        }

        $production->delete();

        return redirect()->route('operator.productions.index')
            ->with('success', 'Produksi berhasil dihapus.');
    }
}