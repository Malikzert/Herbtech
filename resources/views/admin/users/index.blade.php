@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')

@section('content')
<div x-data="{ showModal: false, modalMode: 'create', selectedUser: {} }">
    <!-- Header Actions - Properly Aligned -->
    <div class="mb-6">
        <div class="bg-glass rounded-xl border border-white/50 p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..." 
                        class="w-full h-11 pl-10 pr-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none focus:bg-white transition">
                </div>
                
                <!-- Filter Dropdown -->
                <select name="role" class="modern-select h-11 px-4 py-2 bg-white/50 border border-gray-200 rounded-lg text-sm text-gray-700 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none focus:bg-white transition cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="operator" {{ request('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                </select>
                
                <!-- Filter Button -->
                <button type="submit" class="h-11 px-5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                    Filter
                </button>
                
                @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="h-11 px-5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Reset
                </a>
                @endif
                
                <!-- Spacer -->
                <div class="flex-1"></div>
                
                <!-- Add Button -->
                <button @click="showModal = true; modalMode = 'create'; selectedUser = {}" type="button" class="h-11 px-5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition flex items-center gap-2 shadow-lg shadow-emerald-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah User
                </button>
            </form>
        </div>
    </div>

    <!-- Table with Glass Effect -->
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50/80 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3.5 font-medium text-left">No</th>
                        <th class="px-6 py-3.5 font-medium text-left">Nama</th>
                        <th class="px-6 py-3.5 font-medium text-left">Email</th>
                        <th class="px-6 py-3.5 font-medium text-left">Role</th>
                        <th class="px-6 py-3.5 font-medium text-left">Terdaftar</th>
                        <th class="px-6 py-3.5 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100/50">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-purple-100/80 text-purple-700 border border-purple-200">Admin</span>
                            @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100/80 text-blue-700 border border-blue-200">Operator</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="selectedUser = {{ Js::from($user) }}; showModal = true; modalMode = 'edit'" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button @click="selectedUser = {{ Js::from($user) }}; showModal = true; modalMode = 'delete'" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-gray-500 font-medium">Belum ada user</p>
                                <p class="text-gray-400 text-sm mt-1">Klik tombol "Tambah User" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100/50 bg-gray-50/30">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/40 backdrop-blur-sm"></div>
            
            <div x-show="showModal" @click.stop class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800">
                        <span x-text="modalMode === 'create' ? 'Tambah User' : modalMode === 'edit' ? 'Edit User' : 'Konfirmasi Hapus'"></span>
                    </h3>
                    <button @click="showModal = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <template x-if="modalMode === 'delete'">
                    <div class="p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <p class="text-gray-600 mb-2">Hapus user <span x-text="selectedUser.name" class="font-semibold text-gray-800"></span>?</p>
                        <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <form :action="'/admin/users/' + selectedUser.id" method="POST" class="mt-6">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </template>

                <template x-if="modalMode !== 'delete'">
                    <form :action="modalMode === 'create' ? '{{ route('admin.users.store') }}' : '/admin/users/' + selectedUser.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            @method('PUT')
                        </template>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                            <input type="text" name="name" x-model="selectedUser.name" required
                                class="w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                            <input type="email" name="email" x-model="selectedUser.email" required
                                class="w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                            <select name="role" x-model="selectedUser.role" required
                                class="modern-select w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                                <option value="admin">Admin</option>
                                <option value="operator">Operator</option>
                            </select>
                        </div>
                        
                        <div x-show="modalMode === 'create'">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                            <input type="password" name="password" :required="modalMode === 'create'"
                                class="w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>
                        
                        <div x-show="modalMode === 'create'">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" :required="modalMode === 'create'"
                                class="w-full h-11 px-4 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-white focus:outline-none transition">
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">Simpan</button>
                        </div>
                    </form>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection