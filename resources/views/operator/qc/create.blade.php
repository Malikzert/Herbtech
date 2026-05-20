@extends('layouts.app')

@section('title', 'Pengecekan Mutu (QC)')
@section('header', 'QUALITY CONTROL')

@section('content')
<div class="max-w-4xl mx-auto" x-data="qcForm()" x-init="if(selectedProductionId) { updateProductionInfo(); }">
    <div class="mb-4">
        <a href="{{ route('operator.qc.index') }}" class="text-[#93C5FD] hover:text-[#DBEAFE] font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-[#0f172a]/80 backdrop-blur-md border border-[#334155]/50 p-6">
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

        <form action="{{ route('operator.qc.store') }}" method="POST">
            @csrf

            <div class="mb-6 border-b border-[#334155] pb-4">
                <div class="mb-4">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Pilih Batch Produksi</label>
                    <select name="production_id" x-model="selectedProductionId" @change="updateProductionInfo" required class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                        <option value="" class="bg-[#0f172a]">-- Pilih Batch --</option>
                        @foreach($productions as $production)
                        <option value="{{ $production->id }}" class="bg-[#0f172a]">{{ $production->batch_number }}</option>
                        @endforeach
                    </select>
                </div>

                <h2 class="text-xl font-bold text-[#93C5FD]" x-text="selectedProductionId ? 'Pengecekan Mutu (QC) - ' + getBatchNumber() : 'Pilih batch untuk memulai QC'"></h2>
                <p class="text-[#3B82F6] font-medium mt-1" x-show="selectedProductionId" x-text="'Produk: ' + getProductName()"></p>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-1">Total Barang Diproduksi</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalInspected" name="total_inspected" required min="1" readonly class="w-full px-4 py-2.5 bg-[#1e293b]/60 border border-[#334155] text-[#64748B] cursor-not-allowed text-lg font-bold">
                            <span class="absolute right-4 top-3 text-[#64748B] font-medium">Botol</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#3B82F6] mb-1">Barang Lolos QC (Bagus)</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalPassed" @input="calculateRejected" name="total_passed" required min="0" class="w-full px-4 py-2.5 bg-[#3B82F6]/10 border border-[#3B82F6]/30 focus:ring-[#1DA1F2] text-lg font-bold text-[#DBEAFE]">
                            <span class="absolute right-4 top-3 text-[#3B82F6]/50 font-medium">Botol</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#1DA1F2] mb-1">Barang Cacat / Ditolak</label>
                        <div class="relative">
                            <input type="number" x-model.number="totalRejected" @input="calculatePassed" name="total_rejected" required min="0" class="w-full px-4 py-2.5 bg-[#1DA1F2]/10 border border-[#1DA1F2]/30 focus:ring-[#1DA1F2] text-lg font-bold text-[#93C5FD]">
                            <span class="absolute right-4 top-3 text-[#1DA1F2]/50 font-medium">Botol</span>
                        </div>
                    </div>
                </div>

                <div class="border-t border-[#334155] pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-[10px] font-bold text-[#93C5FD] uppercase tracking-[0.15em]">RINCIAN CACAT (Jika Ada)</h3>
                        <button type="button" @click="addDefect()" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#1DA1F2]/20 text-[#93C5FD] text-sm font-medium hover:bg-[#1DA1F2]/30 transition border border-[#1DA1F2]/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Cacat
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(defect, index) in defects" :key="index">
                            <div class="flex items-center gap-4">
                                <div class="text-[#64748B] font-medium w-6 text-right" x-text="'-'"></div>
                                <div class="flex-1">
                                    <select x-model="defect.defect_cat_id" :name="`defects[${index}][defect_cat_id]`" required class="w-full px-3 py-2 text-sm bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]">
                                        <option value="" class="bg-[#0f172a]">-- Pilih Jenis Cacat --</option>
                                        @foreach($defectCategories as $cat)
                                            <option value="{{ $cat->id }}" class="bg-[#0f172a]">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-32 relative">
                                    <input type="number" x-model.number="defect.quantity" :name="`defects[${index}][quantity]`" required min="1" class="w-full px-3 py-2 text-sm bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] focus:ring-[#1DA1F2] text-right pr-12">
                                    <span class="absolute right-3 top-2 text-sm text-[#64748B]">Botol</span>
                                </div>
                                <button type="button" @click="removeDefect(index)" class="p-2 text-[#64748B] hover:text-[#93C5FD] hover:bg-[#334155] transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </template>
                        <div x-show="defects.length === 0" class="text-sm text-[#64748B] italic py-2">
                            Tidak ada rincian cacat yang ditambahkan.
                        </div>
                    </div>
                </div>

                <div class="border-t border-[#334155] pt-6">
                    <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#64748B] mb-2">Catatan QC</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-3 bg-[#1e293b]/60 border border-[#334155] text-[#93C5FD] placeholder-[#64748B] focus:ring-[#1DA1F2] focus:border-[#1DA1F2]" placeholder="Tuliskan catatan tambahan jika diperlukan..."></textarea>
                </div>

                <div class="bg-[#1e293b]/60 border border-[#334155] p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <span class="font-bold text-[#93C5FD] uppercase text-[10px] tracking-[0.1em]">Hasil Akhir:</span>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 transition" :class="disableRelease ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'">
                                    <input type="radio" name="final_status" value="release" x-model="finalResult" :disabled="disableRelease" class="w-4 h-4 text-[#3B82F6] border-[#334155] bg-[#1e293b] disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="text-sm font-bold" :class="finalResult === 'release' ? 'text-[#DBEAFE]' : 'text-[#64748B]'">Release</span>
                                </label>
                                <label class="flex items-center gap-2 transition" :class="disableRework ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'">
                                    <input type="radio" name="final_status" value="rework" x-model="finalResult" :disabled="disableRework" class="w-4 h-4 text-[#1DA1F2] border-[#334155] bg-[#1e293b] disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="text-sm font-bold" :class="finalResult === 'rework' ? 'text-[#93C5FD]' : 'text-[#64748B]'">Rework</span>
                                </label>
                                <label class="flex items-center gap-2 transition" :class="disableReject ? 'cursor-not-allowed opacity-50' : 'cursor-pointer'">
                                    <input type="radio" name="final_status" value="reject" x-model="finalResult" :disabled="disableReject" class="w-4 h-4 text-[#64748B] border-[#334155] bg-[#1e293b] disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="text-sm font-bold" :class="finalResult === 'reject' ? 'text-[#1DA1F2]' : 'text-[#64748B]'">Reject</span>
                                </label>
                            </div>
                        </div>
                        <div class="text-[10px] text-[#64748B] italic">(Dihitung otomatis)</div>
                    </div>
                    <p x-show="qcMessage" x-text="qcMessage" :class="qcMessageType" class="mt-3 text-sm font-medium"></p>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-[#1DA1F2] hover:bg-[#3B82F6] text-white font-bold transition shadow-md hover:shadow-lg">
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
            'product_name' => $item->product ? $item->product->name : 'Produk',
            'target_quantity' => $item->target_quantity
        ]];
    })) !!};

    const urlParams = new URLSearchParams(window.location.search);
    const defaultProductionId = urlParams.get('production_id') || '';

    function qcForm() {
        return {
            selectedProductionId: defaultProductionId,
            totalInspected: 0,
            totalPassed: 0,
            totalRejected: 0,
            defects: [],
            qcMessage: '',
            qcMessageType: '',
            disableRelease: false,
            disableRework: false,
            disableReject: false,
            finalResult: 'release',

            getBatchNumber() {
                return this.selectedProductionId && productionsData[this.selectedProductionId]
                    ? productionsData[this.selectedProductionId].batch_number : '';
            },

            getProductName() {
                return this.selectedProductionId && productionsData[this.selectedProductionId]
                    ? productionsData[this.selectedProductionId].product_name : '';
            },

            getTargetQuantity() {
                return this.selectedProductionId && productionsData[this.selectedProductionId]
                    ? parseInt(productionsData[this.selectedProductionId].target_quantity) || 0 : 0;
            },

            updateProductionInfo() {
                let target = this.getTargetQuantity();
                this.totalInspected = target;
                this.totalPassed = target;
                this.totalRejected = 0;
                this.evaluateSOP();
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
                this.evaluateSOP();
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
                this.evaluateSOP();
            },

            evaluateSOP() {
                let inspected = parseInt(this.totalInspected) || 0;
                let passed = parseInt(this.totalPassed) || 0;

                if (inspected === 0) {
                    this.disableRelease = false;
                    this.disableRework = false;
                    this.disableReject = false;
                    this.finalResult = 'release';
                    this.qcMessage = '';
                    this.qcMessageType = '';
                    return;
                }

                let percentage = (passed / inspected) * 100;

                if (percentage === 100) {
                    this.finalResult = 'release';
                    this.disableRelease = false;
                    this.disableRework = true;
                    this.disableReject = true;
                    this.qcMessage = 'Kualitas Sempurna (100%). Batch siap di-release.';
                    this.qcMessageType = 'text-[#3B82F6]';
                } else if (percentage >= 90) {
                    this.disableRelease = false;
                    this.disableRework = false;
                    this.disableReject = true;
                    this.qcMessage = 'Persentase lolos ' + percentage.toFixed(1) + '%. Pertimbangkan untuk Rework atau tetap Release.';
                    this.qcMessageType = 'text-[#93C5FD]';
                } else if (percentage >= 40) {
                    this.finalResult = 'rework';
                    this.disableRelease = true;
                    this.disableRework = false;
                    this.disableReject = false;
                    this.qcMessage = 'Persentase lolos ' + percentage.toFixed(1) + '%. Kualitas di bawah standar, wajib Rework.';
                    this.qcMessageType = 'text-[#1DA1F2]';
                } else {
                    this.finalResult = 'reject';
                    this.disableRelease = true;
                    this.disableRework = true;
                    this.disableReject = false;
                    this.qcMessage = 'Fatal Error (Lolos ' + percentage.toFixed(1) + '%). Batch gagal total dan wajib di-Reject.';
                    this.qcMessageType = 'text-[#64748B] font-bold';
                }
            },

            addDefect() {
                this.defects.push({ defect_cat_id: '', quantity: '' });
            },

            removeDefect(index) {
                this.defects.splice(index, 1);
            }
        }
    }
</script>
<style>[x-cloak] { display: none !important; }</style>
@endsection
