@extends('layouts.admin')

@section('title', 'Detail Produk')
@section('header', 'DETAIL PRODUK')

@section('content')
<div class="max-w-2xl">
    <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md p-6 shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
        <div class="absolute inset-0 pointer-events-none opacity-5" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(5,150,105,0.3) 20px, rgba(5,150,105,0.3) 21px);"></div>

        <div class="relative z-10 space-y-4">
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">ID Produk</span>
                <span class="text-sm font-medium text-emerald-50">#{{ $product->id }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Nama</span>
                <span class="text-sm font-medium text-emerald-50">{{ $product->name }}</span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">SKU</span>
                <span class="text-sm font-medium font-mono text-emerald-50">{{ $product->sku_code }}</span>
            </div>
            @if($product->jeniss)
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Jeniss</span>
                <span class="text-sm font-medium text-emerald-50">{{ $product->jeniss }}</span>
            </div>
            @endif
            @if($product->unit)
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Unit</span>
                <span class="text-sm font-medium text-emerald-50">{{ $product->unit }}</span>
            </div>
            @endif
            @if($product->description)
            <div class="py-3 border-b border-white/10">
                <span class="block text-xs font-bold uppercase tracking-wider text-emerald-200/60 mb-2">Deskripsi</span>
                <p class="text-sm text-emerald-50/80">{{ $product->description }}</p>
            </div>
            @endif
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Dibuat</span>
                <span class="text-sm font-medium text-emerald-50">{{ $product->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        <div class="flex gap-3 mt-6 relative z-10">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-white/5 border border-white/20 text-emerald-200/70 hover:text-emerald-200 text-sm font-medium rounded-lg hover:bg-white/10 transition">
                Kembali
            </a>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                Edit
            </a>
        </div>
    </div>
</div>
@endsection
