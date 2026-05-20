@extends('layouts.app')

@section('title', 'Jadwal Produksi')
@section('header', 'JADWAL PRODUKSI')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
    .cyber-card {
        background: rgba(21, 31, 50, 0.6);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(14, 165, 233, 0.3);
    }
    .cyber-card:hover {
        border-color: rgba(14, 165, 233, 0.6);
        box-shadow: 0 0 20px rgba(14, 165, 233, 0.08);
    }
    .filter-btn {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transition: all 0.15s ease;
    }
    .filter-btn-active {
        background: rgba(14, 165, 233, 0.15);
        border-color: rgba(14, 165, 233, 0.5);
        color: #38bdf8;
    }
    .glow-on-hover:hover {
        box-shadow: 0 0 15px rgba(14, 165, 233, 0.6);
    }
</style>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeFilter: '{{ request('status', '') }}' }">

    {{-- Filter Nav --}}
    <div class="flex flex-wrap items-center gap-2 px-4 py-3"
         style="background: rgba(11, 15, 25, 0.8); border: 1px solid rgba(14, 165, 233, 0.15);">
        <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/60 mr-2 font-mono">FILTER</span>
        <a href="{{ route('operator.schedules.index') }}"
           @click.prevent="activeFilter = ''; window.location = $el.href"
           class="filter-btn px-4 py-1.5 border"
           :class="activeFilter === '' ? 'filter-btn-active' : 'border-sky-500/20 text-sky-400/50 hover:border-sky-500/40 hover:text-sky-400/80'">
            Semua
        </a>
        <a href="{{ route('operator.schedules.index', ['status' => 'draft']) }}"
           @click.prevent="activeFilter = 'draft'; window.location = $el.href"
           class="filter-btn px-4 py-1.5 border"
           :class="activeFilter === 'draft' ? 'filter-btn-active' : 'border-sky-500/20 text-sky-400/50 hover:border-sky-500/40 hover:text-sky-400/80'">
            Pending / Draft
        </a>
        <a href="{{ route('operator.schedules.index', ['status' => 'approved']) }}"
           @click.prevent="activeFilter = 'approved'; window.location = $el.href"
           class="filter-btn px-4 py-1.5 border"
           :class="activeFilter === 'approved' ? 'filter-btn-active' : 'border-sky-500/20 text-sky-400/50 hover:border-sky-500/40 hover:text-sky-400/80'">
            Terjadwal / Approved
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4">
        <div class="border border-sky-500/20 bg-[#151f32]/40 backdrop-blur-sm px-5 py-4" style="border-radius: 0;">
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/50 font-mono">Pending / Draft</p>
            <p class="text-3xl font-black text-sky-400 mt-1 font-mono">{{ $schedules->where('status', 'draft')->count() }}</p>
        </div>
        <div class="border border-sky-500/20 bg-[#151f32]/40 backdrop-blur-sm px-5 py-4" style="border-radius: 0;">
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/50 font-mono">Ready To Run</p>
            <p class="text-3xl font-black text-cyan-400 mt-1 font-mono">{{ $schedules->where('status', 'approved')->count() }}</p>
        </div>
    </div>

    {{-- Schedule Cards --}}
    @if($schedules->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($schedules as $schedule)
        <div class="cyber-card transition-all duration-200 flex flex-col" style="border-radius: 0;">
            {{-- Card Header: Tanggal --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-sky-500/15"
                 style="background: rgba(11, 15, 25, 0.5);">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-sky-400/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-bold text-sky-400 font-mono tracking-wider">
                        {{ $schedule->recom_date ? \Carbon\Carbon::parse($schedule->recom_date)->format('d M Y') : '-- -- ----' }}
                    </span>
                </div>
                <span class="text-[10px] font-mono text-sky-400/40 font-bold uppercase tracking-[0.15em]">#{{ $schedule->priority_order ?? '--' }}</span>
            </div>

            {{-- Card Body --}}
            <div class="p-5 flex-1 flex flex-col gap-4">
                {{-- Batch Number --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/40 font-mono mb-1">BATCH ID</p>
                    <p class="text-base font-bold text-cyan-300 font-mono tracking-wider">
                        @if($schedule->batch_number_recommendation)
                            {{ $schedule->batch_number_recommendation }}
                        @else
                            <span class="text-sky-400/50">BCH-AUTO-GEN</span>
                        @endif
                    </p>
                </div>

                {{-- Product Name --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/40 font-mono mb-1">PRODUK</p>
                    <p class="text-sm font-bold text-white">{{ $schedule->product->name ?? 'Unknown Product' }}</p>
                </div>

                {{-- Target Quantity --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/40 font-mono mb-1">TARGET KUANTITAS</p>
                    <p class="text-lg font-black text-sky-300 font-mono tracking-tight">
                        {{ number_format($schedule->recommended_quantity ?? 0) }}
                        <span class="text-[11px] font-bold text-sky-400/50 tracking-[0.15em]">UNIT</span>
                    </p>
                </div>

                {{-- Critical Material (FEFO) --}}
                @if($schedule->critical_material_name)
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/40 font-mono mb-1">BAHAN KRITIS / FEFO</p>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-cyan-500/25 bg-cyan-500/5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span class="text-xs font-semibold text-cyan-300 font-mono">{{ $schedule->critical_material_name }}</span>
                    </div>
                </div>
                @endif

                {{-- Status Badge --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/40 font-mono mb-1">STATUS</p>
                    @if($schedule->status === 'draft')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.15em] font-mono border border-sky-500/30 text-sky-400/80 bg-sky-500/5" style="border-radius: 0;">
                        <span class="w-1.5 h-1.5 bg-sky-400/60" style="border-radius: 0;"></span>
                        SYS_DRAFT / PENDING
                    </span>
                    @elseif($schedule->status === 'approved')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.15em] font-mono border border-cyan-400/40 text-cyan-300 bg-cyan-500/10" style="border-radius: 0;">
                        <span class="w-1.5 h-1.5 bg-cyan-400" style="border-radius: 0;"></span>
                        READY_TO_RUN
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.15em] font-mono border border-sky-500/20 text-sky-400/50 bg-sky-500/5" style="border-radius: 0;">
                        {{ strtoupper($schedule->status) }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Card Footer: Action --}}
            <div class="px-5 py-4 border-t border-sky-500/15" style="background: rgba(11, 15, 25, 0.3);">
                @if($schedule->status === 'approved')
                <a href="{{ url('/operator/productions/create?scheduling_id=' . $schedule->id) }}"
                   class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 text-xs font-black uppercase tracking-[0.15em] font-mono transition-all duration-150 bg-sky-500 text-white hover:bg-cyan-400 hover:shadow-[0_0_15px_rgba(14,165,233,0.6)] glow-on-hover"
                   style="border-radius: 0;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    KERJAKAN SEKARANG
                </a>
                @else
                <div class="w-full px-5 py-2.5 text-xs font-bold uppercase tracking-[0.15em] font-mono text-center text-sky-400/30 border border-sky-500/10" style="border-radius: 0;">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        MENUNGGU PERSETUJUAN
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    {{-- Empty State --}}
    <div class="border border-sky-500/15 bg-[#151f32]/30 backdrop-blur-sm px-8 py-16 text-center" style="border-radius: 0;">
        <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-sky-500/20 bg-[#0b0f19]/50" style="border-radius: 0;">
            <svg class="w-8 h-8 text-sky-400/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h5 class="text-lg font-bold text-sky-400/80 mb-2 font-mono">TIDAK ADA JADWAL</h5>
        <p class="text-sm text-sky-400/40 max-w-md mx-auto font-mono">
            BELUM TERDAPAT REKOMENDASI JADWAL PRODUKSI. SILAKAN TUNGGU ADMIN MELAKUKAN PENJADWALAN MELALUI ALGORITMA GENETIKA.
        </p>
    </div>
    @endif
</div>
@endsection
