@extends('layouts.app')

@section('title', 'Buat Batch Produksi Baru')
@section('header', 'INPUT PRODUKSI')

@section('content')
<div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6 max-w-4xl mx-auto" x-data="productionForm()">
    @if ($errors->any())
        <div class="mb-6 p-4 bg-[#3d2b1f] border-l-4 border-[#8B6914]">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-[#D4B896] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-sm font-bold text-[#D4B896]">Terdapat kesalahan pengisian form:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-[#D4B896]/80 ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('operator.productions.store') }}" method="POST">
        @csrf
        <div class="mb-8">
            <h3 class="text-lg font-bold text-[#D4B896] border-b border-[#3d2b1f] pb-2 mb-4 uppercase tracking-[0.05em]">INFORMASI UTAMA</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Nomor Batch</label>
                    <div class="relative">
                        <input type="text" name="batch_number" required value="BATCH-{{ date('dmy') }}-{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}" class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#6B5740] focus:outline-none cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Pilih Produk</label>
                    <select name="product_id" x-model="selectedProductId" @change="loadRecipe()" required class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914] transition">
                        <option value="" class="bg-[#1a1210]">-- Pilih Jamu --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" class="bg-[#1a1210]">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Target Produksi (Qty)</label>
                    <input type="number" name="target_quantity" x-model="targetQty" @input="recalculateTotals()" required min="1" value="{{ old('target_quantity', 1) }}" class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914] transition" placeholder="Contoh: 100">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Mulai Produksi</label>
                    <input type="datetime-local" name="start_date" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] focus:ring-[#8B6914] focus:border-[#8B6914] transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-1">Penanggung Jawab</label>
                    <div class="relative">
                        <input type="text" name="pic_name" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#6B5740] focus:outline-none cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center border-b border-[#3d2b1f] pb-2 mb-4">
                <h3 class="text-lg font-bold text-[#D4B896] uppercase tracking-[0.05em]">BAHAN BAKU YANG DIPAKAI</h3>
                <button type="button" @click="addMaterial()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#8B6914]/20 text-[#D4B896] text-sm font-medium hover:bg-[#8B6914]/30 transition border border-[#8B6914]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Bahan
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(material, index) in materials" :key="index">
                    <div class="flex items-end gap-4 p-3 bg-[#2c1810]/40 border border-[#3d2b1f] relative">
                        <div class="w-8 text-center pt-3 font-bold text-[#6B5740]" x-text="(index + 1) + '.'"></div>

                        <div class="flex-1">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#6B5740] mb-1">Bahan Baku</label>
                            <div class="px-3 py-2 text-sm bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] font-medium" x-text="material.name"></div>
                            <input type="hidden" :name="`materials[${index}][raw_material_id]`" :value="material.raw_material_id">
                        </div>

                        <div class="w-28">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#6B5740] mb-1">Kebutuhan/Unit</label>
                            <div class="px-3 py-2 text-sm bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] text-center">
                                <span x-text="material.quantity_needed"></span>
                                <span class="text-[#6B5740]" x-text="material.unit"></span>
                            </div>
                        </div>

                        <div class="w-24">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#6B5740] mb-1">Stok</label>
                            <div class="px-3 py-2 text-sm border text-center" :class="material.current_stock < (material.quantity_needed * targetQty) ? 'bg-[#3d2b1f] text-[#8B6914] border-[#8B6914]/30' : 'bg-[#2c1810]/60 text-[#A0845C] border-[#A0845C]/30'">
                                <span x-text="material.current_stock"></span>
                                <span class="text-[#6B5740]" x-text="material.unit"></span>
                            </div>
                        </div>

                        <div class="w-32">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#6B5740] mb-1">Kebutuhan Total</label>
                            <div class="relative">
                                <div class="w-full px-3 py-2 text-sm border border-[#8B6914]/30 text-right pr-12 bg-[#8B6914]/10 text-[#D4B896] font-semibold">
                                    <span x-text="(material.quantity_needed * targetQty).toFixed(2)"></span>
                                </div>
                                <span class="absolute right-3 top-2 text-sm text-[#6B5740]" x-text="material.unit"></span>
                            </div>
                            <input type="hidden" :name="`materials[${index}][quantity]`" :value="(material.quantity_needed * targetQty).toFixed(2)">
                        </div>

                        <button type="button" @click="removeMaterial(index)" class="p-2 text-[#6B5740] hover:bg-[#3d2b1f] transition" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </template>

                <div x-show="materials.length === 0" class="text-center py-6 text-[#6B5740] text-sm border-2 border-dashed border-[#3d2b1f]">
                    <template x-if="!loadingRecipe">
                        <div>Belum ada bahan baku yang ditambahkan.<br>
                        Klik "Tambah Bahan" untuk memilih bahan yang digunakan.</div>
                    </template>
                    <template x-if="loadingRecipe">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-[#6B5740]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memuat resep produk...
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-[#3d2b1f] mt-6">
            <a href="{{ route('operator.productions.index') }}" class="px-6 py-2.5 bg-[#3d2b1f] hover:bg-[#2c1810] text-[#D4B896] font-medium transition">
                BATAL
            </a>
            <button type="submit" name="action" value="start" class="px-6 py-2.5 bg-[#8B6914] hover:bg-[#A0845C] text-white font-bold transition shadow-md hover:shadow-lg">
                MULAI PROSES
            </button>
        </div>
    </form>
</div>

<script>
    const rawMaterialsData = {!! json_encode($rawMaterials->mapWithKeys(function ($item) {
        return [$item->id => $item->unit];
    })) !!};

    function productionForm() {
        return {
            selectedProductId: '',
            targetQty: {{ old('target_quantity', 1) }},
            loadingRecipe: false,
            materials: [
                { raw_material_id: '', quantity: '' }
            ],
            loadRecipe() {
                if (!this.selectedProductId) {
                    this.materials = [{ raw_material_id: '', quantity: '' }];
                    return;
                }
                this.loadingRecipe = true;
                fetch(`/operator/productions/${this.selectedProductId}/recipe`)
                    .then(res => res.json())
                    .then(res => {
                        if (res.success && res.data.length > 0) {
                            this.materials = res.data.map(item => ({
                                raw_material_id: item.raw_material_id,
                                name: item.name,
                                quantity_needed: parseFloat(item.quantity_needed),
                                unit: item.unit,
                                current_stock: parseFloat(item.current_stock),
                            }));
                        } else {
                            this.materials = [{ raw_material_id: '', quantity: '' }];
                        }
                        this.loadingRecipe = false;
                    })
                    .catch(() => {
                        this.materials = [{ raw_material_id: '', quantity: '' }];
                        this.loadingRecipe = false;
                    });
            },
            recalculateTotals() {
                this.materials = this.materials.map(m => ({ ...m }));
            },
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
