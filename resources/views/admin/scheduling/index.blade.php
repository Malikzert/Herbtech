@extends('layouts.admin')

@section('title', 'Penjadwalan Produksi')
@section('header', 'Penjadwalan Produksi Otomatis')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .text-shadow-sm { text-shadow: 0 1px 2px rgba(0,0,0,0.2); }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    @keyframes progress-indeterminate {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(400%); }
    }
    .progress-bar-indeterminate {
        animation: progress-indeterminate 1.5s ease-in-out infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .spinner {
        width: 18px; height: 18px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        display: inline-block;
    }
    .checkbox-glass {
        width: 18px; height: 18px;
        border-radius: 6px;
        border: 2px solid rgba(0,0,0,0.15);
        cursor: pointer; transition: all 0.2s;
        appearance: none; background: rgba(255,255,255,0.5);
    }
    .checkbox-glass:checked {
        background: #065f46;
        border-color: #065f46;
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
    }
    .modal-overlay {
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }
    ::-webkit-scrollbar { width: 4px; height: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 2px; }
</style>
@endsection

@section('content')
<div class="space-y-6">

    {{-- Inline validation / error messages (fallback if Alpine toast fails) --}}
    @if($errors->any())
    <div class="bg-red-100/80 backdrop-blur border border-red-300 text-red-800 px-5 py-4 rounded-xl shadow-sm flex items-start gap-3" role="alert">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-semibold text-sm">Terjadi kesalahan validasi:</p>
            <ul class="list-disc list-inside text-sm mt-1">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100/80 backdrop-blur border border-red-300 text-red-800 px-5 py-4 rounded-xl shadow-sm flex items-start gap-3" role="alert">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-semibold text-sm">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="bg-amber-100/80 backdrop-blur border border-amber-300 text-amber-800 px-5 py-4 rounded-xl shadow-sm flex items-start gap-3" role="alert">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <p class="font-semibold text-sm">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="bg-emerald-100/80 backdrop-blur border border-emerald-300 text-emerald-800 px-5 py-4 rounded-xl shadow-sm flex items-start gap-3" role="alert">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <div>
            <p class="font-semibold text-sm">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- STATS SECTION -- 3-COLUMN GRID WITH GLASS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Total Antrean Batch --}}
        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(251, 191, 36, 0.2);">
                    <i class="bi bi-clock text-amber-500"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">Total Antrean Batch</p>
                    <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ $statusCounts['pending'] ?? $productions->whereIn('status', ['pending','draft'])->count() }}</p>
                    <p class="text-xs text-amber-600/70 mt-0.5">Menunggu penjadwalan</p>
                </div>
            </div>
        </div>

        {{-- Kapasitas Produksi Harian --}}
        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(5, 150, 105, 0.2);">
                    <i class="bi bi-buildings text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">Kapasitas Produksi Harian</p>
                    <p class="text-3xl font-bold text-gray-800 mt-0.5">{{ number_format($dailyCapacity ?? 5000) }}</p>
                    <p class="text-xs text-emerald-600/70 mt-0.5">unit / hari</p>
                </div>
            </div>
        </div>

        {{-- Estimasi Waktu Selesai --}}
        <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-6">
            <div class="flex items-center gap-4">
                <div class="stat-icon" style="background: rgba(99, 102, 241, 0.2);">
                    <i class="bi bi-calendar-event text-indigo-500"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-black/50">Estimasi Waktu Selesai</p>
                    <p class="text-2xl font-bold text-gray-800 mt-0.5">{{ $estimatedCompletion ?? '-' }}</p>
                    <p class="text-xs text-indigo-600/70 mt-0.5">Berdasarkan antrean saat ini</p>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT -- TABLE (LEFT) + GA CONFIG (RIGHT) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- LEFT COLUMN: Production Queue Table --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Table Card --}}
            <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
                <div class="p-5 border-b border-white/50">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(5, 150, 105, 0.2);">
                                <i class="bi bi-list-ul text-emerald-700"></i>
                            </div>
                            <div>
                                <h5 class="font-bold text-gray-800 text-shadow-sm">Antrean Produksi</h5>
                                <p class="text-xs text-black/50">Daftar batch yang belum dijadwalkan</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.scheduling.index') }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium transition border {{ !$filter || $filter === 'all' ? 'bg-emerald-700 text-white border-transparent' : 'bg-white/30 text-gray-700 border-white/50 hover:bg-white/50' }}">
                                Semua
                            </a>
                            <a href="{{ route('admin.scheduling.index', ['filter' => 'scheduled']) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium transition border {{ $filter === 'scheduled' ? 'bg-emerald-700 text-white border-transparent' : 'bg-white/30 text-gray-700 border-white/50 hover:bg-white/50' }}">
                                Terjadwal
                            </a>
                            <a href="{{ route('admin.scheduling.index', ['filter' => 'unscheduled']) }}"
                               class="px-3 py-1.5 rounded-lg text-xs font-medium transition border {{ $filter === 'unscheduled' ? 'bg-emerald-700 text-white border-transparent' : 'bg-white/30 text-gray-700 border-white/50 hover:bg-white/50' }}">
                                Belum
                            </a>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    @if($productions->isNotEmpty())
                    <form id="bulkActionForm" method="POST" action="{{ route('admin.scheduling.review') }}">
                        @csrf
                        <table class="w-full glass-table">
                            <thead class="bg-emerald-800 text-white text-xs uppercase">
                                <tr>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm w-12">
                                        <input type="checkbox" id="selectAll" class="checkbox-glass">
                                    </th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Batch ID</th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Nama Produk</th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Target Qty</th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Prioritas</th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Jadwal</th>
                                    <th class="px-5 py-3.5 font-bold text-left text-white text-shadow-sm">Status</th>
                                    <th class="px-5 py-3.5 font-bold text-right text-white text-shadow-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/30">
                                @foreach($productions as $production)
                                <tr class="hover:bg-white/10 transition">
                                    <td class="px-5 py-4">
                                        <input type="checkbox" name="production_ids[]" value="{{ $production->id }}" class="production-checkbox checkbox-glass">
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(5, 150, 105, 0.15);">
                                                <i class="bi bi-box-seam text-emerald-700 text-xs"></i>
                                            </div>
                                            <span class="font-semibold text-gray-800 text-sm">{{ $production->batch_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700">{{ $production->product->name ?? $production->product_id }}</td>
                                    <td class="px-5 py-4">
                                        <span class="text-sm font-medium text-gray-700">{{ number_format($production->target_quantity ?? 0) }}</span>
                                        <span class="text-xs text-gray-500">unit</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $priority = $production->priority_level ?? 50;
                                            $pColor = $priority >= 80 ? 'bg-red-100 text-red-800 border-red-300' : ($priority >= 60 ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-gray-100 text-gray-800 border-gray-300');
                                        @endphp
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full inline-flex items-center gap-1 border {{ $pColor }}">
                                            <i class="bi bi-star-fill" style="font-size: 8px;"></i>
                                            {{ $priority }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-sm">
                                        @if($production->scheduled_start)
                                            <span class="font-medium text-gray-800">{{ $production->scheduled_start->format('d/m H:i') }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($production->algorithm_generated)
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">
                                                <i class="bi bi-check-circle-fill me-0.5"></i> Terjadwal
                                            </span>
                                        @elseif($production->status === 'draft')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 border border-gray-300">Draft</span>
                                        @elseif($production->status === 'in_progress')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">In Progress</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">
                                                <i class="bi bi-clock me-0.5"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('admin.productions.show', $production->id) }}"
                                           class="w-8 h-8 rounded-lg inline-flex items-center justify-center hover:bg-emerald-100 transition" title="Detail">
                                            <i class="bi bi-eye text-emerald-700 text-sm"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{-- Bulk Actions --}}
                        <div class="p-5 border-t border-white/50 bg-white/10">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="text-sm text-gray-600">
                                    <span class="font-semibold text-gray-800" id="selectedCount">0</span> item dipilih
                                </div>
                                <div class="flex gap-2.5">
                                    <button type="button" onclick="openPreviewModal()"
                                            class="px-4 py-2 rounded-lg text-sm font-medium transition border bg-white/40 text-gray-700 border-white/50 hover:bg-white/60">
                                        <i class="bi bi-eye me-1.5"></i>Preview
                                    </button>
                                    <button type="submit" name="action" value="approve"
                                            onclick="return confirm('Setujui jadwal yang dipilih?')"
                                            class="px-4 py-2 rounded-lg text-sm font-medium transition text-white shadow-sm"
                                            style="background: linear-gradient(135deg, #059669, #047857);">
                                        <i class="bi bi-check-lg me-1.5"></i>Approve
                                    </button>
                                    <button type="submit" name="action" value="reset"
                                            onclick="return confirm('Reset jadwal untuk batch terpilih?')"
                                            class="px-4 py-2 rounded-lg text-sm font-medium transition border bg-white/30 text-gray-700 border-white/50 hover:bg-white/50">
                                        <i class="bi bi-arrow-counterclockwise me-1.5"></i>Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-16">
                        <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-white/30">
                            <i class="bi bi-inbox text-gray-400 text-2xl"></i>
                        </div>
                        <h5 class="text-gray-600 font-medium mb-1">Tidak ada batch pending</h5>
                        <p class="text-gray-400 text-sm">Semua batch sudah dijadwalkan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: GA Config + Actions --}}
        <div class="space-y-5">

            {{-- Genetic Algorithm Configuration Card --}}
            <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-5">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(5, 150, 105, 0.2);">
                        <i class="bi bi-cpu text-emerald-700"></i>
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800 text-shadow-sm">Algoritma Genetika</h5>
                        <p class="text-xs text-black/50">Parameter optimasi produksi</p>
                    </div>
                </div>

                <div class="space-y-3">
                    {{-- FEFO --}}
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(239, 68, 68, 0.15);">
                                <i class="bi bi-hourglass-split text-red-500 text-sm"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Kriteria FEFO</span>
                                <p class="text-xs text-gray-500">First Expiry First Out</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">Aktif</span>
                    </div>

                    {{-- Kapasitas Mesin --}}
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(59, 130, 246, 0.15);">
                                <i class="bi bi-gear-wide-connected text-blue-500 text-sm"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Kapasitas Mesin</span>
                                <p class="text-xs text-gray-500">Efisiensi alat produksi</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">Aktif</span>
                    </div>

                    {{-- Prioritas Produk --}}
                    <div class="flex items-center justify-between p-3 bg-white/10 rounded-lg border border-white/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(251, 191, 36, 0.15);">
                                <i class="bi bi-star text-amber-500 text-sm"></i>
                            </div>
                            <div>
                                <span class="text-sm font-semibold text-gray-800">Prioritas Produk</span>
                                <p class="text-xs text-gray-500">Skala prioritas pelanggan</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">Aktif</span>
                    </div>
                </div>

                <hr class="my-4 border-white/30">

                {{-- Generate Schedule Form --}}
                <form method="POST" action="{{ route('admin.scheduling.generate') }}" id="generateForm">
                    @csrf
                    <div class="mb-4">
                        <label class="text-xs font-semibold uppercase tracking-wider text-black/50 block mb-2">Pilih Batch</label>
                        <select name="production_ids[]" multiple
                                class="w-full rounded-lg border px-3 py-2.5 text-sm bg-white/50 border-white/50 text-gray-800 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none"
                                style="height: 110px;">
                            @forelse(\App\Models\Production::whereIn('status', ['pending', 'draft'])->with('product:id,name')->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->batch_number }} - {{ $p->product->name ?? '' }}</option>
                            @empty
                                <option disabled>Tidak ada batch</option>
                            @endforelse
                        </select>
                        <p class="text-xs text-gray-400 mt-1.5">Tahan Ctrl/Cmd untuk pilih banyak</p>
                    </div>

                    <button type="submit" id="generateBtn"
                            class="w-full py-3 px-5 rounded-xl font-bold text-white text-sm shadow-lg transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background: linear-gradient(135deg, #059669, #047857, #065f46);">
                        <span class="spinner hidden" id="spinner"></span>
                        <i class="bi bi-lightning-charge-fill" id="btnIcon"></i>
                        <span id="btnText">Jalankan Algoritma Genetika</span>
                    </button>
                </form>

                {{-- Progress Bar --}}
                <div id="progressWrapper" class="hidden mt-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-medium text-gray-600">Menjadwalkan...</span>
                        <span class="text-xs font-medium text-gray-600" id="progressPercent">0%</span>
                    </div>
                    <div class="w-full h-2.5 bg-white/40 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full rounded-full progress-bar-indeterminate" style="background: linear-gradient(90deg, #10b981, #059669, #047857); width: 30%;"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Mengoptimasi urutan produksi dengan genetic algorithm...</p>
                </div>
            </div>

            {{-- Low Stock Alerts --}}
            @if(isset($lowStockMaterials) && $lowStockMaterials->isNotEmpty())
            <div class="bg-glass rounded-xl border border-white/50 shadow-sm glass-card p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(239, 68, 68, 0.15);">
                            <i class="bi bi-exclamation-triangle text-red-500"></i>
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-800 text-shadow-sm text-sm">Peringatan Stok</h5>
                            <p class="text-xs text-black/50">Bahan perlu perhatian</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-800 border border-red-300">{{ $lowStockMaterials->count() }}</span>
                </div>

                <div class="space-y-2.5">
                    @foreach($lowStockMaterials as $material)
                    <div class="p-3 rounded-lg flex items-start gap-3 border-l-4 {{ $material->current_stock <= $material->min_stock_level ? 'border-amber-400' : 'border-red-400' }}" style="background: rgba(255,255,255,0.2);">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $material->name }}</p>
                            @if($material->current_stock <= $material->min_stock_level)
                            <p class="text-xs text-amber-700 mt-0.5">
                                <i class="bi bi-box-seam me-1"></i>{{ $material->current_stock }} {{ $material->unit }}
                            </p>
                            @endif
                            @if($material->expired_date && $material->expired_date <= now()->addDays(14))
                            <p class="text-xs text-red-600 mt-0.5">
                                <i class="bi bi-calendar-x me-1"></i>{{ $material->expired_date->format('d/m/Y') }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-white/30 text-center">
                    <a href="{{ route('admin.raw-materials.index') }}" class="text-emerald-700 hover:text-emerald-800 text-sm font-medium transition">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Preview Modal (Alpine.js) --}}
<div x-data="{ previewOpen: false }"
     @toggle-preview.window="previewOpen = $event.detail.open"
     x-show="previewOpen"
     @keydown.escape.window="previewOpen = false"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="previewOpen" x-transition.opacity class="fixed inset-0 modal-overlay" @@click="previewOpen = false"></div>
    <div x-show="previewOpen" x-transition
         class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden max-h-[80vh] flex flex-col border border-emerald-200">
        <div class="flex items-center justify-between p-6 border-b border-emerald-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(5, 150, 105, 0.15);">
                    <i class="bi bi-calendar-check text-emerald-700"></i>
                </div>
                <div>
                    <h5 class="font-bold text-gray-800">Preview Jadwal</h5>
                    <p class="text-xs text-gray-500">Optimasi urutan batch terpilih</p>
                </div>
            </div>
            <button @@click="previewOpen = false" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-emerald-50 transition bg-gray-100">
                <i class="bi bi-x-lg text-gray-500" style="font-size: 12px;"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" id="previewContent">
            <div class="text-center py-8">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-emerald-50">
                    <i class="bi bi-calendar text-emerald-300 text-2xl"></i>
                </div>
                <p class="text-gray-500">Pilih batch untuk melihat preview.</p>
            </div>
        </div>
        <div class="p-6 border-t border-emerald-100 flex justify-end">
            <button @@click="previewOpen = false"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition border bg-white/40 text-gray-700 border-emerald-200 hover:bg-emerald-50">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('generateForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('generateBtn');
    const spinner = document.getElementById('spinner');
    const icon = document.getElementById('btnIcon');
    const text = document.getElementById('btnText');
    const progressWrapper = document.getElementById('progressWrapper');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');

    btn.disabled = true;
    spinner.classList.remove('hidden');
    icon.className = '';
    text.textContent = 'Memproses...';

    progressWrapper.classList.remove('hidden');
    progressBar.classList.add('progress-bar-indeterminate');
    progressBar.style.width = '30%';
    progressPercent.textContent = '0%';

    let progress = 0;
    const progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        const pct = Math.round(progress);
        progressBar.style.width = pct + '%';
        progressPercent.textContent = pct + '%';
    }, 600);

    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        clearInterval(progressInterval);
        if (data.success) {
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            progressBar.classList.remove('progress-bar-indeterminate');
            showNotification('success', data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification('warning', data.message);
            btn.disabled = false;
            spinner.classList.add('hidden');
            icon.className = 'bi bi-lightning-charge-fill';
            text.textContent = 'Jalankan Algoritma Genetika';
            progressWrapper.classList.add('hidden');
        }
    })
    .catch(() => {
        clearInterval(progressInterval);
        showNotification('error', 'Terjadi kesalahan saat menjalankan algoritma.');
        btn.disabled = false;
        spinner.classList.add('hidden');
        icon.className = 'bi bi-lightning-charge-fill';
        text.textContent = 'Jalankan Algoritma Genetika';
        progressWrapper.classList.add('hidden');
    });
});

document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.production-checkbox').forEach(cb => cb.checked = this.checked);
    updateSelectedCount();
});

document.querySelectorAll('.production-checkbox').forEach(cb => {
    cb.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const el = document.getElementById('selectedCount');
    if (el) el.textContent = document.querySelectorAll('.production-checkbox:checked').length;
}

function openPreviewModal() {
    const checked = document.querySelectorAll('.production-checkbox:checked');
    const content = document.getElementById('previewContent');

    if (checked.length === 0) {
        content.innerHTML = '<div class="text-center py-8"><div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: rgba(251, 191, 36, 0.15);"><i class="bi bi-exclamation-triangle text-amber-500 text-2xl"></i></div><p class="text-gray-500">Pilih setidaknya satu batch.</p></div>';
    } else {
        const rows = Array.from(checked).map(function(cb) { return cb.closest('tr'); });
        const batches = rows.map(function(row) { return row.querySelector('td:nth-child(2) span').textContent.trim(); });

        let html = '<div class="space-y-2">';
        html += '<div class="p-4 rounded-xl mb-4 border" style="background: rgba(5, 150, 105, 0.08); border-color: rgba(5, 150, 105, 0.2);">';
        html += '<span class="text-sm text-gray-600">Total Batch:</span>';
        html += '<span class="text-xl font-bold text-emerald-800 ms-2">' + checked.length + '</span>';
        html += '</div>';
        batches.forEach(function(b, i) {
            html += '<div class="p-3 rounded-xl flex items-center gap-3" style="background: rgba(255, 255, 255, 0.4); border: 1px solid rgba(0,0,0,0.05);">';
            html += '<span class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold" style="background: rgba(5, 150, 105, 0.15); color: #065f46;">' + (i + 1) + '</span>';
            html += '<span class="text-sm font-medium text-gray-800">' + b + '</span>';
            html += '</div>';
        });
        html += '</div>';
        content.innerHTML = html;
    }

    window.dispatchEvent(new CustomEvent('toggle-preview', { detail: { open: true } }));
}

function showNotification(type, message) {
    if (typeof Alpine !== 'undefined') {
        const data = Alpine.$data(document.body);
        if (data && data.notif) {
            data.notif.show = true;
            data.notif.type = type;
            data.notif.message = message;
            setTimeout(() => { if (data.notif) data.notif.show = false; }, 5000);
            return;
        }
    }
    window.dispatchEvent(new CustomEvent('notify', { detail: { type: type, message: message } }));
}

document.addEventListener('DOMContentLoaded', updateSelectedCount);
</script>
@endpush
