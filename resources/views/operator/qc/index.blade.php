@extends('layouts.app')

@section('title', 'Quality Control Operator')
@section('header', 'Quality Control')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedQc: {} }">
    <!-- Header with Search & Filters -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('operator.qc.index') }}" class="flex flex-wrap gap-3 items-center w-full">
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..." 
                        class="w-full h-11 pl-10 pr-4 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-700 focus:border-blue-700 focus:outline-none transition">
                </div>
                <select name="status" class="modern-select h-11 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-blue-700 focus:border-blue-700 focus:outline-none transition cursor-pointer">
                <option value="">Semua Hasil</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                <option value="partial_reject" {{ request('status') === 'partial_reject' ? 'selected' : '' }}>Partial Reject</option>
                <option value="full_reject" {{ request('status') === 'full_reject' ? 'selected' : '' }}>Full Reject</option>
            </select>
                <button type="submit" class="h-11 px-5 bg-blue-800 text-white font-medium rounded-lg hover:bg-blue-900 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('operator.qc.index') }}" class="h-11 px-5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                <div class="flex-1"></div>
                <a href="{{ route('operator.qc.create') }}" class="h-11 px-5 bg-blue-800 text-white font-medium rounded-lg hover:bg-blue-900 transition flex items-center gap-2 shadow-lg shadow-blue-800/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah QC
                </a>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium text-left">ID QC</th>
                        <th class="px-6 py-3 font-medium text-left">Batch</th>
                        <th class="px-6 py-3 font-medium text-left">Inspeksi</th>
                        <th class="px-6 py-3 font-medium text-center">Passed</th>
                        <th class="px-6 py-3 font-medium text-center">Rejected</th>
                        <th class="px-6 py-3 font-medium text-left">Hasil</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($qualityControls as $qc)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $qc->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $qc->production->batch_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $qc->inspected_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-center text-blue-800 font-medium">{{ $qc->total_passed }}</td>
                        <td class="px-6 py-4 text-sm text-center text-red-600 font-medium">{{ $qc->total_rejected }}</td>
                        <td class="px-6 py-4">
                            @switch($qc->action)
                                @case('release')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-200 text-blue-900">Passed</span>
                                    @break
                                @case('rework')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Rework</span>
                                    @break
                                @case('reject')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Rejected</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('operator.qc.show', $qc->id) }}" class="text-blue-800 hover:text-blue-900 text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Belum ada data QC.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($qualityControls->hasPages())
        <div class="px-6 py-4 border-t border-gray-100/50 bg-gray-50/30">
            {{ $qualityControls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection