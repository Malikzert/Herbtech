@extends('layouts.app')

@section('title', 'Edit Produksi')
@section('header', 'Edit Produksi: ' . $production->batch_number)

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('operator.productions.show', $production->id) }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Detail
        </a>
    </div>

    <div class="bg-white/60 backdrop-blur-md rounded-xl border border-white/20 p-6 shadow-sm glass-card max-w-2xl">
        <form action="{{ route('operator.productions.update', $production->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No Batch</label>
                <input type="text" name="batch_number" value="{{ old('batch_number', $production->batch_number) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                @error('batch_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                <select name="product_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $production->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
                @error('product_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="datetime-local" name="start_date" value="{{ old('start_date', $production->start_date ? $production->start_date->format('Y-m-d\TH:i') : '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">PIC Name</label>
                <input type="text" name="pic_name" value="{{ old('pic_name', $production->pic_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                @error('pic_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
