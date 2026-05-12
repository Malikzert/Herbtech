@extends('layouts.admin')

@section('title', 'Detail Produksi')
@section('header', 'DETAIL PRODUKSI')

@section('content')
<style>
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
    {{-- PRODUCTION INFO CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-start justify-between pt-4">
            <div>
                <h3 class="text-xl font-black text-emerald-50">{{ $production->batch_number }}</h3>
                <p class="text-emerald-200/60 text-sm font-bold mt-1 tracking-wide">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-bold uppercase tracking-wider bg-red-500/10 text-red-300 border border-red-500/20" style="border-radius: 0;">
                        <span class="w-1.5 h-1.5" style="background: #EF4444; border-radius: 0;"></span>
                        Cancelled
                    </span>
                    @break
                @endswitch
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-4 border-t border-emerald-500/15">
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Tanggal Mulai</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Tanggal Selesai</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">Operator</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $production->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[10px] uppercase tracking-[0.15em] text-emerald-400/60 font-bold">PIC</p>
                <p class="text-sm font-bold text-emerald-50 mt-1">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>

    @if($production->status === 'draft' || $production->status === 'pending')
    {{-- STOCK VALIDATION CARD --}}
    @php
        $stockOk = true;
        $stockIssues = [];
    @endphp
    @foreach($production->productionMaterials as $pm)
        @php
            $material = $pm->rawMaterial;
            if ($material && $material->current_stock < $pm->quantity_used) {
                $stockOk = false;
                $stockIssues[] = [
                    'name' => $material->name,
                    'stock' => $material->current_stock . ' ' . $material->unit,
                    'needed' => $pm->quantity_used . ' ' . $material->unit,
                ];
            }
        @endphp
    @endforeach

    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 pt-4 mb-4">
            <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">
                {{ $production->status === 'pending' ? 'SETUJUI & MULAI PRODUKSI' : 'MULAI PRODUKSI' }}
            </h4>
        </div>

        @if(!empty($stockIssues))
        <div class="p-4 mb-4 border border-red-500/20 bg-red-500/5" style="border-radius: 0;">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm font-bold text-red-300 uppercase tracking-wider">Stok Bahan Baku Tidak Mencukupi!</span>
            </div>
            <ul class="text-sm text-red-200 space-y-1 ml-7 list-disc">
                @foreach($stockIssues as $issue)
                <li>{{ $issue['name'] }}: stok {{ $issue['stock'] }}, dibutuhkan {{ $issue['needed'] }}</li>
                @endforeach
            </ul>
            <p class="text-[10px] text-red-300 font-bold uppercase tracking-wider mt-2">Silakan lakukan pengadaan bahan baku terlebih dahulu.</p>
        </div>
        @else
        <div class="p-4 mb-4 border border-emerald-500/20 bg-emerald-500/5" style="border-radius: 0;">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold text-emerald-300 uppercase tracking-wider">Stok Bahan Baku Tersedia</span>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="in_progress">

            @if($production->product && $production->product->recipes->count() > 0)
            <div class="p-4 border border-amber-500/20 bg-amber-500/5" style="border-radius: 0;">
                <div class="flex items-center gap-2 mb-3">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm font-bold text-amber-300 uppercase tracking-wider">Bahan Baku yang Akan Digunakan</span>
                </div>
                <div class="text-sm space-y-1">
                    @foreach($production->productionMaterials as $pm)
                    <div class="flex justify-between py-1 border-b border-emerald-500/10">
                        <span class="text-emerald-200/70 font-medium">{{ $pm->rawMaterial->name ?? '-' }}:</span>
                        <span class="font-bold {{ $pm->rawMaterial && $pm->rawMaterial->current_stock < $pm->quantity_used ? 'text-red-400' : 'text-emerald-400' }}">
                            {{ number_format($pm->quantity_used, 2) }} {{ $pm->rawMaterial->unit ?? '' }}
                            <span class="text-emerald-400/50">(stok: {{ $pm->rawMaterial->current_stock ?? 0 }})</span>
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="submit" @if(!empty($stockIssues)) disabled @endif
                    class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-2
                    {{ !empty($stockIssues) ? 'bg-gray-500/50 text-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:shadow-[0_0_20px_rgba(16,185,129,0.3)]' }}" style="border-radius: 0;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $production->status === 'pending' ? 'Setujui & Mulai Produksi' : 'Mulai Produksi' }}
            </button>
        </form>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
    @endif

    @if($production->status === 'in_progress')
    {{-- MOVE TO QC CARD --}}
    @php
        $deductOk = true;
        $deductIssues = [];
    @endphp
    @foreach($production->productionMaterials as $pm)
        @php
            $material = $pm->rawMaterial;
            if ($material && $material->current_stock < $pm->quantity_used) {
                $deductOk = false;
                $deductIssues[] = [
                    'name' => $material->name,
                    'stock' => $material->current_stock . ' ' . $material->unit,
                    'needed' => $pm->quantity_used . ' ' . $material->unit,
                ];
            }
        @endphp
    @endforeach

    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 pt-4 mb-4">
            <div class="w-8 h-8 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">KIRIM KE QC</h4>
        </div>

        @if(!empty($deductIssues))
        <div class="p-4 mb-4 border border-red-500/20 bg-red-500/5" style="border-radius: 0;">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm font-bold text-red-300 uppercase tracking-wider">Stok Tidak Mencukupi untuk Pengurangan!</span>
            </div>
            <ul class="text-sm text-red-200 space-y-1 ml-7 list-disc">
                @foreach($deductIssues as $issue)
                <li>{{ $issue['name'] }}: stok {{ $issue['stock'] }}, dibutuhkan {{ $issue['needed'] }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="qc_check">
            <button type="submit" @if(!empty($deductIssues)) disabled @endif
                    class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider transition flex items-center gap-2
                    {{ !empty($deductIssues) ? 'bg-gray-500/50 text-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-amber-600 to-amber-700 text-white hover:shadow-[0_0_20px_rgba(245,158,11,0.3)]' }}" style="border-radius: 0;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kirim ke Quality Control
            </button>
        </form>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
    @endif

    @if($production->status === 'qc_check')
    {{-- QC ACTIONS CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 pt-4 mb-4">
            <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">TINDAKAN QC</h4>
        </div>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] transition flex items-center gap-2" style="border-radius: 0;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Selesaikan Produksi (Release)
                </button>
            </form>
            <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rework">
                <button type="submit" class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-purple-600 to-purple-700 text-white hover:shadow-[0_0_20px_rgba(168,85,247,0.3)] transition flex items-center gap-2" style="border-radius: 0;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Rework
                </button>
            </form>
        </div>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
    @endif

    @if($production->status === 'rework')
    {{-- REWORK ACTIONS CARD --}}
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md p-5 shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 pt-4 mb-4">
            <div class="w-8 h-8 flex items-center justify-center bg-amber-500/15 border border-amber-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">TINDAKAN REWORK</h4>
        </div>
        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="pending">
            <p class="text-sm text-emerald-200/60 font-medium mb-3">Kirim ulang batch rework ke antrean produksi untuk diproses ulang.</p>
            <button type="submit" class="px-5 py-2.5 text-[10px] font-bold uppercase tracking-wider bg-gradient-to-r from-amber-600 to-amber-700 text-white hover:shadow-[0_0_20px_rgba(245,158,11,0.3)] transition flex items-center gap-2" style="border-radius: 0;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Kirim ke Antrean Produksi
            </button>
        </form>
        <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
    </div>
    @endif

    {{-- PRODUCTION MATERIALS HISTORY --}}
    @if($production->productionMaterials->count() > 0)
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_4px_24px_rgba(0,0,0,0.2)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="flex items-center gap-3 px-5 py-4 border-b border-emerald-500/15">
            <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-50">RIWAYAT PENGGUNAAN BAHAN</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">Bahan Baku</th>
                        <th class="px-6 py-4 text-left">Jumlah Digunakan</th>
                        <th class="px-6 py-4 text-left">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @foreach($production->productionMaterials as $pm)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $pm->rawMaterial->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-400">{{ number_format($pm->quantity_used, 2) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-emerald-200/60">{{ $pm->rawMaterial->unit ?? '-' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>
    @endif

    {{-- BACK LINK --}}
    <div>
        <a href="{{ route('admin.productions.index') }}" class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-500/20 hover:border-emerald-500/40 px-3 py-1.5 transition-all duration-200" style="border-radius: 0;">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Produksi
        </a>
    </div>
</div>
@endsection
