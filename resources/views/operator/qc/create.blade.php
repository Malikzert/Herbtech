@extends('layouts.app')

@section('title', 'Pengecekan Mutu (QC)')
@section('header', 'Quality Control')

@section('content')
<div class="max-w-4xl mx-auto" x-data="qcForm()">
    <div class="mb-4">
        <a href="{{ route('operator.qc.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm glass-card">
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

        <form action="{{ route('operator.qc.store') }}" method="POST">
            @csrf
            
            <div class="mb-6 border-b border-gray-200 pb-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Batch Produksi</label>
                    <select name="production_id" x-model="selectedProductionId" @change="updateProductionInfo" required class="modern-select w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                        <option value="">-- Pilih Batch --</option>
                        @foreach($productions as $production)
                        <option value="{{ $production->id }}">{{ $production->batch_number }}</option>
                        @endforeach
                    </select>
                </div>
                
                <h2 class="text-xl font-bold text-gray-800" x-text="selectedProductionId ? 'Pengecekan Mutu (QC) - ' + getBatchNumber() : 'Pilih batch untuk memulai QC'"></h2>
                <p class="text-emerald-700 font-medium mt-1" x-show="selectedProductionId" x-text="'Produk: ' + getProductName()"></p>
            </div>

            <div class="space-y-6">
                <!-- Inputs -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total Barang Diproduksi</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalInspected" name="total_inspected" required min="1" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-lg font-bold">
                            <span class="absolute right-4 top-3 text-gray-400 font-medium">Botol</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-emerald-700 mb-1">Barang Lolos QC (Bagus)</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalPassed" @input="calculateRejected" name="total_passed" required min="0" class="w-full px-4 py-2.5 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 text-lg font-bold text-emerald-700 bg-emerald-50/50">
                            <span class="absolute right-4 top-3 text-emerald-600/50 font-medium">Botol</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-red-700 mb-1">Barang Cacat / Ditolak</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalRejected" @input="calculatePassed" name="total_rejected" required min="0" class="w-full px-4 py-2.5 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 text-lg font-bold text-red-700 bg-red-50/50">
                            <span class="absolute right-4 top-3 text-red-600/50 font-medium">Botol</span>
                        </div>
                    </div>
                </div>

                <!-- Defects -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">RINCIAN CACAT (Jika Ada)</h3>
                        <button type="button" @click="addDefect()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition border border-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Cacat
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(defect, index) in defects" :key="index">
                            <div class="flex items-center gap-4">
                                <div class="text-gray-400 font-medium w-6 text-right" x-text="'-'"></div>
                                <div class="flex-1">
                                    <select x-model="defect.defect_cat_id" :name="`defects[${index}][defect_cat_id]`" required class="modern-select w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500">
                                        <option value="">-- Pilih Jenis Cacat --</option>
                                        @foreach($defectCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32 relative">
                                    <input type="number" x-model.number="defect.quantity" :name="`defects[${index}][quantity]`" required min="1" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 text-right pr-12">
                                    <span class="absolute right-3 top-2 text-sm text-gray-500">Botol</span>
                                </div>
                                <button type="button" @click="removeDefect(index)" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="defects.length === 0" class="text-sm text-gray-500 italic py-2">
                            Tidak ada rincian cacat yang ditambahkan.
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan QC</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500" placeholder="Tuliskan catatan tambahan jika diperlukan..."></textarea>
                </div>

                <!-- Final Result -->
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="font-bold text-gray-700">Hasil Akhir:</span>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-not-allowed opacity-80">
                                <input type="radio" :checked="finalResult === 'release'" disabled class="w-4 h-4 text-emerald-600 border-gray-300">
                                <span class="text-sm font-bold" :class="finalResult === 'release' ? 'text-emerald-700' : 'text-gray-500'">Release</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-not-allowed opacity-80">
                                <input type="radio" :checked="finalResult === 'rework'" disabled class="w-4 h-4 text-amber-600 border-gray-300">
                                <span class="text-sm font-bold" :class="finalResult === 'rework' ? 'text-amber-700' : 'text-gray-500'">Rework</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-not-allowed opacity-80">
                                <input type="radio" :checked="finalResult === 'reject'" disabled class="w-4 h-4 text-red-600 border-gray-300">
                                <span class="text-sm font-bold" :class="finalResult === 'reject' ? 'text-red-700' : 'text-gray-500'">Reject</span>
                            </label>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 italic">(Dihitung otomatis)</div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        SIMPAN HASIL QC & MASUKKAN GUDANG
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const productionsData = {!! json_encode($productions->mapWithKeys(function ($item) {
        return [$item->id => [
            'batch_number' => $item->batch_number,
            'product_name' => $item->product ? $item->product->name : 'Produk'
        ]];
    })) !!};
    
    const urlParams = new URLSearchParams(window.location.search);
    const defaultProductionId = urlParams.get('production_id') || '';

    function qcForm() {
        return {
            selectedProductionId: defaultProductionId,
            totalInspected: 100,
            totalPassed: 100,
            totalRejected: 0,
            defects: [],
            
            getBatchNumber() {
                return this.selectedProductionId && productionsData[this.selectedProductionId] 
                    ? productionsData[this.selectedProductionId].batch_number : '';
            },
            
            getProductName() {
                return this.selectedProductionId && productionsData[this.selectedProductionId] 
                    ? productionsData[this.selectedProductionId].product_name : '';
            },
            
            updateProductionInfo() {
                // Trigger Alpine reactivity
            },

            calculateRejected() {
                if(this.totalInspected && this.totalPassed !== null) {
                    let passed = parseInt(this.totalPassed) || 0;
                    if(passed > this.totalInspected) {
                        this.totalPassed = this.totalInspected;
                        passed = this.totalInspected;
                    }
                    this.totalRejected = Math.max(0, this.totalInspected - passed);
                }
            },

            calculatePassed() {
                if(this.totalInspected && this.totalRejected !== null) {
                    let rejected = parseInt(this.totalRejected) || 0;
                    if(rejected > this.totalInspected) {
                        this.totalRejected = this.totalInspected;
                        rejected = this.totalInspected;
                    }
                    this.totalPassed = Math.max(0, this.totalInspected - rejected);
                }
            },

            addDefect() {
                this.defects.push({ defect_cat_id: '', quantity: '' });
            },

            removeDefect(index) {
                this.defects.splice(index, 1);
            },

            get finalResult() {
                let passed = parseInt(this.totalPassed) || 0;
                let rejected = parseInt(this.totalRejected) || 0;
                let inspected = parseInt(this.totalInspected) || 1;
                
                if (rejected === 0) return 'release';
                if (passed > 0 && rejected < inspected) return 'rework';
                return 'reject';
            }
        }
    }
</script>
@endsection
