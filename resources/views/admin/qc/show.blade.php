@extends('layouts.admin')

@section('title', 'Detail QC')
@section('header', 'DETAIL QUALITY CONTROL')

@section('content')
<style>
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
</style>
<div class="space-y-6">
    {{-- QC INFO CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-start justify-between pt-4">
            <div>
                <h3 class="text-xl font-black text-emerald-50">Batch: {{ $qc->production->batch_number ?? '-' }}</h3>
                <p class="text-emerald-200/60 text-sm font-bold mt-1 tracking-wide">{{ $qc->production->product->name ?? '-' }}</p>
            </div>
            <div>
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
                        Partial Reject
                    </span>
                    @break
                    @case('full_reject')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-900/30 text-blue-300 border border-blue-500/30" style="border-radius: 0;">
                        <span class="w-1.5 h-1.5" style="background: #3B82F6; border-radius: 0;"></span>
                        Full Reject
                    </span>
                    @break
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-4 border-t border-emerald-500/15">
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Inspector</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $qc->inspector_name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Waktu Inspeksi</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $qc->inspected_at ? $qc->inspected_at->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Diperiksa</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $qc->total_inspected ?? 0 }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Aksi</p>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider mt-1
                    @if($qc->action === 'release') bg-emerald-500/10 text-emerald-300 border border-emerald-500/20
                    @elseif($qc->action === 'rework') bg-amber-500/10 text-amber-300 border border-amber-500/20
                    @else bg-red-500/10 text-red-300 border border-red-500/20 @endif" style="border-radius: 0;">
                    <span class="w-1.5 h-1.5" style="background:
                        @if($qc->action === 'release') #34D399
                        @elseif($qc->action === 'rework') #F59E0B
                        @else #EF4444 @endif; border-radius: 0;"></span>
                    {{ ucfirst($qc->action ?? '-') }}
                </span>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>

    {{-- INSPECTION RESULTS --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 pt-4 mb-4">
            <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">HASIL INSPEKSI</h4>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 text-center border border-emerald-500/20 bg-emerald-500/5" style="border-radius: 0;">
                <p class="text-2xl font-black text-emerald-400">{{ $qc->total_passed ?? 0 }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 mt-1">Passed</p>
            </div>
            <div class="p-4 text-center border border-red-500/20 bg-red-500/5" style="border-radius: 0;">
                <p class="text-2xl font-black text-red-400">{{ $qc->total_rejected ?? 0 }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-red-300 mt-1">Rejected</p>
            </div>
            <div class="p-4 text-center border border-emerald-500/20 bg-emerald-500/5" style="border-radius: 0;">
                <p class="text-2xl font-black text-emerald-50">{{ $qc->total_inspected ?? 0 }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-200/60 mt-1">Total</p>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>

    {{-- DEFECTS TABLE --}}
    @if($qc->qcDefects && $qc->qcDefects->count() > 0)
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 px-5 py-4 border-b border-emerald-500/15">
            <div class="w-8 h-8 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">DETAIL DEFECT</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Kategori Defect</th>
                        <th class="px-6 py-4 text-center">Jumlah</th>
                        <th class="px-6 py-4 text-left">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @foreach($qc->qcDefects as $defect)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $defect->defectCategory->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-sm font-bold text-red-400">{{ $defect->quantity }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60">{{ $defect->notes ?? '-' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
    @endif

    {{-- BACK LINK --}}
    <div>
        <a href="{{ route('admin.qc.index') }}" class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 px-3 py-1.5 transition-all duration-200" style="border-radius: 0;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar QC
        </a>
    </div>
</div>
@endsection
