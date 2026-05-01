@extends('layouts.app')

@section('title', 'Kelola Bahan Baku')
@section('header', 'Pengelolaan Bahan Baku')

@section('content')
<div x-data="{ 
    addModal: false, 
    editModal: false, 
    deleteModal: false,
    editData: { id: '', name: '', type: 'herbal', unit: '', current_stock: '' },
    deleteData: { id: '', name: '' }
}">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-emerald-100 border-l-4 border-[#228B22] text-[#228B22] rounded shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white backdrop-blur-md rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Header & Toolbar -->
        <div class="px-6 py-5 border-b border-gray-100/50 flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-xl font-bold text-[#228B22] tracking-tight">Daftar Bahan Baku</h3>
            <button type="button" @click="addModal = true" class="px-4 py-2 bg-[#2D5A27] text-white font-medium rounded-md shadow hover:bg-green-800 transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Bahan Baru
            </button>
        </div>

        <!-- Filter Bar -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
            <form action="{{ route('raw-materials.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari bahan baku..." class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2E8B57]">
                </div>
                <div class="w-full md:w-48">
                    <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#2E8B57] text-gray-700">
                        <option value="all">Semua Tipe</option>
                        <option value="herbal" {{ request('type') == 'herbal' ? 'selected' : '' }}>Herbal</option>
                        <option value="packaging" {{ request('type') == 'packaging' ? 'selected' : '' }}>Packaging</option>
                        <option value="additive" {{ request('type') == 'additive' ? 'selected' : '' }}>Additive</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white font-bold rounded-md hover:bg-gray-700 transition flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter
                </button>
            </form>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-[#333333] text-xs uppercase tracking-wider font-bold">
                        <th class="px-6 py-3 font-bold">ID</th>
                        <th class="px-6 py-3 font-bold">Nama Bahan</th>
                        <th class="px-6 py-3 font-bold">Tipe</th>
                        <th class="px-6 py-3 font-bold">Satuan</th>
                        <th class="px-6 py-3 font-bold">Stok Saat Ini</th>
                        <th class="px-6 py-3 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rawMaterials as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">#{{ $item->id }}</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item->name }}</td>
                        <td class="px-6 py-4">
                            @if($item->type == 'herbal')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-[#228B22]">Herbal</span>
                            @elseif($item->type == 'packaging')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-[#4682B4]">Packaging</span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">Additive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->unit }}</td>
                        <td class="px-6 py-4 text-sm font-bold {{ $item->current_stock < 10 ? 'text-red-500' : 'text-[#333333]' }}">
                            {{ number_format($item->current_stock, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <!-- Edit Button -->
                                <button type="button" @click="editModal = true; editData = { id: {{ json_encode($item->id) }}, name: {{ json_encode($item->name) }}, type: {{ json_encode($item->type) }}, unit: {{ json_encode($item->unit) }}, current_stock: {{ json_encode($item->current_stock) }} }" class="p-2 bg-[#2E8B57]/10 text-[#2E8B57] hover:bg-[#2E8B57]/20 rounded-lg focus:outline-none transition" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <!-- Delete Button -->
                                <button type="button" @click="deleteModal = true; deleteData = { id: {{ json_encode($item->id) }}, name: {{ json_encode($item->name) }} }" class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg focus:outline-none transition" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-[#333333] font-medium">Tidak ada bahan baku ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        @if($rawMaterials->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $rawMaterials->links() }}
        </div>
        @endif
    </div>

    <!-- MODALS -->

    <!-- Add Modal -->
    <div x-show="addModal" class="relative z-50" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div x-show="addModal" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 transition-opacity backdrop-blur-sm"></div>
        
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <!-- Modal panel -->
                <div x-show="addModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg" @click.outside="addModal = false">
                <form action="{{ route('raw-materials.store') }}" method="POST">
                    @csrf
                    <!-- Header -->
                    <div class="bg-[#228B22] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-bold text-white" id="modal-title">Tambah Bahan Baku Baru</h3>
                        <button type="button" @click="addModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="bg-white p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bahan</label>
                            <input type="text" name="name" required placeholder="Masukkan nama bahan..." class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                            <select name="type" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                                <option value="herbal">Herbal</option>
                                <option value="packaging">Packaging</option>
                                <option value="additive">Additive</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan</label>
                                <input type="text" name="unit" required placeholder="Kg, Pcs, dll" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Awal</label>
                                <input type="number" step="0.01" name="current_stock" required value="0" class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="addModal = false" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#2D5A27] text-white font-bold rounded-md hover:bg-green-800 transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Edit Modal -->
    <div x-show="editModal" class="relative z-50" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="editModal" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 transition-opacity backdrop-blur-sm"></div>
        
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="editModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg" @click.outside="editModal = false">
                <!-- Gunakan x-bind:action untuk dinamis URL update -->
                <form :action="`/raw-materials/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Header -->
                    <div class="bg-[#228B22] px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-bold text-white">Edit Bahan Baku</h3>
                        <button type="button" @click="editModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="bg-white p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bahan</label>
                            <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                            <select name="type" x-model="editData.type" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                                <option value="herbal">Herbal</option>
                                <option value="packaging">Packaging</option>
                                <option value="additive">Additive</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Satuan</label>
                                <input type="text" name="unit" x-model="editData.unit" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Stok Awal</label>
                                <input type="number" step="0.01" name="current_stock" x-model="editData.current_stock" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="editModal = false" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-[#2D5A27] text-white font-bold rounded-md hover:bg-green-800 transition">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Delete Modal -->
    <div x-show="deleteModal" class="relative z-50" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="deleteModal" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 transition-opacity backdrop-blur-sm"></div>
        
        <div class="fixed inset-0 z-50 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="deleteModal" x-transition.scale.origin.bottom class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md" @click.outside="deleteModal = false">
                <form :action="`/raw-materials/${deleteData.id}`" method="POST">
                    @csrf
                    @method('DELETE')
                    <!-- Header -->
                    <div class="bg-red-600 px-6 py-4 flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-bold text-white">Hapus Bahan Baku</h3>
                        <button type="button" @click="deleteModal = false" class="text-white hover:text-gray-200 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="bg-white p-6 space-y-4 text-center text-gray-700">
                        <svg class="mx-auto h-12 w-12 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="text-base font-semibold">Apakah Anda yakin ingin menghapus data ini?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan. Data bahan baku <strong x-text="deleteData.name" class="text-gray-800"></strong> akan dihapus selamanya.</p>
                    </div>
                    <!-- Footer -->
                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                        <button type="button" @click="deleteModal = false" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded hover:bg-gray-50 transition font-medium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-medium">Ya, Hapus Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
