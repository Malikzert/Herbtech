@extends('layouts.app')

@section('title', 'Edit QC')
@section('header', 'Edit Quality Control #' . $qc->id)

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.qc.show', $qc->id) }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white/60 backdrop-blur-md rounded-xl border border-white/20 p-6 shadow-sm glass-card">
        <form action="{{ route('operator.qc.update', $qc->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Batch Produksi</label>
                <select name="production_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    <option value="{{ $qc->production_id }}">{{ $qc->production->batch_number }} - {{ $qc->production->product->name ?? 'Produk' }}</option>
                    @foreach($productions as $production)
                        @if($production->id != $qc->production_id)
                        <option value="{{ $production->id }}">{{ $production->batch_number }} - {{ $production->product->name ?? 'Produk' }}</option>
                        @endif
                    @endforeach
                </select>
                @error('production_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total Diperiksa</label>
                <input type="number" name="total_inspected" value="{{ old('total_inspected', $qc->total_inspected) }}" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                @error('total_inspected') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 text-emerald-600">Total Passed</label>
                    <input type="number" name="total_passed" value="{{ old('total_passed', $qc->total_passed) }}" required min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 text-emerald-700 font-bold">
                    @error('total_passed') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 text-red-600">Total Rejected</label>
                    <input type="number" name="total_rejected" value="{{ old('total_rejected', $qc->total_rejected) }}" required min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-red-700 font-bold">
                    @error('total_rejected') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium shadow-md">Simpan Perubahan QC</button>
            </div>
        </form>
    </div>
</div>
@endsection
