@extends('layouts.app')

@section('title', 'Detail QC')
@section('header', 'DETAIL QUALITY CONTROL #' . $qc->id)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.qc.index') }}" class="text-[#93C5FD] hover:text-[#DBEAFE] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        <a href="{{ route('operator.qc.edit', $qc->id) }}" class="px-4 py-2 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium transition">Edit QC</a>
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6">
        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mb-4 uppercase tracking-[0.05em]">Informasi Inspeksi</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Batch Produksi</p>
                <p class="text-base font-semibold text-white">{{ $qc->production->batch_number ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Produk</p>
                <p class="text-base font-semibold text-[#93C5FD]">{{ $qc->production->product->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Inspektur</p>
                <p class="text-base font-semibold text-white">{{ $qc->inspector_name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Waktu Inspeksi</p>
                <p class="text-base font-semibold text-[#93C5FD]">{{ $qc->inspected_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Status</p>
                <p class="text-base font-semibold mt-1">
                    @switch($qc->status)
                        @case('passed')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30">Passed</span>
                            @break
                        @case('partial_reject')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30">Partial Reject</span>
                            @break
                        @case('full_reject')
                            <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1e3a5f] text-[#93C5FD] border border-[#1e3a5f]">Full Reject</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B]">Tindakan Lanjut</p>
                <p class="text-base font-semibold mt-1">
                    <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase tracking-[0.1em] {{ $qc->action == 'release' ? 'bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30' : ($qc->action == 'rework' ? 'bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30' : 'bg-[#334155] text-[#64748B] border border-[#334155]') }}">
                        {{ ucfirst($qc->action) }}
                    </span>
                </p>
            </div>
        </div>

        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mt-8 mb-4 uppercase tracking-[0.05em]">Hasil Kuantitatif</h3>
        <div class="grid grid-cols-3 gap-6 text-center">
            <div class="bg-[#1e293b]/60 border border-[#334155] p-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748B]">Total Diperiksa</p>
                <p class="text-3xl font-black text-white mt-2">{{ $qc->total_inspected }}</p>
            </div>
            <div class="bg-[#3B82F6]/10 border border-[#3B82F6]/30 p-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#3B82F6]">Passed</p>
                <p class="text-3xl font-black text-[#DBEAFE] mt-2">{{ $qc->total_passed }}</p>
            </div>
            <div class="bg-[#1DA1F2]/10 border border-[#1DA1F2]/30 p-4">
                <p class="text-[10px] font-bold uppercase tracking-[0.1em] text-[#1DA1F2]">Rejected</p>
                <p class="text-3xl font-black text-[#93C5FD] mt-2">{{ $qc->total_rejected }}</p>
            </div>
        </div>

        @if($qc->qcDefects && $qc->qcDefects->count() > 0)
        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mt-8 mb-4 uppercase tracking-[0.05em]">Rincian Cacat</h3>
        <div class="bg-[#1e293b]/60 border border-[#334155] overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-[#1e293b] border-b border-[#334155]">
                    <tr>
                        <th class="px-4 py-3"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Jenis Cacat</span></th>
                        <th class="px-4 py-3 text-right"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Jumlah (Botol)</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#334155]">
                    @foreach($qc->qcDefects as $defect)
                    <tr class="hover:bg-[#1e293b]/50 transition-colors duration-150">
                        <td class="px-4 py-3 text-white">{{ $defect->defectCategory->name ?? 'Kategori tidak diketahui' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-[#93C5FD]">{{ $defect->defect_quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($qc->notes)
        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mt-8 mb-4 uppercase tracking-[0.05em]">Catatan QC</h3>
        <div class="bg-[#1DA1F2]/10 border border-[#1DA1F2]/30 p-4 text-[#93C5FD]">
            {{ $qc->notes }}
        </div>
        @endif
    </div>
</div>
@endsection
