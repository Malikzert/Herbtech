@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'Dashboard Manajemen')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards - High Contrast -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Produksi -->
        <div class="bg-glass rounded-xl border border-white/50 p-5 shadow-sm glass-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-800 text-sm font-medium">Total Produksi</p>
                    <h3 class="text-black text-3xl font-extrabold mt-1 drop-shadow-md">{{ $totalProductions ?? 0 }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Semua batch</p>
                </div>
                <div class="p-3 bg-emerald-700/50 rounded-lg border border-emerald-500/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Stok Bahan Rendah -->
        <div class="bg-glass rounded-xl border border-white/50 p-5 shadow-sm glass-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-800 text-sm font-medium">Stok Bahan Rendah</p>
                    <h3 class="text-black text-3xl font-extrabold mt-1 drop-shadow-md">{{ $lowStockCount ?? 0 }}</h3>
                    <p class="text-red-300 text-xs mt-1">Perlu pengadaan</p>
                </div>
                <div class="p-3 bg-red-600/50 rounded-lg border border-red-400/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Menunggu QC -->
        <div class="bg-glass rounded-xl border border-white/50 p-5 shadow-sm glass-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-800 text-sm font-medium">Menunggu QC</p>
                    <h3 class="text-black text-3xl font-extrabold mt-1 drop-shadow-md">{{ $pendingQcCount ?? 0 }}</h3>
                    <p class="text-amber-300 text-xs mt-1">Dalam antrian</p>
                </div>
                <div class="p-3 bg-amber-600/50 rounded-lg border border-amber-400/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-glass rounded-xl border border-white/50 p-5 shadow-sm glass-card">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-800 text-sm font-medium">Total Produk</p>
                    <h3 class="text-black text-3xl font-extrabold mt-1 drop-shadow-md">{{ $totalProducts ?? 0 }}</h3>
                    <p class="text-gray-500 text-xs mt-1">Aktif di sistem</p>
                </div>
                <div class="p-3 bg-blue-600/50 rounded-lg border border-blue-400/50">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Productions & QC Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Productions -->
        <div class="lg:col-span-2 bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
            <div class="px-6 py-4 border-b border-white/30 flex justify-between items-center bg-emerald-800">
                <h3 class="font-bold text-white text-shadow-sm">Produksi Terbaru</h3>
                <a href="{{ route('admin.productions.index') }}" class="text-sm text-emerald-200 hover:text-white font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full glass-table">
                    <thead class="bg-emerald-800 text-white text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm">No Batch</th>
                            <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm">Produk</th>
                            <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm">Operator</th>
                            <th class="px-6 py-3.5 font-bold text-left text-white text-shadow-sm">Status</th>
                            <th class="px-6 py-3.5 font-bold text-right text-white text-shadow-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @forelse($recentProductions as $production)
                        <tr class="hover:bg-white/10 transition">
                            <td class="px-6 py-4 text-sm font-bold text-black">{{ $production->batch_number }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-black">{{ $production->product->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-black">{{ $production->user->name ?? '-' }}</td>
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
                                <a href="{{ route('admin.productions.show', $production->id) }}" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-black font-medium">Belum ada data produksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- QC Summary -->
        <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm glass-card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Ringkasan QC</h3>
                <a href="{{ route('admin.qc.index') }}" class="text-sm text-emerald-200 hover:text-gray-800 font-medium">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-emerald-500/20 rounded-lg border border-emerald-400/30">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-700/50 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">Passed</span>
                    </div>
                    <span class="text-lg font-bold text-gray-800">{{ $passedQcCount ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-500/20 rounded-lg border border-amber-400/30">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-amber-600/50 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">Rework</span>
                    </div>
                    <span class="text-lg font-bold text-gray-800">{{ $reworkQcCount ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-red-500/20 rounded-lg border border-red-400/30">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-600/50 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">Rejected</span>
                    </div>
                    <span class="text-lg font-bold text-gray-800">{{ $rejectedQcCount ?? 0 }}</span>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-white/30">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium text-gray-600">Pass Rate</span>
                    <span class="text-xl font-bold text-emerald-300">{{ $qcPassRate ?? 0 }}%</span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-2 mt-2">
                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $qcPassRate ?? 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection