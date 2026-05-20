@extends('layouts.admin')

@section('title', 'Laporan')
@section('header', 'LAPORAN')

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
    .hybrid-input::placeholder { color: rgba(255,255,255,0.3); }
    .hybrid-input::-webkit-calendar-picker-indicator {
        filter: invert(0.7) sepia(1) hue-rotate(120deg);
        cursor: pointer;
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
    typeOpen: false, 
    typeSelected: '{{ request('type', 'production') }}', 
    typeLabel: '{{ request('type') === 'raw_material' ? 'Bahan Baku' : (request('type') === 'qc' ? 'Quality Control' : 'Produksi') }}'
}">
    {{-- FILTER FORM --}}
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/30 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Filter Laporan</h3>
                        <p class="text-xs text-emerald-200/40">Atur periode dan jenis laporan</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Jenis Laporan</label>
                        <div class="relative" x-data="{ open: false }">
                            <input type="hidden" name="type" :value="typeSelected">
                            <button type="button"
                                @click="open = !open; if(open) { $nextTick(() => { 
                                    let r = $el.getBoundingClientRect(); 
                                    $refs.typeMenu.style.top = (r.bottom + window.scrollY + 6) + 'px'; 
                                    $refs.typeMenu.style.left = (r.left + window.scrollX) + 'px'; 
                                    $refs.typeMenu.style.width = Math.max(r.width, 180) + 'px'; 
                                }) }"
                                class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[180px] whitespace-nowrap">
                                <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="typeLabel"></span>
                                <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <template x-teleport="body">
                                <div x-ref="typeMenu"
                                    x-show="open" @click.outside="open = false"
                                    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                    class="fixed z-[9999] rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] overflow-hidden" style="display: none;">
                                    <div class="py-1">
                                        <button type="button" @click="typeSelected = 'production'; typeLabel = 'Produksi'; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                            :class="typeSelected === 'production' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                            <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'production' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="font-bold uppercase tracking-wider text-[10px]">Produksi</span>
                                        </button>
                                        <button type="button" @click="typeSelected = 'qc'; typeLabel = 'Quality Control'; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                            :class="typeSelected === 'qc' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                            <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'qc' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="font-bold uppercase tracking-wider text-[10px]">Quality Control</span>
                                        </button>
                                        <button type="button" @click="typeSelected = 'raw_material'; typeLabel = 'Bahan Baku'; open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                            :class="typeSelected === 'raw_material' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                            <svg class="w-4 h-4 shrink-0" :class="typeSelected === 'raw_material' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            <span class="font-bold uppercase tracking-wider text-[10px]">Bahan Baku</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}"
                            class="hybrid-input h-11 px-4 rounded-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date', now()->endOfMonth()->toDateString()) }}"
                            class="hybrid-input h-11 px-4 rounded-sm text-sm">
                    </div>
                    <button type="submit" class="inline-flex items-center gap-1.5 h-11 px-6 rounded-sm text-xs font-bold uppercase tracking-wider"
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease; border: none;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tampilkan
                    </button>
                </form>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- PRODUCTION REPORT --}}
    @if($reportType === 'production')
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Laporan Produksi</h3>
                                    <p class="text-xs text-emerald-200/40">{{ $startDate }} - {{ $endDate }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div x-data="{ format: 'csv' }" class="flex items-center">
                                    <a :href="format === 'csv' ? '{{ route('admin.reports.export-csv', ['type' => 'production', 'start_date' => $startDate, 'end_date' => $endDate]) }}' : '{{ route('admin.reports.export-excel', ['type' => 'production', 'start_date' => $startDate, 'end_date' => $endDate]) }}'"
                                       class="h-9 px-3 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-blue-600/20 border border-blue-500/40 text-blue-300 hover:bg-blue-600/30 transition-all duration-200 flex items-center gap-1">
                                        <span :class="format === 'csv' ? 'text-blue-300 font-bold' : 'text-blue-300/40'">CSV</span>
                                        <span class="text-blue-300/30">/</span>
                                        <span :class="format === 'xlsx' ? 'text-blue-300 font-bold' : 'text-blue-300/40'">XLSX</span>
                                    </a>
                                    <button @click="format = format === 'csv' ? 'xlsx' : 'csv'"
                                            class="h-7 px-1.5 rounded-sm text-[9px] font-bold bg-blue-600/10 border border-blue-500/20 text-blue-300 hover:bg-blue-600/20 transition-all duration-200 ml-1">↕</button>
                                </div>
                                <div x-data="{ orient: 'landscape' }" class="flex items-center">
                                    <a :href="'{{ route('admin.reports.export-pdf', ['type' => 'production', 'start_date' => $startDate, 'end_date' => $endDate]) }}&orientation=' + orient"
                                       class="h-9 px-3 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-red-600/20 border border-red-500/40 text-red-300 hover:bg-red-600/30 transition-all duration-200 flex items-center gap-1">
                                        <span :class="orient === 'landscape' ? 'text-red-300 font-bold' : 'text-red-300/40'">PDF L</span>
                                        <span class="text-red-300/30">/</span>
                                        <span :class="orient === 'portrait' ? 'text-red-300 font-bold' : 'text-red-300/40'">PDF P</span>
                                    </a>
                                    <button @click="orient = orient === 'landscape' ? 'portrait' : 'landscape'"
                                            class="h-7 px-1.5 rounded-sm text-[9px] font-bold bg-red-600/10 border border-red-500/20 text-red-300 hover:bg-red-600/20 transition-all duration-200 ml-1">↕</button>
                                </div>
                            </div>
                        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 border-b border-emerald-500/10">
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-3xl font-black text-emerald-50">{{ $totalProductions ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Total Batch</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-3xl font-black text-emerald-300">{{ $completedCount ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Completed</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-3xl font-black text-amber-300">{{ $inProgressCount ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">On Progress</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-3xl font-black text-blue-300">{{ $completionRate ?? 0 }}%</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Completion Rate</div>
            </div>
        </div>

        @if(($productions ?? collect())->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No Batch</th>
                        <th class="px-6 py-4 text-left">Produk</th>
                        <th class="px-6 py-4 text-left">Operator</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @foreach($productions as $production)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $production->batch_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $production->product->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $production->user->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $production->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($production->status)
                                @case('draft')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-gray-500/10 text-gray-300 border border-gray-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #9CA3AF; border-radius: 0;"></span>
                                    Draft
                                </span>
                                @break
                                @case('pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                    Pending
                                </span>
                                @break
                                @case('in_progress')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                    On Progress
                                </span>
                                @break
                                @case('qc_check')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                    QC Check
                                </span>
                                @break
                                @case('rework')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-300 border border-purple-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #A78BFA; border-radius: 0;"></span>
                                    Rework
                                </span>
                                @break
                                @case('completed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                    Completed
                                </span>
                                @break
                                @case('cancelled')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-900/30 text-blue-300 border border-blue-500/30" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #3B82F6; border-radius: 0;"></span>
                                    Cancelled
                                </span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-4" style="border-radius: 0;">
                    <svg class="w-8 h-8 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Tidak Ada Data Produksi</p>
                <p class="text-emerald-200/30 text-xs mt-1">Pada periode yang dipilih</p>
            </div>
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
    @endif

    {{-- QC REPORT --}}
    @if($reportType === 'qc')
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Laporan Quality Control</h3>
                                    <p class="text-xs text-emerald-200/40">{{ $startDate }} - {{ $endDate }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div x-data="{ format: 'csv' }" class="flex items-center">
                                    <a :href="format === 'csv' ? '{{ route('admin.reports.export-csv', ['type' => 'qc', 'start_date' => $startDate, 'end_date' => $endDate]) }}' : '{{ route('admin.reports.export-excel', ['type' => 'qc', 'start_date' => $startDate, 'end_date' => $endDate]) }}'"
                                       class="h-9 px-3 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-blue-600/20 border border-blue-500/40 text-blue-300 hover:bg-blue-600/30 transition-all duration-200 flex items-center gap-1">
                                        <span :class="format === 'csv' ? 'text-blue-300 font-bold' : 'text-blue-300/40'">CSV</span>
                                        <span class="text-blue-300/30">/</span>
                                        <span :class="format === 'xlsx' ? 'text-blue-300 font-bold' : 'text-blue-300/40'">XLSX</span>
                                    </a>
                                    <button @click="format = format === 'csv' ? 'xlsx' : 'csv'"
                                            class="h-7 px-1.5 rounded-sm text-[9px] font-bold bg-blue-600/10 border border-blue-500/20 text-blue-300 hover:bg-blue-600/20 transition-all duration-200 ml-1">↕</button>
                                </div>
                                <div x-data="{ orient: 'landscape' }" class="flex items-center">
                                    <a :href="'{{ route('admin.reports.export-pdf', ['type' => 'qc', 'start_date' => $startDate, 'end_date' => $endDate]) }}&orientation=' + orient"
                                       class="h-9 px-3 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-red-600/20 border border-red-500/40 text-red-300 hover:bg-red-600/30 transition-all duration-200 flex items-center gap-1">
                                        <span :class="orient === 'landscape' ? 'text-red-300 font-bold' : 'text-red-300/40'">L</span>
                                        <span class="text-red-300/30">/</span>
                                        <span :class="orient === 'portrait' ? 'text-red-300 font-bold' : 'text-red-300/40'">P</span>
                                    </a>
                                    <button @click="orient = orient === 'landscape' ? 'portrait' : 'landscape'"
                                            class="h-7 px-1.5 rounded-sm text-[9px] font-bold bg-red-600/10 border border-red-500/20 text-red-300 hover:bg-red-600/20 transition-all duration-200 ml-1">↕</button>
                                </div>
                            </div>
                        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 border-b border-emerald-500/10">
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-2xl font-black text-emerald-50">{{ $totalQc ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Total QC</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-2xl font-black text-emerald-300">{{ $passedCount ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Passed</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-2xl font-black text-amber-300">{{ $partialRejectCount ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Partial Reject</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-2xl font-black text-red-300">{{ $fullRejectCount ?? 0 }}</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Full Reject</div>
            </div>
            <div class="text-center p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm">
                <div class="text-2xl font-black text-blue-300">{{ $passRate ?? 0 }}%</div>
                <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 mt-1">Pass Rate</div>
            </div>
        </div>

        @if(($qcRecords ?? collect())->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">ID QC</th>
                        <th class="px-6 py-4 text-left">Batch</th>
                        <th class="px-6 py-4 text-left">Inspector</th>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Hasil</th>
                        <th class="px-6 py-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @foreach($qcRecords as $qc)
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
                            <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $qc->created_at->format('d M Y') }}</span>
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-900/30 text-blue-300 border border-blue-500/30" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #3B82F6; border-radius: 0;"></span>
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
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-900/30 text-blue-300 border border-blue-500/30" style="border-radius: 0;">
                                    <span class="w-1.5 h-1.5" style="background: #3B82F6; border-radius: 0;"></span>
                                    Reject
                                </span>
                                @break
                            @endswitch
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-16 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-4" style="border-radius: 0;">
                    <svg class="w-8 h-8 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Tidak Ada Data QC</p>
                <p class="text-emerald-200/30 text-xs mt-1">Pada periode yang dipilih</p>
            </div>
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
    @endif

    {{-- RAW MATERIAL REPORT --}}
    @if($reportType === 'raw_material')
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Laporan Bahan Baku</h3>
                                    <p class="text-xs text-emerald-200/40">{{ $startDate }} - {{ $endDate }}</p>
                                </div>
                            </div>
                            <div x-data="{ orient: 'landscape' }" class="flex items-center gap-2">
                                <a href="{{ route('admin.reports.export-excel', ['type' => 'raw_material', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                   class="h-9 px-4 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 hover:bg-emerald-500/25 hover:text-emerald-200 transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    XLSX
                                </a>
                                <a :href="'{{ route('admin.reports.export-pdf', ['type' => 'raw_material', 'start_date' => $startDate, 'end_date' => $endDate]) }}&orientation=' + orient"
                                   class="h-9 px-3 rounded-sm text-[10px] font-bold uppercase tracking-wider bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 hover:bg-emerald-500/25 hover:text-emerald-200 transition-all duration-200 flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    PDF
                                </a>
                                <button @click="orient = orient === 'landscape' ? 'portrait' : 'landscape'"
                                        class="h-7 px-2 rounded-sm text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 hover:bg-emerald-500/20 transition-all duration-200 flex items-center gap-1">
                                    <span :class="orient === 'landscape' ? 'text-emerald-300 font-bold' : 'text-emerald-200/40'">L</span>
                                    <span class="text-emerald-200/20">/</span>
                                    <span :class="orient === 'portrait' ? 'text-emerald-300 font-bold' : 'text-emerald-200/40'">P</span>
                                </button>
                            </div>
                        </div>

        <div class="p-6">
            <div class="inline-flex items-center gap-3 p-4 border border-emerald-500/15 bg-emerald-500/5 rounded-sm mb-6">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0l-4-4m4 4l-4 4M13 17h8m0 0l-4-4m4 4l-4 4M4 7h5m0 0L5 3m4 4L5 11M4 17h5m0 0l-4-4m4 4l-4 4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Penggunaan</p>
                    <p class="text-2xl font-black text-emerald-50">{{ number_format($totalUsage ?? 0, 2) }}</p>
                </div>
            </div>

            @if(($groupedByMaterial ?? collect())->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full hybrid-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left">Bahan Baku</th>
                            <th class="px-6 py-4 text-left">Total Digunakan</th>
                            <th class="px-6 py-4 text-left">Frekuensi Pakai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-500/10">
                        @foreach($groupedByMaterial as $index => $item)
                        <tr class="group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-200/50 group-hover:text-emerald-200/80 transition-colors">{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $item['material_name'] }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-300">{{ number_format($item['total_used'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $item['count'] }}x</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-4" style="border-radius: 0;">
                        <svg class="w-8 h-8 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Tidak Ada Penggunaan Bahan</p>
                    <p class="text-emerald-200/30 text-xs mt-1">Pada periode yang dipilih</p>
                </div>
            </div>
            @endif
        </div>
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
    @endif
</div>
@endsection
