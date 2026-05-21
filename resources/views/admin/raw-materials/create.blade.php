@extends('layouts.admin')

@section('title', 'Tambah Bahan Baku')
@section('header', 'Tambah Bahan Baku')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.raw-materials.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium black mb-1">Nama Bahan</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium black mb-1">SKU</label>
                <input type="text" value="Auto-generated (RM-xxxxxx)" readonly disabled
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-medium black mb-1">Unit</label>
                <input type="text" name="unit" value="{{ old('unit') }}" required placeholder="cth: kg, gram, pcs"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium black mb-1">Stok Saat Ini</label>
                <input type="number" name="current_stock" value="{{ old('current_stock', 0) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium black mb-1">Batas Stok Minimum</label>
                <input type="number" name="min_stock_level" value="{{ old('min_stock_level', 10) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div>
                <label class="block text-sm font-medium black mb-1">Supplier</label>
                <input type="text" name="supplier" value="{{ old('supplier') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.raw-materials.index') }}" class="px-6 py-2 bg-gray-100 black text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection