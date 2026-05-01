@extends('layouts.app')

@section('title', 'Detail Produksi')
@section('header', 'Detail Produksi: ' . $production->batch_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.productions.index') }}" class="text-blue-800 hover:text-blue-900 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
        @if(in_array($production->status, ['draft', 'in_progress']))
        <a href="{{ route('operator.productions.edit', $production->id) }}" class="px-4 py-2 bg-amber-500 text-white font-medium rounded-lg hover:bg-amber-600 transition">Edit Produksi</a>
        @endif
    </div>

    <div class="bg-white/60 backdrop-blur-md rounded-xl border border-white/20 p-6 shadow-sm glass-card">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Informasi Produksi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Nomor Batch</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->batch_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Produk</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->product->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Target Produksi (Qty)</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->target_quantity }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Aktual Produksi (Qty)</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->actual_quantity ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Mulai</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->start_date ? $production->start_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Selesai</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->end_date ? $production->end_date->format('d M Y H:i') : '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-base font-semibold text-gray-900 mt-1">
                    @switch($production->status)
                        @case('draft')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Draft</span>
                            @break
                        @case('in_progress')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-200 text-blue-900">On Progress</span>
                            @break
                        @case('qc_check')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">QC Check</span>
                            @break
                        @case('completed')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-200 text-blue-900">Completed</span>
                            @break
                        @case('cancelled')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Cancelled</span>
                            @break
                    @endswitch
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">PIC</p>
                <p class="text-base font-semibold text-gray-900">{{ $production->pic_name ?? '-' }}</p>
            </div>
        </div>

        <form action="{{ route('operator.productions.updateStatus', $production->id) }}" method="POST" class="mt-8 border-t border-gray-100 pt-6">
            @csrf
            @method('PATCH')
            <div class="flex items-center gap-4">
                <label class="text-sm font-medium text-gray-700">Ubah Status:</label>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700">
                    <option value="draft" {{ $production->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="in_progress" {{ $production->status == 'in_progress' ? 'selected' : '' }}>On Progress</option>
                    <option value="qc_check" {{ $production->status == 'qc_check' ? 'selected' : '' }}>QC Check</option>
                    <option value="completed" {{ $production->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $production->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-lg hover:bg-blue-900">Update Status</button>
            </div>
        </form>
    </div>

    <!-- Bahan Baku Digunakan (if we implement it) -->
    <div class="bg-white/60 backdrop-blur-md rounded-xl border border-white/20 p-6 shadow-sm glass-card">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Quality Control</h3>
        @if($production->qualityControls->count() > 0)
        <table class="w-full">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Inspektor</th>
                    <th class="px-4 py-2 text-center">Total Diperiksa</th>
                    <th class="px-4 py-2 text-center">Passed</th>
                    <th class="px-4 py-2 text-center">Rejected</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($production->qualityControls as $qc)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $qc->inspected_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $qc->inspector_name }}</td>
                    <td class="px-4 py-3 text-sm text-center">{{ $qc->total_inspected }}</td>
                    <td class="px-4 py-3 text-sm text-center text-blue-800 font-bold">{{ $qc->total_passed }}</td>
                    <td class="px-4 py-3 text-sm text-center text-red-600 font-bold">{{ $qc->total_rejected }}</td>
                    <td class="px-4 py-3 text-sm">
                        <span class="px-2 py-1 text-xs rounded-full {{ $qc->action == 'release' ? 'bg-blue-200 text-blue-900' : ($qc->action == 'rework' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucfirst($qc->action) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="text-gray-500 text-sm">Belum ada data Quality Control.</p>
        @endif
    </div>
</div>
@endsection
