@extends('layouts.app')

@section('title', 'Detail Produksi')
@section('header', 'DETAIL PRODUKSI: ' . $production->batch_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.productions.index') }}" class="text-[#93C5FD] hover:text-[#DBEAFE] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        @if(in_array($production->status, ['draft', 'pending']))
        <a href="{{ route('operator.productions.edit', $production->id) }}" class="px-4 py-2 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium transition">Edit Produksi</a>
        @endif
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6">
        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mb-4 uppercase tracking-[0.05em]">Informasi Produksi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Nomor Batch</p>
                <p class="text-base font-semibold text-white">{{ $production->batch_number }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Produk</p>
                <p class="text-base font-semibold text-[#93C5FD]">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Target Produksi (Qty)</p>
                <p class="text-base font-semibold text-white">{{ $production->target_quantity }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Aktual Produksi (Qty)</p>
                <p class="text-base font-semibold text-white">{{ $production->actual_quantity ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Mulai</p>
                <p class="text-base font-semibold text-[#93C5FD]">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Selesai</p>
                <p class="text-base font-semibold text-[#93C5FD]">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">Status</p>
                <p class="text-base font-semibold mt-1">
                    @switch($production->status)
                        @case('draft')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#64748B]/20 text-[#3B82F6] border border-[#64748B]/30">Draft</span>
                            @break
                        @case('pending')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30">Pending</span>
                            @break
                        @case('in_progress')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30">On Progress</span>
                            @break
                        @case('qc_check')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30">QC Check</span>
                            @break
                        @case('rework')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#64748B]/20 text-[#3B82F6] border border-[#64748B]/30">Rework</span>
                            @break
                        @case('completed')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30">Completed</span>
                            @break
                        @case('cancelled')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#334155] text-[#64748B] border border-[#334155]">Cancelled</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm text-[#64748B] uppercase tracking-[0.1em] text-[10px] font-bold">PIC</p>
                <p class="text-base font-semibold text-white">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>

        @if(auth()->user()->can('admin') || in_array($production->status, ['draft', 'pending']))
        <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="mt-8 border-t border-[#334155] pt-6">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-[#93C5FD] uppercase tracking-[0.1em]">Ubah Status:</label>
                <select name="status" class="px-4 py-2 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                    @if(auth()->user()->can('admin'))
                    <option value="draft" {{ $production->status == 'draft' ? 'selected' : '' }} class="bg-[#0f172a]">Draft</option>
                    <option value="pending" {{ $production->status == 'pending' ? 'selected' : '' }} class="bg-[#0f172a]">Pending</option>
                    <option value="in_progress" {{ $production->status == 'in_progress' ? 'selected' : '' }} class="bg-[#0f172a]">On Progress</option>
                    <option value="qc_check" {{ $production->status == 'qc_check' ? 'selected' : '' }} class="bg-[#0f172a]">QC Check</option>
                    <option value="rework" {{ $production->status == 'rework' ? 'selected' : '' }} class="bg-[#0f172a]">Rework</option>
                    <option value="completed" {{ $production->status == 'completed' ? 'selected' : '' }} class="bg-[#0f172a]">Completed</option>
                    <option value="cancelled" {{ $production->status == 'cancelled' ? 'selected' : '' }} class="bg-[#0f172a]">Cancelled</option>
                    @else
                    <option value="draft" {{ $production->status == 'draft' ? 'selected' : '' }} class="bg-[#0f172a]">Draft</option>
                    <option value="pending" {{ $production->status == 'pending' ? 'selected' : '' }} class="bg-[#0f172a]">Pending</option>
                    @endif
                </select>
                <button type="submit" class="px-4 py-2 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white">Update Status</button>
            </div>
        </form>
        @endif
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6">
        <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-4 mb-4 uppercase tracking-[0.05em]">Quality Control</h3>
        @if($production->qualityControls->count() > 0)
        <table class="w-full">
            <thead class="bg-[#1e293b] border-b border-[#334155]">
                <tr>
                    <th class="px-4 py-2 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Tanggal</span></th>
                    <th class="px-4 py-2 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Inspektor</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Total Diperiksa</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Passed</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Rejected</span></th>
                    <th class="px-4 py-2 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#334155]">
                @foreach($production->qualityControls as $qc)
                <tr class="hover:bg-[#1e293b]/50 transition-colors duration-150">
                    <td class="px-4 py-3 text-sm text-[#93C5FD]">{{ $qc->inspected_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-white">{{ $qc->inspector_name }}</td>
                    <td class="px-4 py-3 text-sm text-center text-white">{{ $qc->total_inspected }}</td>
                    <td class="px-4 py-3 text-sm text-center text-[#3B82F6] font-bold">{{ $qc->total_passed }}</td>
                    <td class="px-4 py-3 text-sm text-center text-[#64748B] font-bold">{{ $qc->total_rejected }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase tracking-[0.1em] {{ $qc->action == 'release' ? 'bg-[#3B82F6]/20 text-[#DBEAFE] border border-[#3B82F6]/30' : ($qc->action == 'rework' ? 'bg-[#1DA1F2]/20 text-[#93C5FD] border border-[#1DA1F2]/30' : 'bg-[#334155] text-[#64748B] border border-[#334155]') }}">
                            {{ ucfirst($qc->action) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-[#64748B] text-sm">Belum ada data Quality Control.</p>
        @endif
    </div>
</div>
@endsection
