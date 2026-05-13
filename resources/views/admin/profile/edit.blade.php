@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('header', 'PROFIL')

@section('content')
<div x-data="{ tab: 'profile' }">
    {{-- Hero Section — Valorant-style Agent Card --}}
    <div class="relative mb-8 overflow-hidden rounded-xl border border-emerald-500/30 bg-gradient-to-br from-emerald-900/60 via-emerald-800/40 to-black/60 shadow-[0_0_30px_rgba(5,150,105,0.15)]">
        {{-- Decorative diagonal lines --}}
        <div class="absolute inset-0 pointer-events-none opacity-10" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(5,150,105,0.3) 20px, rgba(5,150,105,0.3) 21px);"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-emerald-400/5 rounded-full blur-2xl"></div>

        <div class="relative z-10 p-6 md:p-8 flex flex-col md:flex-row items-center md:items-start gap-6">
            {{-- Avatar with Valorant-style border --}}
            <div class="relative shrink-0">
                <div class="w-24 h-24 md:w-28 md:h-28 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center text-emerald-50 text-4xl md:text-5xl font-black shadow-[0_0_20px_rgba(5,150,105,0.3)] border-2 border-emerald-400/50">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 border-2 border-emerald-900 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>

            {{-- Identity --}}
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl md:text-3xl font-black text-emerald-50 tracking-tight drop-shadow-lg">{{ $user->name }}</h1>
                <p class="text-emerald-300/80 text-sm font-medium mt-0.5">{{ $user->email }}</p>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-3">
                    <span class="px-3 py-1 text-xs font-bold uppercase tracking-widest rounded-sm bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">{{ ucfirst($user->role) }}</span>
                    <span class="text-emerald-200/60 text-xs font-mono">ID #{{ $user->id }}</span>
                    <span class="flex items-center gap-1.5 text-xs text-emerald-400/70">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Active
                    </span>
                </div>
            </div>

            {{-- Joined date --}}
            <div class="hidden md:flex flex-col items-end justify-start shrink-0">
                <span class="text-[10px] uppercase tracking-[0.2em] text-emerald-300/40 font-semibold">Member Since</span>
                <span class="text-emerald-200/70 text-sm font-bold font-mono">{{ $user->created_at->format('d M Y') }}</span>
            </div>
        </div>

        {{-- Bottom accent line --}}
        <div class="h-[2px] bg-gradient-to-r from-emerald-500/80 via-emerald-400/40 to-transparent"></div>
    </div>

    {{-- Tab Navigation --}}
    <div class="flex border-b border-white/20 mb-8">
        <button @click="tab = 'profile'" :class="tab === 'profile' ? 'border-emerald-400 text-emerald-300' : 'border-transparent text-emerald-200/50 hover:text-emerald-200/80'" class="px-5 py-3 text-sm font-bold uppercase tracking-wider border-b-2 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Informasi Profil
        </button>
        <button @click="tab = 'security'" :class="tab === 'security' ? 'border-emerald-400 text-emerald-300' : 'border-transparent text-emerald-200/50 hover:text-emerald-200/80'" class="px-5 py-3 text-sm font-bold uppercase tracking-wider border-b-2 transition-all duration-200 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Keamanan
        </button>
    </div>

    {{-- ========== TAB 1: PROFILE ========== --}}
    <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Left: Profile form --}}
            <div class="lg:col-span-2">
                <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
                    {{-- Top header with accent --}}
                    <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                    <div>
                        <h3 class="font-bold text-emerald-50 text-sm uppercase tracking-wider">Edit Profil</h3>
                        <p class="text-emerald-200/60 text-xs">Perbarui informasi akun Anda</p>
                    </div>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" class="p-6 space-y-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                            @error('name')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                            @error('email')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right: Account Info Panel --}}
            <div class="space-y-4">
                <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
                    <div class="px-6 py-4 border-b border-white/10">
                        <h4 class="text-sm font-bold uppercase tracking-wider text-emerald-100/80">Info Akun</h4>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-200/40 font-semibold">User ID</span>
                            <p class="text-sm font-mono font-bold text-emerald-50 mt-0.5">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="border-t border-white/10 pt-4">
                            <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-200/40 font-semibold">Role</span>
                            <p class="text-sm font-bold text-emerald-300 mt-0.5 uppercase">{{ $user->role }}</p>
                        </div>
                        <div class="border-t border-white/10 pt-4">
                            <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-200/40 font-semibold">Status</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(5,150,105,0.6)]"></span>
                                <span class="text-sm font-bold text-emerald-50">Aktif</span>
                            </div>
                        </div>
                        <div class="border-t border-white/10 pt-4">
                            <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-200/40 font-semibold">Bergabung</span>
                            <p class="text-sm font-bold text-emerald-50 mt-0.5">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                        @if($user->email_verified_at)
                        <div class="border-t border-white/10 pt-4">
                            <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-200/40 font-semibold">Email Verifikasi</span>
                            <p class="text-sm font-bold text-emerald-50 mt-0.5">{{ $user->email_verified_at->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== TAB 2: SECURITY ========== --}}
    <div x-show="tab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="max-w-2xl">
                <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
                    {{-- Top header with accent --}}
                    <div class="px-6 py-4 border-b border-white/10 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-emerald-50 text-sm uppercase tracking-wider">Ubah Password</h3>
                        <p class="text-emerald-200/60 text-xs">Pastikan password Anda aman</p>
                    </div>
                </div>

                <form action="{{ route('admin.profile.password') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Password Lama</label>
                        <input type="password" name="current_password" required placeholder="Masukkan password saat ini"
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                        @error('current_password')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Password Baru</label>
                            <input type="password" name="password" required placeholder="Min. 8 karakter"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                            @error('password')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-emerald-200/80 mb-1.5">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required placeholder="Ulangi password baru"
                                class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-lg text-sm text-emerald-50 placeholder-emerald-200/30 focus:outline-none focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400/50 transition-all duration-200">
                        </div>
                    </div>

                    {{-- Password requirements hint --}}
                    <div class="p-3 rounded-lg bg-white/5 border border-white/10">
                        <p class="text-[10px] uppercase tracking-wider text-emerald-200/60 font-semibold mb-2">Persyaratan Password</p>
                        <ul class="space-y-1">
                            <li class="flex items-center gap-2 text-xs text-emerald-200/60">
                                <svg class="w-3 h-3 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Minimal 8 karakter
                            </li>
                            <li class="flex items-center gap-2 text-xs text-emerald-200/60">
                                <svg class="w-3 h-3 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                Kombinasi huruf dan angka
                            </li>
                        </ul>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="px-8 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                            Perbarui Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
