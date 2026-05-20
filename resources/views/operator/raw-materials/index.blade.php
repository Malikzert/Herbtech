@extends('layouts.app')

@section('title', 'Bahan Baku')
@section('header', 'DATA BAHAN BAKU')

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
        fetch('/admin/raw-materials/' + id, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(res => {
                if (res.success) { this.detailData = res.data; }
                this.detailLoading = false;
            })
            .catch(() => { this.detailLoading = false; });
    }
}">

<div class="mb-6">
    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-4">
        <form method="GET" action="{{ route('operator.raw-materials.index') }}" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..."
                    class="w-full h-11 pl-10 pr-4 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] placeholder-[#64748B] text-sm focus:ring-[#1DA1F2] focus:border-[#1DA1F2] focus:outline-none transition">
            </div>

            <select name="type" class="h-11 px-4 py-2 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] text-sm focus:ring-[#1DA1F2] focus:border-[#1DA1F2] focus:outline-none transition cursor-pointer">
                <option value="" class="bg-[#0f172a]">Semua Tipe</option>
                <option value="herbal" {{ request('type') === 'herbal' ? 'selected' : '' }} class="bg-[#0f172a]">Herbal</option>
                <option value="packaging" {{ request('type') === 'packaging' ? 'selected' : '' }} class="bg-[#0f172a]">Packaging</option>
                <option value="additive" {{ request('type') === 'additive' ? 'selected' : '' }} class="bg-[#0f172a]">Additive</option>
            </select>

            <button type="submit" class="h-11 px-5 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                Filter
            </button>

            @if(request('search') || request('type'))
            <a href="{{ route('operator.raw-materials.index') }}" class="h-11 px-5 bg-[#334155] hover:bg-[#1e293b] text-[#93C5FD] font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
            @endif
        </form>
    </div>
</div>

{{-- TABLE VIEW --}}
<div x-show="viewMode === 'list'" class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#1e293b] border-b border-[#334155]">
            <tr>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">No</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Nama Bahan</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">SKU</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Tipe</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Stok</span></th>
                <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Status</span></th>
                <th class="px-6 py-3.5 text-right"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-[#334155]">
            @forelse($rawMaterials as $index => $material)
            <tr class="hover:bg-[#1e293b]/50 transition-colors duration-150">
                <td class="px-6 py-4 text-sm text-[#64748B]">{{ $index + 1 }}</td>
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-white">{{ $material->name }}</div>
                    @if($material->supplier)
                    <div class="text-xs text-[#64748B] mt-0.5">{{ $material->supplier }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-[#93C5FD] font-mono">{{ $material->sku ?? '-' }}</td>
                <td class="px-6 py-4">
                    @switch($material->type)
                        @case('herbal')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30">Herbal</span>
                            @break
                        @case('packaging')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30">Packaging</span>
                            @break
                        @case('additive')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#64748B]/20 text-[#3B82F6] border border-[#64748B]/30">Additive</span>
                            @break
                    @endswitch
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-white">
                        {{ number_format($material->current_stock, 0) }} <span class="text-[#64748B] font-normal">{{ $material->unit }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    @if($material->current_stock <= 0)
                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#334155] text-[#64748B] border border-[#334155]">Habis</span>
                    @elseif($material->current_stock < ($material->min_stock_level ?? 10))
                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30">Rendah</span>
                    @else
                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30">Tersedia</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <button @click="openDetail({{ $material->id }})"
                        class="w-9 h-9 flex items-center justify-center text-[#64748B] hover:text-[#93C5FD] hover:bg-[#1e293b]/50 transition-all duration-200"
                        title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-[#334155] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        <p class="text-[#64748B] font-medium">Belum ada bahan baku</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($rawMaterials->hasPages())
<div class="px-6 py-4 border-t border-[#334155] bg-[#1e293b]/50">
    {{ $rawMaterials->links() }}
</div>
@endif
</div>

{{-- WIDGET VIEW --}}
<div x-show="viewMode === 'widget'" style="display: none;" class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#334155]">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center border border-[#334155] bg-[#1e293b]/60" style="border-radius: 0;">
                <svg class="w-4 h-4 text-[#93C5FD]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-[#DBEAFE]">Daftar Bahan Baku</h3>
                <p class="text-xs text-[#64748B]">{{ $rawMaterials->total() }} item</p>
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
                    <span class="widget-card-badge" style="background:rgba(160,132,92,0.25);color:#DBEAFE;border:1px solid rgba(160,132,92,0.4)">Herbal</span>
                    @break
                    @case('packaging')
                    <span class="widget-card-badge" style="background:rgba(29,161,242,0.25);color:#93C5FD;border:1px solid rgba(29,161,242,0.4)">Packaging</span>
                    @break
                    @case('additive')
                    <span class="widget-card-badge" style="background:rgba(107,87,64,0.25);color:#3B82F6;border:1px solid rgba(107,87,64,0.4)">Additive</span>
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
                @if($material->current_stock <= 0)
                <span class="widget-card-status" style="background:rgba(61,43,31,0.5);color:#64748B;border:1px solid rgba(61,43,31,0.6)">
                    <span class="widget-card-status-dot" style="background:#64748B"></span>Habis
                </span>
                @elseif($material->current_stock < ($material->min_stock_level ?? 10))
                <span class="widget-card-status" style="background:rgba(139,105,20,0.2);color:#93C5FD;border:1px solid rgba(139,105,20,0.3)">
                    <span class="widget-card-status-dot" style="background:#93C5FD"></span>Rendah
                </span>
                @else
                <span class="widget-card-status" style="background:rgba(59,130,246,0.2);color:#DBEAFE;border:1px solid rgba(59,130,246,0.3)">
                    <span class="widget-card-status-dot" style="background:#DBEAFE"></span>Tersedia
                </span>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full flex flex-col items-center py-20">
            <svg class="w-16 h-16 text-[#334155] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
            <p class="text-[#64748B] font-medium">Belum ada bahan baku</p>
        </div>
        @endforelse
    </div>
    @if($rawMaterials->hasPages())
    <div class="px-6 py-4 border-t border-[#334155] bg-[#1e293b]/50">
        {{ $rawMaterials->links() }}
    </div>
    @endif
</div>

{{-- DETAIL MODAL --}}
<div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="detailModalOpen" @click="detailModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

        <div x-show="detailModalOpen" @click.stop
            class="relative w-full max-w-lg rounded-sm border border-[#334155] bg-[#0f172a]/95 backdrop-blur-xl shadow-[0_0_60px_rgba(0,0,0,0.5)]">
            <div class="h-[2px] bg-gradient-to-r from-[#1DA1F2]/60 via-[#3B82F6]/30 to-transparent"></div>

            <div class="flex justify-between items-center px-6 py-4 border-b border-[#334155]">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center border border-[#334155] bg-[#1e293b]/60" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-[#93C5FD]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[#DBEAFE]">Detail Bahan Baku</h3>
                        <p class="text-xs text-[#64748B]">Informasi lengkap bahan baku</p>
                    </div>
                </div>
                <button @click="detailModalOpen = false" class="w-8 h-8 flex items-center justify-center text-[#64748B] hover:text-[#93C5FD] hover:bg-[#1e293b]/50 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 pb-6">
                <div x-show="detailLoading" class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-[#93C5FD]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                <template x-if="detailData && !detailLoading">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <template x-if="detailData.image">
                                    <img :src="'{{ asset('image') }}/' + detailData.image" class="w-16 h-16 object-cover border border-[#334155] rounded-sm">
                                </template>
                                <template x-if="!detailData.image">
                                    <div class="w-16 h-16 flex items-center justify-center bg-[#1e293b]/60 border border-[#334155] rounded-sm">
                                        <svg class="w-8 h-8 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                    </div>
                                </template>
                                <div>
                                    <h4 class="text-lg font-black text-[#DBEAFE]" x-text="detailData.name"></h4>
                                    <p class="text-xs text-[#64748B]" x-text="detailData.sku || 'Tanpa SKU'"></p>
                                </div>
                            </div>
                            <span x-show="detailData.stock_status === 'available'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30 rounded-sm">
                                <span class="w-1.5 h-1.5" style="background: #DBEAFE; border-radius: 0;"></span>
                                Tersedia
                            </span>
                            <span x-show="detailData.stock_status === 'low'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30 rounded-sm">
                                <span class="w-1.5 h-1.5" style="background: #93C5FD; border-radius: 0;"></span>
                                Rendah
                            </span>
                            <span x-show="detailData.stock_status === 'out'"
                                class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-[#334155] text-[#64748B] border border-[#334155] rounded-sm">
                                <span class="w-1.5 h-1.5" style="background: #64748B; border-radius: 0;"></span>
                                Habis
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-[#334155]">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Supplier</p>
                                <p class="text-sm font-bold text-[#DBEAFE]" x-text="detailData.supplier || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Tipe</p>
                                <p class="text-sm font-bold text-[#DBEAFE] capitalize" x-text="detailData.type || '-'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Sisa Stok</p>
                                <p class="text-sm font-bold text-[#DBEAFE]" x-text="(detailData.current_stock || 0) + ' ' + (detailData.unit || '')"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Min. Stok Level</p>
                                <p class="text-sm font-bold text-[#DBEAFE]" x-text="detailData.min_stock_level || '0'"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-[#334155]"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-[#334155]"></div>
        </div>
    </div>
</div>
</div>
@endsection
