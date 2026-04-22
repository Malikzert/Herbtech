@extends('layouts.admin')

@section('title', 'Produksi Operator')
@section('header', 'Kelola Produksi')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedProduction: {} }">
    <!-- Header with Search & Filters -->
    <div class="mb-6 flex flex-col lg:flex-row gap-4 justify-between">
        <form method="GET" action="{{ route('operator.productions.index') }}" class="flex flex-col lg:flex-row gap-3 w-full">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari batch..." 
                    class="pl-10 pr-4 py-2.5 w-full lg:w-72 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>On Progress</option>
                <option value="qc_check" {{ request('status') === 'qc_check' ? 'selected' : '' }}>QC Check</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">Filter</button>
            @if(request('search') || request('status'))
            <a href="{{ route('operator.productions.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200">Reset</a>
            @endif
        </form>
        
        <button @click="showModal = true; modalMode = 'create'; selectedProduction = {}" class="inline-flex items-center px-4 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produksi
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 font-medium text-left">No Batch</th>
                        <th class="px-6 py-3 font-medium text-left">Produk</th>
                        <th class="px-6 py-3 font-medium text-left">Mulai</th>
                        <th class="px-6 py-3 font-medium text-left">Status</th>
                        <th class="px-6 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($productions as $production)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $production->batch_number }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->product->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $production->start_date ? $production->start_date->format('d M Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            @switch($production->status)
                                @case('draft')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Draft</span>
                                    @break
                                @case('in_progress')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">On Progress</span>
                                    @break
                                @case('qc_check')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">QC Check</span>
                                    @break
                                @case('completed')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">Completed</span>
                                    @break
                                @case('cancelled')
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Cancelled</span>
                                    @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('operator.productions.show', $production->id) }}" class="text-emerald-600 hover:text-emerald-700 text-sm font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada produksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($productions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $productions->links() }}
        </div>
        @endif
    </div>

    <!-- Create Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div x-show="showModal" @click.stop class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Produksi Baru</h3>
                    <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <form action="{{ route('operator.productions.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Batch</label>
                            <input type="text" name="batch_number" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                            <select name="product_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                                @foreach(\App\Models\Product::all() as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="datetime-local" name="start_date" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PIC Name</label>
                            <input type="text" name="pic_name" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection