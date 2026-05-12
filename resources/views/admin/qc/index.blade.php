@extends('layouts.admin')

@section('title', 'Quality Control')
@section('header', 'QUALITY CONTROL')

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

<div x-data="{ 
    statusOpen: false, statusSelected: '{{ request('status') }}', statusLabel: '{{ request('status') ? ucfirst(str_replace('_', ' ', request('status'))) : 'Semua Hasil' }}',
    actionOpen: false, actionSelected: '{{ request('action') }}', actionLabel: '{{ request('action') ? ucfirst(request('action')) : 'Semua Action' }}'
}">

    {{-- STAT ROW --}}
    @php
        $totalQc = \App\Models\QualityControl::count();
        $passedQc = \App\Models\QualityControl::where('status', 'passed')->count();
        $reworkQc = \App\Models\QualityControl::where('action', 'rework')->count();
        $rejectedQc = \App\Models\QualityControl::where('action', 'reject')->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Inspeksi</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $totalQc }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Passed</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $passedQc }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-purple-500/15 border border-purple-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Rework</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $reworkQc }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Rejected</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $rejectedQc }}</p>
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
                        <p class="text-xs text-emerald-200/40">Cari dan filter data quality control</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.qc.index') }}" class="flex flex-wrap gap-3 items-center">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..."
                            class="hybrid-input w-full h-11 pl-10 pr-4 rounded-sm text-sm">
                    </div>

                    {{-- Custom Status Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <input type="hidden" name="status" :value="statusSelected">
                        <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => { 
                                let r = $el.getBoundingClientRect(); 
                                $refs.statusMenu.style.top = (r.bottom + window.scrollY + 6) + 'px'; 
                                $refs.statusMenu.style.left = (r.left + window.scrollX) + 'px'; 
                                $refs.statusMenu.style.width = Math.max(r.width, 180) + 'px'; 
                            }) }"
                            class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[150px] whitespace-nowrap">
                            <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="statusLabel"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <template x-teleport="body">
                            <div x-ref="statusMenu"
                                x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="fixed z-[9999] rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <button type="button" @click="statusSelected = ''; statusLabel = 'Semua Hasil'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="!statusSelected ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="!statusSelected ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Hasil</span>
                                    </button>
                                    <button type="button" @click="statusSelected = 'passed'; statusLabel = 'Passed'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="statusSelected === 'passed' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="statusSelected === 'passed' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Passed</span>
                                    </button>
                                    <button type="button" @click="statusSelected = 'partial_reject'; statusLabel = 'Partial Reject'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="statusSelected === 'partial_reject' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="statusSelected === 'partial_reject' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Partial Reject</span>
                                    </button>
                                    <button type="button" @click="statusSelected = 'full_reject'; statusLabel = 'Full Reject'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="statusSelected === 'full_reject' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="statusSelected === 'full_reject' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Full Reject</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Custom Action Dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <input type="hidden" name="action" :value="actionSelected">
                        <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => { 
                                let r = $el.getBoundingClientRect(); 
                                $refs.actionMenu.style.top = (r.bottom + window.scrollY + 6) + 'px'; 
                                $refs.actionMenu.style.left = (r.left + window.scrollX) + 'px'; 
                                $refs.actionMenu.style.width = Math.max(r.width, 170) + 'px'; 
                            }) }"
                            class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[150px] whitespace-nowrap">
                            <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="actionLabel"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <template x-teleport="body">
                            <div x-ref="actionMenu"
                                x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="fixed z-[9999] rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <button type="button" @click="actionSelected = ''; actionLabel = 'Semua Action'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="!actionSelected ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="!actionSelected ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Action</span>
                                    </button>
                                    <button type="button" @click="actionSelected = 'release'; actionLabel = 'Release'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="actionSelected === 'release' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="actionSelected === 'release' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Release</span>
                                    </button>
                                    <button type="button" @click="actionSelected = 'rework'; actionLabel = 'Rework'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="actionSelected === 'rework' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="actionSelected === 'rework' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Rework</span>
                                    </button>
                                    <button type="button" @click="actionSelected = 'reject'; actionLabel = 'Reject'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="actionSelected === 'reject' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="actionSelected === 'reject' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Reject</span>
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

                    @if(request()->hasAny(['search', 'status', 'action']))
                    <a href="{{ route('admin.qc.index') }}" class="h-11 px-5 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-700/40 hover:border-emerald-500/50 rounded-sm transition-all duration-200">
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

    {{-- TABLE CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Riwayat Inspeksi QC</h3>
                    <p class="text-xs text-emerald-200/40">{{ $qualityControls->total() }} item</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">ID QC</th>
                        <th class="px-6 py-4 text-left">Batch</th>
                        <th class="px-6 py-4 text-left">Inspector</th>
                        <th class="px-6 py-4 text-left">Inspeksi</th>
                        <th class="px-6 py-4 text-center">Passed</th>
                        <th class="px-6 py-4 text-center">Rejected</th>
                        <th class="px-6 py-4 text-left">Hasil</th>
                        <th class="px-6 py-4 text-left">Action</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @forelse($qualityControls as $qc)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">#{{ $qc->id }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $qc->production->batch_number ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $qc->inspector_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $qc->inspected_at->format('d M Y, H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-emerald-300">{{ $qc->total_passed }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-red-300">{{ $qc->total_rejected }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($qc->status)
                                @case('passed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                    Passed
                                </span>
                                @break
                                @case('partial_reject')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                    Partial
                                </span>
                                @break
                                @case('full_reject')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #EF4444; border-radius: 0;"></span>
                                    Full Reject
                                </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            @switch($qc->action)
                                @case('release')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                    Release
                                </span>
                                @break
                                @case('rework')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-300 border border-purple-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #A78BFA; border-radius: 0;"></span>
                                    Rework
                                </span>
                                @break
                                @case('reject')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-gray-500/10 text-gray-300 border border-gray-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #9CA3AF; border-radius: 0;"></span>
                                    Reject
                                </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.qc.show', $qc->id) }}" 
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 rounded-sm transition-all duration-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius: 0;">
                                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Data QC</p>
                                <p class="text-emerald-200/30 text-xs mt-2">Belum ada inspeksi quality control yang tercatat</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($qualityControls->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $qualityControls->links() }}
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
</div>
@endsection
