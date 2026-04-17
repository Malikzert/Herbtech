@extends('layouts.app')

@section('title', 'Inventaris Bahan Baku')
@section('header', 'Inventaris Bahan Baku')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    /* Custom DataTables Styling for Tailwind */
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.25rem 2rem 0.25rem 0.5rem;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.25rem 0.5rem; margin-left: 0.5rem;
        outline: none;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #2D5A27; box-shadow: 0 0 0 1px #2D5A27;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #2D5A27; color: white !important; border: 1px solid #2D5A27; border-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800">Daftar Ketersediaan Bahan</h3>
        <button class="bg-emerald-custom hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition shadow-sm text-sm flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Bahan Baru
        </button>
    </div>
    
    <div class="p-6">
        <table id="inventoryTable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-3 font-medium border-b">Nama Bahan</th>
                    <th class="px-6 py-3 font-medium border-b">Tipe</th>
                    <th class="px-6 py-3 font-medium border-b">Unit</th>
                    <th class="px-6 py-3 font-medium border-b text-right">Saldo</th>
                    <th class="px-6 py-3 font-medium border-b text-center">Status</th>
                    <th class="px-6 py-3 font-medium border-b text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <!-- Example Row 1: Safe -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Jahe Merah (Zingiber officinale)</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Herbal</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Kg</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-800 text-right">125.50</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aman</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                    </td>
                </tr>

                <!-- Example Row 2: Safe -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Botol Kaca 150ml</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Packaging</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Pcs</td>
                    <td class="px-6 py-4 text-sm font-bold text-gray-800 text-right">500.00</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aman</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                    </td>
                </tr>

                <!-- Example Row 3: Critical -->
                <tr class="hover:bg-red-50 transition bg-red-50/30">
                    <td class="px-6 py-4 text-sm font-medium text-red-900">Kunyit (Curcuma longa)</td>
                    <td class="px-6 py-4 text-sm text-red-700">Herbal</td>
                    <td class="px-6 py-4 text-sm text-red-700">Kg</td>
                    <td class="px-6 py-4 text-sm font-bold text-red-600 text-right">3.20</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 animate-pulse">Kritis</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                    </td>
                </tr>
                
                <!-- Example Row 4: Critical -->
                <tr class="hover:bg-red-50 transition bg-red-50/30">
                    <td class="px-6 py-4 text-sm font-medium text-red-900">Label Botol</td>
                    <td class="px-6 py-4 text-sm text-red-700">Packaging</td>
                    <td class="px-6 py-4 text-sm text-red-700">Pcs</td>
                    <td class="px-6 py-4 text-sm font-bold text-red-600 text-right">0.00</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Habis</span>
                    </td>
                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery & DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#inventoryTable').DataTable({
            language: {
                search: "Cari Data:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Awal",
                    last: "Akhir",
                    next: "Lanjut",
                    previous: "Kembali"
                }
            },
            pageLength: 10,
        });
    });
</script>
@endpush
