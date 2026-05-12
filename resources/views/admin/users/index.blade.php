@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('header', 'Manajemen User')

@section('content')
<style>
    @keyframes greenPulse {
        0%, 100% { box-shadow: inset 0 0 8px rgba(5, 150, 105, 0.1); }
        50% { box-shadow: inset 0 0 25px rgba(5, 150, 105, 0.25); }
    }
    .hybrid-card {
        animation: greenPulse 4s ease-in-out infinite;
    }
    .hybrid-btn {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        border: none;
        color: #fff;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        transition: all 0.2s ease;
    }
    .hybrid-btn:hover {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        box-shadow: 0 0 20px rgba(5, 150, 105, 0.4);
        transform: translateY(-1px);
    }
    .hybrid-input {
        background: rgba(6, 78, 59, 0.6);
        border: 1.5px solid rgba(5, 150, 105, 0.25);
        color: #fff;
        transition: all 0.2s ease;
    }
    .hybrid-input:focus {
        border-color: #10B981;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.2);
        outline: none;
    }
    .hybrid-input::placeholder {
        color: rgba(255,255,255,0.3);
    }
    .hybrid-select {
        background: rgba(6, 78, 59, 0.6);
        border: 1.5px solid rgba(5, 150, 105, 0.25);
        color: #fff;
        transition: all 0.2s ease;
    }
    .hybrid-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 12px rgba(5, 150, 105, 0.2);
        outline: none;
    }
    .hybrid-select option {
        background: #064E3B;
        color: #fff;
    }
    .hybrid-table thead {
        background: rgba(5, 150, 105, 0.15);
    }
    .hybrid-table thead th {
        color: #34D399;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 10px;
    }
    .hybrid-table tbody tr {
        border-bottom: 1px solid rgba(5, 150, 105, 0.08);
        transition: all 0.2s ease;
    }
    .hybrid-table tbody tr:hover {
        background: rgba(5, 150, 105, 0.05);
    }
    .hybrid-badge-admin {
        background: rgba(5, 150, 105, 0.15);
        color: #34D399;
        border: 1px solid rgba(5, 150, 105, 0.3);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 0;
    }
    .hybrid-badge-operator {
        background: rgba(212, 168, 67, 0.15);
        color: #D4A843;
        border: 1px solid rgba(212, 168, 67, 0.3);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 10px;
        padding: 2px 10px;
        border-radius: 0;
    }
    .hybrid-link {
        color: rgba(255,255,255,0.5);
        transition: all 0.2s ease;
    }
    .hybrid-link:hover {
        color: #34D399;
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: rgba(6, 78, 59, 0.3); }
    ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 0; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
</style>

<div x-data="{ showModal: false, modalMode: 'create', selectedUser: {}, roleOpen: false, roleSelected: '{{ request('role') }}', roleLabel: '{{ request('role') ? ucfirst(request('role')) : 'Semua Role' }}' }">
    <!-- Header Actions -->
    <div class="mb-6">
        <div class="relative overflow-hidden rounded-sm border border-emerald-500/30 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.1)] hybrid-card">
            <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
            <div class="p-5">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-center">
                    <!-- Search Input -->
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari user..."
                            class="hybrid-input w-full h-11 pl-10 pr-4 rounded-sm text-sm">
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <input type="hidden" name="role" :value="roleSelected">
                        <button type="button"
                            @click="open = !open; if(open) { $nextTick(() => { let r = $el.getBoundingClientRect(); $refs.roleMenu.style.top = (r.bottom + 6) + 'px'; $refs.roleMenu.style.left = r.left + 'px'; $refs.roleMenu.style.width = Math.max(r.width, 180) + 'px'; }) }"
                            class="flex items-center gap-2 h-11 px-4 pr-10 bg-emerald-900/60 border border-emerald-500/25 rounded-sm text-sm text-emerald-200/80 hover:border-emerald-400/50 focus:border-emerald-400 transition-all duration-200 cursor-pointer min-w-[160px] whitespace-nowrap">
                            <svg class="w-4 h-4 shrink-0 text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                            <span class="truncate font-bold uppercase tracking-wider text-[10px]" x-text="roleLabel"></span>
                            <svg class="w-3.5 h-3.5 text-emerald-400/50 shrink-0 ml-auto transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        {{-- Menu --}}
                        <template x-teleport="body">
                            <div x-ref="roleMenu"
                                x-show="open"
                                @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                                class="fixed z-[9999] rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.5)] overflow-hidden" style="display: none;">
                                <div class="py-1">
                                    <button type="button" @click="roleSelected = ''; roleLabel = 'Semua Role'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="!roleSelected ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="!roleSelected ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Semua Role</span>
                                    </button>
                                    <button type="button" @click="roleSelected = 'admin'; roleLabel = 'Admin'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="roleSelected === 'admin' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="roleSelected === 'admin' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Admin</span>
                                    </button>
                                    <button type="button" @click="roleSelected = 'operator'; roleLabel = 'Operator'; open = false"
                                        class="w-full text-left px-4 py-2.5 text-sm transition-all duration-150 flex items-center gap-3"
                                        :class="roleSelected === 'operator' ? 'text-emerald-300 bg-emerald-500/20 font-bold' : 'text-emerald-200/60 hover:text-emerald-200 hover:bg-emerald-500/10'">
                                        <svg class="w-4 h-4 shrink-0" :class="roleSelected === 'operator' ? 'opacity-100' : 'opacity-0'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="font-bold uppercase tracking-wider text-[10px]">Operator</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Filter Button -->
                    <button type="submit" class="hybrid-btn h-11 px-6 rounded-sm text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2H4V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6H3v-6zM12 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                        Filter
                    </button>

                    @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="h-11 px-5 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-emerald-400/60 hover:text-emerald-300 border border-emerald-700/40 hover:border-emerald-500/50 rounded-sm transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Reset
                    </a>
                    @endif

                    <div class="flex-1"></div>

                    <!-- Add Button -->
                    <button @click="showModal = true; modalMode = 'create'; selectedUser = {}" type="button" class="hybrid-btn h-11 px-6 rounded-sm text-xs flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah User
                    </button>
                </form>
            </div>
            <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/30"></div>
            <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/30"></div>
        </div>
    </div>

    <!-- Table -->
    <div class="relative overflow-hidden rounded-sm border border-emerald-500/25 bg-emerald-900/60 backdrop-blur-md shadow-[0_0_30px_rgba(5,150,105,0.08)]">
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
        <div class="overflow-x-auto">
            <table class="w-full hybrid-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-left">Terdaftar</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-500/10">
                    @forelse($users as $index => $user)
                    <tr class="group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-bold text-emerald-200/50 group-hover:text-emerald-200/80 transition-colors">{{ $index + 1 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-emerald-500/15 text-emerald-400 font-black text-sm border border-emerald-500/30" style="border-radius: 0;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <span class="text-sm font-bold text-emerald-50/90 group-hover:text-emerald-50 transition-colors">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                            <span class="hybrid-badge-admin">Admin</span>
                            @else
                            <span class="hybrid-badge-operator">Operator</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-emerald-200/60 group-hover:text-emerald-200/80 transition-colors">{{ $user->created_at?->format('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button @click="selectedUser = {{ Js::from($user) }}; showModal = true; modalMode = 'edit'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all duration-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <button @click="selectedUser = {{ Js::from($user) }}; showModal = true; modalMode = 'delete'"
                                    class="w-9 h-9 flex items-center justify-center text-emerald-200/40 hover:text-red-400 hover:bg-red-500/15 transition-all duration-200" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 flex items-center justify-center border border-emerald-500/20 bg-emerald-500/5 mb-5" style="border-radius: 0;">
                                    <svg class="w-10 h-10 text-emerald-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                </div>
                                <p class="text-emerald-200/60 font-bold text-sm uppercase tracking-wider">Belum Ada User</p>
                                <p class="text-emerald-200/30 text-xs mt-2">Klik tombol "Tambah User" untuk memulai</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-emerald-500/10 bg-emerald-500/5">
            {{ $users->links() }}
        </div>
        @endif
        <div class="absolute bottom-0 right-0 w-16 h-[2px] bg-emerald-500/25"></div>
        <div class="absolute bottom-0 right-0 w-[2px] h-16 bg-emerald-500/25"></div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

            <div x-show="showModal" @click.stop
                class="relative w-full max-w-lg rounded-sm border border-emerald-500/30 bg-emerald-900/95 backdrop-blur-xl shadow-[0_0_60px_rgba(5,150,105,0.15)]">
                <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>

                <div class="flex justify-between items-center px-6 py-4 border-b border-emerald-500/15">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 flex items-center justify-center bg-emerald-500/15 border border-emerald-500/30" style="border-radius: 0;">
                            <template x-if="modalMode === 'delete'">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </template>
                            <template x-if="modalMode !== 'delete'">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </template>
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-wider text-emerald-50">
                            <span x-text="modalMode === 'create' ? 'Tambah User' : modalMode === 'edit' ? 'Edit User' : 'Hapus User'"></span>
                        </h3>
                    </div>
                    <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center text-emerald-200/30 hover:text-emerald-300 hover:bg-emerald-500/15 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <template x-if="modalMode === 'delete'">
                    <div class="px-6 py-6 text-center">
                        <div class="w-20 h-20 mx-auto mb-5 flex items-center justify-center border border-red-500/30 bg-red-500/10" style="border-radius: 0;">
                            <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </div>
                        <p class="text-emerald-200/80 text-sm mb-1">
                            Hapus <span x-text="selectedUser.name" class="font-bold text-emerald-50"></span>?
                        </p>
                        <p class="text-xs text-emerald-200/40">Tindakan ini tidak dapat dibatalkan.</p>

                        <form :action="'/admin/users/' + selectedUser.id" method="POST" class="mt-6">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-center gap-3">
                                <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-200 font-bold text-xs uppercase tracking-wider rounded-sm hover:bg-emerald-500/10 transition-all">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold text-xs uppercase tracking-wider rounded-sm shadow-lg hover:shadow-red-500/30 transition-all">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </template>

                <template x-if="modalMode !== 'delete'">
                    <form :action="modalMode === 'create' ? '{{ route('admin.users.store') }}' : '/admin/users/' + selectedUser.id" method="POST" class="px-6 py-6 space-y-4">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            @method('PUT')
                        </template>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Nama</label>
                            <input type="text" name="name" x-model="selectedUser.name" required
                                class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Email</label>
                            <input type="email" name="email" x-model="selectedUser.email" required
                                class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Role</label>
                            <select name="role" x-model="selectedUser.role" required
                                class="hybrid-select w-full h-11 px-4 rounded-sm text-sm cursor-pointer">
                                <option value="admin">Admin</option>
                                <option value="operator">Operator</option>
                            </select>
                        </div>

                        <div x-show="modalMode === 'create'">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Password</label>
                            <input type="password" name="password" :required="modalMode === 'create'"
                                class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                        </div>

                        <div x-show="modalMode === 'create'">
                            <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-emerald-400/60 mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" :required="modalMode === 'create'"
                                class="hybrid-input w-full h-11 px-4 rounded-sm text-sm">
                        </div>

                        <div class="flex justify-end gap-3 pt-2 border-t border-emerald-500/15">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-emerald-500/5 border border-emerald-500/25 text-emerald-200/60 hover:text-emerald-200 font-bold text-xs uppercase tracking-wider rounded-sm hover:bg-emerald-500/10 transition-all">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs uppercase tracking-wider rounded-sm shadow-lg hover:shadow-emerald-500/30 transition-all">Simpan</button>
                        </div>
                    </form>
                </template>
                <div class="absolute bottom-0 right-0 w-12 h-[2px] bg-emerald-500/30"></div>
                <div class="absolute bottom-0 right-0 w-[2px] h-12 bg-emerald-500/30"></div>
            </div>
        </div>
    </div>
</div>
@endsection
