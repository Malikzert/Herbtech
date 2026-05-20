@extends('layouts.app')

@section('title', 'Edit Produksi')
@section('header', 'EDIT PRODUKSI: ' . $production->batch_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.productions.show', $production->id) }}" class="text-[#93C5FD] hover:text-[#DBEAFE] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6 max-w-2xl">
        <form action="{{ route('operator.productions.update', $production->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">No Batch</label>
                <input type="text" name="batch_number" value="{{ old('batch_number', $production->batch_number) }}" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                @error('batch_number') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Produk</label>
                <select name="product_id" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $production->product_id == $product->id ? 'selected' : '' }} class="bg-[#0f172a]">{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Target Produksi (Qty)</label>
                <input type="number" name="target_quantity" value="{{ old('target_quantity', $production->target_quantity) }}" required min="1" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                @error('target_quantity') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Aktual Produksi (Qty)</label>
                <input type="number" name="actual_quantity" value="{{ old('actual_quantity', $production->actual_quantity) }}" min="0" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]" placeholder="Kosongkan jika belum selesai">
                @error('actual_quantity') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Tanggal Mulai</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date', $production->start_date ? $production->start_date->format('Y-m-d\TH:i') : '') }}" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                @error('start_date') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">PIC Name</label>
                <input type="text" name="pic_name" value="{{ old('pic_name', $production->pic_name) }}" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                @error('pic_name') <span class="text-[#1DA1F2] text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-[#334155]">
                <button type="submit" class="px-6 py-2.5 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
