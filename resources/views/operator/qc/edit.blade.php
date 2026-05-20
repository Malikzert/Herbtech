@extends('layouts.app')

@section('title', 'Edit QC')
@section('header', 'EDIT QUALITY CONTROL #' . $qc->id)

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.qc.show', $qc->id) }}" class="text-[#D4B896] hover:text-[#F5EDE0] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6">
        <form action="{{ route('operator.qc.update', $qc->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Batch Produksi</label>
                <select name="production_id" required class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914]">
                    <option value="{{ $qc->production_id }}" class="bg-[#1a1210]">{{ $qc->production->batch_number }} - {{ $qc->production->product->name ?? 'Produk' }}</option>
                    @foreach($productions as $production)
                        @if($production->id != $qc->production_id)
                        <option value="{{ $production->id }}" class="bg-[#1a1210]">{{ $production->batch_number }} - {{ $production->product->name ?? 'Produk' }}</option>
                        @endif
                    @endforeach
                </select>
                @error('production_id') <span class="text-[#8B6914] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Total Diperiksa</label>
                <input type="number" name="total_inspected" value="{{ old('total_inspected', $qc->total_inspected) }}" required min="1" class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914]">
                @error('total_inspected') <span class="text-[#8B6914] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#A0845C] mb-1">Total Passed</label>
                    <input type="number" name="total_passed" value="{{ old('total_passed', $qc->total_passed) }}" required min="0" class="w-full px-4 py-2.5 bg-[#A0845C]/10 border border-[#A0845C]/30 text-[#F5EDE0] font-bold focus:ring-[#8B6914] focus:border-[#8B6914]">
                    @error('total_passed') <span class="text-[#8B6914] text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#8B6914] mb-1">Total Rejected</label>
                    <input type="number" name="total_rejected" value="{{ old('total_rejected', $qc->total_rejected) }}" required min="0" class="w-full px-4 py-2.5 bg-[#8B6914]/10 border border-[#8B6914]/30 text-[#D4B896] font-bold focus:ring-[#8B6914] focus:border-[#8B6914]">
                    @error('total_rejected') <span class="text-[#8B6914] text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-[#3d2b1f]">
                <button type="submit" class="px-6 py-2.5 bg-[#8B6914] hover:bg-[#A0845C] text-white font-medium shadow-md">Simpan Perubahan QC</button>
            </div>
        </form>
    </div>
</div>
@endsection
