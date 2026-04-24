@extends('layouts.admin')

@section('title', 'Kelola Resep')
@section('header', 'Master Resep')

@section('content')
<div x-data="{ 
    showModal: false, 
    selectedProductId: '',
    selectedProductName: '',
    rawMaterials: {{ Js::from(\App\Models\RawMaterial::all()->map(fn($rm) => ['id' => $rm->id, 'name' => $rm->name, 'sku' => $rm->sku, 'unit' => $rm->unit])) }},
    recipeData: {},
    async loadRecipes() {
        if(this.selectedProductId) {
            const response = await fetch(`/admin/recipes/by-product/${this.selectedProductId}`);
            const recipes = await response.json();
            this.recipeData = {};
            recipes.forEach(r => {
                this.recipeData[r.raw_material_id] = {
                    checked: true,
                    quantity_needed: r.quantity_needed,
                    unit: r.unit
                };
            });
        } else {
            this.recipeData = {};
        }
    },
    toggleMaterial(materialId) {
        if(!this.recipeData[materialId]) {
            this.recipeData[materialId] = { checked: true, quantity_needed: '', unit: materialId.unit };
        } else {
            this.recipeData[materialId].checked = !this.recipeData[materialId].checked;
            if(this.recipeData[materialId].checked && !this.recipeData[materialId].quantity_needed) {
                this.recipeData[materialId].quantity_needed = '';
            }
        }
    }
}">
    <!-- Header -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.recipes.index') }}" class="flex flex-wrap gap-3 items-center">
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                        class="w-full h-11 pl-10 pr-4 bg-white border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                </div>
                
                <button type="submit" class="h-11 px-5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request('search'))
                <a href="{{ route('admin.recipes.index') }}" class="h-11 px-5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Products with Recipes -->
    <div class="space-y-4">
        @forelse(\App\Models\Product::with('recipes.rawMaterial')->when(request('search'), fn($q) => $q->where('name', 'like', '%'.request('search').'%'))->get() as $product)
        <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 bg-gray-50/50 border-b border-gray-100/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $product->recipes->count() }} bahan baku</p>
                </div>
                <button @click="selectedProductId = '{{ $product->id }}'; selectedProductName = '{{ $product->name }}'; loadRecipes(); showModal = true" type="button" class="px-4 py-2 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Kelola Resep
                </button>
            </div>
            
            @if($product->recipes->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3 font-medium text-left">No</th>
                            <th class="px-6 py-3 font-medium text-left">Bahan Baku</th>
                            <th class="px-6 py-3 font-medium text-left">SKU</th>
                            <th class="px-6 py-3 font-medium text-left">Jumlah per Unit</th>
                            <th class="px-6 py-3 font-medium text-right">Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50">
                        @foreach($product->recipes as $index => $recipe)
                        <tr class="hover:bg-gray-50/30 transition">
                            <td class="px-6 py-3 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 text-sm font-medium text-gray-800">{{ $recipe->rawMaterial->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-sm text-gray-500 font-mono">{{ $recipe->rawMaterial->sku ?? '-' }}</td>
                            <td class="px-6 py-3">
                                <span class="text-sm font-semibold text-emerald-600">{{ number_format($recipe->quantity_needed, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ $recipe->unit }}</span>
                            </td>
                            <td class="px-6 py-3 text-right">
                                @php $stockStatus = $recipe->rawMaterial->stock_status ?? 'available'; @endphp
                                @if($stockStatus === 'out')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">Habis</span>
                                @elseif($stockStatus === 'low')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-700">Menipis</span>
                                @else
                                <span class="text-sm text-gray-600">{{ $recipe->rawMaterial->current_stock ?? 0 }} {{ $recipe->rawMaterial->unit ?? '' }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-8 text-center">
                <p class="text-gray-400">Belum ada resep untuk produk ini</p>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-glass rounded-xl border border-white/50 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <p class="text-gray-500 font-medium">Tidak ada produk</p>
        </div>
        @endforelse
    </div>

    @if(\App\Models\Product::count() > 0)
    <!-- Glassmorphism Modal with Checkbox List -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            
            <div x-show="showModal" @click.stop class="relative bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl w-full max-w-2xl border border-white/50 max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100/50 shrink-0">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Kelola Resep</h3>
                        <p class="text-sm text-gray-500">Produk: <span x-text="selectedProductName" class="font-medium"></span></p>
                    </div>
                    <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100/50 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.recipes.store-batch') }}" method="POST" class="p-6 overflow-y-auto flex-1">
                    @csrf
                    <input type="hidden" name="product_id" x-model="selectedProductId">
                    
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 mb-4">
                        <p class="text-sm text-emerald-700">Pilih bahan baku yang digunakan dan isi jumlah kebutuhan per unit produksi.</p>
                    </div>

                    <!-- Checkbox Table -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="px-4 py-3 font-medium text-center w-12">Pilih</th>
                                    <th class="px-4 py-3 font-medium text-left">Bahan Baku</th>
                                    <th class="px-4 py-3 font-medium text-left">Unit</th>
                                    <th class="px-4 py-3 font-medium text-left">Jumlah Needed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <template x-for="material in rawMaterials" :key="material.id">
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" 
                                                :id="'material_' + material.id" 
                                                :checked="recipeData[material.id]?.checked"
                                                @change="toggleMaterial(material.id)"
                                                class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                        </td>
                                        <td class="px-4 py-3">
                                            <label :for="'material_' + material.id" class="text-sm font-medium text-gray-800 cursor-pointer" x-text="material.name"></label>
                                            <div class="text-xs text-gray-400" x-text="material.sku"></div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <span x-text="material.unit || 'gram'"></span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <input type="number" 
                                                    :name="'quantities[' + material.id + ']'" 
                                                    x-model="recipeData[material.id]?.quantity_needed"
                                                    :disabled="!recipeData[material.id]?.checked"
                                                    step="0.01" min="0.01"
                                                    :required="recipeData[material.id]?.checked"
                                                    :placeholder="recipeData[material.id]?.checked ? 'Jumlah...' : '-'"
                                                    class="w-24 px-3 py-1.5 text-sm border rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 disabled:bg-gray-100 disabled:text-gray-400">
                                                <span class="text-xs text-gray-500" x-text="material.unit || 'gram'"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-gray-100">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100/80 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">Simpan Resep</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection