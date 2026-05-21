@extends('layouts.admin')

@section('title', 'QC Bahan Baku')
@section('header', 'QUALITY CONTROL BAHAN BAKU')

@section('content')
<style>
    .hybrid-input {
        background: rgba(6, 78, 59, 0.6);
        border: 1.5px solid rgba(5, 150, 105, 0.25);
        color: #fff;
        transition: all 0.2s ease;
    }
    .hybrid-input:focus {
        border-color: #10B981;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.2);
        outline: none;
    }
    .hybrid-table thead { background: rgba(5, 150, 105, 0.15); }
    .hybrid-table thead th {
        color: #34D399;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
    }
    .hybrid-table tbody tr { border-bottom: 1px solid rgba(5, 150, 105, 0.08); transition: all 0.2s ease; }
    .hybrid-table tbody tr:hover { background: rgba(5, 150, 105, 0.05); }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(6, 78, 59, 0.3); }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
</style>

<div x-data="{ detailModalOpen: false, detailData: null }">
    {{-- STAT ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="relative overflow-hidden border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total QC</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $qcRecords->total() }}</p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Lolos</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterialQc::where('status', 'passed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Rework</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterialQc::where('status', 'rework')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Ditolak</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ \App\Models\RawMaterialQc::where('status', 'decline')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="mb-6 border border-emerald-500/30 bg-emerald-500/10 backdrop-blur-md p-4" style="border-radius:0;">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-bold text-emerald-200">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- NOTIFICATION: New QC Passed Alerts --}}
    @php
        $newlyPassed = \App\Models\RawMaterialQc::where('status', 'passed')
            ->where('created_at', '>=', now()->subDays(1))
            ->with('rawMaterial')
            ->get();
    @endphp
    @if($newlyPassed->isNotEmpty())
    <div class="mb-6 border border-emerald-500/30 bg-emerald-500/10 backdrop-blur-md p-4" style="border-radius:0;">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius:0;">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Bahan Baku Siap Digunakan</h3>
                <p class="text-xs text-emerald-200/40">{{ $newlyPassed->count() }} material baru lolos QC (24 jam terakhir)</p>
            </div>
        </div>
        <div class="space-y-2">
            @foreach($newlyPassed as $qc)
            <div class="flex items-center justify-between px-4 py-2.5 bg-emerald-500/5 border border-emerald-500/15" style="border-radius:0;">
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 bg-emerald-400" style="border-radius:0;"></span>
                    <span class="text-sm font-bold text-emerald-50">{{ $qc->rawMaterial->name }}</span>
                    <span class="text-xs font-mono text-emerald-200/40">{{ $qc->rawMaterial->sku ?? '-' }}</span>
                </div>
                <span class="text-xs font-bold text-emerald-400">{{ $qc->qc_percentage }}%</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="relative overflow-hidden border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius:0;">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Riwayat QC Bahan Baku</h3>
                    <p class="text-xs text-emerald-200/40">Semua record pengecekan kualitas</p>
                </div>
            </div>
            <a href="{{ route('admin.raw-materials.index') }}" class="h-9 px-4 text-xs font-bold uppercase tracking-wider border border-emerald-500/30 text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200 flex items-center gap-2" style="border-radius:0;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Bahan Baku</th>
                        <th class="px-6 py-4 text-left">Operator</th>
                        <th class="px-6 py-4 text-center">Diperiksa</th>
                        <th class="px-6 py-4 text-center">Baik</th>
                        <th class="px-6 py-4 text-center">Rusak</th>
                        <th class="px-6 py-4 text-center">% Lolos</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @forelse($qcRecords as $index => $qc)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-200/50">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-emerald-50/90">{{ $qc->rawMaterial->name ?? '-' }}</div>
                            <div class="text-xs text-emerald-200/40 font-mono">{{ $qc->rawMaterial->sku ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60">{{ $qc->user->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-emerald-200/80">{{ $qc->total_qty_checked }}</td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-emerald-400">{{ $qc->good_qty }}</td>
                        <td class="px-6 py-4 text-center font-mono text-sm text-red-400">{{ $qc->bad_qty }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-mono text-sm font-bold"
                                :class="{
                                    'text-emerald-400': {{ $qc->qc_percentage }} >= 80,
                                    'text-amber-400': {{ $qc->qc_percentage }} >= 50 && {{ $qc->qc_percentage }} < 80,
                                    'text-red-400': {{ $qc->qc_percentage }} < 50 && {{ $qc->qc_percentage }} > 0,
                                    'text-emerald-200/30': {{ $qc->qc_percentage }} == 0
                                }">
                                {{ number_format($qc->qc_percentage, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @switch($qc->status)
                                @case('waiting')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-300 border border-sky-500/20" style="border-radius:0;">
                                    <span class="w-1.5 h-1.5 bg-sky-400" style="border-radius:0;"></span>
                                    Waiting
                                </span>
                                @break
                                @case('passed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-300 border border-emerald-500/20" style="border-radius:0;">
                                    <span class="w-1.5 h-1.5 bg-emerald-400" style="border-radius:0;"></span>
                                    Passed
                                </span>
                                @break
                                @case('rework')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius:0;">
                                    <span class="w-1.5 h-1.5 bg-amber-400" style="border-radius:0;"></span>
                                    Rework
                                </span>
                                @break
                                @case('decline')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20" style="border-radius:0;">
                                    <span class="w-1.5 h-1.5 bg-red-400" style="border-radius:0;"></span>
                                    Decline
                                </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="detailModalOpen = true; detailData = {{ Js::from($qc->load('rawMaterial', 'user')) }}"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all" title="Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                @if($qc->status === 'rework')
                                <form method="POST" action="{{ route('admin.raw-materials.qc.resend', $qc->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="h-9 px-3 text-[10px] font-bold uppercase tracking-wider bg-amber-500/15 text-amber-300 border border-amber-500/30 hover:bg-amber-500/25 hover:border-amber-400/50 transition-all duration-200 flex items-center gap-1.5" style="border-radius:0;"
                                        onclick="return confirm('Kirim ulang bahan ini untuk QC?')">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        KIRIM ULANG
                                    </button>
                                </form>
                                @endif
                                @if($qc->status === 'decline')
                                <span class="text-[10px] text-red-400/60 font-bold uppercase tracking-wider px-2">Diblokir</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius:0;">
                                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada Riwayat QC</p>
                                <p class="text-emerald-200/30 text-xs mt-2">Data QC akan muncul setelah operator melakukan pemeriksaan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($qcRecords->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $qcRecords->links() }}
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>

    {{-- DETAIL MODAL --}}
    <div x-show="detailModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="detailModalOpen" @click="detailModalOpen = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>
            <div x-show="detailModalOpen" @click.stop
                class="relative w-full max-w-lg border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)]" style="border-radius:0;">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                <div class="flex justify-between items-center px-6 py-4 border-b border-emerald-500/15">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius:0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Detail QC</h3>
                            <p class="text-xs text-emerald-200/40" x-text="detailData?.raw_material?.name || ''"></p>
                        </div>
                    </div>
                    <button @click="detailModalOpen = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="px-6 pb-6">
                    <template x-if="detailData">
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 p-4 border border-emerald-500/15 bg-emerald-500/5" style="border-radius:0;">
                                <div class="flex-1">
                                    <p class="text-lg font-black text-emerald-50" x-text="detailData.raw_material?.name"></p>
                                    <p class="text-xs text-emerald-200/40 font-mono" x-text="detailData.raw_material?.sku || 'Tanpa SKU'"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-3xl font-black font-mono" x-text="detailData.qc_percentage + '%'"
                                        :class="{'text-emerald-400': detailData.qc_percentage >= 80, 'text-amber-400': detailData.qc_percentage >= 50 && detailData.qc_percentage < 80, 'text-red-400': detailData.qc_percentage < 50 && detailData.qc_percentage > 0}"></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="p-3 border border-emerald-500/15" style="border-radius:0;">
                                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold mb-1">Diperiksa</p>
                                    <p class="text-xl font-black text-emerald-50 font-mono" x-text="detailData.total_qty_checked"></p>
                                </div>
                                <div class="p-3 border border-emerald-500/15" style="border-radius:0;">
                                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold mb-1">Baik</p>
                                    <p class="text-xl font-black text-emerald-400 font-mono" x-text="detailData.good_qty"></p>
                                </div>
                                <div class="p-3 border border-emerald-500/15" style="border-radius:0;">
                                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold mb-1">Rusak</p>
                                    <p class="text-xl font-black text-red-400 font-mono" x-text="detailData.bad_qty"></p>
                                </div>
                            </div>
                            <div class="flex justify-between py-3 border-b border-emerald-500/15">
                                <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Operator</span>
                                <span class="text-sm font-bold text-emerald-50" x-text="detailData.user?.name || '-'"></span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-emerald-500/15">
                                <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Status</span>
                                <span class="text-sm font-bold" x-text="detailData.status?.toUpperCase()"
                                    :class="{'text-emerald-400': detailData.status === 'passed', 'text-amber-400': detailData.status === 'rework', 'text-red-400': detailData.status === 'decline', 'text-sky-400': detailData.status === 'waiting'}"></span>
                            </div>
                            <template x-if="detailData.notes">
                                <div class="py-3">
                                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold mb-2">Catatan</p>
                                    <p class="text-sm text-emerald-200/80" x-text="detailData.notes"></p>
                                </div>
                            </template>
                            <div class="flex justify-between py-3">
                                <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Waktu QC</span>
                                <span class="text-sm text-emerald-200/60" x-text="new Date(detailData.created_at).toLocaleString('id-ID')"></span>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
        </div>
    </div>
</div>
@endsection
