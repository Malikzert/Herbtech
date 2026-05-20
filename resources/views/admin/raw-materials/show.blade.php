@extends('layouts.admin')

@section('title', 'Detail Bahan Baku')
@section('header', 'Detail Bahan Baku')

@section('content')
<div class="max-w-2xl">
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="px-6 py-4 border-b border-white/30 bg-emerald-800">
            <h3 class="font-bold text-white text-shadow-sm">{{ $raw_material->name }}</h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">ID Bahan</span>
                <span class="text-sm font-bold text-black">#{{ $raw_material->id }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Nama</span>
                <span class="text-sm font-bold text-black">{{ $raw_material->name }}</span>
            </div>
            @if($raw_material->sku)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">SKU</span>
                <span class="text-sm font-bold text-black font-mono">{{ $raw_material->sku }}</span>
            </div>
            @endif
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Tipe</span>
                <span class="text-sm font-bold text-black">{{ ucfirst($raw_material->type) }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Unit</span>
                <span class="text-sm font-bold text-black">{{ $raw_material->unit }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Stok Saat Ini</span>
                <span class="text-sm font-bold {{ $raw_material->current_stock < 10 ? 'text-red-700' : 'text-black' }}">{{ number_format($raw_material->current_stock, 2) }} {{ $raw_material->unit }}</span>
            </div>
            @if($raw_material->min_stock_level)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Min Stok Level</span>
                <span class="text-sm font-bold text-black">{{ $raw_material->min_stock_level }}</span>
            </div>
            @endif
            @if($raw_material->supplier)
            <div class="flex justify-between py-3 border-b border-white/20">
                <span class="text-sm font-medium text-black">Supplier</span>
                <span class="text-sm font-bold text-black">{{ $raw_material->supplier }}</span>
            </div>
            @endif
            <div class="flex justify-between py-3">
                <span class="text-sm font-medium text-black">Dibuat</span>
                <span class="text-sm text-black">{{ $raw_material->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-white/30 bg-white/10 flex gap-3">
            <a href="{{ route('admin.raw-materials.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                Kembali
            </a>
            <a href="{{ route('admin.raw-materials.edit', $raw_material->id) }}" class="px-5 py-2.5 bg-emerald-700 text-white font-medium rounded-lg hover:bg-emerald-800 transition">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection
