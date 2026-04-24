@extends('layouts.admin')

@section('title', 'Detail Produksi')
@section('header', 'Detail Produksi')

@section('content')
<div class="space-y-6">
    <!-- Production Info Card -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-xl font-bold text-white">{{ $production->batch_number }}</h3>
                <p class="text-gray-400 mt-1">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
                @switch($production->status)
                    @case('draft')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-gray-500/30 text-gray-300">Draft</span>
                        @break
                    @case('in_progress')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-blue-500/30 text-blue-300">On Progress</span>
                        @break
                    @case('qc_check')
                        <span class="px-3 py-1.5 text-sm font-medium rounded-full bg-amber-500/30 text-amber-300">QC Check</span>
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
                <p class="text-sm font-medium text-white">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Tanggal Selesai</p>
                <p class="text-sm font-medium text-white">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">Operator</p>
                <p class="text-sm font-medium text-white">{{ $production->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase">PIC</p>
                <p class="text-sm font-medium text-white">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if($production->status === 'draft')
    <!-- Start Production with Target Quantity -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Mulai Produksi</h4>
        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="in_progress">
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">Jumlah Produksi (unit)</label>
                <input type="number" name="target_quantity" value="1" min="1" required
                    class="input-glass w-full max-w-xs h-11 px-4 border border-white/30 rounded-lg text-sm text-white focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 focus:outline-none">
                <p class="text-xs text-gray-400 mt-1">Masukkan jumlah unit yang akan diproduksi</p>
            </div>
            
            @if($production->product && $production->product->recipes->count() > 0)
            <div class="bg-amber-500/20 border border-amber-400/30 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm font-medium text-amber-300">Peringatan: Stok bahan akan dikurangi</span>
                </div>
                <div class="text-sm text-gray-300 space-y-1">
                    @foreach($production->product->recipes as $recipe)
                    <div class="flex justify-between">
                        <span>{{ $recipe->rawMaterial->name ?? '-' }}:</span>
                        <span class="font-medium">{{ $recipe->quantity_needed }} {{ $recipe->unit }} per unit</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <button type="submit" class="px-5 py-2.5 bg-blue-500/80 text-white font-medium rounded-lg hover:bg-blue-500 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Mulai Produksi
            </button>
        </form>
    </div>
    @endif

    @if($production->status === 'in_progress')
    <!-- Move to QC -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Kirim ke QC</h4>
        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="qc_check">
            <button type="submit" class="px-5 py-2.5 bg-amber-500/80 text-white font-medium rounded-lg hover:bg-amber-500 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kirim ke Quality Control
            </button>
        </form>
    </div>
    @endif

    @if($production->status === 'qc_check')
    <!-- Complete Production -->
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Selesaikan Produksi</h4>
        <form action="{{ route('admin.productions.update-status', $production->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="completed">
            <button type="submit" class="btn-glass px-5 py-2.5 font-medium rounded-lg transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Selesaikan Produksi
            </button>
        </form>
    </div>
    @endif

    <!-- Production Materials History -->
    @if($production->productionMaterials->count() > 0)
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm">
        <h4 class="text-lg font-bold text-white mb-4">Riwayat Penggunaan Bahan</h4>
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