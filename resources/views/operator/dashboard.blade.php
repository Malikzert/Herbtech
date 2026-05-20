@extends('layouts.app')

@section('title', 'Operator Dashboard')
@section('header', 'DASHBOARD OPERASIONAL')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards - Valorant Style -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="relative bg-[#2c1810] overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-[#8B6914]/20" style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
            <div class="absolute top-0 right-0 w-[3px] h-full bg-gradient-to-b from-[#D4B896] via-[#8B6914] to-transparent opacity-70"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Sedang Berjalan</span>
                    <div class="w-10 h-10 bg-[#8B6914]/20 flex items-center justify-center border border-[#8B6914]/30">
                        <svg class="w-5 h-5 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-black text-white tracking-tight">{{ $inProgressCount }}</h3>
            </div>
            <div class="h-[2px] bg-gradient-to-r from-[#D4B896] via-[#8B6914] to-transparent"></div>
        </div>

        <div class="relative bg-[#3d2b1f] overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-[#A0845C]/20" style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
            <div class="absolute top-0 right-0 w-[3px] h-full bg-gradient-to-b from-[#F5EDE0] via-[#A0845C] to-transparent opacity-70"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[#F5EDE0] text-[10px] font-bold uppercase tracking-[0.15em]">Menunggu QC</span>
                    <div class="w-10 h-10 bg-[#A0845C]/20 flex items-center justify-center border border-[#A0845C]/30">
                        <svg class="w-5 h-5 text-[#F5EDE0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-black text-white tracking-tight">{{ $qcCheckCount }}</h3>
            </div>
            <div class="h-[2px] bg-gradient-to-r from-[#F5EDE0] via-[#A0845C] to-transparent"></div>
        </div>

        <div class="relative bg-[#1a1210] overflow-hidden group">
            <div class="absolute -top-6 -right-6 w-20 h-20 bg-[#6B5740]/20" style="clip-path: polygon(100% 0, 0% 100%, 100% 100%);"></div>
            <div class="absolute top-0 right-0 w-[3px] h-full bg-gradient-to-b from-[#D4B896] via-[#6B5740] to-transparent opacity-70"></div>
            <div class="relative p-6">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Stok Aman</span>
                    <div class="w-10 h-10 bg-[#6B5740]/20 flex items-center justify-center border border-[#6B5740]/30">
                        <svg class="w-5 h-5 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                </div>
                <h3 class="text-4xl font-black text-white tracking-tight">{{ $safeStockCount }}</h3>
            </div>
            <div class="h-[2px] bg-gradient-to-r from-[#D4B896] via-[#6B5740] to-transparent"></div>
        </div>
    </div>

    <!-- Active Productions Table -->
    <div>
        <div class="flex items-center gap-3 mb-5">
            <h3 class="text-lg font-black text-white uppercase tracking-[0.1em]">Tabel Produksi Aktif</h3>
            <div class="flex-1 h-[2px] bg-gradient-to-r from-[#8B6914]/50 to-transparent"></div>
        </div>
        <div class="bg-[#1a1210] border border-[#3d2b1f] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#2c1810] border-b border-[#3d2b1f]">
                            <th class="px-6 py-3.5 text-left">
                                <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">No Batch</span>
                            </th>
                            <th class="px-6 py-3.5 text-left">
                                <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Produk</span>
                            </th>
                            <th class="px-6 py-3.5 text-left">
                                <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Status</span>
                            </th>
                            <th class="px-6 py-3.5 text-center">
                                <span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Aksi</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#3d2b1f]">
                        @forelse($activeProductions as $production)
                        <tr class="hover:bg-[#2c1810]/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-white">{{ $production->batch_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-[#D4B896]">{{ $production->product->name ?? 'Produk' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($production->status == 'qc_check')
                                    <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">QC</span>
                                @else
                                    <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">Proses</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($production->status == 'qc_check')
                                    <a href="{{ route('operator.qc.create', ['production_id' => $production->id]) }}" class="inline-block px-4 py-1.5 bg-[#8B6914] hover:bg-[#A0845C] text-white text-[10px] font-bold uppercase tracking-[0.1em] transition-colors duration-150">
                                        Cek QC
                                    </a>
                                @else
                                    <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="qc_check">
                                        <button type="submit" class="px-4 py-1.5 bg-[#A0845C] hover:bg-[#8B6914] text-white text-[10px] font-bold uppercase tracking-[0.1em] transition-colors duration-150">
                                            Selesai
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <span class="text-[#6B5740] text-sm">Tidak ada produksi aktif hari ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
