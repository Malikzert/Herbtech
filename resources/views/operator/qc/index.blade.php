@extends('layouts.admin')

@section('title', 'Quality Control Operator')
@section('header', 'Quality Control')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedQc: {} }">
    <!-- Header with Search & Filters -->
    <div class="mb-6 flex flex-col lg:flex-row gap-4 justify-between">
        <form method="GET" action="{{ route('operator.qc.index') }}" class="flex flex-col lg:flex-row gap-3 w-full">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..." 
                    class="pl-10 pr-4 py-2.5 w-full lg:w-72 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Hasil</option>
                <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>Passed</option>
                <option value="partial_reject" {{ request('status') === 'partial_reject' ? 'selected' : '' }}>Partial Reject</option>
                <option value="full_reject" {{ request('status') === 'full_reject' ? 'selected' : '' }}>Full Reject</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Filter</button>
            @if(request('search') || request('status'))
            <a href="{{ route('operator.qc.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">Reset</a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
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
                        <td class="px-6 py-4 text-sm text-center text-emerald-600 font-medium">{{ $qc->total_passed }}</td>
                        <td class="px-6 py-4 text-sm text-center text-red-600 font-medium">{{ $qc->total_rejected }}</td>
                        <td class="px-6 py-4">
                            @switch($qc->action)
                                @case('release')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Passed</span>
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
                            <a href="{{ route('operator.qc.show', $qc->id) }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">Detail</a>
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $qualityControls->links() }}
        </div>
        @endif
    </div>
</div>
@endsection