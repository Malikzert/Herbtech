@extends('layouts.app')

@section('title', 'Quality Control Operator')
@section('header', 'QUALITY CONTROL')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedQc: {} }">
    <div class="mb-6">
        <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-4">
            <form method="GET" action="{{ route('operator.qc.index') }}" class="flex flex-wrap gap-3 items-center w-full">
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..."
                        class="w-full h-11 pl-10 pr-4 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] text-sm focus:ring-[#8B6914] focus:border-[#8B6914] focus:outline-none transition">
                </div>
                <select name="status" class="h-11 px-4 py-2 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] text-sm focus:ring-[#8B6914] focus:border-[#8B6914] focus:outline-none transition cursor-pointer">
                <option value="" class="bg-[#1a1210]">Semua Hasil</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }} class="bg-[#1a1210]">Passed</option>
                <option value="partial_reject" {{ request('status') === 'partial_reject' ? 'selected' : '' }} class="bg-[#1a1210]">Partial Reject</option>
                <option value="full_reject" {{ request('status') === 'full_reject' ? 'selected' : '' }} class="bg-[#1a1210]">Full Reject</option>
            </select>
                <button type="submit" class="h-11 px-5 bg-[#8B6914] hover:bg-[#A0845C] text-white font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('operator.qc.index') }}" class="h-11 px-5 bg-[#3d2b1f] hover:bg-[#2c1810] text-[#D4B896] font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                <div class="flex-1"></div>
                <a href="{{ route('operator.qc.create') }}" class="h-11 px-5 bg-[#8B6914] hover:bg-[#A0845C] text-white font-medium transition flex items-center gap-2 shadow-lg shadow-[#8B6914]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah QC
                </a>
            </form>
        </div>
    </div>

    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2c1810] border-b border-[#3d2b1f]">
                    <tr>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">ID QC</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Batch</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Inspeksi</span></th>
                        <th class="px-6 py-3.5 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Passed</span></th>
                        <th class="px-6 py-3.5 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Rejected</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Hasil</span></th>
                        <th class="px-6 py-3.5 text-right"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3d2b1f]">
                    @forelse($qualityControls as $qc)
                    <tr class="hover:bg-[#2c1810]/50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-bold text-white">#{{ $qc->id }}</td>
                        <td class="px-6 py-4 text-sm text-[#D4B896]">{{ $qc->production->batch_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-[#D4B896]">{{ $qc->inspected_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-center text-[#A0845C] font-bold">{{ $qc->total_passed }}</td>
                        <td class="px-6 py-4 text-sm text-center text-[#8B6914] font-bold">{{ $qc->total_rejected }}</td>
                        <td class="px-6 py-4">
                            @switch($qc->action)
                                @case('release')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">Passed</span>
                                    @break
                                @case('rework')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">Rework</span>
                                    @break
                                @case('reject')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#3d2b1f] text-[#6B5740] border border-[#3d2b1f]">Rejected</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('operator.qc.show', $qc->id) }}" class="text-[#D4B896] hover:text-[#F5EDE0] text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#6B5740]">Belum ada data QC.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($qualityControls->hasPages())
        <div class="px-6 py-4 border-t border-[#3d2b1f] bg-[#2c1810]/50">
            {{ $qualityControls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
