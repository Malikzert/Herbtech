@extends('layouts.app')

@section('title', 'Bahan Baku')
@section('header', 'Data Bahan Baku')

@section('content')
<div class="mb-6">
    <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
        <form method="GET" action="{{ route('operator.raw-materials.index') }}" class="flex flex-wrap gap-3 items-center">
            <!-- Search Input -->
            <div class="relative flex-1 min-w-[200px]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..." 
                    class="w-full h-11 pl-10 pr-4 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-700 focus:border-blue-700 focus:outline-none transition">
            </div>
            
            <!-- Filter Dropdown -->
            <select name="type" class="modern-select h-11 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-blue-700 focus:border-blue-700 focus:outline-none transition cursor-pointer">
                <option value="">Semua Tipe</option>
                <option value="herbal" {{ request('type') === 'herbal' ? 'selected' : '' }}>Herbal</option>
                <option value="packaging" {{ request('type') === 'packaging' ? 'selected' : '' }}>Packaging</option>
                <option value="additive" {{ request('type') === 'additive' ? 'selected' : '' }}>Additive</option>
            </select>
            
            <!-- Filter Button -->
            <button type="submit" class="h-11 px-5 bg-blue-800 text-white font-medium rounded-lg hover:bg-blue-900 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                Filter
            </button>
            
            @if(request('search') || request('type'))
            <a href="{{ route('operator.raw-materials.index') }}" class="h-11 px-5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
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
                    <th class="px-6 py-3.5 font-medium text-left">Nama Bahan</th>
                    <th class="px-6 py-3.5 font-medium text-left">SKU</th>
                    <th class="px-6 py-3.5 font-medium text-left">Tipe</th>
                    <th class="px-6 py-3.5 font-medium text-left">Stok</th>
                    <th class="px-6 py-3.5 font-medium text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100/50">
                @forelse($rawMaterials as $index => $material)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $material->name }}</div>
                        @if($material->supplier)
                        <div class="text-xs text-gray-500 mt-0.5">{{ $material->supplier }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $material->sku ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @switch($material->type)
                            @case('herbal')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100/80 text-green-700 border border-green-200">Herbal</span>
                                @break
                            @case('packaging')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-200/80 text-blue-900 border border-blue-400">Packaging</span>
                                @break
                            @case('additive')
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100/80 text-purple-700 border border-purple-200">Additive</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            {{ number_format($material->current_stock, 0) }} <span class="text-gray-500 font-normal">{{ $material->unit }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($material->current_stock <= 0)
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100/80 text-red-700 border border-red-200">Habis</span>
                        @elseif($material->current_stock < ($material->min_stock_level ?? 10))
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100/80 text-amber-700 border border-amber-200">Rendah</span>
                        @else
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-200/80 text-blue-900 border border-blue-400">Tersedia</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                            <p class="text-gray-500 font-medium">Belum ada bahan baku</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($rawMaterials->hasPages())
    <div class="px-6 py-4 border-t border-gray-100/50 bg-gray-50/30">
        {{ $rawMaterials->links() }}
    </div>
    @endif
</div>
@endsection
