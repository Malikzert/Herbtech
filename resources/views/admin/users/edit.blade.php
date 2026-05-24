@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'EDIT USER')

@section('content')
<div class="max-w-lg">
    <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md p-6 shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
        <div class="absolute inset-0 pointer-events-none opacity-5" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(5,150,105,0.3) 20px, rgba(5,150,105,0.3) 21px);"></div>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="relative z-10 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Role</label>
                <select name="role" required
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }} class="bg-gray-900">Admin</option>
                    <option value="operator" {{ $user->role === 'operator' ? 'selected' : '' }} class="bg-gray-900">Operator</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Password Baru (opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ubah"
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                    class="w-full h-11 px-4 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
            </div>

            <div class="p-3 rounded-lg bg-red-500/10 border border-red-500/30">
                <p class="text-[10px] uppercase tracking-wider text-red-300 font-semibold mb-2 font-mono">Persyaratan Password Baru WAJIB</p>
                <ul class="space-y-1">
                    <li class="flex items-center gap-2 text-xs text-red-200/80 font-mono">
                        <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Minimal 8 karakter
                    </li>
                    <li class="flex items-center gap-2 text-xs text-red-200/80 font-mono">
                        <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Huruf BESAR (A-Z)
                    </li>
                    <li class="flex items-center gap-2 text-xs text-red-200/80 font-mono">
                        <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Huruf kecil (a-z)
                    </li>
                    <li class="flex items-center gap-2 text-xs text-red-200/80 font-mono">
                        <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Angka (0-9)
                    </li>
                    <li class="flex items-center gap-2 text-xs text-red-200/80 font-mono">
                        <svg class="w-3 h-3 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Karakter spesial/simbol (@, #, $, dll)
                    </li>
                </ul>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" class="flex-1 px-5 py-2.5 bg-white/5 border border-white/20 text-emerald-200/70 hover:text-emerald-200 text-sm font-medium rounded-lg hover:bg-white/10 transition text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
