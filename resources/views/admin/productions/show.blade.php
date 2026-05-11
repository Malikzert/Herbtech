@extends('layouts.admin')

@section('title', 'Detail Produksi')
@section('header', 'Detail Produksi')

@section('content')
<div class="space-y-6">
    <!-- Production Info Card -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold black">{{ $production->batch_number }}</h3>
                <p class="text-gray-400 mt-1">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
                @switch($production->status)
                    @case('draft')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-gray-500/30 text-gray-300">Draft</span>
                        @break
                    @case('pending')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-amber-500/30 text-amber-300">Pending</span>
                        @break
                    @case('in_progress')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-500/30 text-blue-300">On Progress</span>
                        @break
                    @case('qc_check')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-amber-500/30 text-amber-300">QC Check</span>
                        @break
                    @case('rework')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-purple-500/30 text-purple-300">Rework</span>
                        @break
                    @case('completed')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-emerald-500/30 text-emerald-300">Completed</span>
                        @break
                    @case('cancelled')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-red-500/30 text-red-300">Cancelled</span>
                        @break
                @endswitch
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div>
                <p class="text-xs text-gray-400 uppercase">Tanggal Mulai</p>
                <p class="text-sm font-medium black">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Tanggal Selesai</p>
                <p class="text-sm font-medium black">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Operator</p>
                <p class="text-sm font-medium black">{{ $production->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">PIC</p>
                <p class="text-sm font-medium black">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if($production->status === 'draft' || $production->status === 'pending')
    <!-- Stock Validation Card -->
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

    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold black mb-4">
            {{ $production->status === 'pending' ? 'Setujui & Mulai Produksi' : 'Mulai Produksi' }}
        </h4>

        @if(!empty($stockIssues))
        <div class="bg-red-500/20 border border-red-400/30 rounded-lg p-4 mb-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm font-bold text-red-300">Stok Bahan Baku Tidak Mencukupi!</span>
            </div>
            <ul class="text-sm text-red-200 space-y-1 ml-7 list-disc">
                @foreach($stockIssues as $issue)
                <li>{{ $issue['name'] }}: stok {{ $issue['stock'] }}, dibutuhkan {{ $issue['needed'] }}</li>
                @endforeach
            </ul>
            <p class="text-xs text-red-300 mt-2">Silakan lakukan pengadaan bahan baku terlebih dahulu.</p>
        </div>
        @else
        <div class="bg-emerald-500/20 border border-emerald-400/30 rounded-lg p-4 mb-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold text-emerald-300">Stok Bahan Baku Tersedia</span>
            </div>
        </div>
        @endif

        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="in_progress">

            @if($production->product && $production->product->recipes->count() > 0)
            <div class="bg-amber-500/20 border border-amber-400/30 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm font-medium text-amber-300">Bahan Baku yang Akan Digunakan</span>
                </div>
                <div class="text-sm text-gray-300 space-y-1">
                    @foreach($production->productionMaterials as $pm)
                    <div class="flex justify-between">
                        <span>{{ $pm->rawMaterial->name ?? '-' }}:</span>
                        <span class="font-medium {{ $pm->rawMaterial && $pm->rawMaterial->current_stock < $pm->quantity_used ? 'text-red-400' : 'text-emerald-400' }}">
                            {{ number_format($pm->quantity_used, 2) }} {{ $pm->rawMaterial->unit ?? '' }}
                            (stok: {{ $pm->rawMaterial->current_stock ?? 0 }})
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="submit" @if(!empty($stockIssues)) disabled @endif
                    class="px-5 py-2.5 font-medium rounded-lg transition flex items-center gap-2
                    {{ !empty($stockIssues) ? 'bg-gray-500/50 text-gray-400 cursor-not-allowed' : 'bg-emerald-600/80 text-white hover:bg-emerald-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $production->status === 'pending' ? 'Setujui & Mulai Produksi' : 'Mulai Produksi' }}
            </button>
        </form>
    </div>
    @endif

    @if($production->status === 'in_progress')
    <!-- Stock Deduction Warning -->
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

    <!-- Move to QC -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold black mb-4">Kirim ke QC</h4>

        @if(!empty($deductIssues))
        <div class="bg-red-500/20 border border-red-400/30 rounded-lg p-4 mb-4">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm font-bold text-red-300">Stok Tidak Mencukupi untuk Pengurangan!</span>
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
                    class="px-5 py-2.5 font-medium rounded-lg transition flex items-center gap-2
                    {{ !empty($deductIssues) ? 'bg-gray-500/50 text-gray-400 cursor-not-allowed' : 'bg-amber-500/80 text-white hover:bg-amber-500' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kirim ke Quality Control
            </button>
        </form>
    </div>
    @endif

    @if($production->status === 'qc_check')
    <!-- QC Actions -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold black mb-4">Tindakan QC</h4>
        <div class="flex flex-wrap gap-3">
            <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="px-5 py-2.5 bg-emerald-600/80 text-white font-medium rounded-lg hover:bg-emerald-600 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Selesaikan Produksi (Release)
                </button>
            </form>
            <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rework">
                <button type="submit" class="px-5 py-2.5 bg-purple-600/80 text-white font-medium rounded-lg hover:bg-purple-600 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Rework
                </button>
            </form>
        </div>
    </div>
    @endif

    @if($production->status === 'rework')
    <!-- Rework Actions -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold black mb-4">Tindakan Rework</h4>
        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="pending">
            <p class="text-sm text-gray-300 mb-3">Kirim ulang batch rework ke antrean produksi untuk diproses ulang.</p>
            <button type="submit" class="px-5 py-2.5 bg-amber-600/80 text-white font-medium rounded-lg hover:bg-amber-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Kirim ke Antrean Produksi
            </button>
        </form>
    </div>
    @endif

    <!-- Production Materials History -->
    @if($production->productionMaterials->count() > 0)
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold black mb-4">Riwayat Penggunaan Bahan</h4>
        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead class="glass-table text-gray-300 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-2 font-medium text-left">Bahan Baku</th>
                        <th class="px-4 py-2 font-medium text-left">Jumlah Digunakan</th>
                        <th class="px-4 py-2 font-medium text-left">Unit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @foreach($production->productionMaterials as $pm)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $pm->rawMaterial->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-emerald-400">{{ number_format($pm->quantity_used, 2) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $pm->rawMaterial->unit ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div>
        <a href="{{ route('admin.productions.index') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Daftar Produksi
        </a>
    </div>
</div>
@endsection
