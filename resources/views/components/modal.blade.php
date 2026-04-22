<div x-data="{ show: false, mode: 'create', data: {} }" x-init="$watch('window.location.hash', () => {}); document.addEventListener('keydown', e => { if(e.key === 'Escape' && show) show = false; })">
    <!-- Dynamic Trigger Buttons -->
    <div id="modal-triggers">
        @yield('modal-triggers')
    </div>

    <!-- Modal Backdrop -->
    <div x-show="show" 
         x-transition.opacity
         class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50"
         @click="show = false"
         style="display: none;"></div>

    <!-- Modal Content -->
    <div x-show="show" 
         x-transition.enter="transition ease-out duration-300"
         x-transition.enter-start="opacity-0 scale-95 translate-y-4"
         x-transition.enter-end="opacity-100 scale-100 translate-y-0"
         x-transition.leave="transition ease-in duration-200"
         x-transition.leave-start="opacity-100 scale-100"
         x-transition.leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-h-[90vh] overflow-hidden" @click.stop>
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800" x-text="mode === 'create' ? 'Tambah Data' : mode === 'edit' ? 'Edit Data' : 'Konfirmasi Hapus'"></h3>
                <button @click="show = false" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <!-- Create/Edit Form -->
                <form x-show="mode !== 'delete'" id="modal-form" method="POST" :action="mode === 'create' ? '{{ $createUrl ?? '' }}' : (data.id ? '{{ $editUrl ?? '' }}/' + data.id : '')">
                    @csrf
                    <template x-if="mode === 'edit'">
                        @method('PUT')
                    </template>
                    
                    <div class="space-y-4">
                        {{ $slot }}
                    </div>
                </form>

                <!-- Delete Confirmation -->
                <div x-show="mode === 'delete'" class="text-center py-4">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <p class="text-gray-600 mb-2">Apakah Anda yakin ingin menghapus data ini?</p>
                    <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                    
                    <form :action="'{{ $deleteUrl ?? '' }}/' + data.id" method="POST" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                            Ya, Hapus Data
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Footer -->
            <div x-show="mode !== 'delete'" class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3">
                <button @click="show = false" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" form="modal-form" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(mode, itemData = {}) {
            window.modalApp = Alpine.data('modal', { mode, data: itemData });
            const app = Alpine.store('app') || {};
            const container = document.querySelector('[x-data*="modal"]') || document.querySelector('[x-data]');
            if(container && container.__x) {
                container.__x.$data.show = true;
                container.__x.$data.mode = mode;
                container.__x.$data.data = itemData;
            } else {
                const el = document.querySelector('[x-data]');
                if(el && el._x_dataStack && el._x_dataStack[0]) {
                    el._x_dataStack[0].show = true;
                    el._x_dataStack[0].mode = mode;
                    el._x_dataStack[0].data = itemData;
                }
            }
        }

        window.openModal = openModal;
    </script>
</div>