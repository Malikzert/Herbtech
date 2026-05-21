@extends('layouts.app')

@section('title', 'QC Bahan Baku')
@section('header', 'QUALITY CONTROL BAHAN BAKU')

@section('content')
<style>
    .qc-input {
        background: rgba(15, 23, 42, 0.8);
        border: 1.5px solid rgba(56, 189, 248, 0.25);
        color: #fff;
        transition: all 0.2s ease;
    }
    .qc-input:focus {
        border-color: #38BDF8;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.15);
        outline: none;
    }
    .qc-input::placeholder { color: rgba(255,255,255,0.2); }
    .qc-table thead { background: rgba(56, 189, 248, 0.08); }
    .qc-table thead th {
        color: #38BDF8;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
    }
    .qc-table tbody tr {
        border-bottom: 1px solid rgba(56, 189, 248, 0.06);
        transition: all 0.2s ease;
    }
    .qc-table tbody tr:hover {
        background: rgba(56, 189, 248, 0.03);
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(15, 23, 42, 0.5); }
    ::-webkit-scrollbar-thumb { background: rgba(56, 189, 248, 0.25); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(56, 189, 248, 0.4); }

    {{-- QC Modal theme overrides --}}
    .theme-dark .qc-modal-container {
        background: #0f172a !important;
        box-shadow: 0 0 60px rgba(56, 189, 248, 0.1) !important;
    }
    .theme-dark .qc-modal-container .border-b {
        border-color: rgba(56, 189, 248, 0.15) !important;
    }
    .theme-dark .qc-modal-container .btn-close,
    .theme-dark .qc-modal-container .btn-close svg {
        color: rgba(255,255,255,0.3) !important;
    }
    .theme-dark .qc-modal-container .btn-close:hover {
        background: rgba(56,189,248,0.15) !important;
        color: #1DA1F2 !important;
    }
    .theme-light .qc-modal-container {
        background: #ffffff !important;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15) !important;
    }
    .theme-light .qc-modal-container .border-b {
        border-color: #e5e7eb !important;
    }

    {{-- Dark modal -- typography & surfaces --}}
    .theme-dark .qc-modal-container .modal-h,
    .theme-dark .qc-modal-container .modal-body {
        color: #ffffff !important;
    }
    .theme-dark .qc-modal-container .modal-sub {
        color: rgba(255,255,255,0.5) !important;
    }
    .theme-dark .qc-modal-container .modal-icon svg {
        color: #1DA1F2 !important;
    }
    .theme-dark .qc-modal-container .modal-icon {
        border-color: rgba(29,161,242,0.3) !important;
        background: rgba(29,161,242,0.15) !important;
    }
    .theme-dark .qc-modal-container .info-card {
        background: rgba(56,189,248,0.08) !important;
        border-color: rgba(56,189,248,0.2) !important;
    }
    .theme-dark .qc-modal-container .info-card .text-gray-900,
    .theme-dark .qc-modal-container .info-card .font-black {
        color: #ffffff !important;
    }
    .theme-dark .qc-modal-container .info-card .text-gray-500 {
        color: rgba(255,255,255,0.5) !important;
    }
    .theme-dark .qc-modal-container .info-card .text-gray-400 {
        color: rgba(255,255,255,0.4) !important;
    }

    .theme-dark .qc-modal-container input[name="good_qty"] {
        background: rgba(5,150,105,0.15) !important;
        border-color: rgba(5,150,105,0.4) !important;
        color: #34d399 !important;
    }
    .theme-dark .qc-modal-container input[name="good_qty"]:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.2) !important;
    }
    .theme-dark .qc-modal-container input[name="good_qty"]::placeholder {
        color: rgba(255,255,255,0.2) !important;
    }

    .theme-dark .qc-modal-container .summary-neutral {
        background: rgba(255,255,255,0.06) !important;
        border-color: rgba(255,255,255,0.1) !important;
    }
    .theme-dark .qc-modal-container .summary-neutral .text-gray-900 {
        color: #ffffff !important;
    }
    .theme-dark .qc-modal-container .summary-neutral .text-gray-500 {
        color: rgba(255,255,255,0.4) !important;
    }
    .theme-dark .qc-modal-container .summary-bad {
        background: rgba(239,68,68,0.15) !important;
        border-color: rgba(239,68,68,0.3) !important;
    }
    .theme-dark .qc-modal-container .summary-bad .text-red-600 {
        color: #f87171 !important;
    }
    .theme-dark .qc-modal-container .summary-bad .text-red-500 {
        color: #ef4444 !important;
    }

    .theme-dark .qc-modal-container .pct-emerald {
        background: rgba(5,150,105,0.15) !important;
        border-color: #10b981 !important;
    }
    .theme-dark .qc-modal-container .pct-amber {
        background: rgba(245,158,11,0.15) !important;
        border-color: #f59e0b !important;
    }
    .theme-dark .qc-modal-container .pct-red {
        background: rgba(239,68,68,0.15) !important;
        border-color: #ef4444 !important;
    }
    .theme-dark .qc-modal-container .pct-gray {
        background: rgba(255,255,255,0.05) !important;
        border-color: rgba(255,255,255,0.15) !important;
    }
    .theme-dark .qc-modal-container .text-emerald-700 { color: #34d399 !important; }
    .theme-dark .qc-modal-container .text-amber-700 { color: #fbbf24 !important; }
    .theme-dark .qc-modal-container .text-red-700 { color: #f87171 !important; }
    .theme-dark .qc-modal-container .text-emerald-600 { color: #34d399 !important; }
    .theme-dark .qc-modal-container .text-amber-600 { color: #fbbf24 !important; }
    .theme-dark .qc-modal-container .text-red-600 { color: #f87171 !important; }
    .theme-dark .qc-modal-container .text-gray-300 { color: rgba(255,255,255,0.2) !important; }
    .theme-dark .qc-modal-container .text-gray-400 { color: rgba(255,255,255,0.4) !important; }
    .theme-dark .qc-modal-container .text-gray-500 { color: rgba(255,255,255,0.4) !important; }
    .theme-dark .qc-modal-container .text-gray-600 { color: rgba(255,255,255,0.6) !important; }

    .theme-dark .qc-modal-container label {
        color: rgba(255,255,255,0.6) !important;
    }
    .theme-dark .qc-modal-container .modal-input {
        background: rgba(255,255,255,0.06) !important;
        border-color: rgba(255,255,255,0.15) !important;
        color: #ffffff !important;
    }
    .theme-dark .qc-modal-container .modal-input:focus {
        border-color: #1DA1F2 !important;
        box-shadow: 0 0 0 2px rgba(29,161,242,0.2) !important;
    }
    .theme-dark .qc-modal-container .modal-input::placeholder {
        color: rgba(255,255,255,0.3) !important;
    }

    .theme-dark .qc-modal-container .border-t {
        border-color: rgba(255,255,255,0.1) !important;
    }

    .theme-dark .qc-modal-container .btn-passed-inactive {
        background: rgba(56,189,248,0.1) !important;
        border-color: rgba(56,189,248,0.3) !important;
        color: #93C5FD !important;
    }
    .theme-dark .qc-modal-container .btn-rework-inactive {
        background: rgba(245,158,11,0.1) !important;
        border-color: rgba(245,158,11,0.3) !important;
        color: #fcd34d !important;
    }
    .theme-dark .qc-modal-container .btn-decline-inactive {
        background: rgba(239,68,68,0.1) !important;
        border-color: rgba(239,68,68,0.3) !important;
        color: #fca5a5 !important;
    }
</style>

<div x-data="{
    showModal: false,
    selectedMaterial: null,
    totalQty: 0,
    goodQty: 0,
    selectedStatus: null,
    notes: '',

    get percentage() {
        if (this.totalQty <= 0) return 0;
        return Math.round((this.goodQty / this.totalQty) * 100);
    },

    get canPassed() { return this.percentage >= 70; },
    get canRework() { return this.percentage >= 50 && this.percentage < 80; },
    get canDecline() { return this.percentage < 50; },

    get activeButton() {
        if (this.percentage >= 80) return 'passed';
        if (this.percentage >= 70) return null;
        if (this.percentage >= 50) return 'rework';
        return 'decline';
    },

    get isQcValid() {
        return this.totalQty > 0 &&
               this.goodQty >= 0 &&
               this.goodQty <= this.totalQty;
    },

    openQc(material) {
        this.selectedMaterial = material;
        this.totalQty = material.current_stock || 0;
        this.goodQty = 0;
        this.selectedStatus = null;
        this.notes = '';
        this.showModal = true;
    },

    submitForm() {
        if (!this.selectedStatus || !this.isQcValid) return;
        $refs.qcForm.submit();
    }
}">

    {{-- STAT ROW --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="relative overflow-hidden border border-sky-500/25 bg-slate-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.3)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-sky-500/60 via-sky-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-sky-500/15 border border-sky-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-sky-400/60 font-bold">Menunggu QC</p>
                    <p class="text-2xl font-black text-sky-50 mt-0.5">{{ $materials->total() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-sky-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-sky-500/30"></div>
        </div>

        <div class="relative overflow-hidden border border-sky-500/25 bg-slate-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.3)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-sky-500/60 via-sky-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-sky-500/15 border border-sky-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-sky-400/60 font-bold">Total Bahan</p>
                    <p class="text-2xl font-black text-sky-50 mt-0.5">{{ \App\Models\RawMaterial::count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-sky-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-sky-500/30"></div>
        </div>

        <div class="relative overflow-hidden border border-sky-500/25 bg-slate-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.3)]" style="border-radius:0;">
            <div class="h-[2px] bg-gradient-to-r from-sky-500/60 via-sky-400/30 to-transparent"></div>
            <div class="flex items-center gap-4 mt-3">
                <div class="w-12 h-12 flex items-center justify-center bg-sky-500/15 border border-sky-500/30" style="border-radius:0;">
                    <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-[0.15em] text-sky-400/60 font-bold">Butuh QC Ulang</p>
                    <p class="text-2xl font-black text-sky-50 mt-0.5">{{ \App\Models\RawMaterial::where('qc_status', 'rework')->count() }}</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-sky-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-sky-500/30"></div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="mb-6 border border-sky-500/30 bg-sky-500/10 backdrop-blur-md p-4" style="border-radius:0;">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-sm font-bold text-sky-200">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- TABLE --}}
    <div class="relative overflow-hidden border border-sky-500/25 bg-slate-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(56,189,248,0.05)]">
        <div class="h-[2px] bg-gradient-to-r from-sky-500/60 via-sky-400/30 to-transparent"></div>
        <div class="flex items-center justify-between px-6 py-4 border-b border-sky-500/15">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 flex items-center justify-center bg-sky-500/15 border border-sky-500/30" style="border-radius:0;">
                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-wider text-sky-50">Antrian QC Bahan Baku</h3>
                    <p class="text-xs text-sky-200/40">{{ $materials->total() }} material menunggu pemeriksaan</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full qc-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama Bahan</th>
                        <th class="px-6 py-4 text-left">SKU</th>
                        <th class="px-6 py-4 text-left">Supplier</th>
                        <th class="px-6 py-4 text-left">Status QC</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sky-500/10">
                    @forelse($materials as $index => $material)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-sky-200/50 group-hover:text-sky-200/80 transition-colors">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-sky-50/90 group-hover:text-sky-50 transition-colors">{{ $material->name }}</div>
                            @if($material->supplier)
                            <div class="text-xs text-sky-200/40 mt-0.5">{{ $material->supplier }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-mono text-sky-200/60 group-hover:text-sky-200/80 transition-colors">{{ $material->sku ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-sky-200/60 group-hover:text-sky-200/80 transition-colors">{{ $material->supplier ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($material->qc_status === 'waiting')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-sky-500/10 text-sky-300 border border-sky-500/20" style="border-radius:0;">
                                <span class="w-1.5 h-1.5" style="background:#38BDF8;border-radius:0;"></span>
                                Menunggu
                            </span>
                            @elseif($material->qc_status === 'rework')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 text-amber-300 border border-amber-500/20" style="border-radius:0;">
                                <span class="w-1.5 h-1.5" style="background:#F59E0B;border-radius:0;"></span>
                                QC Ulang
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="openQc({{ Js::from($material) }})"
                                class="h-9 px-4 text-xs font-bold uppercase tracking-wider border border-sky-500/30 text-sky-300 hover:bg-sky-500/15 hover:border-sky-400/50 transition-all duration-200" style="border-radius:0;">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    QC Now
                                </span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-sky-500/20 bg-sky-500/5 mb-5" style="border-radius:0;">
                                    <svg class="w-10 h-10 text-sky-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-sky-200/60 font-bold text-sm uppercase tracking-wider">Tidak Ada Antrian QC</p>
                                <p class="text-sky-200/30 text-xs mt-2">Semua bahan baku sudah terverifikasi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($materials->hasPages())
        <div class="px-6 py-4 border-t border-sky-500/10 bg-sky-500/5">
            {{ $materials->links() }}
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-sky-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-sky-500/25"></div>
    </div>

    {{-- QC FORM MODAL (BRIGHT THEME) --}}
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display:none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/60"></div>

            <div x-show="showModal" @click.stop
                class="qc-modal-container relative w-full max-w-lg bg-white shadow-2xl" style="border-radius:0;">
                <div class="h-[3px] bg-gradient-to-r from-sky-500 via-sky-400 to-sky-300"></div>

                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-sky-500/10 border border-sky-500/30 modal-icon" style="border-radius:0;">
                            <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="modal-h text-sm font-bold uppercase tracking-wider text-gray-900">QC Bahan Baku</h3>
                            <p class="modal-sub text-xs text-gray-500" x-text="selectedMaterial?.name || ''"></p>
                        </div>
                    </div>
                    <button @click="showModal = false" class="btn-close w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 pb-6">
                    <form x-ref="qcForm" method="POST" action="{{ route('operator.raw-materials.qc.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="raw_material_id" :value="selectedMaterial?.id">
                        <input type="hidden" name="status" :value="selectedStatus">

                        {{-- Material Info Card --}}
                        <div class="info-card flex items-center gap-4 p-4 bg-sky-50 border border-sky-200" style="border-radius:0;">
                            <div class="flex-1">
                                <p class="text-lg font-black text-gray-900" x-text="selectedMaterial?.name"></p>
                                <p class="text-xs text-gray-500 font-mono" x-text="selectedMaterial?.sku || 'Tanpa SKU'"></p>
                                <p class="text-xs text-gray-400 mt-0.5" x-text="selectedMaterial?.supplier ? 'Supplier: ' + selectedMaterial.supplier : ''"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase tracking-[0.15em] text-gray-500 font-bold">Total Stok</p>
                                <p class="text-lg font-black text-gray-900 font-mono" x-text="(selectedMaterial?.current_stock || 0) + ' ' + (selectedMaterial?.unit || '')"></p>
                            </div>
                        </div>

                        {{-- Input: Only Good Qty --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Baik / Lolos</label>
                            <input type="number" name="good_qty" x-model="goodQty" min="0" required
                                :max="totalQty"
                                class="w-full h-12 px-4 text-lg font-black font-mono text-center text-emerald-600 bg-emerald-50 border-2 border-emerald-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all" style="border-radius:0;"
                                placeholder="0">
                            <p class="text-xs text-gray-400 mt-1">Masukkan jumlah bahan baku yang lolos pemeriksaan</p>
                        </div>

                        {{-- Auto-calculated summary --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="summary-neutral p-3 bg-gray-50 border border-gray-200 text-center" style="border-radius:0;">
                                <p class="text-[10px] uppercase tracking-[0.15em] text-gray-500 font-bold mb-0.5">Total Diperiksa</p>
                                <p class="text-xl font-black text-gray-900 font-mono" x-text="totalQty"></p>
                                <input type="hidden" name="total_qty_checked" x-model="totalQty">
                            </div>
                            <div class="summary-bad p-3 bg-red-50 border border-red-200 text-center" style="border-radius:0;">
                                <p class="text-[10px] uppercase tracking-[0.15em] text-red-500 font-bold mb-0.5">Rusak / Cacat</p>
                                <p class="text-xl font-black text-red-600 font-mono" x-text="Math.max(0, totalQty - goodQty)"></p>
                            </div>
                        </div>

                        {{-- Percentage Display --}}
                        <div class="p-4 text-center" style="border-radius:0;"
                            :class="{
                                'pct-emerald bg-emerald-50 border-2 border-emerald-400': percentage >= 80,
                                'pct-amber bg-amber-50 border-2 border-amber-400': percentage >= 50 && percentage < 80,
                                'pct-red bg-red-50 border-2 border-red-400': percentage > 0 && percentage < 50,
                                'pct-gray bg-gray-50 border-2 border-gray-300': percentage === 0
                            }">
                            <p class="text-[10px] uppercase tracking-[0.15em] font-bold mb-1"
                                :class="{
                                    'text-emerald-700': percentage >= 80,
                                    'text-amber-700': percentage >= 50 && percentage < 80,
                                    'text-red-700': percentage > 0 && percentage < 50,
                                    'text-gray-500': percentage === 0
                                }">Persentase Kelulusan</p>
                            <p class="text-4xl font-black font-mono tracking-tight"
                                :class="{
                                    'text-emerald-600': percentage >= 80,
                                    'text-amber-600': percentage >= 50 && percentage < 80,
                                    'text-red-600': percentage > 0 && percentage < 50,
                                    'text-gray-300': percentage === 0
                                }" x-text="percentage + '%'"></p>
                            <p class="text-xs mt-1 font-mono" x-text="goodQty + ' / ' + totalQty + ' lolos pemeriksaan'"
                                :class="{
                                    'text-emerald-600': percentage >= 80,
                                    'text-amber-600': percentage >= 50 && percentage < 80,
                                    'text-red-600': percentage > 0 && percentage < 50,
                                    'text-gray-400': percentage === 0
                                }"></p>
                        </div>

                        {{-- Threshold Info --}}
                        <div class="flex items-center justify-center gap-2 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                            <template x-if="totalQty > 0 && percentage >= 80">
                                <span class="text-emerald-700">&check; PASSED (&ge;80%) — Bahan baku layak pakai</span>
                            </template>
                            <template x-if="totalQty > 0 && percentage >= 70 && percentage < 80">
                                <span class="text-amber-700">! PASSED / REWORK (70-79%)</span>
                            </template>
                            <template x-if="totalQty > 0 && percentage >= 50 && percentage < 70">
                                <span class="text-amber-700">! REWORK (50-69%) — Perlu sortir ulang</span>
                            </template>
                            <template x-if="totalQty > 0 && percentage < 50">
                                <span class="text-red-700">&#10007; DECLINE (&lt;50%) — Bahan baku ditolak</span>
                            </template>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Catatan</label>
                            <textarea name="notes" x-model="notes" rows="2" placeholder="Catatan hasil QC..."
                                class="modal-input w-full px-4 py-3 text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:border-sky-500 focus:ring-1 focus:ring-sky-200 outline-none transition-all" style="border-radius:0;"></textarea>
                        </div>

                        {{-- Status Buttons --}}
                        <div class="flex gap-3 pt-3 border-t border-gray-200">
                            <button type="button" @click="selectedStatus = 'passed'; submitForm()"
                                x-show="canPassed"
                                class="flex-1 h-12 text-xs font-bold uppercase tracking-wider transition-all duration-200"
                                :class="selectedStatus === 'passed'
                                    ? 'bg-sky-600 text-white shadow-lg shadow-sky-600/30'
                                    : 'btn-passed-inactive bg-sky-100 text-sky-700 border-2 border-sky-300 hover:bg-sky-200'"
                                style="border-radius:0;">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    PASSED
                                </span>
                            </button>

                            <button type="button" @click="selectedStatus = 'rework'; submitForm()"
                                x-show="canRework"
                                class="flex-1 h-12 text-xs font-bold uppercase tracking-wider transition-all duration-200"
                                :class="selectedStatus === 'rework'
                                    ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30'
                                    : 'btn-rework-inactive bg-amber-50 text-amber-700 border-2 border-amber-300 hover:bg-amber-100'"
                                style="border-radius:0;">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    REWORK
                                </span>
                            </button>

                            <button type="button" @click="selectedStatus = 'decline'; submitForm()"
                                x-show="canDecline"
                                class="flex-1 h-12 text-xs font-bold uppercase tracking-wider transition-all duration-200"
                                :class="selectedStatus === 'decline'
                                    ? 'bg-red-600 text-white shadow-lg shadow-red-600/30'
                                    : 'btn-decline-inactive bg-red-50 text-red-700 border-2 border-red-300 hover:bg-red-100'"
                                style="border-radius:0;">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    DECLINE
                                </span>
                            </button>
                        </div>

                        {{-- Hints --}}
                        <template x-if="activeButton">
                            <p class="text-[10px] uppercase tracking-wider text-gray-500 text-center pt-1">
                                <template x-if="activeButton === 'passed'">
                                    <span class="text-emerald-700">Hanya opsi PASSED yang tersedia (&ge;80%)</span>
                                </template>
                                <template x-if="activeButton === 'rework'">
                                    <span class="text-amber-700">Hanya opsi REWORK yang tersedia (50-69%)</span>
                                </template>
                                <template x-if="activeButton === 'decline'">
                                    <span class="text-red-700">Hanya opsi DECLINE yang tersedia (&lt;50%)</span>
                                </template>
                            </p>
                        </template>
                        <template x-if="!activeButton && percentage >= 70 && percentage < 80">
                            <p class="text-[10px] uppercase tracking-wider text-amber-700 text-center pt-1 font-bold">Pilih PASSED atau REWORK (70-79%)</p>
                        </template>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
