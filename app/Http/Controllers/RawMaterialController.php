<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RawMaterialController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $type = $request->get('type');
        
        $query = RawMaterial::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($type) {
            $query->where('type', $type);
        }
        
        $rawMaterials = $query->latest()->paginate(10)->appends($request->query());
        
        return view('admin.raw-materials.index', compact('rawMaterials'));
    }

    public function create()
    {
        return view('admin.raw-materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:raw_materials',
            'type' => 'required|in:herbal,packaging,additive',
            'unit' => 'required|string|max:50',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        RawMaterial::create($validated);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dibuat.');
    }

    public function show(string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        return view('admin.raw-materials.show', compact('rawMaterial'));
    }

    public function edit(string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        return view('admin.raw-materials.edit', compact('rawMaterial'));
    }

    public function update(Request $request, string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['nullable', 'string', 'max:100', Rule::unique('raw_materials')->ignore($rawMaterial->id)],
            'type' => 'required|in:herbal,packaging,additive',
            'unit' => 'required|string|max:50',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $rawMaterial->update($validated);

        return redirect()->route('admin.raw-materials.show', $rawMaterial->id)
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $rawMaterial = RawMaterial::findOrFail($id);
        $rawMaterial->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}