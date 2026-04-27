@extends('layouts.admin')

@section('title', 'Laporan')
@section('header', 'Laporan Produksi & QC')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-bold text-white mb-1 text-shadow-sm">Jenis Laporan</label>
                <select name="type" class="input-glass px-4 py-2.5 border border-white/30 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-400">
                    <option value="production" {{ request('type', 'production') === 'production' ? 'selected' : '' }}>Produksi</option>
                    <option value="raw_material" {{ request('type') === 'raw_material' ? 'selected' : '' }}>Bahan Baku</option>
                    <option value="qc" {{ request('type') === 'qc' ? 'selected' : '' }}>Quality Control</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-white mb-1 text-shadow-sm">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}" class="input-glass px-4 py-2.5 border border-white/30 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-400">
            </div>
            <div>
                <label class="block text-sm font-bold text-white mb-1 text-shadow-sm">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->endOfMonth()->toDateString()) }}" class="input-glass px-4 py-2.5 border border-white/30 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-400">
            </div>
            <button type="submit" class="btn-glass px-4 py-2.5 font-medium rounded-xl transition">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Production Report -->
    @if($reportType === 'production')
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-white/30 flex justify-between items-center">
            <h3 class="font-bold text-white text-shadow-sm">Laporan Produksi</h3>
            <span class="text-sm text-white/70">{{ $startDate }} - {{ $endDate }}</span>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 border-b border-white/30">
            <div class="text-center">
                <div class="text-3xl font-bold black">{{ $totalProductions ?? 0 }}</div>
                <div class="text-sm text-white/80">Total Batch</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-emerald-300">{{ $completedCount ?? 0 }}</div>
                <div class="text-sm text-white/80">Completed</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-amber-300">{{ $inProgressCount ?? 0 }}</div>
                <div class="text-sm text-white/80">On Progress</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-300">{{ $completionRate ?? 0 }}%</div>
                <div class="text-sm text-white/80">Completion Rate</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead>
                    <tr>
                        <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm text-xs uppercase">No Batch</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm text-xs uppercase">Produk</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm text-xs uppercase">Operator</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm text-xs uppercase">Tanggal</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm text-xs uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($productions ?? [] as $production)
                    <tr class="hover:bg-white/10">
                        <td class="px-6 py-4 text-sm font-bold black">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm font-medium black">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium black">{{ $production->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium black">{{ $production->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @switch($production->status)
                                @case('completed')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-500/40 text-white border border-emerald-400/50">Completed</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-500/40 text-white border border-blue-400/50">On Progress</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-500/40 text-white border border-red-400/50">Cancelled</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-500/40 text-white border border-gray-400/50">Draft</span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-700 font-medium">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- QC Report -->
    @if($reportType === 'qc')
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden">
        <div class="px-6 py-4 border-b border-white/30">
            <h3 class="font-bold text-white text-shadow-sm">Laporan Quality Control</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 border-b border-white/30">
            <div class="text-center">
                <div class="text-2xl font-bold black">{{ $totalQc ?? 0 }}</div>
                <div class="text-sm text-white/80">Total QC</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-emerald-300">{{ $passedCount ?? 0 }}</div>
                <div class="text-sm text-white/80">Passed</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-amber-300">{{ $partialRejectCount ?? 0 }}</div>
                <div class="text-sm text-white/80">Partial Reject</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-300">{{ $fullRejectCount ?? 0 }}</div>
                <div class="text-sm text-white/80">Full Reject</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-300">{{ $passRate ?? 0 }}%</div>
                <div class="text-sm text-white/80">Pass Rate</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Raw Material Report -->
    @if($reportType === 'raw_material')
    <div class="bg-glass rounded-xl border border-white/50 p-6">
        <h3 class="font-bold text-white text-shadow-sm mb-4">Penggunaan Bahan Baku</h3>
        <p class="black font-bold text-lg">Total penggunaan: {{ number_format($totalUsage ?? 0, 2) }}</p>
    </div>
    @endif
</div>
@endsection