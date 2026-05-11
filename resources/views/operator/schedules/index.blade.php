@extends('layouts.app')

@section('title', 'Jadwal Produksi')
@section('header', 'Jadwal Produksi Aktif')

@section('styles')
<style>
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .badge-fefo {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #dc2626;
    }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Stats Header --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(37, 99, 235, 0.2);">
                    <i class="bi bi-play-circle text-blue-600"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">In Progress</p>
                    <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $schedules->where('status', 'in_progress')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.2);">
                    <i class="bi bi-clipboard-check text-amber-500"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">QC Check</p>
                    <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $schedules->whereIn('status', ['qc_check', 'rework'])->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(220, 38, 38, 0.12);">
                    <i class="bi bi-exclamation-triangle text-red-500"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">FEFO Alert</p>
                    <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $hasExpiryWarnings ? '!' : '0' }}</p>
                    <p class="text-xs text-red-600/70 mt-0.5">{{ $hasExpiryWarnings ? 'Bahan mendekati kadaluwarsa' : 'Aman' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule Table --}}
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        @if($schedules->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead class="bg-blue-800 text-white text-xs uppercase">
                    <tr>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Urutan</th>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Batch ID</th>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Nama Produk</th>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Target Qty</th>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Jam Mulai</th>
                        <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Status</th>
                        <th class="px-5 py-3.5 font-bold text-center text-white text-shadow-sm">Resep</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/30">
                    @foreach($schedules as $i => $production)
                    <tr class="hover:bg-white/10 transition" x-data="{ open: false }">
                        <td class="px-5 py-4">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold"
                                  style="background: rgba(37, 99, 235, 0.12); color: #1e40af;">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(37, 99, 235, 0.12);">
                                    <i class="bi bi-box-seam text-blue-700 text-xs"></i>
                                </div>
                                <span class="font-semibold text-gray-800 text-sm">{{ $production->batch_number }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-700">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="text-sm font-medium text-gray-700">{{ number_format($production->target_quantity ?? 0) }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm">
                            <span class="font-medium text-gray-800">{{ $production->scheduled_start?->format('d/m H:i') ?? '-' }}</span>
                        </td>
                        <td class="px-5 py-4">
                            @switch($production->status)
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">
                                        <i class="bi bi-arrow-repeat me-0.5"></i> Proses
                                    </span>
                                    @break
                                @case('qc_check')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                        <i class="bi bi-clipboard-check me-0.5"></i> QC Check
                                    </span>
                                    @break
                                @case('rework')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-300">
                                        <i class="bi bi-arrow-counterclockwise me-0.5"></i> Rework
                                    </span>
                                    @break
                            @endswitch

                            @if($production->expiring_materials->isNotEmpty())
                            <div class="mt-1 flex flex-wrap gap-1">
                                @foreach($production->expiring_materials as $mat)
                                <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-bold rounded badge-fefo">
                                    <i class="bi bi-clock"></i>
                                    {{ $mat->name }} ({{ $mat->days_to_expiry }}hr)
                                </span>
                                @endforeach
                            </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button @click="open = !open"
                                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition border bg-white/40 text-gray-700 border-white/50 hover:bg-white/60 inline-flex items-center gap-1">
                                <i class="bi bi-book" :class="open ? 'bi-book-fill' : 'bi-book'"></i>
                                <span x-text="open ? 'Tutup' : 'Detail Resep'"></span>
                            </button>
                        </td>
                    </tr>
                    <tr x-show="open" x-cloak x-transition
                        class="bg-white/5">
                        <td colspan="7" class="px-5 py-4">
                            <div class="rounded-xl border border-white/30 p-4" style="background: rgba(255,255,255,0.15);">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-6 h-6 rounded flex items-center justify-center" style="background: rgba(37, 99, 235, 0.15);">
                                        <i class="bi bi-list-check text-blue-700 text-xs"></i>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-wider text-gray-600">Kebutuhan Bahan Baku</span>
                                </div>

                                @if($production->productionMaterials->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($production->productionMaterials as $pm)
                                    @php
                                        $mat = $pm->rawMaterial;
                                        $isLow = $mat && $mat->current_stock < ($mat->min_stock_level ?? 10);
                                        $isExpiring = $mat && $mat->expired_date && Carbon\Carbon::now()->diffInDays($mat->expired_date, false) <= 14 && Carbon\Carbon::now()->diffInDays($mat->expired_date, false) >= 0;
                                    @endphp
                                    <div class="flex items-center gap-2.5 p-2.5 rounded-lg border"
                                         style="background: rgba(255,255,255,0.3); border-color: rgba(0,0,0,0.06);">
                                        <div class="w-7 h-7 rounded flex items-center justify-center shrink-0
                                            {{ $isLow || $isExpiring ? 'bg-red-100' : 'bg-emerald-100' }}">
                                            <i class="bi {{ $isLow || $isExpiring ? 'bi-exclamation-triangle text-red-600' : 'bi-check-circle text-emerald-600' }} text-xs"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $mat->name ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-500">
                                                {{ number_format($pm->quantity_used, 2) }} {{ $mat->unit ?? '' }}
                                                <span class="mx-1">|</span>
                                                Stok: {{ $mat->current_stock ?? 0 }}
                                                @if($isExpiring && $mat->expired_date)
                                                    <span class="text-red-600 font-medium ms-1">
                                                        ({{ Carbon\Carbon::now()->diffInDays($mat->expired_date) }}hr)
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <p class="text-sm text-gray-500 italic">Tidak ada data bahan baku.</p>
                                @endif

                                @if($production->schedule_notes)
                                <div class="mt-3 text-xs text-gray-500 italic border-t border-white/20 pt-3">
                                    <i class="bi bi-info-circle me-1"></i>{{ $production->schedule_notes }}
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
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-white/30">
                <i class="bi bi-calendar-x text-gray-400 text-3xl"></i>
            </div>
            <h5 class="text-gray-600 font-semibold text-lg mb-1">Belum ada jadwal produksi aktif</h5>
            <p class="text-gray-400 text-sm max-w-md mx-auto">Silakan tunggu konfirmasi Admin untuk penjadwalan produksi menggunakan Algoritma Genetika.</p>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush
