@extends('layouts.app')

@section('title', 'Produksi Operator')
@section('header', 'KELOLA PRODUKSI')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedProduction: {} }">
    <div class="mb-6">
        <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-4">
            <form method="GET" action="{{ route('operator.productions.index') }}" class="flex flex-wrap gap-3 items-center w-full">
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..."
                        class="w-full h-11 pl-10 pr-4 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] placeholder-[#64748B] text-sm focus:ring-[#1DA1F2] focus:border-[#1DA1F2] focus:outline-none transition">
                </div>
                <select name="status" class="h-11 px-4 py-2 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] text-sm focus:ring-[#1DA1F2] focus:border-[#1DA1F2] focus:outline-none transition cursor-pointer">
                <option value="" class="bg-[#0f172a]">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }} class="bg-[#0f172a]">Draft</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }} class="bg-[#0f172a]">On Progress</option>
                <option value="qc_check" {{ request('status') === 'qc_check' ? 'selected' : '' }} class="bg-[#0f172a]">QC Check</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }} class="bg-[#0f172a]">Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }} class="bg-[#0f172a]">Cancelled</option>
            </select>
                <button type="submit" class="h-11 px-5 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('operator.productions.index') }}" class="h-11 px-5 bg-[#334155] hover:bg-[#1e293b] text-[#93C5FD] font-medium transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                <div class="flex-1"></div>
                <a href="{{ route('operator.productions.create') }}" class="h-11 px-5 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium transition flex items-center gap-2 shadow-lg shadow-[#1DA1F2]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produksi
                </a>
            </form>
        </div>
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#1e293b] border-b border-[#334155]">
                    <tr>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">No Batch</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Produk</span></th>
                        <th class="px-6 py-3.5 text-center"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Target Qty</span></th>
                        <th class="px-6 py-3.5 text-center"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Aktual Qty</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Mulai</span></th>
                        <th class="px-6 py-3.5 text-left"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Status</span></th>
                        <th class="px-6 py-3.5 text-right"><span class="text-[#93C5FD] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#334155]">
                    @forelse($productions as $production)
                    <tr class="hover:bg-[#1e293b]/50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-bold text-white">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm text-[#93C5FD]">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-center text-[#93C5FD]">{{ $production->target_quantity }}</td>
                        <td class="px-6 py-4 text-sm text-center text-[#93C5FD]">{{ $production->actual_quantity ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-[#93C5FD]">{{ $production->start_date ? $production->start_date->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4">
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
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('operator.productions.show', $production->id) }}" class="text-[#93C5FD] hover:text-[#DBEAFE] text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#64748B]">Belum ada produksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productions->hasPages())
        <div class="px-6 py-4 border-t border-[#334155] bg-[#1e293b]/50">
            {{ $productions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
