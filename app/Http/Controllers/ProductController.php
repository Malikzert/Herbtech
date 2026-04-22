<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        
        $query = Product::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku_code', 'like', "%{$search}%");
            });
        }
        
        if ($category) {
            $query->where('category', $category);
        }
        
        $products = $query->latest()->paginate(10)->appends($request->query());
        $categories = Product::distinct()->pluck('category')->filter()->values();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku_code' => 'required|string|max:100|unique:products',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dibuat.');
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku_code' => ['required', 'string', 'max:100', Rule::unique('products')->ignore($product->id)],
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.show', $product->id)
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}