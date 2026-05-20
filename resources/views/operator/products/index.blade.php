@extends('layouts.app')

@section('title', 'Produk')
@section('header', 'DATA PRODUK')

@section('content')
<div x-data="{ viewMode: (localStorage.getItem('adminViewMode') || 'list'),
    detailModalOpen: false, detailData: null, detailLoading: false,
    init() {
        window.addEventListener('admin-view-change', (e) => { this.viewMode = e.detail; });
    },
    openDetail(id) {
        this.detailLoading = true;
        this.detailData = null;
        this.detailModalOpen = true;
        fetch('/admin/products/' + id, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(res => {
                if (res.success) { this.detailData = res.data; }
                this.detailLoading = false;
            })
            .catch(() => { this.detailLoading = false; });
    }
}">

<div class="mb-6">
    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-4">
        <form method="GET" action="{{ route('operator.products.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                    class="w-full h-11 pl-10 pr-4 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] text-sm focus:ring-[#8B6914] focus:border-[#8B6914] focus:outline-none transition">
            </div>

            <select name="category" class="h-11 px-4 py-2 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] text-sm focus:ring-[#8B6914] focus:border-[#8B6914] focus:outline-none transition cursor-pointer">
                <option value="" class="bg-[#1a1210]">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }} class="bg-[#1a1210]">{{ $cat }}</option>
                @endforeach
            </select>

            <button type="submit" class="h-11 px-5 bg-[#8B6914] hover:bg-[#A0845C] text-white font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                Filter
            </button>

            @if(request('search') || request('category'))
            <a href="{{ route('operator.products.index') }}" class="h-11 px-5 bg-[#3d2b1f] hover:bg-[#2c1810] text-[#D4B896] font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
            @endif
        </form>
    </div>
</div>

{{-- TABLE VIEW --}}
<div x-show="viewMode === 'list'" class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#2c1810] border-b border-[#3d2b1f]">
            <tr>
                <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">No</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Nama Produk</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">SKU</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Kategori</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Unit</span></th>
                <th class="px-6 py-3.5 text-right"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#3d2b1f]">
            @forelse($products as $index => $product)
            <tr class="hover:bg-[#2c1810]/50 transition-colors duration-150">
                <td class="px-6 py-4 text-sm text-[#6B5740]">{{ $index + 1 }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-white">{{ $product->name }}</div>
                    @if($product->description)
                    <div class="text-xs text-[#6B5740] mt-0.5 line-clamp-1">{{ $product->description }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-[#D4B896] font-mono">{{ $product->sku_code }}</td>
                <td class="px-6 py-4">
                    @if($product->category)
                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">{{ $product->category }}</span>
                    @else
                    <span class="text-xs text-[#6B5740]">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-[#D4B896]">{{ $product->unit ?? '-' }}</td>
                <td class="px-6 py-4 text-right">
                    <button @click="openDetail({{ $product->id }})"
                        class="w-9 h-9 flex items-center justify-center text-[#6B5740] hover:text-[#D4B896] hover:bg-[#2c1810]/50 transition-all duration-200"
                        title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-[#3d2b1f] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="text-[#6B5740] font-medium">Belum ada produk</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
<div class="px-6 py-4 border-t border-[#3d2b1f] bg-[#2c1810]/50">
    {{ $products->links() }}
</div>
@endif
</div>

{{-- WIDGET VIEW --}}
<div x-show="viewMode === 'widget'" style="display: none;" class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#3d2b1f]">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center border border-[#3d2b1f] bg-[#2c1810]/60" style="border-radius: 0;">
                <svg class="w-4 h-4 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#F5EDE0]">Daftar Produk</h3>
                <p class="text-xs text-[#6B5740]">{{ $products->total() }} item</p>
            </div>
        </div>
    </div>
    <div class="widget-grid">
        @forelse($products as $product)
        <div class="widget-card cursor-pointer" @click="openDetail({{ $product->id }})">
            <div class="widget-card-header">
                <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/(defaultPRK).png') }}" alt="{{ $product->name }}">
                @if($product->category)
                <span class="widget-card-badge" style="background:rgba(139,105,20,0.25);color:#D4B896;border:1px solid rgba(139,105,20,0.4)">{{ $product->category }}</span>
                @endif
            </div>
            <div class="widget-card-body">
                <h3 class="widget-card-title">{{ $product->name }}</h3>
                <p class="widget-card-subtitle">{{ $product->sku_code }}</p>
                <div class="widget-card-details">
                    @if($product->unit)
                    <span class="widget-card-detail">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        {{ $product->unit }}
                    </span>
                    @endif
                    @if($product->description)
                    <span class="widget-card-detail" title="{{ $product->description }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deskripsi
                    </span>
                    @endif
                </div>
                <div class="widget-card-spacer"></div>
                <span class="widget-card-status" style="background:rgba(160,132,92,0.2);color:#F5EDE0;border:1px solid rgba(160,132,92,0.3)">
                    <span class="widget-card-status-dot" style="background:#F5EDE0"></span>Aktif
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full flex flex-col items-center py-20">
            <svg class="w-16 h-16 text-[#3d2b1f] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <p class="text-[#6B5740] font-medium">Belum ada produk</p>
        </div>
        @endforelse
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-[#3d2b1f] bg-[#2c1810]/50">
        {{ $products->links() }}
    </div>
    @endif
</div>

{{-- DETAIL MODAL --}}
<div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="detailModalOpen" @click="detailModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

        <div x-show="detailModalOpen" @click.stop
            class="relative w-full max-w-lg rounded-sm border border-[#3d2b1f] bg-[#1a1210]/95 backdrop-blur-xl shadow-[0_0_60px_rgba(0,0,0,0.5)]">
            <div class="h-[2px] bg-gradient-to-r from-[#8B6914]/60 via-[#A0845C]/30 to-transparent"></div>

            <div class="flex justify-between items-center px-6 py-4 border-b border-[#3d2b1f]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center border border-[#3d2b1f] bg-[#2c1810]/60" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#F5EDE0]">Detail Produk</h3>
                        <p class="text-xs text-[#6B5740]">Informasi lengkap produk</p>
                    </div>
                </div>
                <button @click="detailModalOpen = false" class="w-8 h-8 flex items-center justify-center text-[#6B5740] hover:text-[#D4B896] hover:bg-[#2c1810]/50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 pb-6">
                <div x-show="detailLoading" class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-[#D4B896]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <template x-if="detailData && !detailLoading">
                    <div class="space-y-5">
                        <div class="flex items-center gap-4">
                            <template x-if="detailData.image">
                                <img :src="'{{ asset('image') }}/' + detailData.image" class="w-16 h-16 object-cover border border-[#3d2b1f] rounded-sm">
                            </template>
                            <template x-if="!detailData.image">
                                <div class="w-16 h-16 flex items-center justify-center bg-[#2c1810]/60 border border-[#3d2b1f] rounded-sm">
                                    <svg class="w-8 h-8 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                            </template>
                            <div>
                                <h4 class="text-lg font-black text-[#F5EDE0]" x-text="detailData.name"></h4>
                                <p class="text-xs text-[#6B5740]" x-text="detailData.sku_code || 'Tanpa SKU'"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#3d2b1f]">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Kategori</p>
                                <p class="text-sm font-bold text-[#F5EDE0]" x-text="detailData.category || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Unit</p>
                                <p class="text-sm font-bold text-[#F5EDE0]" x-text="detailData.unit || '-'"></p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Deskripsi</p>
                            <p class="text-sm text-[#D4B896]/80" x-text="detailData.description || 'Tidak ada deskripsi'"></p>
                        </div>

                        <div class="pt-3 border-t border-[#3d2b1f]">
                            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-3">Komposisi Resep</p>
                            <template x-if="detailData.recipes && detailData.recipes.length > 0">
                                <div class="space-y-2">
                                    <template x-for="(recipe, idx) in detailData.recipes" :key="idx">
                                        <div class="flex items-center justify-between px-3 py-2 bg-[#2c1810]/60 border border-[#3d2b1f] rounded-sm">
                                            <span class="text-sm font-bold text-[#F5EDE0]" x-text="recipe.raw_material?.name || 'Unknown'"></span>
                                            <span class="text-xs font-mono text-[#6B5740]" x-text="recipe.quantity_needed + ' ' + (recipe.unit || '')"></span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!detailData.recipes || detailData.recipes.length === 0">
                                <p class="text-xs text-[#6B5740] italic">Belum ada resep untuk produk ini</p>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-[#3d2b1f]"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-[#3d2b1f]"></div>
        </div>
    </div>
</div>
</div>
@endsection
