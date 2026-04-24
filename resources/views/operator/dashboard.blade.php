@extends('layouts.app')

@section('title', 'Operator Dashboard')
@section('header', 'Dashboard Produksi')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards (Dashboard Operasional) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl shadow-md p-6 border border-emerald-500/30 flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm font-medium mb-1">Sedang Berjalan</p>
                <h3 class="text-3xl font-extrabold text-white">{{ $inProgressCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center shadow-inner">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm p-6 border border-amber-200 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Menunggu QC</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $qcCheckCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center border border-amber-100">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm p-6 border border-blue-200 flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm font-medium mb-1">Stok Aman</p>
                <h3 class="text-3xl font-bold text-gray-800">{{ $safeStockCount }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
        </div>
    </div>

    <!-- Active Productions Table -->
    <div>
        <h3 class="text-xl font-bold text-gray-800 mb-5 tracking-tight">Tabel Produksi Aktif Hari Ini</h3>
        <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 font-medium text-left">No Batch</th>
                            <th class="px-6 py-3 font-medium text-left">Produk</th>
                            <th class="px-6 py-3 font-medium text-left">Status</th>
                            <th class="px-6 py-3 font-medium text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($activeProductions as $production)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $production->batch_number }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $production->product->name ?? 'Produk' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $production->status == 'qc_check' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $production->status == 'qc_check' ? 'QC' : 'Proses' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($production->status == 'qc_check')
                                    <a href="{{ route('operator.qc.create', ['production_id' => $production->id]) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg transition shadow-sm">
                                        Cek QC
                                    </a>
                                @else
                                    <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="qc_check">
                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold py-1.5 px-3 rounded-lg transition shadow-sm">
                                            Selesai
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada produksi aktif hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
