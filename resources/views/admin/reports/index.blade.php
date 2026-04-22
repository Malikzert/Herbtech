@extends('layouts.admin')

@section('title', 'Laporan')
@section('header', 'Laporan Produksi & QC')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
                <select name="type" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                    <option value="production" {{ request('type', 'production') === 'production' ? 'selected' : '' }}>Produksi</option>
                    <option value="raw_material" {{ request('type') === 'raw_material' ? 'selected' : '' }}>Bahan Baku</option>
                    <option value="qc" {{ request('type') === 'qc' ? 'selected' : '' }}>Quality Control</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->startOfMonth()->toDateString()) }}" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->endOfMonth()->toDateString()) }}" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">
                Tampilkan
            </button>
        </form>
    </div>

    <!-- Production Report -->
    @if($reportType === 'production')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Laporan Produksi</h3>
            <span class="text-sm text-gray-500">{{ $startDate }} - {{ $endDate }}</span>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6 border-b border-gray-100">
            <div class="text-center">
                <div class="text-3xl font-bold text-gray-800">{{ $totalProductions ?? 0 }}</div>
                <div class="text-sm text-gray-500">Total Batch</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-emerald-600">{{ $completedCount ?? 0 }}</div>
                <div class="text-sm text-gray-500">Completed</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-amber-600">{{ $inProgressCount ?? 0 }}</div>
                <div class="text-sm text-gray-500">On Progress</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600">{{ $completionRate ?? 0 }}%</div>
                <div class="text-sm text-gray-500">Completion Rate</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium text-left">No Batch</th>
                        <th class="px-6 py-3 font-medium text-left">Produk</th>
                        <th class="px-6 py-3 font-medium text-left">Operator</th>
                        <th class="px-6 py-3 font-medium text-left">Tanggal</th>
                        <th class="px-6 py-3 font-medium text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($productions ?? [] as $production)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @switch($production->status)
                                @case('completed')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Completed</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">On Progress</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Cancelled</span>
                                    @break
                                @default
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Draft</span>
                            @endswitch
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- QC Report -->
    @if($reportType === 'qc')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Laporan Quality Control</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-6 border-b border-gray-100">
            <div class="text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalQc ?? 0 }}</div>
                <div class="text-sm text-gray-500">Total QC</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-emerald-600">{{ $passedCount ?? 0 }}</div>
                <div class="text-sm text-gray-500">Passed</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-amber-600">{{ $partialRejectCount ?? 0 }}</div>
                <div class="text-sm text-gray-500">Partial Reject</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $fullRejectCount ?? 0 }}</div>
                <div class="text-sm text-gray-500">Full Reject</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $passRate ?? 0 }}%</div>
                <div class="text-sm text-gray-500">Pass Rate</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Raw Material Report -->
    @if($reportType === 'raw_material')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">Penggunaan Bahan Baku</h3>
        <p class="text-gray-500">Total penggunaan: {{ number_format($totalUsage ?? 0, 2) }}</p>
    </div>
    @endif
</div>
@endsection