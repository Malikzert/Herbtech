@extends('layouts.app')

@section('title', 'Jadwal Produksi')
@section('header', 'JADWAL PRODUKSI AKTIF')

@section('styles')
<style>
    .stat-icon {
        width: 48px; height: 48px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .badge-fefo {
        background: rgba(139, 105, 20, 0.15);
        border: 1px solid rgba(139, 105, 20, 0.3);
        color: #D4B896;
    }
    [x-cloak] { display: none !important; }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats Header --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-[#A0845C]/20 text-[#F5EDE0]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740]">In Progress</p>
                    <p class="text-3xl font-black text-white mt-0.5">{{ $schedules->where('status', 'in_progress')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-[#8B6914]/20 text-[#D4B896]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740]">QC Check</p>
                    <p class="text-3xl font-black text-white mt-0.5">{{ $schedules->whereIn('status', ['qc_check', 'rework'])->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon bg-[#3d2b1f] text-[#8B6914]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740]">FEFO Alert</p>
                    <p class="text-3xl font-black text-white mt-0.5">{{ $hasExpiryWarnings ? '!' : '0' }}</p>
                    <p class="text-[10px] text-[#8B6914]/70 mt-0.5">{{ $hasExpiryWarnings ? 'Bahan mendekati kadaluwarsa' : 'Aman' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule Table --}}
    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 overflow-hidden">
        @if($schedules->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#2c1810] border-b border-[#3d2b1f]">
                    <tr>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Urutan</span></th>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Batch ID</span></th>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Nama Produk</span></th>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Target Qty</span></th>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Jam Mulai</span></th>
                        <th class="px-5 py-3.5 text-left"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Status</span></th>
                        <th class="px-5 py-3.5 text-center"><span class="text-[#D4B896] text-[10px] font-bold uppercase tracking-[0.15em]">Resep</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#3d2b1f]">
                    @foreach($schedules as $i => $production)
                    <tr class="hover:bg-[#2c1810]/50 transition-colors duration-150" x-data="{ open: false }">
                        <td class="px-5 py-4">
                            <span class="w-8 h-8 flex items-center justify-center text-xs font-bold bg-[#A0845C]/20 text-[#F5EDE0]">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 flex items-center justify-center shrink-0 bg-[#A0845C]/20">
                                    <svg class="w-4 h-4 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <span class="font-semibold text-white text-sm">{{ $production->batch_number }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-[#D4B896]">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-white">{{ number_format($production->target_quantity ?? 0) }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="font-medium text-[#D4B896]">{{ $production->scheduled_start?->format('d/m H:i') ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @switch($production->status)
                                @case('in_progress')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">Proses</span>
                                    @break
                                @case('qc_check')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#8B6914]/20 text-[#D4B896] border border-[#8B6914]/30">QC Check</span>
                                    @break
                                @case('rework')
                                    <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#6B5740]/20 text-[#A0845C] border border-[#6B5740]/30">Rework</span>
                                    @break
                            @endswitch

                            @if($production->expiring_materials->isNotEmpty())
                            <div class="mt-1 flex flex-wrap gap-1">
                                @foreach($production->expiring_materials as $mat)
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-bold rounded-sm badge-fefo">
                                    {{ $mat->name }} ({{ $mat->days_to_expiry }}hr)
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button @click="open = !open"
                                    class="px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.1em] transition border bg-[#3d2b1f] text-[#D4B896] border-[#3d2b1f] hover:bg-[#2c1810] inline-flex items-center gap-1">
                                <span x-text="open ? 'Tutup' : 'Detail Resep'"></span>
                            </button>
                        </td>
                    </tr>
                    <tr x-show="open" x-cloak x-transition
                        class="bg-[#1a1210]/40">
                        <td colspan="7" class="px-5 py-4">
                            <div class="border border-[#3d2b1f] p-4 bg-[#2c1810]/40">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 flex items-center justify-center bg-[#A0845C]/20">
                                        <svg class="w-3 h-3 text-[#D4B896]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740]">Kebutuhan Bahan Baku</span>
                                </div>

                                @if($production->productionMaterials->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($production->productionMaterials as $pm)
                                    @php
                                        $mat = $pm->rawMaterial;
                                        $isLow = $mat && $mat->current_stock < ($mat->min_stock_level ?? 10);
                                        $isExpiring = $mat && $mat->expired_date && Carbon\Carbon::now()->diffInDays($mat->expired_date, false) <= 14 && Carbon\Carbon::now()->diffInDays($mat->expired_date, false) >= 0;
                                    @endphp
                                    <div class="flex items-center gap-2.5 p-2.5 border border-[#3d2b1f] bg-[#1a1210]/60">
                                        <div class="w-7 h-7 flex items-center justify-center shrink-0 {{ $isLow || $isExpiring ? 'bg-[#8B6914]/20' : 'bg-[#A0845C]/20' }}">
                                            <svg class="w-3.5 h-3.5 {{ $isLow || $isExpiring ? 'text-[#D4B896]' : 'text-[#F5EDE0]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $isLow || $isExpiring ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z' : 'M5 13l4 4L19 7' }}"></path></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-white truncate">{{ $mat->name ?? '-' }}</p>
                                            <p class="text-[11px] text-[#6B5740]">
                                                {{ number_format($pm->quantity_used, 2) }} {{ $mat->unit ?? '' }}
                                                <span class="mx-1">|</span>
                                                Stok: {{ $mat->current_stock ?? 0 }}
                                                @if($isExpiring && $mat->expired_date)
                                                    <span class="text-[#8B6914] font-medium ms-1">
                                                        ({{ Carbon\Carbon::now()->diffInDays($mat->expired_date) }}hr)
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-[#6B5740] italic">Tidak ada data bahan baku.</p>
                                @endif

                                @if($production->schedule_notes)
                                <div class="mt-3 text-xs text-[#6B5740] italic border-t border-[#3d2b1f] pt-3">
                                    {{ $production->schedule_notes }}
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-16">
            <div class="w-16 h-16 flex items-center justify-center mx-auto mb-4 bg-[#3d2b1f]">
                <svg class="w-8 h-8 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h5 class="text-[#D4B896] font-bold text-lg mb-1">Belum ada jadwal produksi aktif</h5>
            <p class="text-[#6B5740] text-sm max-w-md mx-auto">Silakan tunggu konfirmasi Admin untuk penjadwalan produksi menggunakan Algoritma Genetika.</p>
        </div>
        @endif
    </div>
</div>
@endsection
