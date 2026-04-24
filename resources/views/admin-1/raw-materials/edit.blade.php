@extends('layouts.admin')

@section('title', 'Edit Bahan Baku')
@section('header', 'Edit Bahan Baku')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.raw-materials.update', $rawMaterial->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                <input type="text" name="name" value="{{ old('name', $rawMaterial->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $rawMaterial->sku) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <input type="text" name="unit" value="{{ old('unit', $rawMaterial->unit) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                <input type="number" name="current_stock" value="{{ old('current_stock', $rawMaterial->current_stock) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batas Stok Minimum</label>
                <input type="number" name="min_stock_level" value="{{ old('min_stock_level', $rawMaterial->min_stock_level) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                <input type="text" name="supplier" value="{{ old('supplier', $rawMaterial->supplier) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    Perbarui
                </button>
                <a href="{{ route('admin.raw-materials.index') }}" class="px-6 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection