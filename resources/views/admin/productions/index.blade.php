@extends('layouts.admin')

@section('title', 'Produksi')
@section('header', 'Monitoring Produksi')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedProduction: {} }">
    <!-- Header Actions - Properly Aligned -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.productions.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch/produk/operator..." 
                        class="input-glass w-full h-11 pl-10 pr-4 border border-white/30 rounded-lg text-sm text-black placeholder-gray-400 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none">
                </div>
                
                <!-- Filter Dropdown -->
                <select name="status" class="modern-select input-glass h-11 px-4 py-2 border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>On Progress</option>
                    <option value="qc_check" {{ request('status') === 'qc_check' ? 'selected' : '' }}>QC Check</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                
                <!-- Date Range -->
                <div class="flex gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-glass h-11 px-3 py-2 border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-glass h-11 px-3 py-2 border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none">
                </div>
                
                <!-- Filter Button -->
                <button type="submit" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request()->hasAny(['search', 'status', 'start_date', 'end_date']))
                <a href="{{ route('admin.productions.index') }}" class="h-11 px-5 bg-white/10 text-white font-medium rounded-lg hover:bg-white/20 transition flex items-center gap-2 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table with Glass Effect -->
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead class="bg-emerald-800 text-white text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3.5 font-bold text-left text-white">No Batch</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Produk</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Operator</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Mulai</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Status</th>
                        <th class="px-6 py-3.5 font-bold text-right text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($productions as $production)
                    <tr class="hover:bg-white/10 transition">
                        <td class="px-6 py-4 text-sm font-bold text-black">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $production->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $production->start_date ? $production->start_date->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            @switch($production->status)
                                @case('draft')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-800 border border-gray-300">Draft</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">On Progress</span>
                                    @break
                                @case('qc_check')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">QC Check</span>
                                    @break
                                @case('completed')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">Completed</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-300">Cancelled</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.productions.show', $production->id) }}" class="text-emerald-700 hover:text-emerald-900 text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                <p class="text-black font-medium">Belum ada data produksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($productions->hasPages())
        <div class="px-6 py-4 border-t border-white/20 bg-white/5">
            {{ $productions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection