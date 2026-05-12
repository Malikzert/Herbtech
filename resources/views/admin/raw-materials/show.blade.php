@extends('layouts.admin')

@section('title', 'Detail Bahan Baku')
@section('header', 'Detail Bahan Baku')

@section('content')
<div class="max-w-2xl">
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="px-6 py-4 border-b border-white/30 bg-emerald-800">
            <h3 class="font-bold text-white text-shadow-sm">{{ $rawMaterial->name }}</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">ID Bahan</span>
                <span class="text-sm font-bold text-black">#{{ $rawMaterial->id }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Nama</span>
                <span class="text-sm font-bold text-black">{{ $rawMaterial->name }}</span>
            </div>
            @if($rawMaterial->sku)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">SKU</span>
                <span class="text-sm font-bold text-black font-mono">{{ $rawMaterial->sku }}</span>
            </div>
            @endif
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Tipe</span>
                <span class="text-sm font-bold text-black">{{ ucfirst($rawMaterial->type) }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Unit</span>
                <span class="text-sm font-bold text-black">{{ $rawMaterial->unit }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Stok Saat Ini</span>
                <span class="text-sm font-bold {{ $rawMaterial->current_stock < 10 ? 'text-red-700' : 'text-black' }}">{{ number_format($rawMaterial->current_stock, 2) }} {{ $rawMaterial->unit }}</span>
            </div>
            @if($rawMaterial->min_stock_level)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Min Stok Level</span>
                <span class="text-sm font-bold text-black">{{ $rawMaterial->min_stock_level }}</span>
            </div>
            @endif
            @if($rawMaterial->supplier)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Supplier</span>
                <span class="text-sm font-bold text-black">{{ $rawMaterial->supplier }}</span>
            </div>
            @endif
            <div class="flex justify-between py-3">
                <span class="text-sm font-medium text-black">Dibuat</span>
                <span class="text-sm text-black">{{ $rawMaterial->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-white/30 bg-white/10 flex gap-3">
            <a href="{{ route('admin.raw-materials.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                Kembali
            </a>
            <a href="{{ route('admin.raw-materials.edit', $rawMaterial->id) }}" class="px-5 py-2.5 bg-emerald-700 text-white font-medium rounded-lg hover:bg-emerald-800 transition">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection
