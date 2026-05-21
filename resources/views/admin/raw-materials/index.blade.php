@extends('layouts.admin')

@section('title', 'Kelola Bahan Baku')
@section('header', 'BAHAN BAKU')

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
    showModal: false, modalMode: 'create', selectedMaterial: {},
    detailModalOpen: false, detailData: null, detailLoading: false,
    typeOpen: false, typeSelected: '{{ request('type') }}', typeLabel: '{{ request('type') ? ucfirst(request('type')) : 'Semua Tipe' }}',
    viewMode: (localStorage.getItem('adminViewMode') || 'list'),
    init() {
        window.addEventListener('admin-view-change', (e) => { this.viewMode = e.detail; });
    },
    openDetail(id) {
        this.detailLoading = true;
        this.detailData = null;
        this.detailModalOpen = true;
        fetch('/admin/raw-materials/' + id, { headers: { 'Accept': 'application/json' } })
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
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Bahan</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $rawMaterials->total() }}</p>
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
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Jenis Tipe</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterial::distinct()->pluck('type')->filter()->count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Stok Rendah</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterial::where('current_stock', '<', 10)->count() }}</p>
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
                        <p class="text-xs text-emerald-200/40">Cari dan filter data bahan baku</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.raw-materials.index') }}" class="flex flex-wrap gap-3 items-center">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..."
                            class="hybrid-input w-full h-11 pl-10 pr-4 rounded-sm text-sm">
                    </div>

                    {{-- Custom Type Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <input type="hidden" name="type" :value="typeSelected">
                        <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => { 
                                let r = $el.getBoundingClientRect(); 
                                $refs.typeMenu.style.top = (r.bottom + window.scrollY + 6) + 'px'; 
                                $refs.typeMenu.style.left = (r.left + window.scrollX) + 'px'; 
                                $refs.typeMenu.style.width = Math.max(r.width, 180) + 'px'; 
                            }) }"
                            class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[160px] whitespace-nowrap">
                            <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="typeLabel"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" 
                                 :class="open && 'rotate-180'" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <template x-teleport="body">
                            <div x-ref="typeMenu"
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
                                <div class="py-1">
                                    <button type="button" @click="typeSelected = ''; typeLabel = 'Semua Tipe'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="!typeSelected ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="!typeSelected ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Tipe</span>
                                    </button>
                                    <button type="button" @click="typeSelected = 'herbal'; typeLabel = 'Herbal'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="typeSelected === 'herbal' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'herbal' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Herbal</span>
                                    </button>
                                    <button type="button" @click="typeSelected = 'packaging'; typeLabel = 'Packaging'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="typeSelected === 'packaging' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'packaging' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Packaging</span>
                                    </button>
                                    <button type="button" @click="typeSelected = 'additive'; typeLabel = 'Additive'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="typeSelected === 'additive' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'additive' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Additive</span>
                                    </button>
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

                    @if(request('search') || request('type'))
                    <a href="{{ route('admin.raw-materials.index') }}" class="h-11 px-5 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-700/40 hover:border-emerald-500/50 rounded-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reset
                    </a>
                    @endif

                    <div class="flex-1"></div>

                    <button @click="showModal = true; modalMode = 'create'; selectedMaterial = {}" type="button" class="h-11 px-6 rounded-sm text-xs font-bold uppercase tracking-wider flex items-center gap-2"
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Bahan
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
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Daftar Bahan Baku</h3>
                    <p class="text-xs text-emerald-200/40">{{ $rawMaterials->total() }} item</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama Bahan</th>
                        <th class="px-6 py-4 text-left">SKU</th>
                        <th class="px-6 py-4 text-left">Tipe</th>
                        <th class="px-6 py-4 text-left">Stok</th>
                        <th class="px-6 py-4 text-left">Status QC</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @forelse($rawMaterials as $index => $material)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-200/50 group-hover:text-emerald-200/80 transition-colors">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $material->name }}</div>
                            @if($material->supplier)
                            <div class="text-xs text-emerald-200/40 mt-0.5">{{ $material->supplier }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $material->sku ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($material->type)
                                @case('herbal')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 group-hover:bg-emerald-500/15 group-hover:border-emerald-500/30 transition-all" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                    Herbal
                                </span>
                                @break
                                @case('packaging')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20 group-hover:bg-blue-500/15 group-hover:border-blue-500/30 transition-all" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                    Packaging
                                </span>
                                @break
                                @case('additive')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-300 border border-purple-500/20 group-hover:bg-purple-500/15 group-hover:border-purple-500/30 transition-all" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #A78BFA; border-radius: 0;"></span>
                                    Additive
                                </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">
                                {{ number_format($material->current_stock, 0) }} <span class="text-emerald-200/40 font-normal text-xs">{{ $material->unit }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($material->qc_status === 'waiting')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-300 border border-sky-500/20" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #38BDF8; border-radius: 0;"></span>
                                Menunggu QC
                            </span>
                            @elseif($material->qc_status === 'accept')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                Diterima
                            </span>
                            @elseif($material->qc_status === 'rework')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                QC Ulang
                            </span>
                            @elseif($material->qc_status === 'decline')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #EF4444; border-radius: 0;"></span>
                                Ditolak
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-gray-500/10 text-gray-400 border border-gray-500/20" style="border-radius: 0;">
                                <span class="w-1.5 h-1.5" style="background: #6B7280; border-radius: 0;"></span>
                                {{ $material->qc_status ?? '-' }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="openDetail({{ $material->id }})"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200"
                                    title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button @click="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'edit'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'delete'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-red-400 hover:bg-red-500/15 transition-all duration-200"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius: 0;">
                                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                </div>
                                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Bahan Baku</p>
                                <p class="text-emerald-200/30 text-xs mt-2">Klik tombol "Tambah Bahan" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rawMaterials->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $rawMaterials->links() }}
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
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Daftar Bahan Baku</h3>
                    <p class="text-xs text-emerald-200/40">{{ $rawMaterials->total() }} item</p>
                </div>
            </div>
        </div>
        <div class="widget-grid">
            @forelse($rawMaterials as $material)
            <div class="widget-card cursor-pointer" @click="openDetail({{ $material->id }})">
                <div class="widget-card-header">
                    <img src="{{ $material->image ? asset('image/' . $material->image) : asset('image/(defaultRAW).jpg') }}" alt="{{ $material->name }}">
                    @switch($material->type)
                        @case('herbal')
                        <span class="widget-card-badge" style="background:rgba(52,211,153,0.2);color:#6ee7b7;border:1px solid rgba(52,211,153,0.3)">Herbal</span>
                        @break
                        @case('packaging')
                        <span class="widget-card-badge" style="background:rgba(96,165,250,0.2);color:#93c5fd;border:1px solid rgba(96,165,250,0.3)">Packaging</span>
                        @break
                        @case('additive')
                        <span class="widget-card-badge" style="background:rgba(167,139,250,0.2);color:#c4b5fd;border:1px solid rgba(167,139,250,0.3)">Additive</span>
                        @break
                    @endswitch
                </div>
                <div class="widget-card-body">
                    <h3 class="widget-card-title">{{ $material->name }}</h3>
                    <p class="widget-card-subtitle">{{ $material->sku ?? '-' }}</p>
                    <div class="widget-card-details">
                        <span class="widget-card-detail">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4"/></svg>
                            {{ number_format($material->current_stock, 0) }} {{ $material->unit }}
                        </span>
                        @if($material->supplier)
                        <span class="widget-card-detail">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $material->supplier }}
                        </span>
                        @endif
                    </div>
                    <div class="widget-card-spacer"></div>
                    <div class="flex items-center justify-between">
                        @if($material->qc_status === 'waiting')
                        <span class="widget-card-status" style="background:rgba(56,189,248,0.15);color:#7dd3fc;border:1px solid rgba(56,189,248,0.3)">
                            <span class="widget-card-status-dot" style="background:#38BDF8"></span>Menunggu QC
                        </span>
                        @elseif($material->qc_status === 'accept')
                        <span class="widget-card-status" style="background:rgba(52,211,153,0.15);color:#6ee7b7;border:1px solid rgba(52,211,153,0.3)">
                            <span class="widget-card-status-dot" style="background:#34d399"></span>Diterima
                        </span>
                        @elseif($material->qc_status === 'rework')
                        <span class="widget-card-status" style="background:rgba(245,158,11,0.15);color:#fcd34d;border:1px solid rgba(245,158,11,0.3)">
                            <span class="widget-card-status-dot" style="background:#f59e0b"></span>QC Ulang
                        </span>
                        @elseif($material->qc_status === 'decline')
                        <span class="widget-card-status" style="background:rgba(239,68,68,0.15);color:#fca5a5;border:1px solid rgba(239,68,68,0.3)">
                            <span class="widget-card-status-dot" style="background:#ef4444"></span>Ditolak
                        </span>
                        @else
                        <span class="widget-card-status" style="background:rgba(107,114,128,0.15);color:#9ca3af;border:1px solid rgba(107,114,128,0.3)">
                            <span class="widget-card-status-dot" style="background:#6b7280"></span>{{ $material->qc_status ?? '-' }}
                        </span>
                        @endif
                        <div class="widget-card-actions" style="border:none;margin:0;padding:0">
                            <button @click.stop="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'edit'" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click.stop="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'delete'" class="btn-delete" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full flex flex-col items-center py-20">
                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius:0;">
                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Bahan Baku</p>
                <p class="text-emerald-200/30 text-xs mt-2">Klik tombol "Tambah Bahan" untuk memulai</p>
            </div>
            @endforelse
        </div>
        @if($rawMaterials->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $rawMaterials->links() }}
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
                                <span x-text="modalMode === 'create' ? 'Tambah Bahan Baku' : modalMode === 'edit' ? 'Edit Bahan Baku' : 'Hapus Bahan Baku'"></span>
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
                                Hapus <span x-text="selectedMaterial.name" class="font-bold text-emerald-50"></span>?
                            </p>
                            <p class="text-xs text-emerald-200/40">Data yang sudah dihapus tidak dapat dikembalikan.</p>

                            <form :action="'/admin/raw-materials/' + selectedMaterial.id" method="POST" class="mt-6">
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
                        <form :action="modalMode === 'create' ? '{{ route('admin.raw-materials.store') }}' : '/admin/raw-materials/' + selectedMaterial.id" method="POST" class="space-y-4" enctype="multipart/form-data">
                            @csrf
                            <template x-if="modalMode === 'edit'">
                                @method('PUT')
                            </template>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Nama Bahan</label>
                                <input type="text" name="name" x-model="selectedMaterial.name" required
                                    class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">SKU</label>
                                    <input type="text" x-model="selectedMaterial.sku" readonly
                                        :placeholder="modalMode === 'create' ? 'Auto-generated (RM-xxxxxx)' : selectedMaterial.sku"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm font-mono opacity-60 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Tipe</label>
                                    <select name="type" x-model="selectedMaterial.type" required
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm cursor-pointer">
                                        <option value="herbal" class="bg-gray-900">Herbal</option>
                                        <option value="packaging" class="bg-gray-900">Packaging</option>
                                        <option value="additive" class="bg-gray-900">Additive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Unit</label>
                                    <input type="text" name="unit" x-model="selectedMaterial.unit" required placeholder="cth: kg, gram"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Stok Saat Ini</label>
                                    <input type="number" name="current_stock" x-model="selectedMaterial.current_stock" min="0"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Min Stok Level</label>
                                    <input type="number" name="min_stock_level" x-model="selectedMaterial.min_stock_level" min="0"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Supplier</label>
                                    <input type="text" name="supplier" x-model="selectedMaterial.supplier" placeholder="Nama supplier"
                                        class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Gambar</label>
                                <template x-if="modalMode === 'edit' && selectedMaterial.image">
                                    <div class="mb-2">
                                        <img :src="'{{ asset('image') }}/' + selectedMaterial.image" class="h-20 w-20 object-cover border border-emerald-500/20 rounded-sm">
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
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Detail Bahan Baku</h3>
                            <p class="text-xs text-emerald-200/40">Informasi lengkap bahan baku</p>
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
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <template x-if="detailData.image">
                                        <img :src="'{{ asset('image') }}/' + detailData.image" class="w-16 h-16 object-cover border border-emerald-500/20 rounded-sm">
                                    </template>
                                    <template x-if="!detailData.image">
                                        <div class="w-16 h-16 flex items-center justify-center bg-emerald-500/10 border border-emerald-500/20 rounded-sm">
                                            <svg class="w-8 h-8 text-emerald-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                        </div>
                                    </template>
                                    <div>
                                        <h4 class="text-lg font-black text-emerald-50" x-text="detailData.name"></h4>
                                        <p class="text-xs text-emerald-200/40" x-text="detailData.sku || 'Tanpa SKU'"></p>
                                    </div>
                                </div>
                                <span x-show="detailData.qc_status === 'waiting'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-300 border border-sky-500/20 rounded-sm">
                                    <span class="w-1.5 h-1.5" style="background: #38BDF8; border-radius: 0;"></span>
                                    Menunggu QC
                                </span>
                                <span x-show="detailData.qc_status === 'accept'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20 rounded-sm">
                                    <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                    Diterima
                                </span>
                                <span x-show="detailData.qc_status === 'rework'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20 rounded-sm">
                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                    QC Ulang
                                </span>
                                <span x-show="detailData.qc_status === 'decline'"
                                    class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20 rounded-sm">
                                    <span class="w-1.5 h-1.5" style="background: #EF4444; border-radius: 0;"></span>
                                    Ditolak
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-emerald-500/15">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Supplier</p>
                                    <p class="text-sm font-bold text-emerald-50" x-text="detailData.supplier || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Tipe</p>
                                    <p class="text-sm font-bold text-emerald-50 capitalize" x-text="detailData.type || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Sisa Stok</p>
                                    <p class="text-sm font-bold text-emerald-50" x-text="(detailData.current_stock || 0) + ' ' + (detailData.unit || '')"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1">Min. Stok Level</p>
                                    <p class="text-sm font-bold text-emerald-50" x-text="detailData.min_stock_level || '0'"></p>
                                </div>
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
