<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RawMaterialQc;
use Illuminate\Http\Request;

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
        
        $view = auth()->user()->can('admin') ? 'admin.raw-materials.index' : 'operator.raw-materials.index';
        return view($view, compact('rawMaterials'));
    }

    public function create()
    {
        return view('admin.raw-materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:herbal,packaging,additive',
            'unit' => 'required|string|max:50',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Nama bahan baku wajib diisi.',
            'name.max' => 'Nama bahan baku maksimal 255 karakter.',
            'type.required' => 'Jenis bahan baku (Herbal/Packaging/Additive) wajib dipilih.',
            'type.in' => 'Jenis bahan baku tidak valid. Pilih Herbal, Packaging, atau Additive.',
            'unit.required' => 'Satuan bahan baku wajib diisi (contoh: kg, gram, liter).',
            'unit.max' => 'Satuan bahan baku maksimal 50 karakter.',
            'current_stock.integer' => 'Jumlah stok harus berupa angka bulat.',
            'current_stock.min' => 'Jumlah stok tidak boleh negatif.',
            'min_stock_level.integer' => 'Batas minimum stok harus berupa angka bulat.',
            'min_stock_level.min' => 'Batas minimum stok tidak boleh negatif.',
            'supplier.max' => 'Nama supplier maksimal 255 karakter.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $filename);
            $validated['image'] = $filename;
        }

        $rawMaterial = RawMaterial::create($validated);

        RawMaterialQc::create([
            'raw_material_id' => $rawMaterial->id,
            'user_id' => auth()->id(),
            'total_qty_checked' => 0,
            'good_qty' => 0,
            'bad_qty' => 0,
            'qc_percentage' => 0,
            'status' => 'waiting',
        ]);

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dibuat.');
    }

    public function show(RawMaterial $raw_material)
    {
        if (request()->wantsJson()) {
            $raw_material->loadMissing('productionMaterials.rawMaterial', 'recipes');
            return response()->json([
                'success' => true,
                'data' => $raw_material->append('stock_status'),
            ]);
        }

        return view('admin.raw-materials.show', compact('raw_material'));
    }

    public function edit(RawMaterial $raw_material)
    {
        return view('admin.raw-materials.edit', compact('raw_material'));
    }

    public function update(Request $request, RawMaterial $raw_material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:herbal,packaging,additive',
            'unit' => 'required|string|max:50',
            'current_stock' => 'nullable|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'supplier' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required' => 'Nama bahan baku wajib diisi.',
            'name.max' => 'Nama bahan baku maksimal 255 karakter.',
            'type.required' => 'Jenis bahan baku (Herbal/Packaging/Additive) wajib dipilih.',
            'type.in' => 'Jenis bahan baku tidak valid. Pilih Herbal, Packaging, atau Additive.',
            'unit.required' => 'Satuan bahan baku wajib diisi (contoh: kg, gram, liter).',
            'unit.max' => 'Satuan bahan baku maksimal 50 karakter.',
            'current_stock.integer' => 'Jumlah stok harus berupa angka bulat.',
            'current_stock.min' => 'Jumlah stok tidak boleh negatif.',
            'min_stock_level.integer' => 'Batas minimum stok harus berupa angka bulat.',
            'min_stock_level.min' => 'Batas minimum stok tidak boleh negatif.',
            'supplier.max' => 'Nama supplier maksimal 255 karakter.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('image')) {
            if ($raw_material->image && file_exists(public_path('image/' . $raw_material->image))) {
                @unlink(public_path('image/' . $raw_material->image));
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('image'), $filename);
            $validated['image'] = $filename;
        }

        $raw_material->update($validated);

        return redirect()->route('admin.raw-materials.show', $raw_material->id)
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(RawMaterial $raw_material)
    {
        $raw_material->delete();

        return redirect()->route('admin.raw-materials.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}