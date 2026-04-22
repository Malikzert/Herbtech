@extends('layouts.app')

@section('title', 'Produk')
@section('header', 'Data Produk')

@section('content')
<div class="mb-6">
    <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
        <form method="GET" action="{{ route('operator.products.index') }}" class="flex flex-wrap gap-3 items-center">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                    class="w-full h-11 pl-10 pr-4 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
            </div>
            
            <!-- Filter Dropdown -->
            <select name="category" class="modern-select h-11 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition cursor-pointer">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
            
            <!-- Filter Button -->
            <button type="submit" class="h-11 px-5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                Filter
            </button>
            
            @if(request('search') || request('category'))
            <a href="{{ route('operator.products.index') }}" class="h-11 px-5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Reset
            </a>
            @endif
        </form>
    </div>
</div>

    <!-- Table with Glass Effect -->
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3.5 font-medium text-left">No</th>
                    <th class="px-6 py-3.5 font-medium text-left">Nama Produk</th>
                    <th class="px-6 py-3.5 font-medium text-left">SKU</th>
                    <th class="px-6 py-3.5 font-medium text-left">Kategori</th>
                    <th class="px-6 py-3.5 font-medium text-left">Unit</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($products as $index => $product)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        @if($product->description)
                        <div class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $product->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $product->sku_code }}</td>
                    <td class="px-6 py-4">
                        @if($product->category)
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100/80 text-emerald-700 border border-emerald-200">{{ $product->category }}</span>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $product->unit ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <p class="text-gray-500 font-medium">Belum ada produk</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100/50 bg-gray-50/30">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
