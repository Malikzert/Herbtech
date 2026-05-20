@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('header', 'DASHBOARD')

@section('content')
<style>
    @keyframes pulseGlow {
        0%, 100% { box-shadow: inset 0 0 12px rgba(239, 68, 68, 0.08), inset 0 0 4px rgba(239, 68, 68, 0.05); }
        50% { box-shadow: inset 0 0 35px rgba(239, 68, 68, 0.25), inset 0 0 15px rgba(239, 68, 68, 0.1); }
    }
    .card-glow-red {
        animation: pulseGlow 4s ease-in-out infinite;
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
</style>
<div class="space-y-6">
    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Produksi</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $totalProductions ?? 0 }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Stok Bahan Rendah</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $lowStockCount ?? 0 }}</p>
                    <p class="text-[10px] text-red-700/80 mt-0.5 font-bold uppercase tracking-wider">Perlu pengadaan</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Menunggu QC</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $pendingQcCount ?? 0 }}</p>
                    <p class="text-[10px] text-amber-400/80 mt-0.5 font-bold uppercase tracking-wider">Dalam antrian</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>

        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-blue-500/15 border border-blue-500/30" style="border-radius: 0;">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Total Produk</p>
                    <p class="text-2xl font-black text-emerald-50 mt-0.5">{{ $totalProducts ?? 0 }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>
    </div>

    {{-- CHARTS SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- BAR CHART --}}
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_4px_24px_rgba(0,0,0,0.2)] card-glow-red">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4 border-b border-emerald-500/15 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Stok Bahan Baku</h3>
                            <p class="text-xs text-emerald-200/40">15 bahan dengan stok paling rendah</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-red-700/70" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-red-700/60 border border-red-900/30 px-3 py-1" style="border-radius: 0;">
                            EARLY WARNING
                        </span>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="stockBarChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- LINE CHART --}}
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="p-5">
                <div class="flex items-center justify-between mb-4 border-b border-emerald-500/15 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Quality Control Check</h3>
                            <p class="text-xs text-emerald-200/40">Tren 12 bulan terakhir</p>
                        </div>
                    </div>
                    <div class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/50 border border-emerald-500/20 px-3 py-1" style="border-radius: 0;">
                        TRACKING
                    </div>
                </div>
                <div class="relative">
                    <canvas id="qcLineChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT PRODUCTIONS & QC OVERVIEW --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Productions --}}
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_4px_24px_rgba(0,0,0,0.2)] lg:col-span-2">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-500/15">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Produksi Terbaru</h3>
                        <p class="text-xs text-emerald-200/40">5 batch terakhir</p>
                    </div>
                </div>
                <a href="{{ route('admin.productions.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 px-3 py-1.5 transition-all duration-200" style="border-radius: 0;">Lihat Semua</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full hybrid-table">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">No Batch</th>
                            <th class="px-6 py-4 text-left">Produk</th>
                            <th class="px-6 py-4 text-left">Operator</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-emerald-500/10">
                        @forelse($recentProductions as $production)
                        <tr class="group">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $production->batch_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $production->product->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $production->user->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
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
                                        On Progress
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
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.productions.show', $production->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 transition-all duration-200" style="border-radius: 0;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <p class="text-emerald-200/60 font-bold text-xs uppercase tracking-wider">Belum Ada Data Produksi</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
        </div>

        {{-- QC Summary --}}
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="flex items-center justify-between mb-4 pt-2">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-emerald-50">Ringkasan QC</h3>
                        <p class="text-xs text-emerald-200/40">Hasil inspeksi</p>
                    </div>
                </div>
                <a href="{{ route('admin.qc.index') }}" class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 px-3 py-1.5 transition-all duration-200" style="border-radius: 0;">Lihat Semua</a>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 border border-emerald-500/15 bg-emerald-500/5" style="border-radius: 0;">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Passed</span>
                    </div>
                    <span class="text-lg font-black text-emerald-300">{{ $passedQcCount ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 border border-amber-500/15 bg-amber-500/5" style="border-radius: 0;">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Rework</span>
                    </div>
                    <span class="text-lg font-black text-amber-300">{{ $reworkQcCount ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-3 border border-red-500/15 bg-red-500/5" style="border-radius: 0;">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 flex items-center justify-center bg-red-500/15 border border-red-500/30" style="border-radius: 0;">
                            <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-50/80">Rejected</span>
                    </div>
                    <span class="text-lg font-black text-red-300">{{ $rejectedQcCount ?? 0 }}</span>
                </div>
            </div>

            <div class="mt-5 pt-4 border-t border-emerald-500/15">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400/60">Pass Rate</span>
                    <span class="text-base font-black text-emerald-300">{{ $qcPassRate ?? 0 }}%</span>
                </div>
                <div class="w-full bg-emerald-900/40 h-2" style="border-radius: 0;">
                    <div class="bg-emerald-500 h-2" style="width: {{ $qcPassRate ?? 0 }}%; border-radius: 0;"></div>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textColor = 'rgba(255,255,255,0.7)';
    const gridColor = 'rgba(255,255,255,0.06)';

    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";

    // === BAR CHART ===
    const stockCtx = document.getElementById('stockBarChart');
    if (stockCtx) {
        new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($matLabels) !!},
                datasets: [{
                    label: 'Stok',
                    data: {!! json_encode($matStocks) !!},
                    backgroundColor: {!! json_encode(array_fill(0, count($matStocks), 'rgba(5, 150, 105, 0.7)')) !!},
                    borderColor: {!! json_encode(array_fill(0, count($matStocks), '#059669')) !!},
                    borderWidth: 1,
                    borderRadius: 0,
                    borderSkipped: false,
                    hoverBackgroundColor: '#059669',
                    hoverBorderColor: '#34D399',
                    hoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        titleColor: '#34D399',
                        bodyColor: '#fff',
                        borderColor: '#059669',
                        borderWidth: 2,
                        cornerRadius: 0,
                        padding: 12,
                        callbacks: {
                            label: function(ctx) {
                                return 'Stok: ' + ctx.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            color: textColor,
                            font: { size: 9, weight: 'bold' },
                            stepSize: 1,
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: textColor,
                            font: { size: 8, weight: 'bold' },
                            maxRotation: 45,
                        }
                    }
                },
                onHover: function(e) {
                    const point = this.getElementsAtEventForMode(e, 'index', { intersect: true });
                    e.native.target.style.cursor = point.length ? 'pointer' : 'default';
                }
            }
        });
    }

    // === LINE CHART ===
    const qcCtx = document.getElementById('qcLineChart');
    if (qcCtx) {
        new Chart(qcCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($qcDates) !!},
                datasets: [
                    {
                        label: 'Passed',
                        data: {!! json_encode($qcPassed) !!},
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.08)',
                        borderWidth: 2,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#064E3B',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3,
                        pointHoverBorderColor: '#A7F3D0',
                        hoverBorderColor: '#34D399',
                        tension: 0.3,
                        fill: true,
                    },
                    {
                        label: 'Rework',
                        data: {!! json_encode($qcRework) !!},
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.05)',
                        borderWidth: 2,
                        pointBackgroundColor: '#F59E0B',
                        pointBorderColor: '#064E3B',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3,
                        pointHoverBorderColor: '#FDE68A',
                        hoverBorderColor: '#FBBF24',
                        tension: 0.3,
                        fill: false,
                    },
                    {
                        label: 'Rejected',
                        data: {!! json_encode($qcRejected) !!},
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.05)',
                        borderWidth: 2,
                        pointBackgroundColor: '#EF4444',
                        pointBorderColor: '#064E3B',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 8,
                        pointHoverBorderWidth: 3,
                        pointHoverBorderColor: '#FCA5A5',
                        hoverBorderColor: '#F87171',
                        tension: 0.3,
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2.5,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            color: textColor,
                            font: { size: 9, weight: 'bold' },
                            boxWidth: 12,
                            boxHeight: 2,
                            usePointStyle: true,
                            pointStyle: 'line',
                            padding: 12,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        titleColor: '#34D399',
                        bodyColor: '#fff',
                        borderColor: '#059669',
                        borderWidth: 2,
                        cornerRadius: 0,
                        padding: 12,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor, drawBorder: false },
                        ticks: {
                            color: textColor,
                            font: { size: 9, weight: 'bold' },
                            stepSize: 1,
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: textColor,
                            font: { size: 8, weight: 'bold' },
                            maxRotation: 45,
                        }
                    }
                },
                interaction: {
                    intersect: true,
                    mode: 'index',
                },
                onHover: function(e) {
                    const point = this.getElementsAtEventForMode(e, 'index', { intersect: true });
                    e.native.target.style.cursor = point.length ? 'pointer' : 'default';
                }
            }
        });
    }
});
</script>
@endpush
