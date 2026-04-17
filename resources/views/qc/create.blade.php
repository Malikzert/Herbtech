@extends('layouts.app')

@section('title', 'Form Quality Control')
@section('header', 'Input Hasil QC')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-emerald-custom px-6 py-4">
        <h3 class="text-lg font-bold text-white">Form Inspeksi Quality Control</h3>
        <p class="text-emerald-100 text-sm">Validasi parameter fisik batch produksi</p>
    </div>

    <form action="#" method="POST" class="p-6 space-y-6">
        @csrf
        
        <!-- Batch Information Info Box -->
        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">No. Batch</p>
                    <p class="font-bold text-gray-800">BCH-20260401-05</p>
                </div>
                <div>
                    <p class="text-gray-500">Produk</p>
                    <p class="font-bold text-gray-800">Jamu Kunyit Asam</p>
                </div>
                <div>
                    <p class="text-gray-500">Inspektor</p>
                    <p class="font-bold text-gray-800">{{ auth()->check() ? auth()->user()->name : 'Operator QC' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu Inspeksi</p>
                    <p class="font-bold text-gray-800">{{ date('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <hr class="border-gray-100">

        <!-- Physical Parameters -->
        <div>
            <h4 class="text-md font-semibold text-gray-800 mb-4">Pemeriksaan Parameter Fisik</h4>
            
            <div class="space-y-4">
                
                <!-- Warna -->
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-custom transition">
                    <div>
                        <p class="font-medium text-gray-800">Warna</p>
                        <p class="text-xs text-gray-500">Kesesuaian warna ekstrak dengan standar</p>
                    </div>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_warna" value="passed" class="w-5 h-5 text-emerald-custom border-gray-300 focus:ring-emerald-custom" required>
                            <span class="ml-2 text-sm font-medium text-green-700">Passed</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_warna" value="failed" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-red-700">Failed</span>
                        </label>
                    </div>
                </div>

                <!-- Aroma -->
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-custom transition">
                    <div>
                        <p class="font-medium text-gray-800">Aroma</p>
                        <p class="text-xs text-gray-500">Kekuatan dan kekhasan aroma herbal</p>
                    </div>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_aroma" value="passed" class="w-5 h-5 text-emerald-custom border-gray-300 focus:ring-emerald-custom" required>
                            <span class="ml-2 text-sm font-medium text-green-700">Passed</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_aroma" value="failed" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-red-700">Failed</span>
                        </label>
                    </div>
                </div>

                <!-- Rasa -->
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-custom transition">
                    <div>
                        <p class="font-medium text-gray-800">Rasa</p>
                        <p class="text-xs text-gray-500">Keseimbangan rasa dan tidak ada rasa menyimpang</p>
                    </div>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_rasa" value="passed" class="w-5 h-5 text-emerald-custom border-gray-300 focus:ring-emerald-custom" required>
                            <span class="ml-2 text-sm font-medium text-green-700">Passed</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_rasa" value="failed" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-red-700">Failed</span>
                        </label>
                    </div>
                </div>

                <!-- Ketahanan (Viskositas/Kekeruhan) -->
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:border-emerald-custom transition">
                    <div>
                        <p class="font-medium text-gray-800">Ketahanan / Kekeruhan</p>
                        <p class="text-xs text-gray-500">Bebas dari endapan abnormal</p>
                    </div>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_ketahanan" value="passed" class="w-5 h-5 text-emerald-custom border-gray-300 focus:ring-emerald-custom" required>
                            <span class="ml-2 text-sm font-medium text-green-700">Passed</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="param_ketahanan" value="failed" class="w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500">
                            <span class="ml-2 text-sm font-medium text-red-700">Failed</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="pt-4 border-t border-gray-100">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
            <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-custom focus:border-emerald-custom outline-none transition" placeholder="Tulis catatan jika ada parameter yang failed..."></textarea>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <button type="button" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="submit" class="px-6 py-2 bg-emerald-custom hover:bg-emerald-700 text-white font-medium rounded-lg shadow-sm transition">
                Simpan Hasil QC
            </button>
        </div>
    </form>
</div>
@endsection
