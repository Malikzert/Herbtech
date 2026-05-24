<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Product;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $productId = $request->get('product_id');
        
        $query = Recipe::with('product', 'rawMaterial');
        
        if ($search) {
            $query->whereHas('rawMaterial', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        if ($productId) {
            $query->where('product_id', $productId);
        }
        
        $recipes = $query->latest()->paginate(10)->appends($request->query());
        $products = Product::all();
        $rawMaterials = RawMaterial::where('is_active', true)->get();
        
        return view('admin.recipes.index', compact('recipes', 'products', 'rawMaterials'));
    }

    public function getByProduct($productId)
    {
        $recipes = Recipe::with('rawMaterial')->where('product_id', $productId)->get();
        return response()->json($recipes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity_needed' => 'required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'raw_material_id.required' => 'Bahan baku wajib dipilih.',
            'raw_material_id.exists' => 'Bahan baku yang dipilih tidak ditemukan.',
            'quantity_needed.required' => 'Jumlah kebutuhan bahan baku wajib diisi.',
            'quantity_needed.numeric' => 'Jumlah kebutuhan harus berupa angka.',
            'quantity_needed.min' => 'Jumlah kebutuhan minimal 0.01.',
            'unit.max' => 'Satuan maksimal 50 karakter.',
        ]);

        $existing = Recipe::where('product_id', $validated['product_id'])
            ->where('raw_material_id', $validated['raw_material_id'])
            ->first();

        if ($existing) {
            $existing->update($validated);
            return back()->with('success', 'Resep berhasil diperbarui.');
        }

        Recipe::create($validated);

        return back()->with('success', 'Resep berhasil ditambahkan.');
    }

    public function storeBatch(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|numeric|min:0.01',
        ], [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',
            'quantities.required' => 'Data jumlah kebutuhan bahan baku wajib diisi.',
            'quantities.array' => 'Format data bahan baku tidak valid.',
            'quantities.*.numeric' => 'Jumlah kebutuhan setiap bahan baku harus berupa angka.',
            'quantities.*.min' => 'Jumlah kebutuhan setiap bahan baku minimal 0.01.',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantities = $validated['quantities'];
        
        foreach ($quantities as $rawMaterialId => $quantity) {
            if ($quantity === null || $quantity === '' || $quantity <= 0) {
                continue;
            }

            $rawMaterial = RawMaterial::find($rawMaterialId);
            if (!$rawMaterial) {
                continue;
            }

            Recipe::updateOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'raw_material_id' => $rawMaterialId,
                ],
                [
                    'quantity_needed' => $quantity,
                    'unit' => $rawMaterial->unit ?? 'gram',
                ]
            );
        }

        return back()->with('success', 'Resep untuk ' . $product->name . ' berhasil disimpan.');
    }

    public function update(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'quantity_needed' => 'required|numeric|min:0.01',
            'unit' => 'nullable|string|max:50',
        ]);

        $recipe->update($validated);

        return back()->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();

        return back()->with('success', 'Resep berhasil dihapus.');
    }
}