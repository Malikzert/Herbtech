@extends('layouts.app')

@section('title', 'Detail QC')
@section('header', 'Detail Quality Control #' . $qc->id)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.qc.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        <a href="{{ route('operator.qc.edit', $qc->id) }}" class="px-4 py-2 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 transition">Edit QC</a>
    </div>

    <div class="bg-white/60 backdrop-blur-md rounded-xl border border-white/20 p-6 shadow-sm glass-card">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Informasi Inspeksi</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Batch Produksi</p>
                <p class="text-base font-semibold text-gray-900">{{ $qc->production->batch_number ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Produk</p>
                <p class="text-base font-semibold text-gray-900">{{ $qc->production->product->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Inspektur</p>
                <p class="text-base font-semibold text-gray-900">{{ $qc->inspector_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Waktu Inspeksi</p>
                <p class="text-base font-semibold text-gray-900">{{ $qc->inspected_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-base font-semibold text-gray-900 mt-1">
                    @switch($qc->status)
                        @case('passed')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Passed</span>
                            @break
                        @case('partial_reject')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Partial Reject</span>
                            @break
                        @case('full_reject')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Full Reject</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tindakan Lanjut</p>
                <p class="text-base font-semibold text-gray-900 mt-1">
                    <span class="px-2 py-1 text-xs rounded-full {{ $qc->action == 'release' ? 'bg-emerald-100 text-emerald-700' : ($qc->action == 'rework' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                        {{ ucfirst($qc->action) }}
                    </span>
                </p>
            </div>
        </div>

        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mt-8 mb-4">Hasil Kuantitatif</h3>
        <div class="grid grid-cols-3 gap-6 text-center">
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                <p class="text-sm text-gray-500 font-medium">Total Diperiksa</p>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $qc->total_inspected }}</p>
            </div>
            <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
                <p class="text-sm text-emerald-600 font-medium">Passed</p>
                <p class="text-3xl font-bold text-emerald-700 mt-2">{{ $qc->total_passed }}</p>
            </div>
            <div class="bg-red-50 rounded-xl p-4 border border-red-100">
                <p class="text-sm text-red-600 font-medium">Rejected</p>
                <p class="text-3xl font-bold text-red-700 mt-2">{{ $qc->total_rejected }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
