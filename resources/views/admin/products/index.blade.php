@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('header', 'PRODUK')

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
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(6, 78, 59, 0.3); }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
</style>

<div x-data="{ showModal: false, modalMode: 'create', selectedProduct: {}, catOpen: false, catSelected: '{{ request('category') }}', catLabel: '{{ request('category') ?: 'Semua Kategori' }}' }">

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
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $products->total() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Kategori</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $categories->count() }}</p>
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
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Status</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">Aktif</p>
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
                        <p class="text-xs text-emerald-200/40">Cari dan filter data produk</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.products.index') }}" x-ref="filterForm" class="flex flex-wrap gap-3 items-center">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                            class="hybrid-input w-full h-11 pl-10 pr-4 rounded-sm text-sm">
                    </div>

                    {{-- Custom Category Dropdown --}}
                    <div class="relative" 
                         x-data="{ 
                            open: false, 
                            catSelected: '{{ request('category') }}', 
                            catLabel: '{{ request('category') ?: 'Semua Kategori' }}' 
                         }">
                        <input type="hidden" name="category" :value="catSelected">
                        <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => { 
                                let r = $el.getBoundingClientRect(); 
                                $refs.catMenu.style.top = (r.bottom + window.scrollY + 6) + 'px'; 
                                $refs.catMenu.style.left = (r.left + window.scrollX) + 'px'; 
                                $refs.catMenu.style.width = Math.max(r.width, 200) + 'px'; 
                            }) }"
                            class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[180px] whitespace-nowrap">
                            <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="catLabel"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" 
                                 :class="open && 'rotate-180'" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <template x-teleport="body">
                            <div x-ref="catMenu"
                                x-show="open"
                                @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-150" 
                                x-transition:enter-start="opacity-0 scale-95" 
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100" 
                                x-transition:leave-start="opacity-100 scale-100" 
                                x-transition:leave-end="opacity-0 scale-95"
                                class="fixed z-[9999] rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] overflow-hidden" 
                                style="display: none;">
                                <div class="py-1 max-h-60 overflow-y-auto">
                                    <a href="{{ route('admin.products.index') }}"
                                       class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3 no-underline"
                                       :class="!catSelected ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="!catSelected ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Kategori</span>
                                    </a>
                                    @foreach($categories as $cat)
                                    <a href="{{ route('admin.products.index', ['category' => $cat]) }}"
                                       class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3 no-underline"
                                       :class="catSelected === '{{ $cat }}' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="catSelected === '{{ $cat }}' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">{{ $cat }}</span>
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="submit" class="h-11 px-6 rounded-sm text-xs font-bold uppercase tracking-wider flex items-center gap-2" 
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                        Filter
                    </button>

                    @if(request('search') || request('category'))
                    <a href="{{ route('admin.products.index') }}" class="h-11 px-5 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-700/40 hover:border-emerald-500/50 rounded-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reset
                    </a>
                    @endif

                    <div class="flex-1"></div>

                    <button @click="showModal = true; modalMode = 'create'; selectedProduct = {}" type="button" class="h-11 px-6 rounded-sm text-xs font-bold uppercase tracking-wider flex items-center gap-2"
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Produk
                    </button>
                </form>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Daftar Produk</h3>
                    <p class="text-xs text-emerald-200/40">{{ $products->total() }} item</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama Produk</th>
                        <th class="px-6 py-4 text-left">SKU</th>
                        <th class="px-6 py-4 text-left">Kategori</th>
                        <th class="px-6 py-4 text-left">Unit</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @forelse($products as $index => $product)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-200/50 group-hover:text-emerald-200/80 transition-colors">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $product->name }}</div>
                            @if($product->description)
                            <div class="text-xs text-emerald-200/40 mt-0.5 line-clamp-1">{{ $product->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $product->sku_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($product->category)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 group-hover:bg-emerald-500/15 group-hover:border-emerald-500/30 transition-all" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                {{ $product->category }}
                            </span>
                            @else
                            <span class="text-xs text-emerald-200/30">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $product->unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'edit'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'delete'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-red-400 hover:bg-red-500/15 transition-all duration-200"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius: 0;">
                                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Produk</p>
                                <p class="text-emerald-200/30 text-xs mt-2">Klik tombol "Tambah Produk" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $products->links() }}
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>

    {{-- MODAL --}}
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div x-show="showModal" @click.stop
                class="relative w-full max-w-lg rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>

                <div class="flex justify-between items-center px-6 py-4 border-b border-emerald-500/15">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <template x-if="modalMode === 'delete'">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </template>
                            <template x-if="modalMode !== 'delete'">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </template>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">
                                <span x-text="modalMode === 'create' ? 'Tambah Produk' : modalMode === 'edit' ? 'Edit Produk' : 'Hapus Produk'"></span>
                            </h3>
                            <p class="text-xs text-emerald-200/40" x-text="modalMode === 'delete' ? 'Tindakan ini permanen' : 'Lengkapi form di bawah'"></p>
                        </div>
                    </div>
                    <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 pb-6">
                    <template x-if="modalMode === 'delete'">
                        <div class="text-center py-4">
                            <div class="w-20 h-20 mx-auto mb-5 flex items-center justify-center border border-red-500/30 bg-red-500/10" style="border-radius: 0;">
                                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </div>
                            <p class="text-emerald-200/80 text-sm mb-1">
                                Hapus <span x-text="selectedProduct.name" class="font-bold text-emerald-50"></span>?
                            </p>
                            <p class="text-xs text-emerald-200/40">Data yang sudah dihapus tidak dapat dikembalikan.</p>

                            <form :action="'/admin/products/' + selectedProduct.id" method="POST" class="mt-6">
                                @csrf
                                @method('DELETE')
                                <div class="flex justify-center gap-3">
                                    <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-200 font-bold text-xs uppercase tracking-wider rounded-sm hover:bg-emerald-500/10 transition-all">Batal</button>
                                    <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider rounded-sm shadow-lg hover:shadow-red-500/30 transition-all">Ya, Hapus</button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <template x-if="modalMode !== 'delete'">
                        <form :action="modalMode === 'create' ? '{{ route('admin.products.store') }}' : '/admin/products/' + selectedProduct.id" method="POST" class="space-y-4">
                            @csrf
                            <template x-if="modalMode === 'edit'">
                                @method('PUT')
                            </template>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Nama Produk</label>
                                <input type="text" name="name" x-model="selectedProduct.name" required
                                    class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">SKU Code</label>
                                <input type="text" name="sku_code" x-model="selectedProduct.sku_code" required
                                    class="hybrid-input w-full h-11 px-4 rounded-sm text-sm font-mono">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Kategori</label>
                                    <input type="text" name="category" x-model="selectedProduct.category" placeholder="cth: Jamu"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Unit</label>
                                    <input type="text" name="unit" x-model="selectedProduct.unit" placeholder="cth: pcs, box"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Deskripsi</label>
                                <textarea name="description" x-model="selectedProduct.description" rows="3" placeholder="Deskripsi produk..."
                                    class="hybrid-input w-full px-4 py-3 rounded-sm text-sm"></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-3 border-t border-emerald-500/15">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-200 font-bold text-xs uppercase tracking-wider rounded-sm hover:bg-emerald-500/10 transition-all">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-sm text-xs font-bold uppercase tracking-wider shadow-lg"
                                        style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; border: none; transition: all 0.2s ease;"
                                        onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.2)'">Simpan</button>
                            </div>
                        </form>
                    </template>
                </div>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
        </div>
    </div>
</div>
@endsection
