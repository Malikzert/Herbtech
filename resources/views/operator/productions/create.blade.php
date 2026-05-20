@extends('layouts.app')

@section('title', 'Buat Batch Produksi Baru')
@section('header', 'INPUT PRODUKSI')

@section('content')
<div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6 max-w-4xl mx-auto"
     x-data="productionForm({{ $scheduling ? $scheduling->product_id : 'null' }}, {{ $scheduling ? $scheduling->recommended_quantity : old('target_quantity', 1) }})"
     @if($scheduling) x-init="if (selectedProductId) { loadRecipe(); }" @endif>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-[#334155] border-l-4 border-[#1DA1F2]">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-[#93C5FD] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="text-sm font-bold text-[#93C5FD]">Terdapat kesalahan pengisian form:</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-[#93C5FD]/80 ml-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($scheduling)
    <div class="mb-6 p-4 bg-sky-500/5 border border-sky-500/25">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 flex items-center justify-center shrink-0 bg-sky-500/15 border border-sky-500/25">
                <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-sky-400 uppercase tracking-[0.1em] font-mono">REKOMENDASI JADWAL</p>
                <p class="text-[11px] text-sky-400/70 mt-1 font-mono">
                    Form ini telah diisi berdasarkan rekomendasi jadwal dari sistem penjadwalan.
                    @if($scheduling->recom_date)
                    Tanggal rekomendasi: <span class="text-sky-300">{{ \Carbon\Carbon::parse($scheduling->recom_date)->format('d M Y') }}</span>.
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('operator.productions.store') }}" method="POST">
        @csrf
        @if($scheduling)
        <input type="hidden" name="scheduling_id" value="{{ $scheduling->id }}">
        @endif
        <div class="mb-8">
            <h3 class="text-lg font-bold text-[#93C5FD] border-b border-[#334155] pb-2 mb-4 uppercase tracking-[0.05em]">INFORMASI UTAMA</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Nomor Batch</label>
                    <div class="relative">
                        <input type="text" name="batch_number" required value="{{ $scheduling && $scheduling->batch_number_recommendation ? $scheduling->batch_number_recommendation : 'BATCH-' . date('dmy') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#64748B] focus:outline-none cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Pilih Produk</label>
                    <select name="product_id" x-model="selectedProductId" @change="loadRecipe()" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2] transition">
                        <option value="" class="bg-[#0f172a]">-- Pilih Jamu --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" class="bg-[#0f172a]" {{ $scheduling && $scheduling->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Target Produksi (Qty)</label>
                    <input type="number" name="target_quantity" x-model="targetQty" @input="recalculateTotals()" required min="1" value="{{ old('target_quantity', $scheduling ? $scheduling->recommended_quantity : 1) }}" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2] transition" placeholder="Contoh: 100">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Mulai Produksi</label>
                    <input type="datetime-local" name="start_date" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2] transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Penanggung Jawab</label>
                    <div class="relative">
                        <input type="text" name="pic_name" value="{{ auth()->user()->name }}" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#64748B] focus:outline-none cursor-not-allowed" readonly>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#64748B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center border-b border-[#334155] pb-2 mb-4">
                <h3 class="text-lg font-bold text-[#93C5FD] uppercase tracking-[0.05em]">BAHAN BAKU YANG DIPAKAI</h3>
                <button type="button" @click="addMaterial()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#1DA1F2]/20 text-[#93C5FD] text-sm font-medium hover:bg-[#1DA1F2]/30 transition border border-[#1DA1F2]/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Bahan
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(material, index) in materials" :key="index">
                    <div class="flex items-end gap-4 p-3 bg-[#1e293b]/40 border border-[#334155] relative">
                        <div class="w-8 text-center pt-3 font-bold text-[#64748B]" x-text="(index + 1) + '.'"></div>

                        <div class="flex-1">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748B] mb-1">Bahan Baku</label>
                            <div class="px-3 py-2 text-sm bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] font-medium" x-text="material.name"></div>
                            <input type="hidden" :name="`materials[${index}][raw_material_id]`" :value="material.raw_material_id">
                        </div>

                        <div class="w-28">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748B] mb-1">Kebutuhan/Unit</label>
                            <div class="px-3 py-2 text-sm bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] text-center">
                                <span x-text="material.quantity_needed"></span>
                                <span class="text-[#64748B]" x-text="material.unit"></span>
                            </div>
                        </div>

                        <div class="w-24">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748B] mb-1">Stok</label>
                            <div class="px-3 py-2 text-sm border text-center" :class="material.current_stock < (material.quantity_needed * targetQty) ? 'bg-[#334155] text-[#1DA1F2] border-[#1DA1F2]/30' : 'bg-[#1e293b]/60 text-[#3B82F6] border-[#3B82F6]/30'">
                                <span x-text="material.current_stock"></span>
                                <span class="text-[#64748B]" x-text="material.unit"></span>
                            </div>
                        </div>

                        <div class="w-32">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.1em] text-[#64748B] mb-1">Kebutuhan Total</label>
                            <div class="relative">
                                <div class="w-full px-3 py-2 text-sm border border-[#1DA1F2]/30 text-right pr-12 bg-[#1DA1F2]/10 text-[#93C5FD] font-semibold">
                                    <span x-text="(material.quantity_needed * targetQty).toFixed(2)"></span>
                                </div>
                                <span class="absolute right-3 top-2 text-sm text-[#64748B]" x-text="material.unit"></span>
                            </div>
                            <input type="hidden" :name="`materials[${index}][quantity]`" :value="(material.quantity_needed * targetQty).toFixed(2)">
                        </div>

                        <button type="button" @click="removeMaterial(index)" class="p-2 text-[#64748B] hover:bg-[#334155] transition" title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </template>

                <div x-show="materials.length === 0" class="text-center py-6 text-[#64748B] text-sm border-2 border-dashed border-[#334155]">
                    <template x-if="!loadingRecipe">
                        <div>Belum ada bahan baku yang ditambahkan.<br>
                        Klik "Tambah Bahan" untuk memilih bahan yang digunakan.</div>
                    </template>
                    <template x-if="loadingRecipe">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-[#64748B]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memuat resep produk...
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-[#334155] mt-6">
            <a href="{{ route('operator.productions.index') }}" class="px-6 py-2.5 bg-[#334155] hover:bg-[#1e293b] text-[#93C5FD] font-medium transition">
                BATAL
            </a>
            <button type="submit" name="action" value="start" class="px-6 py-2.5 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-bold transition shadow-md hover:shadow-lg">
                MULAI PROSES
            </button>
        </div>
    </form>
</div>

<script>
    const rawMaterialsData = {!! json_encode($rawMaterials->mapWithKeys(function ($item) {
        return [$item->id => $item->unit];
    })) !!};

    function productionForm(initialProductId = null, initialQty = 1) {
        return {
            selectedProductId: initialProductId,
            targetQty: initialQty,
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
