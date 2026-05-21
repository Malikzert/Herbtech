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

<div x-data="{ showModal: false, modalMode: 'create', selectedProduct: {},
    detailModalOpen: false, detailData: null, detailLoading: false,
    catOpen: false, catSelected: '{{ request('jeniss') }}', catLabel: '{{ request('jeniss') ?: 'Semua Jeniss' }}',
    viewMode: (localStorage.getItem('adminViewMode') || 'list'),
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
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Jeniss</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $jenissList->count() }}</p>
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
                            catSelected: '{{ request('jeniss') }}', 
                            catLabel: '{{ request('jeniss') ?: 'Semua Jeniss' }}' 
                         }">
                        <input type="hidden" name="jeniss" :value="catSelected">
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
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Jeniss</span>
                                    </a>
                                    @foreach($jenissList as $jenis)
                                    <a href="{{ route('admin.products.index', ['jeniss' => $jenis]) }}"
                                       class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3 no-underline"
                                       :class="catSelected === '{{ $jenis }}' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="catSelected === '{{ $jenis }}' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">{{ $jenis }}</span>
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

                    @if(request('search') || request('jeniss'))
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

    {{-- TABLE VIEW --}}
    <div x-show="viewMode === 'list'" class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
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
                        <th class="px-6 py-4 text-left">Jeniss</th>
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
                            @if($product->jeniss)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 group-hover:bg-emerald-500/15 group-hover:border-emerald-500/30 transition-all" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                {{ $product->jeniss }}
                            </span>
                            @else
                            <span class="text-xs text-emerald-200/30">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $product->unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openDetail({{ $product->id }})"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
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

    {{-- WIDGET VIEW --}}
    <div x-show="viewMode === 'widget'" style="display: none;" class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Daftar Produk</h3>
                    <p class="text-xs text-emerald-200/40">{{ $products->total() }} item</p>
                </div>
            </div>
        </div>
        <div class="widget-grid">
            @forelse($products as $product)
            <div class="widget-card cursor-pointer" @click="openDetail({{ $product->id }})">
                <div class="widget-card-header">
                    <img src="{{ $product->image ? asset('image/' . $product->image) : asset('image/(defaultPRK).png') }}" alt="{{ $product->name }}">
                    @if($product->jeniss)
                    <span class="widget-card-badge" style="background:rgba(52,211,153,0.2);color:#6ee7b7;border:1px solid rgba(52,211,153,0.3)">{{ $product->jeniss }}</span>
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
                    <div class="flex items-center justify-between">
                        <span class="widget-card-status" style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.3)">
                            <span class="widget-card-status-dot" style="background:#34d399"></span>Aktif
                        </span>
                        <div class="widget-card-actions" style="border:none;margin:0;padding:0">
                            <button @click.stop="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'edit'" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click.stop="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'delete'" class="btn-delete" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center py-20">
                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius:0;">
                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Produk</p>
                <p class="text-emerald-200/30 text-xs mt-2">Klik tombol "Tambah Produk" untuk memulai</p>
            </div>
            @endforelse
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
                                <input type="text" x-model="selectedProduct.sku_code" readonly
                                    :placeholder="modalMode === 'create' ? 'Auto-generated (PRD-xxxxxx)' : selectedProduct.sku_code"
                                    class="hybrid-input w-full h-11 px-4 rounded-sm text-sm font-mono opacity-60 cursor-not-allowed">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Jeniss</label>
                                    <input type="text" name="jeniss" x-model="selectedProduct.jeniss" placeholder="cth: Jamu"
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

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Gambar</label>
                                <template x-if="modalMode === 'edit' && selectedProduct.image">
                                    <div class="mb-2">
                                        <img :src="'{{ asset('image') }}/' + selectedProduct.image" class="h-20 w-20 object-cover border border-emerald-500/20 rounded-sm">
                                    </div>
                                </template>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                    class="hybrid-input w-full text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-sm file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-wider file:bg-emerald-500/20 file:text-emerald-300 hover:file:bg-emerald-500/30 file:cursor-pointer cursor-pointer">
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

    {{-- DETAIL MODAL --}}
    <div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="detailModalOpen" @click="detailModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div x-show="detailModalOpen" @click.stop
                class="relative w-full max-w-lg rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>

                <div class="flex justify-between items-center px-6 py-4 border-b border-emerald-500/15">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Detail Produk</h3>
                            <p class="text-xs text-emerald-200/40">Informasi lengkap produk</p>
                        </div>
                    </div>
                    <button @click="detailModalOpen = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 pb-6">
                    <div x-show="detailLoading" class="flex items-center justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-emerald-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <template x-if="detailData && !detailLoading">
                        <div class="space-y-5">
                            <div class="flex items-center gap-4">
                                <template x-if="detailData.image">
                                    <img :src="'{{ asset('image') }}/' + detailData.image" class="w-16 h-16 object-cover border border-emerald-500/20 rounded-sm">
                                </template>
                                <template x-if="!detailData.image">
                                    <div class="w-16 h-16 flex items-center justify-center bg-emerald-500/10 border border-emerald-500/20 rounded-sm">
                                        <svg class="w-8 h-8 text-emerald-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                </template>
                                <div>
                                    <h4 class="text-lg font-black text-emerald-50" x-text="detailData.name"></h4>
                                    <p class="text-xs text-emerald-200/40" x-text="detailData.sku_code || 'Tanpa SKU'"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-emerald-500/15">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Jeniss</p>
                                    <p class="text-sm font-bold text-emerald-50" x-text="detailData.jeniss || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Unit</p>
                                    <p class="text-sm font-bold text-emerald-50" x-text="detailData.unit || '-'"></p>
                                </div>
                            </div>

                            <div class="pt-2">
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Deskripsi</p>
                                <p class="text-sm text-emerald-200/80" x-text="detailData.description || 'Tidak ada deskripsi'"></p>
                            </div>

                            <div class="pt-3 border-t border-emerald-500/15">
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-3">Komposisi Resep</p>
                                <template x-if="detailData.recipes && detailData.recipes.length > 0">
                                    <div class="space-y-2">
                                        <template x-for="(recipe, idx) in detailData.recipes" :key="idx">
                                            <div class="flex items-center justify-between px-3 py-2 bg-emerald-500/5 border border-emerald-500/10 rounded-sm">
                                                <span class="text-sm font-bold text-emerald-50" x-text="recipe.raw_material?.name || 'Unknown'"></span>
                                                <span class="text-xs font-mono text-emerald-200/60" x-text="recipe.quantity_needed + ' ' + (recipe.unit || '')"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="!detailData.recipes || detailData.recipes.length === 0">
                                    <p class="text-xs text-emerald-200/40 italic">Belum ada resep untuk produk ini</p>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
        </div>
    </div>
</div>
@endsection
