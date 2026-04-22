@extends('layouts.app')

@section('title', 'Operator Dashboard')
@section('header', 'Dashboard Produksi')

@section('content')
<div class="space-y-8">
    <!-- Big Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('operator.productions.index') }}" class="group block bg-gradient-to-br from-emerald-600 to-emerald-800 hover:from-emerald-700 hover:to-emerald-900 rounded-2xl shadow-md p-8 text-center transition-all duration-300 transform hover:-translate-y-1 border border-emerald-500/30">
            <div class="mx-auto w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform shadow-inner">
                <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </div>
            <h3 class="text-xl font-extrabold text-white mb-1 tracking-tight">Mulai Produksi</h3>
            <p class="text-emerald-100 text-sm font-medium">Buat batch baru</p>
        </a>

        <a href="{{ route('operator.qc.create') }}" class="group block bg-white/80 backdrop-blur-md hover:bg-white border-2 border-emerald-500/50 rounded-2xl shadow-sm p-8 text-center transition-all duration-300 transform hover:-translate-y-1">
            <div class="mx-auto w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1 tracking-tight">Input Hasil QC</h3>
            <p class="text-gray-500 text-sm font-medium">Validasi kualitas batch</p>
        </a>

        <a href="{{ route('operator.raw-materials.index') }}" class="group block bg-white/80 backdrop-blur-md hover:bg-white border border-gray-200/60 rounded-2xl shadow-sm p-8 text-center transition-all duration-300 transform hover:-translate-y-1 hover:border-blue-300/50">
            <div class="mx-auto w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-1 tracking-tight">Cek Stok</h3>
            <p class="text-gray-500 text-sm font-medium">Lihat ketersediaan bahan</p>
        </a>
    </div>

    <!-- Active Productions Cards -->
    <div>
        <h3 class="text-xl font-bold text-gray-800 mb-5 tracking-tight">Produksi Sedang Berjalan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($activeProductions as $production)
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden hover:shadow-md transition">
                <div class="absolute top-0 right-0 w-2 h-full {{ $production->status == 'qc_check' ? 'bg-blue-400' : 'bg-yellow-400' }}"></div>
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $production->status == 'qc_check' ? 'bg-blue-100/80 text-blue-800' : 'bg-yellow-100/80 text-yellow-800' }} mb-3 backdrop-blur-sm border {{ $production->status == 'qc_check' ? 'border-blue-200' : 'border-yellow-200' }}">
                            {{ ucfirst(str_replace('_', ' ', $production->status)) }}
                        </span>
                        <h4 class="text-xl font-extrabold text-gray-800 tracking-tight">{{ $production->product->name ?? 'Produk' }}</h4>
                        <p class="text-sm font-medium text-gray-500 mt-1">Batch: <span class="text-gray-700">{{ $production->batch_number }}</span></p>
                    </div>
                    <div class="text-right bg-white/60 p-2 rounded-lg border border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Mulai</p>
                        <p class="text-sm font-bold text-gray-800">{{ $production->start_date ? $production->start_date->format('H:i') : '-' }}</p>
                    </div>
                </div>
                
                <!-- Progress -->
                <div class="mt-5 bg-white/50 p-3 rounded-xl border border-gray-100/50">
                    <div class="flex justify-between text-xs font-bold text-gray-500 mb-2">
                        <span>{{ $production->status == 'qc_check' ? 'Menunggu Validasi QC' : 'Progress Produksi' }}</span>
                        <span class="{{ $production->status == 'qc_check' ? 'text-blue-600' : 'text-yellow-600' }}">{{ $production->status == 'qc_check' ? '90%' : '50%' }}</span>
                    </div>
                    <div class="w-full bg-gray-200/80 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $production->status == 'qc_check' ? 'bg-gradient-to-r from-blue-400 to-blue-500' : 'bg-gradient-to-r from-yellow-400 to-yellow-500' }} h-2.5 rounded-full shadow-inner" style="width: {{ $production->status == 'qc_check' ? '90%' : '50%' }}"></div>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    @if($production->status == 'qc_check')
                        <a href="{{ route('operator.qc.create', ['production_id' => $production->id]) }}" class="w-full block bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold py-2.5 px-4 rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition shadow-sm text-sm text-center">
                            Input Form QC
                        </a>
                    @else
                        <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="qc_check">
                            <button type="submit" class="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold py-2.5 px-4 rounded-xl hover:bg-emerald-100 transition text-sm text-center">
                                Selesaikan
                            </button>
                        </form>
                        <a href="{{ route('operator.productions.show', $production->id) }}" class="block bg-white text-gray-600 font-bold py-2.5 px-4 rounded-xl hover:bg-gray-50 transition border border-gray-200 text-sm shadow-sm hover:shadow text-center">
                            Detail
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full p-8 bg-white/80 backdrop-blur-md border border-gray-200/60 rounded-2xl text-center text-gray-500 shadow-sm">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                <p class="font-medium text-lg">Tidak ada batch produksi yang sedang berjalan saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
