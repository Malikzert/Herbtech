@extends('layouts.admin')

@section('title', 'Penjadwalan Produksi')
@section('header', 'PENJADWALAN')

@section('styles')
<style>
    @keyframes progress-indeterminate {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(400%); }
    }
    .progress-bar-indeterminate {
        animation: progress-indeterminate 1.5s ease-in-out infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
        width: 18px; height: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
    }
    .hybrid-checkbox {
        width: 18px; height: 18px;
        border: 1.5px solid rgba(5, 150, 105, 0.3);
        cursor: pointer; transition: all 0.2s;
        appearance: none; background: rgba(6, 78, 59, 0.6);
    }
    .hybrid-checkbox:checked {
        background: #059669;
        border-color: #059669;
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
    }
    .hybrid-table thead {
        background: rgba(5, 150, 105, 0.15);
    }
    .hybrid-table thead th {
        color: #34D399;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
    }
    .hybrid-table tbody tr {
        border-bottom: 1px solid rgba(5, 150, 105, 0.08);
        transition: all 0.2s ease;
    }
    .hybrid-table tbody tr:hover {
        background: rgba(5, 150, 105, 0.05);
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(6, 78, 59, 0.3); }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Flash Messages --}}
    @if($errors->any())
    <div class="relative overflow-hidden rounded-sm border border-red-500/30 bg-red-900/60 backdrop-blur-md p-4 shadow-[0_4px_24px_rgba(0,0,0,0.2)] flex items-start gap-3">
        <svg class="w-5 h-5 shrink-0 mt-0.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-bold text-xs uppercase tracking-wider text-red-300">Terjadi kesalahan validasi:</p>
            <ul class="list-disc list-inside text-sm text-red-200/80 mt-1">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif



    {{-- GA RESULTS --}}
    @php $ga = session('ga_result') ?? $ga_result_from_db; @endphp
    @if($ga)
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center justify-between mt-3 mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-blue-500/15 border border-blue-500/30" style="border-radius: 0;">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Hasil Rekomendasi Batch</h3>
                    <p class="text-xs text-emerald-200/40">{{ ($ga['generations'] ?? 0) > 0 ? 'Dioptimalkan dengan Algoritma Genetika (' . $ga['generations'] . ' generasi)' : 'Rekomendasi langsung berdasarkan skor' }}</p>
                </div>
            </div>
            <button onclick="this.closest('.relative').remove()" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Recommended --}}
        <div class="mb-4">
            <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-400" style="border-radius: 0;"></span>
                Batch Direkomendasikan ({{ count($ga['recommended_batches']) }})
            </h4>
            <div class="space-y-2">
                @foreach($ga['recommended_batches'] as $batch)
                <div class="flex items-center justify-between p-3 border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-6 h-6 flex items-center justify-center text-[10px] font-black bg-emerald-500/15 text-emerald-400 border border-emerald-500/30 shrink-0" style="border-radius: 0;">{{ $loop->iteration }}</span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-emerald-50/90 truncate">{{ $batch['product_name'] }}</p>
                            <p class="text-[10px] text-emerald-200/40 truncate">Reject: {{ $batch['reject_rate'] ?? 0 }}%</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 shrink-0">
                        <div class="text-right">
                            <span class="text-[10px] text-emerald-200/40 block">FEFO</span>
                            <span class="text-xs font-bold text-emerald-50/80">{{ $batch['fefo_score'] ?? '-' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-emerald-200/40 block">Stok</span>
                            <span class="text-xs font-bold text-emerald-50/80">{{ $batch['stock_score'] ?? '-' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-emerald-200/40 block">Fitness</span>
                            <span class="text-xs font-bold text-emerald-300">{{ $batch['fitness_score'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Not Recommended --}}
        @if(!empty($ga['not_recommended_batches']))
        <div class="mb-4">
            <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-amber-400/60 mb-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-amber-400" style="border-radius: 0;"></span>
                Batch Tidak Direkomendasikan ({{ count($ga['not_recommended_batches']) }})
            </h4>
            <div class="space-y-2">
                @foreach($ga['not_recommended_batches'] as $batch)
                <div class="p-3 border border-amber-500/15 bg-amber-500/5" style="border-radius: 0;">
                    <div class="flex items-start gap-3">
                        <span class="w-6 h-6 flex items-center justify-center text-[10px] font-black bg-amber-500/15 text-amber-400 border border-amber-500/30 shrink-0 mt-0.5" style="border-radius: 0;">{{ $loop->iteration }}</span>
                        <div>
                            <p class="text-sm font-bold text-emerald-50/90">{{ $batch['product_name'] }}</p>
                            <p class="text-[10px] text-amber-300/80 mt-1">{{ $batch['reason'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Remaining Stock --}}
        @if(!empty($ga['remaining_stock']))
        <div>
            <h4 class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-2 flex items-center gap-2">
                <span class="w-2 h-2 bg-blue-400" style="border-radius: 0;"></span>
                Simulasi Sisa Stok
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                @foreach($ga['remaining_stock'] as $stock)
                <div class="p-2 border border-emerald-500/10 bg-emerald-500/5" style="border-radius: 0;">
                    <p class="text-[10px] font-bold text-emerald-50/70 truncate" title="{{ $stock['name'] }}">{{ $stock['name'] }}</p>
                    <p class="text-xs font-black {{ $stock['remaining_stock'] <= 0 ? 'text-red-400' : 'text-emerald-300' }}">{{ number_format($stock['remaining_stock'], 2) }} <span class="text-[9px] font-normal text-emerald-200/40">{{ $stock['unit'] }}</span></p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
    @endif

    {{-- STATS --}}
    @php
        $queueCount = $statusCounts['pending'] ?? $productions->whereIn('status', ['pending','draft'])->count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Antrean Batch</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $queueCount }}</p>
                    <p class="text-[10px] text-amber-400/80 mt-0.5 font-bold uppercase tracking-wider">Menunggu penjadwalan</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Kapasitas Produksi Harian</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ number_format($dailyCapacity ?? 5000) }}</p>
                    <p class="text-[10px] text-emerald-400/80 mt-0.5 font-bold uppercase tracking-wider">Unit / hari</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-500/15 border border-blue-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Estimasi Waktu Selesai</p>
                    <p class="text-xl font-black text-emerald-50 mt-0.5">{{ $estimatedCompletion ?? '-' }}</p>
                    <p class="text-[10px] text-blue-400/80 mt-0.5 font-bold uppercase tracking-wider">Berdasarkan antrean</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Production Queue / Schedule Recommendations --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">
                                @if($productions->isNotEmpty()) Antrean Produksi @else Rekomendasi Jadwal @endif
                            </h3>
                            <p class="text-xs text-emerald-200/40">
                                @if($productions->isNotEmpty()) Daftar batch yang belum dijadwalkan @else Hasil Algoritma Genetika @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.scheduling.index') }}"
                           class="h-8 px-3 text-[10px] font-bold uppercase tracking-wider flex items-center rounded-sm transition-all duration-200 {{ !$filter || $filter === 'all' ? 'bg-emerald-600 text-white' : 'border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-300 hover:border-emerald-400/50' }}">
                            Semua
                        </a>
                        <a href="{{ route('admin.scheduling.index', ['filter' => 'scheduled']) }}"
                           class="h-8 px-3 text-[10px] font-bold uppercase tracking-wider flex items-center rounded-sm transition-all duration-200 {{ $filter === 'scheduled' ? 'bg-emerald-600 text-white' : 'border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-300 hover:border-emerald-400/50' }}">
                            Terjadwal
                        </a>
                        <a href="{{ route('admin.scheduling.index', ['filter' => 'unscheduled']) }}"
                           class="h-8 px-3 text-[10px] font-bold uppercase tracking-wider flex items-center rounded-sm transition-all duration-200 {{ $filter === 'unscheduled' ? 'bg-emerald-600 text-white' : 'border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-300 hover:border-emerald-400/50' }}">
                            Belum
                        </a>
                    </div>
                </div>

                @if($productions->isNotEmpty())
                <form id="bulkActionForm" method="POST" action="{{ route('admin.scheduling.review') }}">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full hybrid-table">
                            <thead>
                                <tr>
                                    <th class="px-5 py-4 text-left w-12">
                                        <input type="checkbox" id="selectAll" class="hybrid-checkbox">
                                    </th>
                                    <th class="px-5 py-4 text-left">Batch ID</th>
                                    <th class="px-5 py-4 text-left">Produk</th>
                                    <th class="px-5 py-4 text-left">Target Qty</th>
                                    <th class="px-5 py-4 text-left">Prioritas</th>
                                    <th class="px-5 py-4 text-left">Jadwal</th>
                                    <th class="px-5 py-4 text-left">Status</th>
                                    <th class="px-5 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-emerald-500/10">
                                @foreach($productions as $production)
                                <tr class="group">
                                    <td class="px-5 py-4">
                                        <input type="checkbox" name="production_ids[]" value="{{ $production->id }}" class="production-checkbox hybrid-checkbox">
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $production->batch_number }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $production->product->name ?? $production->product_id }}</td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-bold text-emerald-50/90">{{ number_format($production->target_quantity ?? 0) }}</span>
                                        <span class="text-xs text-emerald-200/40">unit</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php $priority = $production->priority_level ?? 50; @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider
                                            {{ $priority >= 80 ? 'bg-red-500/10 text-red-300 border border-red-500/20' : ($priority >= 60 ? 'bg-amber-500/10 text-amber-300 border border-amber-500/20' : 'bg-gray-500/10 text-gray-300 border border-gray-500/20') }}" style="border-radius: 0;">
                                            <span class="w-1.5 h-1.5" style="background: currentColor; border-radius: 0;"></span>
                                            {{ $priority }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm">
                                        @if($production->scheduled_start)
                                            <span class="font-bold text-emerald-50/90">{{ $production->scheduled_start->format('d/m H:i') }}</span>
                                        @else
                                            <span class="text-emerald-200/30">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($production->algorithm_generated)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20" style="border-radius: 0;">
                                            <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                            Terjadwal
                                        </span>
                                        @else
                                            @switch($production->status)
                                                @case('draft')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-gray-500/10 text-gray-300 border border-gray-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #9CA3AF; border-radius: 0;"></span>
                                                    Draft
                                                </span>
                                                @break
                                                @case('pending')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                                    Pending
                                                </span>
                                                @break
                                                @case('in_progress')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                                    In Progress
                                                </span>
                                                @break
                                                @case('qc_check')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                                    QC Check
                                                </span>
                                                @break
                                                @case('rework')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-purple-500/10 text-purple-300 border border-purple-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #A78BFA; border-radius: 0;"></span>
                                                    Rework
                                                </span>
                                                @break
                                                @case('completed')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                                    Completed
                                                </span>
                                                @break
                                                @case('cancelled')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-900/30 text-blue-300 border border-blue-500/30" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #3B82F6; border-radius: 0;"></span>
                                                    Cancelled
                                                </span>
                                                @break
                                                @default
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius: 0;">
                                                    <span class="w-1.5 h-1.5" style="background: #F59E0B; border-radius: 0;"></span>
                                                    Pending
                                                </span>
                                            @endswitch
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.productions.show', $production->id) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 transition-all duration-200" style="border-radius: 0;">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Bulk Actions --}}
                    <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="text-sm text-emerald-200/60">
                                <span class="font-bold text-emerald-50" id="selectedCount">0</span> item dipilih
                            </div>
                            <div class="flex gap-2.5">
                                <button type="button" onclick="openPreviewModal()"
                                        class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-300 hover:border-emerald-400/50 rounded-sm transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Preview
                                </button>
                                <button type="submit" name="action" value="approve"
                                        onclick="return confirm('Setujui jadwal yang dipilih?')"
                                        class="px-5 py-2.5 rounded-sm text-[10px] font-bold uppercase tracking-wider flex items-center gap-2"
                                        style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease; border: none;"
                                        onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                                        onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Approve
                                </button>
                                <button type="submit" name="action" value="reset"
                                        onclick="return confirm('Reset jadwal untuk batch terpilih?')"
                                        class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/25 text-emerald-200/60 hover:text-red-400 hover:border-red-500/30 rounded-sm transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                @elseif(isset($schedulings) && $schedulings->isNotEmpty())
                @php
                    $schedRec = $schedulings->where('is_recommended', true)->sortBy('priority_order');
                    $schedNotRec = $schedulings->where('is_recommended', false);
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full hybrid-table">
                        <thead>
                            <tr>
                                <th class="px-5 py-4 text-left w-12">#</th>
                                <th class="px-5 py-4 text-left">Produk</th>
                                <th class="px-5 py-4 text-left">Qty Rekomendasi</th>
                                <th class="px-5 py-4 text-left">Tanggal</th>
                                <th class="px-5 py-4 text-left">Prioritas</th>
                                <th class="px-5 py-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-500/10">
                            @forelse($schedRec as $s)
                            <tr class="group">
                                <td class="px-5 py-4">
                                    <span class="text-sm font-bold text-emerald-50/90">{{ $s->priority_order }}</span>
                                </td>
                                <td class="px-5 py-4 text-sm text-emerald-200/60">{{ $s->product->name ?? 'Unknown' }}</td>
                                <td class="px-5 py-4">
                                    <span class="text-sm font-bold text-emerald-50/90">{{ $s->recommended_quantity }}</span>
                                    <span class="text-xs text-emerald-200/40">batch</span>
                                </td>
                                <td class="px-5 py-4 text-sm text-emerald-200/60">
                                    {{ $s->recom_date ? \Carbon\Carbon::parse($s->recom_date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius: 0;">
                                        <span class="w-1.5 h-1.5" style="background: #34D399; border-radius: 0;"></span>
                                        Rekomendasi
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-blue-500/10 text-blue-300 border border-blue-500/20" style="border-radius: 0;">
                                        <span class="w-1.5 h-1.5" style="background: #60A5FA; border-radius: 0;"></span>
                                        Draft
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-emerald-200/40 text-xs">Tidak ada rekomendasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($schedNotRec->isNotEmpty())
                <div class="px-6 py-3 border-t border-amber-500/10 bg-amber-500/5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-400/60 mb-2">Tidak Direkomendasikan ({{ $schedNotRec->count() }})</p>
                    @foreach($schedNotRec as $s)
                    <p class="text-[10px] text-amber-300/70 mt-1">• {{ $s->product->name ?? 'Unknown' }} — {{ $s->rejection_reason }}</p>
                    @endforeach
                </div>
                @endif
                @else
                <div class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center">
                        <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Tidak Ada Batch Pending</p>
                        <p class="text-emerald-200/30 text-xs mt-1">Semua batch sudah dijadwalkan.</p>
                    </div>
                </div>
                @endif
                <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
            </div>
        </div>

        {{-- RIGHT: GA Config + Low Stock --}}
        <div class="space-y-6">
            {{-- GA Config --}}
            <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                <div class="flex items-center gap-3 mt-3 mb-5">
                    <div class="w-10 h-10 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Algoritma Genetika</h3>
                        <p class="text-xs text-emerald-200/40">Parameter optimasi produksi</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Kriteria FEFO</span>
                                <p class="text-[10px] text-emerald-200/40">First Expiry First Out</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5" style="border-radius: 0;">Aktif</span>
                    </div>

                    <div class="flex items-center justify-between p-3 border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-blue-500/15 border border-blue-500/30" style="border-radius: 0;">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Kapasitas Mesin</span>
                                <p class="text-[10px] text-emerald-200/40">Efisiensi alat produksi</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5" style="border-radius: 0;">Aktif</span>
                    </div>

                    <div class="flex items-center justify-between p-3 border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Prioritas Produk</span>
                                <p class="text-[10px] text-emerald-200/40">Skala prioritas pelanggan</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5" style="border-radius: 0;">Aktif</span>
                    </div>
                </div>

                <div class="border-t border-emerald-500/15 my-4"></div>

                <form method="POST" action="{{ route('admin.scheduling.generate') }}" id="generateForm">
                    @csrf
                    <div class="mb-4">
                        <label class="text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 block mb-2">Pilih Batch</label>
                        <select name="production_ids[]" multiple
                                class="hybrid-input w-full rounded-sm px-3 py-2.5 text-sm"
                                style="height: 110px;">
                            @forelse(\App\Models\Production::whereIn('status', ['pending', 'draft'])->with('product:id,name')->get() as $p)
                                <option value="{{ $p->id }}" class="bg-emerald-900 text-emerald-50">{{ $p->batch_number }} - {{ $p->product->name ?? '' }}</option>
                            @empty
                                <option disabled class="bg-emerald-900 text-emerald-50">Tidak ada batch</option>
                            @endforelse
                        </select>
                        <p class="text-[10px] text-emerald-200/30 mt-1.5">Tahan Ctrl/Cmd untuk pilih banyak</p>
                    </div>

                    <button type="submit" id="generateBtn"
                            class="w-full py-3 px-5 rounded-sm text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; transition: all 0.2s ease; border: none;"
                            onmouseover="this.style.background='linear-gradient(135deg, #10B981 0%, #059669 100%)'; this.style.boxShadow='0 0 20px rgba(5,150,105,0.4)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #059669 0%, #047857 100%)'; this.style.boxShadow='none'">
                        <span class="spinner hidden" id="spinner"></span>
                        <svg class="w-4 h-4" id="btnIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span id="btnText">Jalankan Algoritma Genetika</span>
                    </button>
                </form>

                <div id="progressWrapper" class="hidden mt-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200/60">Menjadwalkan...</span>
                        <span class="text-[10px] font-bold text-emerald-200/60" id="progressPercent">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-emerald-900/40 overflow-hidden" style="border-radius: 0;">
                        <div id="progressBar" class="h-full progress-bar-indeterminate" style="background: linear-gradient(90deg, #10b981, #059669, #047857); width: 30%; border-radius: 0;"></div>
                    </div>
                    <p class="text-[10px] text-emerald-200/30 mt-1.5">Mengoptimasi urutan produksi dengan genetic algorithm...</p>
                </div>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>

            {{-- Low Stock Alerts --}}
            @if(isset($lowStockMaterials) && $lowStockMaterials->isNotEmpty())
            <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                <div class="flex items-center justify-between mt-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Peringatan Stok</h3>
                            <p class="text-xs text-emerald-200/40">Bahan perlu perhatian</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-red-300 bg-red-500/10 border border-red-500/20 px-2.5 py-1" style="border-radius: 0;">{{ $lowStockMaterials->count() }}</span>
                </div>

                <div class="space-y-2.5">
                    @foreach($lowStockMaterials as $material)
                    <div class="p-3 border-l-4 {{ $material->current_stock <= $material->min_stock_level ? 'border-amber-500' : 'border-red-500' }} border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-50/80 truncate">{{ $material->name }}</p>
                        @if($material->current_stock <= $material->min_stock_level)
                        <p class="text-[10px] text-amber-400/80 mt-0.5">{{ $material->current_stock }} {{ $material->unit }}</p>
                        @endif
                        @if($material->expired_date && $material->expired_date <= now()->addDays(14))
                        <p class="text-[10px] text-red-400/80 mt-0.5">Exp: {{ $material->expired_date->format('d/m/Y') }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-emerald-500/15 text-center">
                    <a href="{{ route('admin.raw-materials.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 transition">
                        Lihat Semua
                        <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Preview Modal --}}
<div x-data="{ previewOpen: false }"
     @toggle-preview.window="previewOpen = $event.detail.open"
     x-show="previewOpen"
     @keydown.escape.window="previewOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="previewOpen" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @@click="previewOpen = false"></div>
    <div x-show="previewOpen" x-transition
         class="relative w-full max-w-2xl rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)] max-h-[80vh] flex flex-col">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent shrink-0"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Preview Jadwal</h3>
                    <p class="text-xs text-emerald-200/40">Optimasi urutan batch terpilih</p>
                </div>
            </div>
            <button @@click="previewOpen = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1" id="previewContent">
            <div class="text-center py-8">
                <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-emerald-500/20 bg-emerald-500/5" style="border-radius: 0;">
                    <svg class="w-8 h-8 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Pilih Batch</p>
                <p class="text-emerald-200/30 text-xs mt-1">Pilih batch untuk melihat preview.</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-emerald-500/15 flex justify-end shrink-0">
            <button @@click="previewOpen = false"
                    class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-300 hover:border-emerald-400/50 rounded-sm transition-all duration-200">
                Tutup
            </button>
        </div>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('generateForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('generateBtn');
    const spinner = document.getElementById('spinner');
    const icon = document.getElementById('btnIcon');
    const text = document.getElementById('btnText');
    const progressWrapper = document.getElementById('progressWrapper');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');

    btn.disabled = true;
    spinner.classList.remove('hidden');
    icon.style.display = 'none';
    text.textContent = 'Memproses...';

    progressWrapper.classList.remove('hidden');
    progressBar.classList.add('progress-bar-indeterminate');
    progressBar.style.width = '30%';
    progressPercent.textContent = '0%';

    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        const pct = Math.round(progress);
        progressBar.style.width = pct + '%';
        progressPercent.textContent = pct + '%';
    }, 600);

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        clearInterval(progressInterval);
        if (data.success) {
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            progressBar.classList.remove('progress-bar-indeterminate');
            showNotification('success', data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification('warning', data.message);
            btn.disabled = false;
            spinner.classList.add('hidden');
            icon.style.display = '';
            text.textContent = 'Jalankan Algoritma Genetika';
            progressWrapper.classList.add('hidden');
        }
    })
    .catch(() => {
        clearInterval(progressInterval);
        showNotification('error', 'Terjadi kesalahan saat menjalankan algoritma.');
        btn.disabled = false;
        spinner.classList.add('hidden');
        icon.style.display = '';
        text.textContent = 'Jalankan Algoritma Genetika';
        progressWrapper.classList.add('hidden');
    });
});

document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.production-checkbox').forEach(cb => cb.checked = this.checked);
    updateSelectedCount();
});

document.querySelectorAll('.production-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const el = document.getElementById('selectedCount');
    if (el) el.textContent = document.querySelectorAll('.production-checkbox:checked').length;
}

function openPreviewModal() {
    const checked = document.querySelectorAll('.production-checkbox:checked');
    const content = document.getElementById('previewContent');

    if (checked.length === 0) {
        content.innerHTML = '<div class="text-center py-8"><div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 border border-amber-500/20 bg-amber-500/5" style="border-radius: 0;"><svg class="w-8 h-8 text-amber-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg></div><p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Pilih Batch</p><p class="text-emerald-200/30 text-xs mt-1">Pilih setidaknya satu batch.</p></div>';
    } else {
        const rows = Array.from(checked).map(function(cb) { return cb.closest('tr'); });
        const batches = rows.map(function(row) { return row.querySelector('td:nth-child(2) .text-sm').textContent.trim(); });

        let html = '<div class="space-y-2">';
        html += '<div class="p-4 border border-emerald-500/15 bg-emerald-500/5 mb-4" style="border-radius: 0;">';
        html += '<span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60">Total Batch:</span>';
        html += '<span class="text-xl font-black text-emerald-50 ms-2">' + checked.length + '</span>';
        html += '</div>';
        batches.forEach(function(b, i) {
            html += '<div class="flex items-center gap-3 p-3 border border-emerald-500/10 bg-emerald-500/5" style="border-radius: 0;">';
            html += '<span class="w-8 h-8 flex items-center justify-center text-xs font-black bg-emerald-500/15 text-emerald-400 border border-emerald-500/30" style="border-radius: 0;">' + (i + 1) + '</span>';
            html += '<span class="text-sm font-bold text-emerald-50/80">' + b + '</span>';
            html += '</div>';
        });
        html += '</div>';
        content.innerHTML = html;
    }

    window.dispatchEvent(new CustomEvent('toggle-preview', { detail: { open: true } }));
}

function showNotification(type, message) {
    if (typeof Alpine !== 'undefined') {
        const data = Alpine.$data(document.body);
        if (data && data.notif) {
            data.notif.show = true;
            data.notif.type = type;
            data.notif.message = message;
            setTimeout(() => { if (data.notif) data.notif.show = false; }, 5000);
            return;
        }
    }
    window.dispatchEvent(new CustomEvent('notify', { detail: { type: type, message: message } }));
}

document.addEventListener('DOMContentLoaded', updateSelectedCount);
</script>
@endpush
