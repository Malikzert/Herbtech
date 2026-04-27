@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('header', 'Data Produk')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedProduct: {} }">
    <!-- Header Actions - Properly Aligned -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                        class="w-full h-11 pl-10 pr-4 input-glass border border-white/30 rounded-lg text-sm text-black placeholder-gray-400 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                </div>
                
                <!-- Filter Dropdown -->
                <select name="category" class="modern-select h-11 px-4 py-2 input-glass border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                
                <!-- Filter Button -->
                <button type="submit" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request('search') || request('category'))
                <a href="{{ route('admin.products.index') }}" class="h-11 px-5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-lg hover:bg-white/30 transition flex items-center gap-2 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                
                <!-- Spacer -->
                <div class="flex-1"></div>
                
                <!-- Add Button -->
                <button @click="showModal = true; modalMode = 'create'; selectedProduct = {}" type="button" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2 shadow-lg shadow-emerald-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Produk
                </button>
            </form>
        </div>
    </div>

    <!-- Table with Glass Effect -->
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="overflow-x-auto">
            <table class="w-full glass-table">
                <thead class="bg-emerald-800 text-white text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3.5 font-bold text-left text-white">No</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Nama Produk</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">SKU</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Kategori</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Unit</th>
                        <th class="px-6 py-3.5 font-bold text-right text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($products as $index => $product)
                    <tr class="hover:bg-white/10 transition">
                        <td class="px-6 py-4 text-sm font-bold text-black">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-black">{{ $product->name }}</div>
                            @if($product->description)
                            <div class="text-xs text-black mt-0.5 line-clamp-1">{{ $product->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-black font-mono">{{ $product->sku_code }}</td>
                        <td class="px-6 py-4">
                            @if($product->category)
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">{{ $product->category }}</span>
                            @else
                            <span class="text-xs text-black">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-black">{{ $product->unit ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'edit'" class="p-2 text-black hover:text-blue-700 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="selectedProduct = {{ Js::from($product) }}; showModal = true; modalMode = 'delete'" class="p-2 text-black hover:text-red-700 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-white/30 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="text-black font-medium">Belum ada produk</p>
                                <p class="text-black text-sm mt-1">Klik tombol "Tambah Produk" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-white/30 bg-white/10">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- Glassmorphism Modal -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-md"></div>
            
            <div x-show="showModal" @click.stop class="relative bg-white backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-black">
                        <span x-text="modalMode === 'create' ? 'Tambah Produk' : modalMode === 'edit' ? 'Edit Produk' : 'Konfirmasi Hapus'"></span>
                    </h3>
                    <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <template x-if="modalMode === 'delete'">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <p class="text-gray-700 mb-2">Hapus produk <span x-text="selectedProduct.name" class="font-semibold text-black"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <form :action="'/admin/products/' + selectedProduct.id" method="POST" class="mt-6">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </template>

                <template x-if="modalMode !== 'delete'">
                    <form :action="modalMode === 'create' ? '{{ route('admin.products.store') }}' : '/admin/products/' + selectedProduct.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            @method('PUT')
                        </template>
                        
                        <div>
                            <label class="block text-sm font-bold text-black mb-1.5">Nama Produk</label>
                            <input type="text" name="name" x-model="selectedProduct.name" required
                                class="w-full h-11 px-4 bg-white border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-black mb-1.5">SKU Code</label>
                            <input type="text" name="sku_code" x-model="selectedProduct.sku_code" required
                                class="w-full h-11 px-4 bg-white border border-gray-300 rounded-lg text-sm font-mono text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Kategori</label>
                                <input type="text" name="category" x-model="selectedProduct.category" placeholder="cth: Jamu"
                                    class="w-full h-11 px-4 bg-white border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Unit</label>
                                <input type="text" name="unit" x-model="selectedProduct.unit" placeholder="cth: pcs, box"
                                    class="w-full h-11 px-4 bg-white border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-black mb-1.5">Deskripsi</label>
                            <textarea name="description" x-model="selectedProduct.description" rows="3" placeholder="Deskripsi produk..."
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition"></textarea>
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition shadow-lg shadow-emerald-900/20">Simpan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection