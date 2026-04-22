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
        return view('operator.productions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'batch_number' => 'required|string|max:100|unique:productions,batch_number',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'pic_name' => 'nullable|string|max:255',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'draft';

        $production = Production::create($validated);

        return redirect()->route('operator.productions.show', $production->id)
            ->with('success', 'Produksi berhasil dibuat.');
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