@extends('layouts.app')

@section('title', 'Buat Batch Produksi Baru')
@section('header', 'Input Produksi')

@section('content')
<div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm glass-card max-w-4xl mx-auto" x-data="productionForm()">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan pengisian form:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('operator.productions.store') }}" method="POST">
        @csrf
        <div class="mb-8">
            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4 tracking-tight">INFORMASI UTAMA</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Batch</label>
                    <div class="relative">
                        <input type="text" name="batch_number" required value="BATCH-{{ date('dmy') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600 focus:outline-none" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Produk</label>
                    <select name="product_id" required class="modern-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-700 transition">
                        <option value="">-- Pilih Jamu --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Produksi (Qty)</label>
                    <input type="number" name="target_quantity" required min="1" value="{{ old('target_quantity', 1) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-700 transition" placeholder="Contoh: 100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai Produksi</label>
                    <input type="datetime-local" name="start_date" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-700 transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
                    <div class="relative">
                        <input type="text" name="pic_name" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600 focus:outline-none" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-4">
                <h3 class="text-lg font-bold text-gray-800 tracking-tight">BAHAN BAKU YANG DIPAKAI</h3>
                <button type="button" @click="addMaterial()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-100 text-blue-900 text-sm font-medium rounded-lg hover:bg-blue-200 transition border border-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Bahan
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(material, index) in materials" :key="index">
                    <div class="flex items-end gap-4 p-3 bg-gray-50/50 rounded-xl border border-gray-100 relative">
                        <div class="w-8 text-center pt-3 font-bold text-gray-400" x-text="(index + 1) + '.'"></div>
                        
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Bahan Baku</label>
                            <select x-model="material.raw_material_id" :name="`materials[${index}][raw_material_id]`" required class="modern-select w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-700">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($rawMaterials as $rm)
                                    <option value="{{ $rm->id }}">{{ $rm->name }} (Stok: {{ $rm->current_stock }} {{ $rm->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="w-32">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah</label>
                            <div class="relative">
                                <input type="number" step="0.1" min="0.1" x-model="material.quantity" :name="`materials[${index}][quantity]`" required class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-700 focus:border-blue-700 text-right pr-12">
                                <span class="absolute right-3 top-2 text-sm text-gray-400" x-text="getUnit(material.raw_material_id)"></span>
                            </div>
                        </div>

                        <button type="button" @click="removeMaterial(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </template>
                
                <div x-show="materials.length === 0" class="text-center py-6 text-gray-500 text-sm border-2 border-dashed border-gray-200 rounded-xl">
                    Belum ada bahan baku yang ditambahkan.<br>
                    Klik "Tambah Bahan" untuk memilih bahan yang digunakan.
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
            <a href="{{ route('operator.productions.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition">
                BATAL
            </a>
            <button type="submit" name="action" value="start" class="px-6 py-2.5 bg-gradient-to-r from-blue-700 to-blue-800 text-white font-bold rounded-xl hover:from-blue-800 hover:to-blue-900 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                MULAI PROSES
            </button>
        </div>
    </form>
</div>

<!-- Raw Materials Data for Alpine -->
<script>
    const rawMaterialsData = {!! json_encode($rawMaterials->mapWithKeys(function ($item) {
        return [$item->id => $item->unit];
    })) !!};

    function productionForm() {
        return {
            materials: [
                { raw_material_id: '', quantity: '' }
            ],
            addMaterial() {
                this.materials.push({ raw_material_id: '', quantity: '' });
            },
            removeMaterial(index) {
                this.materials.splice(index, 1);
            },
            getUnit(id) {
                return id && rawMaterialsData[id] ? rawMaterialsData[id] : '-';
            }
        }
    }
</script>
@endsection
