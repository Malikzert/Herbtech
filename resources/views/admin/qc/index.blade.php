@extends('layouts.admin')

@section('title', 'Quality Control')
@section('header', 'Riwayat QC')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedQc: {} }">
    <!-- Header Actions - Properly Aligned -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.qc.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..." 
                        class="input-glass w-full h-11 pl-10 pr-4 border border-white/30 rounded-lg text-sm text-black placeholder-gray-400 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none">
                </div>
                
                <!-- Filter Dropdown - Status -->
                <select name="status" class="modern-select input-glass h-11 px-4 py-2 border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none cursor-pointer">
                    <option value="">Semua Hasil</option>
                    <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                    <option value="partial_reject" {{ request('status') === 'partial_reject' ? 'selected' : '' }}>Partial Reject</option>
                    <option value="full_reject" {{ request('status') === 'full_reject' ? 'selected' : '' }}>Full Reject</option>
                </select>
                
                <!-- Filter Dropdown - Action -->
                <select name="action" class="modern-select input-glass h-11 px-4 py-2 border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none cursor-pointer">
                    <option value="">Semua Action</option>
                    <option value="release" {{ request('action') === 'release' ? 'selected' : '' }}>Release</option>
                    <option value="rework" {{ request('action') === 'rework' ? 'selected' : '' }}>Rework</option>
                    <option value="reject" {{ request('action') === 'reject' ? 'selected' : '' }}>Reject</option>
                </select>
                
                <!-- Filter Button -->
                <button type="submit" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request()->hasAny(['search', 'status', 'action']))
                <a href="{{ route('admin.qc.index') }}" class="h-11 px-5 bg-white/10 text-white font-medium rounded-lg hover:bg-white/20 transition flex items-center gap-2 border border-white/30">
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
                        <th class="px-6 py-3.5 font-bold text-left text-white">ID QC</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Batch</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Inspector</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Inspeksi</th>
                        <th class="px-6 py-3.5 font-bold text-center text-white">Passed</th>
                        <th class="px-6 py-3.5 font-bold text-center text-white">Rejected</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Hasil</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Action</th>
                        <th class="px-6 py-3.5 font-bold text-right text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($qualityControls as $qc)
                    <tr class="hover:bg-white/10 transition">
                        <td class="px-6 py-4 text-sm font-bold text-black">#{{ $qc->id }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $qc->production->batch_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $qc->inspector_name }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $qc->inspected_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-center text-emerald-700 font-bold">{{ $qc->total_passed }}</td>
                        <td class="px-6 py-4 text-sm text-center text-red-700 font-bold">{{ $qc->total_rejected }}</td>
                        <td class="px-6 py-4">
                            @switch($qc->status)
                                @case('passed')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">Passed</span>
                                    @break
                                @case('partial_reject')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">Partial</span>
                                    @break
                                @case('full_reject')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-300">Full Reject</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            @switch($qc->action)
                                @case('release')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">Release</span>
                                    @break
                                @case('rework')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-300">Rework</span>
                                    @break
                                @case('reject')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-200 text-gray-800 border border-gray-300">Reject</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.qc.show', $qc->id) }}" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-black font-medium">Belum ada data QC</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($qualityControls->hasPages())
        <div class="px-6 py-4 border-t border-white/20 bg-white/5">
            {{ $qualityControls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection