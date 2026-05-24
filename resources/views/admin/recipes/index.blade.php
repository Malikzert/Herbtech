@extends('layouts.admin')

@section('title', 'Kelola Resep')
@section('header', 'RESEP')

@section('content')
<style>
    .hybrid-input {
        background: rgba(6, 78, 59, 0.6);
        border: 1.5px solid rgba(5, 150, 105, 0.25);
        color: #fff;
        transition: all 0.2s ease;
    }
    .hybrid-input:focus {
        border-color: #10B981;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.2);
        outline: none;
    }
    .hybrid-input::placeholder {
        color: rgba(255,255,255,0.3);
    }
    .hybrid-table thead {
        background: rgba(5, 150, 105, 0.15);
    }
    .hybrid-table thead th {
        color: #34D399;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
    }
    .hybrid-table tbody tr {
        border-bottom: 1px solid rgba(5, 150, 105, 0.08);
        transition: all 0.2s ease;
    }
    .hybrid-table tbody tr:hover {
        background: rgba(5, 150, 105, 0.05);
    }
    .hybrid-btn {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border: none;
        color: #fff;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
    }
    .hybrid-btn:hover {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        box-shadow: 0 0 20px rgba(5, 150, 105, 0.4);
        transform: translateY(-1px);
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(6, 78, 59, 0.3); }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
</style>

<div x-data="{ 
    showModal: false, 
    selectedProductId: '',
    selectedProductName: '',
    rawMaterials: {{ Js::from(\App\Models\RawMaterial::where('is_active', true)->get()->map(fn($rm) => ['id' => $rm->id, 'name' => $rm->name, 'sku' => $rm->sku, 'unit' => $rm->unit])) }},
    recipeData: {},
    async loadRecipes() {
        if(this.selectedProductId) {
            const response = await fetch(`/admin/recipes/by-product/${this.selectedProductId}`);
            const recipes = await response.json();
            this.recipeData = {};
            recipes.forEach(r => {
                this.recipeData[r.raw_material_id] = {
                    checked: true,
                    quantity_needed: r.quantity_needed,
                    unit: r.unit
                };
            });
        } else {
            this.recipeData = {};
        }
    },
    toggleMaterial(materialId) {
        if(!this.recipeData[materialId]) {
            this.recipeData[materialId] = { checked: true, quantity_needed: '', unit: materialId.unit };
        } else {
            this.recipeData[materialId].checked = !this.recipeData[materialId].checked;
            if(this.recipeData[materialId].checked && !this.recipeData[materialId].quantity_needed) {
                this.recipeData[materialId].quantity_needed = '';
            }
        }
    }
}">

    {{-- STAT ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Produk</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\Product::count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Resep Tersimpan</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\Recipe::count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Bahan Baku</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterial::count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/30 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Filter & Pencarian</h3>
                        <p class="text-xs text-emerald-200/40">Cari produk dan kelola resep</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.recipes.index') }}" class="flex flex-wrap gap-3 items-center">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                            class="hybrid-input w-full h-11 pl-10 pr-4 rounded-sm text-sm">
                    </div>

                    <button type="submit" class="h-11 px-6 rounded-sm text-xs font-bold uppercase tracking-wider flex items-center gap-2" 
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                        Filter
                    </button>

                    @if(request('search'))
                    <a href="{{ route('admin.recipes.index') }}" class="h-11 px-5 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-700/40 hover:border-emerald-500/50 rounded-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reset
                    </a>
                    @endif
                </form>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- PRODUCT RECIPE LIST --}}
    <div class="space-y-4">
        @php
            $searchQuery = request('search');
            $products = \App\Models\Product::with('recipes.rawMaterial')
                ->when($searchQuery, fn($q) => $q->where('name', 'like', '%'.$searchQuery.'%'))
                ->get();
        @endphp
        @forelse($products as $product)
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">{{ $product->name }}</h3>
                        <p class="text-xs text-emerald-200/40">{{ $product->recipes->count() }} bahan baku</p>
                    </div>
                </div>
                <button @click="selectedProductId = '{{ $product->id }}'; selectedProductName = '{{ $product->name }}'; loadRecipes(); showModal = true" type="button" class="h-10 px-5 rounded-sm text-[10px] font-bold uppercase tracking-wider flex items-center gap-2"
                        style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease;"
                        onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                        onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Kelola Resep
                </button>
            </div>

            @if($product->recipes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full hybrid-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left">Bahan Baku</th>
                            <th class="px-6 py-4 text-left">SKU</th>
                            <th class="px-6 py-4 text-left">Jumlah per Unit</th>
                            <th class="px-6 py-4 text-right">Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-500/10">
                        @foreach($product->recipes as $index => $recipe)
                        <tr class="group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-200/50 group-hover:text-emerald-200/80 transition-colors">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $recipe->rawMaterial->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $recipe->rawMaterial->sku ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-300">{{ number_format($recipe->quantity_needed, 2) }}</span>
                                <span class="text-xs text-emerald-200/40">{{ $recipe->unit }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php $stock = $recipe->rawMaterial->current_stock ?? 0; @endphp
                                @if($stock <= 0)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20">
                                    <span class="w-1.5 h-1.5" style="background: #EF4444; border-radius: 0;"></span>
                                    Habis
                                </span>
                                @elseif($stock < 10)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20">
                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                    Menipis
                                </span>
                                @else
                                <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ number_format($stock) }} {{ $recipe->rawMaterial->unit ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 flex items-center justify-center border border-emerald-500/15 bg-emerald-500/5 mb-4" style="border-radius: 0;">
                        <svg class="w-8 h-8 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Belum Ada Resep</p>
                    <p class="text-emerald-200/30 text-xs mt-1">Klik "Kelola Resep" untuk menambahkan</p>
                </div>
            </div>
            @endif
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
        </div>
        @empty
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-12 shadow-[0_0_30px_rgba(5,150,105,0.08)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex flex-col items-center py-8 relative z-10">
                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius: 0;">
                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Tidak Ada Produk</p>
                <p class="text-emerald-200/30 text-xs mt-2">Buat produk terlebih dahulu untuk mengelola resep</p>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
        </div>
        @endforelse
    </div>

    {{-- MODAL KELOLA RESEP --}}
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div x-show="showModal" @click.stop
                class="relative w-full max-w-2xl rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)] max-h-[90vh] flex flex-col">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent shrink-0"></div>

                <div class="flex justify-between items-center px-6 py-4 border-b border-emerald-500/15 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Kelola Resep</h3>
                            <p class="text-xs text-emerald-200/40">Produk: <span x-text="selectedProductName" class="font-bold text-emerald-200/80"></span></p>
                        </div>
                    </div>
                    <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.recipes.store-batch') }}" method="POST" class="p-6 overflow-y-auto flex-1">
                    @csrf
                    <input type="hidden" name="product_id" x-model="selectedProductId">

                    <div class="mb-4 px-4 py-3 border border-emerald-500/20 bg-emerald-500/10 rounded-sm">
                        <p class="text-xs text-emerald-200/70 font-medium">Pilih bahan baku yang digunakan dan isi jumlah kebutuhan per unit produksi.</p>
                    </div>

                    <div class="border border-emerald-500/15 rounded-sm overflow-hidden">
                        <table class="w-full hybrid-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-center w-12">Pilih</th>
                                    <th class="px-4 py-3 text-left">Bahan Baku</th>
                                    <th class="px-4 py-3 text-left">Unit</th>
                                    <th class="px-4 py-3 text-left">Jumlah Needed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-500/10">
                                <template x-for="material in rawMaterials" :key="material.id">
                                    <tr class="group hover:bg-emerald-500/5 transition">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" 
                                                :id="'material_' + material.id" 
                                                :checked="recipeData[material.id]?.checked"
                                                @change="toggleMaterial(material.id)"
                                                class="w-4 h-4 rounded-sm border-emerald-500/30 bg-emerald-900/60 text-emerald-500 focus:ring-emerald-500/30 focus:ring-offset-0 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3">
                                            <label :for="'material_' + material.id" class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors cursor-pointer" x-text="material.name"></label>
                                            <div class="text-xs text-emerald-200/30" x-text="material.sku"></div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-sm text-emerald-200/60" x-text="material.unit || 'gram'"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <input type="number" 
                                                    :name="'quantities[' + material.id + ']'" 
                                                    x-model="recipeData[material.id]?.quantity_needed"
                                                    :disabled="!recipeData[material.id]?.checked"
                                                    step="0.01" min="0.01"
                                                    :required="recipeData[material.id]?.checked"
                                                    :placeholder="recipeData[material.id]?.checked ? 'Jumlah...' : '-'"
                                                    class="w-24 h-9 px-3 text-sm rounded-sm border border-emerald-500/20 bg-emerald-900/60 text-emerald-50 placeholder-emerald-200/20 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200 disabled:opacity-30 disabled:cursor-not-allowed">
                                                <span class="text-xs text-emerald-200/40" x-text="material.unit || 'gram'"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-emerald-500/15">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-200 font-bold text-xs uppercase tracking-wider rounded-sm hover:bg-emerald-500/10 transition-all">Batal</button>
                        <button type="submit" class="px-5 py-2.5 rounded-sm text-xs font-bold uppercase tracking-wider shadow-lg"
                                style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; border: none; transition: all 0.2s ease;"
                                onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                                onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.2)'">Simpan Resep</button>
                    </div>
                </form>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
        </div>
    </div>
</div>
@endsection
