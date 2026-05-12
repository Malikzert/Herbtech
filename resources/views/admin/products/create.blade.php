@extends('layouts.admin')

@section('title', 'Tambah Produk')
@section('header', 'TAMBAH PRODUK')

@section('content')
<div class="max-w-2xl">
    <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md p-6 shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
        <div class="absolute inset-0 pointer-events-none opacity-5" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(5,150,105,0.3) 20px, rgba(5,150,105,0.3) 21px);"></div>

        <form action="{{ route('admin.products.store') }}" method="POST" class="relative z-10 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">SKU</label>
                <input type="text" name="sku_code" value="{{ old('sku_code') }}" required
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 font-mono placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}"
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Unit</label>
                <input type="text" name="unit" value="{{ old('unit') }}" required placeholder="cth: pcs, box, pack"
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                    Simpan
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-white/5 border border-white/20 text-emerald-200/70 hover:text-emerald-200 text-sm font-medium rounded-lg hover:bg-white/10 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
