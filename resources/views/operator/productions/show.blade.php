@extends('layouts.app')

@section('title', 'Detail Produksi')
@section('header', 'DETAIL PRODUKSI: ' . $production->batch_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.productions.index') }}" class="text-[#D4B896] hover:text-[#F5EDE0] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        @if(in_array($production->status, ['draft', 'pending']))
        <a href="{{ route('operator.productions.edit', $production->id) }}" class="px-4 py-2 bg-[#8B6914] hover:bg-[#A0845C] text-white font-medium transition">Edit Produksi</a>
        @endif
    </div>

    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
        <h3 class="text-lg font-bold text-[#D4B896] border-b border-[#3d2b1f] pb-4 mb-4 uppercase tracking-[0.05em]">Informasi Produksi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Nomor Batch</p>
                <p class="text-base font-semibold text-white">{{ $production->batch_number }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Produk</p>
                <p class="text-base font-semibold text-[#D4B896]">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Target Produksi (Qty)</p>
                <p class="text-base font-semibold text-white">{{ $production->target_quantity }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Aktual Produksi (Qty)</p>
                <p class="text-base font-semibold text-white">{{ $production->actual_quantity ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Mulai</p>
                <p class="text-base font-semibold text-[#D4B896]">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Selesai</p>
                <p class="text-base font-semibold text-[#D4B896]">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">Status</p>
                <p class="text-base font-semibold mt-1">
                    @switch($production->status)
                        @case('draft')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#6B5740]/20 text-[#A0845C] border border-[#6B5740]/30">Draft</span>
                            @break
                        @case('pending')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">Pending</span>
                            @break
                        @case('in_progress')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">On Progress</span>
                            @break
                        @case('qc_check')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">QC Check</span>
                            @break
                        @case('rework')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#6B5740]/20 text-[#A0845C] border border-[#6B5740]/30">Rework</span>
                            @break
                        @case('completed')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">Completed</span>
                            @break
                        @case('cancelled')
                            <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3d2b1f] text-[#6B5740] border border-[#3d2b1f]">Cancelled</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm text-[#6B5740] uppercase tracking-[0.1em] text-[10px] font-bold">PIC</p>
                <p class="text-base font-semibold text-white">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>

        @if(auth()->user()->can('admin') || in_array($production->status, ['draft', 'pending']))
        <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="mt-8 border-t border-[#3d2b1f] pt-6">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-[#D4B896] uppercase tracking-[0.1em]">Ubah Status:</label>
                <select name="status" class="px-4 py-2 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914]">
                    @if(auth()->user()->can('admin'))
                    <option value="draft" {{ $production->status == 'draft' ? 'selected' : '' }} class="bg-[#1a1210]">Draft</option>
                    <option value="pending" {{ $production->status == 'pending' ? 'selected' : '' }} class="bg-[#1a1210]">Pending</option>
                    <option value="in_progress" {{ $production->status == 'in_progress' ? 'selected' : '' }} class="bg-[#1a1210]">On Progress</option>
                    <option value="qc_check" {{ $production->status == 'qc_check' ? 'selected' : '' }} class="bg-[#1a1210]">QC Check</option>
                    <option value="rework" {{ $production->status == 'rework' ? 'selected' : '' }} class="bg-[#1a1210]">Rework</option>
                    <option value="completed" {{ $production->status == 'completed' ? 'selected' : '' }} class="bg-[#1a1210]">Completed</option>
                    <option value="cancelled" {{ $production->status == 'cancelled' ? 'selected' : '' }} class="bg-[#1a1210]">Cancelled</option>
                    @else
                    <option value="draft" {{ $production->status == 'draft' ? 'selected' : '' }} class="bg-[#1a1210]">Draft</option>
                    <option value="pending" {{ $production->status == 'pending' ? 'selected' : '' }} class="bg-[#1a1210]">Pending</option>
                    @endif
                </select>
                <button type="submit" class="px-4 py-2 bg-[#8B6914] hover:bg-[#A0845C] text-white">Update Status</button>
            </div>
        </form>
        @endif
    </div>

    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
        <h3 class="text-lg font-bold text-[#D4B896] border-b border-[#3d2b1f] pb-4 mb-4 uppercase tracking-[0.05em]">Quality Control</h3>
        @if($production->qualityControls->count() > 0)
        <table class="w-full">
            <thead class="bg-[#2c1810] border-b border-[#3d2b1f]">
                <tr>
                    <th class="px-4 py-2 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Tanggal</span></th>
                    <th class="px-4 py-2 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Inspektor</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Total Diperiksa</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Passed</span></th>
                    <th class="px-4 py-2 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Rejected</span></th>
                    <th class="px-4 py-2 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#3d2b1f]">
                @foreach($production->qualityControls as $qc)
                <tr class="hover:bg-[#2c1810]/50 transition-colors duration-150">
                    <td class="px-4 py-3 text-sm text-[#D4B896]">{{ $qc->inspected_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-white">{{ $qc->inspector_name }}</td>
                    <td class="px-4 py-3 text-sm text-center text-white">{{ $qc->total_inspected }}</td>
                    <td class="px-4 py-3 text-sm text-center text-[#A0845C] font-bold">{{ $qc->total_passed }}</td>
                    <td class="px-4 py-3 text-sm text-center text-[#6B5740] font-bold">{{ $qc->total_rejected }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-block px-2 py-1 text-[10px] font-bold uppercase tracking-[0.1em] {{ $qc->action == 'release' ? 'bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30' : ($qc->action == 'rework' ? 'bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30' : 'bg-[#3d2b1f] text-[#6B5740] border border-[#3d2b1f]') }}">
                            {{ ucfirst($qc->action) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-[#6B5740] text-sm">Belum ada data Quality Control.</p>
        @endif
    </div>
</div>
@endsection
