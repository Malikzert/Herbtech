@extends('layouts.admin')

@section('title', 'Detail QC')
@section('header', 'Detail Quality Control')

@section('content')
<div class="space-y-6">
    <!-- QC Info Card -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-white">Batch: {{ $qc->production->batch_number ?? '-' }}</h3>
                <p class="text-gray-400 mt-1">{{ $qc->production->product->name ?? '-' }}</p>
            </div>
            <div>
                @switch($qc->status)
                    @case('passed')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-emerald-500/30 text-emerald-300">Passed</span>
                        @break
                    @case('partial_reject')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-amber-500/30 text-amber-300">Partial Reject</span>
                        @break
                    @case('full_reject')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-500/30 text-red-300">Full Reject</span>
                        @break
                @endswitch
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div>
                <p class="text-xs text-gray-400 uppercase">Inspector</p>
                <p class="text-sm font-medium text-white">{{ $qc->inspector_name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Waktu Inspeksi</p>
                <p class="text-sm font-medium text-white">{{ $qc->inspected_at ? $qc->inspected_at->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Total Diperiksa</p>
                <p class="text-sm font-medium text-white">{{ $qc->total_inspected ?? 0 }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Aksi</p>
                <span class="px-2 py-1 text-xs font-medium rounded-full 
                    @if($qc->action === 'release') bg-emerald-500/30 text-emerald-300
                    @elseif($qc->action === 'rework') bg-amber-500/30 text-amber-300
                    @else bg-red-500/30 text-red-300 @endif">
                    {{ ucfirst($qc->action ?? '-') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Inspection Results -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Hasil Inspeksi</h4>
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-emerald-500/20 border border-emerald-400/30 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-emerald-400">{{ $qc->total_passed ?? 0 }}</p>
                <p class="text-sm text-emerald-300">Passed</p>
            </div>
            <div class="bg-red-500/20 border border-red-400/30 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-red-400">{{ $qc->total_rejected ?? 0 }}</p>
                <p class="text-sm text-red-300">Rejected</p>
            </div>
            <div class="bg-white/10 border border-white/30 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-white">{{ $qc->total_inspected ?? 0 }}</p>
                <p class="text-sm text-gray-300">Total</p>
            </div>
        </div>
    </div>

    <!-- Defects -->
    @if($qc->qcDefects && $qc->qcDefects->count() > 0)
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Detail Defect</h4>
        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead class="glass-table text-gray-300 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2 font-medium text-left">Kategori Defect</th>
                        <th class="px-4 py-2 font-medium text-center">Jumlah</th>
                        <th class="px-4 py-2 font-medium text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @foreach($qc->qcDefects as $defect)
                    <tr>
                        <td class="px-4 py-3 text-sm text-white">{{ $defect->defectCategory->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-center font-medium text-red-400">{{ $defect->quantity }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $defect->notes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div>
        <a href="{{ route('admin.qc.index') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar QC
        </a>
    </div>
</div>
@endsection