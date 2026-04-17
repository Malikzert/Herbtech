@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard Manajemen')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm p-6 border border-emerald-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-emerald-800/70 uppercase tracking-wider mb-1">Total Stok Bahan Baku</p>
                    <h3 class="text-4xl font-extrabold text-gray-800 mt-1">{{ number_format($totalRawMaterials, 2) }} <span class="text-lg text-gray-500 font-medium">Kg</span></h3>
                </div>
                <div class="p-3 bg-emerald-100/50 rounded-xl">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
            </div>
        </div>
        
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm p-6 border border-yellow-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-yellow-800/70 uppercase tracking-wider mb-1">Batch Aktif</p>
                    <h3 class="text-4xl font-extrabold text-gray-800 mt-1">{{ $activeBatches }}</h3>
                </div>
                <div class="p-3 bg-yellow-100/50 rounded-xl">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm p-6 border border-blue-100 hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-semibold text-blue-800/70 uppercase tracking-wider mb-1">Persentase QC Pass</p>
                    <h3 class="text-4xl font-extrabold text-gray-800 mt-1">{{ $qcPassRate }}%</h3>
                </div>
                <div class="p-3 bg-blue-100/50 rounded-xl">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Production Table -->
    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100/50 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-800 tracking-tight">Produksi Terbaru</h3>
            <a href="#" class="text-sm text-emerald-custom hover:underline font-medium">Lihat Semua Laporan</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-medium">No Batch</th>
                        <th class="px-6 py-3 font-medium">Produk</th>
                        <th class="px-6 py-3 font-medium">Tanggal Mulai</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentProductions as $production)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->start_date ? $production->start_date->format('d M Y, H:i') : '-' }}</td>
                        <td class="px-6 py-4">
                            @if(in_array($production->status, ['in_progress', 'qc_check']))
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst(str_replace('_', ' ', $production->status)) }}</span>
                            @elseif($production->status == 'completed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($production->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="#" class="text-emerald-custom hover:text-emerald-700">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data produksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
