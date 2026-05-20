@extends('layouts.app')

@section('title', 'Operator Dashboard')
@section('header', 'DASHBOARD OPERASIONAL')

@section('styles')
<style>
    [x-cloak] { display: none !important; }
    .dashboard-card {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(51, 65, 85, 0.5);
    }
    .dashboard-card:hover {
        border-color: rgba(56, 189, 248, 0.3);
    }
    .stat-card {
        background: rgba(21, 31, 50, 0.6);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(56, 189, 248, 0.15);
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 0 20px rgba(56, 189, 248, 0.06);
    }
    .table-header {
        background: rgba(30, 41, 59, 0.8);
        border-bottom: 1px solid rgba(51, 65, 85, 0.5);
    }
    .table-row {
        transition: all 0.15s ease;
    }
    .table-row:hover {
        background: rgba(30, 41, 59, 0.4);
    }
    .chart-tooltip {
        position: absolute;
        background: rgba(11, 15, 25, 0.95);
        border: 1px solid rgba(56, 189, 248, 0.3);
        padding: 6px 10px;
        font-size: 10px;
        font-family: monospace;
        color: #38bdf8;
        pointer-events: none;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- ============ STAT ROW ============ --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="stat-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[2px] h-full bg-gradient-to-b from-sky-400 to-transparent opacity-60"></div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/60 font-mono">Sedang Berjalan</span>
                <div class="w-9 h-9 bg-sky-500/15 flex items-center justify-center border border-sky-500/20">
                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-4xl font-black text-white font-mono tracking-tight">{{ $inProgressCount }}</p>
            <div class="mt-2 h-[2px] bg-gradient-to-r from-sky-400/50 to-transparent"></div>
        </div>

        <div class="stat-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[2px] h-full bg-gradient-to-b from-cyan-400 to-transparent opacity-60"></div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-cyan-400/60 font-mono">Menunggu QC</span>
                <div class="w-9 h-9 bg-cyan-500/15 flex items-center justify-center border border-cyan-500/20">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-4xl font-black text-white font-mono tracking-tight">{{ $qcCheckCount }}</p>
            <div class="mt-2 h-[2px] bg-gradient-to-r from-cyan-400/50 to-transparent"></div>
        </div>

        <div class="stat-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[2px] h-full bg-gradient-to-b from-emerald-400 to-transparent opacity-60"></div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 font-mono">Selesai Bulan Ini</span>
                <div class="w-9 h-9 bg-emerald-500/15 flex items-center justify-center border border-emerald-500/20">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <p class="text-4xl font-black text-white font-mono tracking-tight">{{ $completedCount }}</p>
            <div class="mt-2 h-[2px] bg-gradient-to-r from-emerald-400/50 to-transparent"></div>
        </div>

        <div class="stat-card p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-[2px] h-full bg-gradient-to-b from-violet-400 to-transparent opacity-60"></div>
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-violet-400/60 font-mono">QC Pass Rate</span>
                <div class="w-9 h-9 bg-violet-500/15 flex items-center justify-center border border-violet-500/20">
                    <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <p class="text-4xl font-black text-white font-mono tracking-tight">{{ $qcPassRate }}<span class="text-lg text-violet-400/60">%</span></p>
            <div class="mt-2 h-[2px] bg-gradient-to-r from-violet-400/50 to-transparent"></div>
        </div>
    </div>

    {{-- ============ CHARTS ROW ============ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- LEFT: Donut + Bar --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Donut: Status Distribusi --}}
            <div class="dashboard-card p-5 relative">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-4 bg-sky-400"></div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.15em] text-sky-400/80 font-mono">Status Produksi</h3>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="w-32 h-32 -rotate-90" viewBox="0 0 100 100">
                        @php
                            $circumference = 2 * pi() * 38;
                            $total = max($inProgressCount + $qcCheckCount + $completedCount, 1);
                            $segments = [
                                ['label' => 'Proses', 'count' => $inProgressCount, 'color' => '#38bdf8'],
                                ['label' => 'QC', 'count' => $qcCheckCount, 'color' => '#22d3ee'],
                                ['label' => 'Selesai', 'count' => $completedCount, 'color' => '#34d399'],
                            ];
                            $offset = 0;
                        @endphp
                        @foreach($segments as $seg)
                        @php
                            $len = ($seg['count'] / $total) * $circumference;
                            $gap = max($circumference - $len, 0);
                        @endphp
                        <circle cx="50" cy="50" r="38" fill="none"
                                stroke="{{ $seg['color'] }}" stroke-width="12"
                                stroke-dasharray="{{ $len }} {{ $gap }}"
                                stroke-dashoffset="{{ -$offset }}"
                                class="transition-all duration-500" />
                        @php $offset += $len; @endphp
                        @endforeach
                        <circle cx="50" cy="50" r="38" fill="none"
                                stroke="rgba(56,189,248,0.08)" stroke-width="12"
                                stroke-dasharray="{{ $circumference }}" />
                    </svg>
                    <p class="text-2xl font-black text-white font-mono mt-2">{{ $totalActive }}</p>
                    <p class="text-[9px] font-mono text-sky-400/40 uppercase tracking-[0.15em]">Total Batch</p>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-1 text-center">
                    @foreach($segments as $seg)
                    <div>
                        <div class="w-2 h-2 mx-auto mb-1" style="background:{{ $seg['color'] }}"></div>
                        <p class="text-[9px] font-bold font-mono text-sky-400/60">{{ $seg['count'] }}</p>
                        <p class="text-[7px] font-mono text-sky-400/30 uppercase tracking-[0.1em]">{{ $seg['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Bar Chart: Aktivitas Mingguan --}}
            <div class="dashboard-card p-5 relative">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-4 bg-cyan-400"></div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.15em] text-cyan-400/80 font-mono">Aktivitas 7 Hari</h3>
                </div>
                <div class="flex items-end justify-center gap-2 h-36 pt-4">
                    @php $maxVal = max(max($weeklyData), 1); @endphp
                    @foreach($weeklyLabels as $idx => $label)
                    @php
                        $height = max(($weeklyData[$idx] / $maxVal) * 100, 4);
                        $barColor = $idx === 6 ? 'rgba(56,189,248,0.9)' : 'rgba(56,189,248,0.4)';
                    @endphp
                    <div class="flex flex-col items-center gap-1.5 flex-1">
                        <span class="text-[9px] font-bold font-mono text-sky-400/70">{{ $weeklyData[$idx] }}</span>
                        <div class="w-full flex justify-center">
                            <div class="w-5 transition-all duration-300" style="height: {{ $height }}px; background: linear-gradient(to top, {{ $barColor }}, rgba(34,211,238,0.6));"></div>
                        </div>
                        <span class="text-[8px] font-mono text-sky-400/40 uppercase">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT: QC Ring + Stock Health --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- QC Pass Rate Ring --}}
            <div class="dashboard-card p-5 relative">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-4 bg-violet-400"></div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.15em] text-violet-400/80 font-mono">Quality Control</h3>
                </div>
                <div class="flex flex-col items-center">
                    <svg class="w-32 h-32 -rotate-90" viewBox="0 0 100 100">
                        @php
                            $ringR = 38;
                            $ringC = 2 * pi() * $ringR;
                            $passedLen = ($qcPassRate / 100) * $ringC;
                            $remainLen = $ringC - $passedLen;
                        @endphp
                        <circle cx="50" cy="50" r="{{ $ringR }}" fill="none"
                                stroke="rgba(52,211,153,0.1)" stroke-width="10" />
                        <circle cx="50" cy="50" r="{{ $ringR }}" fill="none"
                                stroke="#34d399" stroke-width="10"
                                stroke-dasharray="{{ $passedLen }} {{ $remainLen }}"
                                stroke-linecap="butt"
                                class="transition-all duration-700" />
                        <circle cx="50" cy="50" r="{{ $ringR }}" fill="none"
                                stroke="rgba(244,63,94,0.08)" stroke-width="10"
                                stroke-dasharray="{{ $remainLen }}"
                                stroke-dashoffset="{{ -$passedLen }}" />
                    </svg>
                    <p class="text-2xl font-black text-white font-mono mt-2">{{ $qcPassRate }}<span class="text-sm text-emerald-400/60">%</span></p>
                    <p class="text-[9px] font-mono text-sky-400/40 uppercase tracking-[0.15em]">Pass Rate</p>
                </div>
                <div class="mt-4 grid grid-cols-3 gap-1 text-center">
                    <div>
                        <p class="text-xs font-bold font-mono text-emerald-400">{{ $qcPassed }}</p>
                        <p class="text-[7px] font-mono text-emerald-400/40 uppercase tracking-[0.1em]">Pass</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold font-mono text-amber-400">{{ $qcRework }}</p>
                        <p class="text-[7px] font-mono text-amber-400/40 uppercase tracking-[0.1em]">Rework</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold font-mono text-rose-400">{{ $qcRejected }}</p>
                        <p class="text-[7px] font-mono text-rose-400/40 uppercase tracking-[0.1em]">Reject</p>
                    </div>
                </div>
            </div>

            {{-- Stock Health --}}
            <div class="dashboard-card p-5 relative">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-1 h-4 bg-emerald-400"></div>
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/80 font-mono">Kesehatan Stok</h3>
                </div>
                @php
                    $maxStock = max($safeStockCount + $lowStockCount + $outStockCount, 1);
                    $safePct = ($safeStockCount / $maxStock) * 100;
                    $lowPct = ($lowStockCount / $maxStock) * 100;
                    $outPct = ($outStockCount / $maxStock) * 100;
                @endphp
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-[9px] font-bold font-mono text-emerald-400/80">TERSEDIA</span>
                            <span class="text-[9px] font-mono text-emerald-400/60">{{ $safeStockCount }}</span>
                        </div>
                        <div class="h-2 bg-slate-800/50">
                            <div class="h-full bg-emerald-500/60 transition-all duration-500" style="width: {{ $safePct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-[9px] font-bold font-mono text-amber-400/80">RENDAH</span>
                            <span class="text-[9px] font-mono text-amber-400/60">{{ $lowStockCount }}</span>
                        </div>
                        <div class="h-2 bg-slate-800/50">
                            <div class="h-full bg-amber-500/60 transition-all duration-500" style="width: {{ $lowPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-[9px] font-bold font-mono text-rose-400/80">HABIS</span>
                            <span class="text-[9px] font-mono text-rose-400/60">{{ $outStockCount }}</span>
                        </div>
                        <div class="h-2 bg-slate-800/50">
                            <div class="h-full bg-rose-500/60 transition-all duration-500" style="width: {{ $outPct }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-sky-500/10">
                    <p class="text-[9px] font-mono text-sky-400/40 text-center">Total {{ $totalMaterials }} Bahan Baku Terdaftar</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ TABLE PRODUKSI AKTIF ============ --}}
    <div>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-1.5 h-6 bg-sky-400"></div>
            <h3 class="text-sm font-black text-white uppercase tracking-[0.12em] font-mono">Produksi Aktif</h3>
            <div class="flex-1 h-[2px] bg-gradient-to-r from-sky-400/30 to-transparent"></div>
            @if($activeProductions->isNotEmpty())
            <span class="text-[9px] font-mono text-sky-400/50 border border-sky-500/20 px-2 py-0.5">{{ $activeProductions->count() }} batch</span>
            @endif
        </div>

        <div class="border border-sky-500/10 bg-[#0b0f19]/60 backdrop-blur-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="table-header">
                            <th class="px-5 py-3.5 text-left w-12">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">#</span>
                            </th>
                            <th class="px-5 py-3.5 text-left">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">No Batch</span>
                            </th>
                            <th class="px-5 py-3.5 text-left">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">Produk</span>
                            </th>
                            <th class="px-5 py-3.5 text-left">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">Target Qty</span>
                            </th>
                            <th class="px-5 py-3.5 text-left">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">Mulai</span>
                            </th>
                            <th class="px-5 py-3.5 text-left">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">Status</span>
                            </th>
                            <th class="px-5 py-3.5 text-center">
                                <span class="text-[9px] font-bold font-mono uppercase tracking-[0.15em] text-sky-400/50">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sky-500/8">
                        @forelse($activeProductions as $idx => $production)
                        <tr class="table-row">
                            <td class="px-5 py-4">
                                <span class="text-[10px] font-mono text-sky-400/30 font-bold">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 flex items-center justify-center shrink-0 bg-sky-500/10 border border-sky-500/20">
                                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-white font-mono tracking-wider">{{ $production->batch_number }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-semibold text-sky-300">{{ $production->product->name ?? 'Produk' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-sm font-bold text-white font-mono">{{ number_format($production->target_quantity ?? 0) }}</span>
                                <span class="text-[9px] font-mono text-sky-400/30 ml-1">{{ $production->product->unit ?? 'unit' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                @if($production->start_date)
                                <span class="text-xs font-mono text-sky-400/70">{{ \Carbon\Carbon::parse($production->start_date)->format('d/m H:i') }}</span>
                                @elseif($production->scheduled_start)
                                <span class="text-xs font-mono text-sky-400/50">{{ \Carbon\Carbon::parse($production->scheduled_start)->format('d/m H:i') }}</span>
                                @else
                                <span class="text-xs font-mono text-sky-400/30">--:--</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($production->status == 'in_progress')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold font-mono uppercase tracking-[0.12em] border border-sky-500/30 bg-sky-500/8 text-sky-400">
                                    <span class="w-1.5 h-1.5 bg-sky-400 animate-pulse"></span>
                                    Proses
                                </span>
                                @elseif($production->status == 'qc_check')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold font-mono uppercase tracking-[0.12em] border border-cyan-500/30 bg-cyan-500/8 text-cyan-400">
                                    <span class="w-1.5 h-1.5 bg-cyan-400"></span>
                                    QC Check
                                </span>
                                @elseif($production->status == 'rework')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[9px] font-bold font-mono uppercase tracking-[0.12em] border border-amber-500/30 bg-amber-500/8 text-amber-400">
                                    <span class="w-1.5 h-1.5 bg-amber-400"></span>
                                    Rework
                                </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($production->status == 'qc_check')
                                <a href="{{ route('operator.qc.create', ['production_id' => $production->id]) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-1.5 text-[9px] font-bold font-mono uppercase tracking-[0.12em] transition-all duration-150 bg-cyan-500 hover:bg-cyan-400 text-white">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Cek QC
                                </a>
                                @else
                                <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="qc_check">
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-4 py-1.5 text-[9px] font-bold font-mono uppercase tracking-[0.12em] transition-all duration-150 bg-sky-500 hover:bg-sky-400 text-white">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 flex items-center justify-center border border-sky-500/15 bg-[#0b0f19]/50 mb-3">
                                        <svg class="w-7 h-7 text-sky-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    </div>
                                    <p class="text-sm font-bold text-sky-400/60 font-mono mb-1">BELUM ADA PRODUKSI AKTIF</p>
                                    <p class="text-[10px] font-mono text-sky-400/30">Silakan buat produksi baru melalui menu Input Produksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
