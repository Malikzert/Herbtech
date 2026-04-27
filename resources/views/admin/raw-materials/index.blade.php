@extends('layouts.admin')

@section('title', 'Kelola Bahan Baku')
@section('header', 'Data Bahan Baku')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedMaterial: {} }">
    <!-- Header Actions - Properly Aligned -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.raw-materials.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan..." 
                        class="w-full h-11 pl-10 pr-4 input-glass border border-white/30 rounded-lg text-sm text-black placeholder-gray-500 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                </div>
                
                <!-- Filter Dropdown -->
                <select name="type" class="modern-select h-11 px-4 py-2 input-glass border border-white/30 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="herbal" {{ request('type') === 'herbal' ? 'selected' : '' }}>Herbal</option>
                    <option value="packaging" {{ request('type') === 'packaging' ? 'selected' : '' }}>Packaging</option>
                    <option value="additive" {{ request('type') === 'additive' ? 'selected' : '' }}>Additive</option>
                </select>
                
                <!-- Filter Button -->
                <button type="submit" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request('search') || request('type'))
                <a href="{{ route('admin.raw-materials.index') }}" class="h-11 px-5 bg-white/20 backdrop-blur-sm text-white font-medium rounded-lg hover:bg-white/30 transition flex items-center gap-2 border border-white/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                
                <!-- Spacer -->
                <div class="flex-1"></div>
                
                <!-- Add Button -->
                <button @click="showModal = true; modalMode = 'create'; selectedMaterial = {}" type="button" class="h-11 px-5 bg-emerald-700 hover:bg-emerald-800 text-white font-medium rounded-lg transition flex items-center gap-2 shadow-lg shadow-emerald-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Bahan
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
                        <th class="px-6 py-3.5 font-bold text-left text-white">Nama Bahan</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">SKU</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Tipe</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Stok</th>
                        <th class="px-6 py-3.5 font-bold text-left text-white">Status</th>
                        <th class="px-6 py-3.5 font-bold text-right text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @forelse($rawMaterials as $index => $material)
                    <tr class="hover:bg-white/10 transition">
                        <td class="px-6 py-4 text-sm text-black">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-black">{{ $material->name }}</div>
                            @if($material->supplier)
                            <div class="text-xs text-black mt-0.5">{{ $material->supplier }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-mono text-black">{{ $material->sku ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @switch($material->type)
                                @case('herbal')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 border border-green-300">Herbal</span>
                                    @break
                                @case('packaging')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 border border-blue-300">Packaging</span>
                                    @break
                                @case('additive')
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800 border border-purple-300">Additive</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-black">
                                {{ number_format($material->current_stock, 0) }} <span class="text-black font-normal">{{ $material->unit }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($material->current_stock <= 0)
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 border border-red-300">Habis</span>
                            @elseif($material->current_stock < ($material->min_stock_level ?? 10))
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300">Rendah</span>
                            @else
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">Tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'edit'" class="p-2 text-black hover:text-blue-700 hover:bg-blue-100 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="selectedMaterial = {{ Js::from($material) }}; showModal = true; modalMode = 'delete'" class="p-2 text-black hover:text-red-700 hover:bg-red-100 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-400/50 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                <p class="text-black font-medium">Belum ada bahan baku</p>
                                <p class="text-black text-sm mt-1">Klik tombol "Tambah Bahan" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rawMaterials->hasPages())
        <div class="px-6 py-4 border-t border-white/30 bg-white/10">
            {{ $rawMaterials->links() }}
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
                        <span x-text="modalMode === 'create' ? 'Tambah Bahan Baku' : modalMode === 'edit' ? 'Edit Bahan Baku' : 'Konfirmasi Hapus'"></span>
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
                        <p class="text-gray-700 mb-2">Hapus bahan <span x-text="selectedMaterial.name" class="font-semibold text-black"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <form :action="'/admin/raw-materials/' + selectedMaterial.id" method="POST" class="mt-6">
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
                    <form :action="modalMode === 'create' ? '{{ route('admin.raw-materials.store') }}' : '/admin/raw-materials/' + selectedMaterial.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            @method('PUT')
                        </template>
                        
                        <div>
                            <label class="block text-sm font-bold text-black mb-1.5">Nama Bahan</label>
                            <input type="text" name="name" x-model="selectedMaterial.name" required
                                class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">SKU</label>
                                <input type="text" name="sku" x-model="selectedMaterial.sku" placeholder="Optional"
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm font-mono text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Tipe</label>
                                <select name="type" x-model="selectedMaterial.type" required
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                                    <option value="herbal">Herbal</option>
                                    <option value="packaging">Packaging</option>
                                    <option value="additive">Additive</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Unit</label>
                                <input type="text" name="unit" x-model="selectedMaterial.unit" required placeholder="cth: kg, gram"
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Stok Saat Ini</label>
                                <input type="number" name="current_stock" x-model="selectedMaterial.current_stock" min="0"
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Min Stok Level</label>
                                <input type="number" name="min_stock_level" x-model="selectedMaterial.min_stock_level" min="0"
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-1.5">Supplier</label>
                                <input type="text" name="supplier" x-model="selectedMaterial.supplier" placeholder="Nama supplier"
                                    class="w-full h-11 px-4 border border-gray-300 rounded-lg text-sm text-black focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 focus:outline-none transition">
                            </div>
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