@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header', 'PROFIL SAYA')

@section('content')
<div x-data="profileForm()">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-6">
            <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-6 text-center">
                <div class="relative inline-block">
                    <div class="w-28 h-28 mx-auto bg-gradient-to-br from-[#8B6914] via-[#A0845C] to-[#5C4A1E] flex items-center justify-center text-white text-5xl font-black">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-[#8B6914] flex items-center justify-center border-2 border-[#1a1210]">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h2 class="mt-4 text-xl font-bold text-white">{{ $user->name }}</h2>
                <p class="text-sm text-[#6B5740]">{{ $user->email }}</p>
                <div class="mt-4 inline-flex items-center px-3 py-1.5 bg-[#8B6914]/20 border border-[#8B6914]/30">
                    <span class="w-2 h-2 mr-2 bg-[#D4B896] rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-[#D4B896] uppercase tracking-[0.1em]">Operator Produksi</span>
                </div>
                @if($user->employee_id)
                <div class="mt-3 text-sm text-[#6B5740]">
                    <span class="font-medium">NIK:</span> {{ $user->employee_id }}
                </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-4 text-center">
                    <div class="text-2xl font-black text-[#D4B896]">124</div>
                    <div class="text-[10px] text-[#6B5740] mt-1 uppercase tracking-[0.1em] font-bold">Total Batch</div>
                    <div class="text-[10px] text-[#A0845C] font-bold uppercase tracking-[0.1em]">Selesai</div>
                </div>
                <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-4 text-center">
                    <div class="text-2xl font-black text-[#D4B896]">98%</div>
                    <div class="text-[10px] text-[#6B5740] mt-1 uppercase tracking-[0.1em] font-bold">Akurasi QC</div>
                    <div class="text-[10px] text-[#A0845C] font-bold uppercase tracking-[0.1em]">Rate</div>
                </div>
            </div>

            <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 p-5">
                <h3 class="text-[10px] font-bold text-[#D4B896] uppercase tracking-[0.15em] mb-4">Info Akun</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-[#3d2b1f]">
                        <div class="flex items-center text-[#6B5740]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path></svg>
                            <span class="text-sm">ID User</span>
                        </div>
                        <span class="text-sm font-bold text-white">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-[#3d2b1f]">
                        <div class="flex items-center text-[#6B5740]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm">Status</span>
                        </div>
                        <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.1em] bg-[#A0845C]/20 text-[#F5EDE0] border border-[#A0845C]/30">Aktif</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center text-[#6B5740]">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm">Bergabung</span>
                        </div>
                        <span class="text-sm text-[#D4B896]">{{ $user->created_at?->format('d M Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-[#1a1210]/80 backdrop-blur-md border border-[#3d2b1f]/50 overflow-hidden">
                <div class="border-b border-[#3d2b1f]">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-[#D4B896] text-[#D4B896]' : 'border-transparent text-[#6B5740] hover:text-[#A0845C] hover:border-[#A0845C]'" class="w-1/2 py-4 px-6 text-[10px] font-bold uppercase tracking-[0.15em] text-center border-b-2 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </button>
                        <button @click="activeTab = 'security'" :class="activeTab === 'security' ? 'border-[#D4B896] text-[#D4B896]' : 'border-transparent text-[#6B5740] hover:text-[#A0845C] hover:border-[#A0845C]'" class="w-1/2 py-4 px-6 text-[10px] font-bold uppercase tracking-[0.15em] text-center border-b-2 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Keamanan
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <div x-show="activeTab === 'profile'" x-transition>
                        @if(session('success') && session('tab') === 'profile')
                            <div class="mb-6 p-4 bg-[#A0845C]/10 border border-[#A0845C]/30 flex items-center">
                                <svg class="w-5 h-5 text-[#D4B896] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-sm font-medium text-[#D4B896]">{{ session('success') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('operator.profile.update') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Alamat Email</label>
                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Nomor Telepon</label>
                                <div class="relative">
                                    <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-[#6B5740]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-[#8B6914] hover:bg-[#A0845C] text-white font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div x-show="activeTab === 'security'" x-transition x-cloak>
                        @if(session('success') && session('tab') === 'security')
                            <div class="mb-6 p-4 bg-[#A0845C]/10 border border-[#A0845C]/30 flex items-center">
                                <svg class="w-5 h-5 text-[#D4B896] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p class="text-sm font-medium text-[#D4B896]">{{ session('success') }}</p>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-6 p-4 bg-[#3d2b1f] border border-[#8B6914]/30">
                                <ul class="list-disc list-inside text-sm text-[#D4B896]">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('operator.profile.password') }}" method="POST" class="space-y-5" x-data="passwordForm()">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Password Saat Ini</label>
                                <div class="relative">
                                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" required
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all pr-12">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B5740] hover:text-[#D4B896] transition-colors">
                                        <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="showCurrent" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Password Baru</label>
                                <div class="relative">
                                    <input :type="showNew ? 'text' : 'password'" name="password" required minlength="8"
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all pr-12">
                                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B5740] hover:text-[#D4B896] transition-colors">
                                        <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="showNew" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                                <p class="mt-1.5 text-xs text-[#6B5740]">Minimal 8 karakter</p>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-[#6B5740] mb-2">Konfirmasi Password Baru</label>
                                <div class="relative">
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" required
                                        class="w-full px-4 py-3 bg-[#2c1810]/60 border border-[#3d2b1f] text-[#D4B896] placeholder-[#6B5740] focus:outline-none focus:ring-[#8B6914] focus:border-[#8B6914] transition-all pr-12">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-[#6B5740] hover:text-[#D4B896] transition-colors">
                                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="px-6 py-3 bg-[#8B6914] hover:bg-[#A0845C] text-white font-bold transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function profileForm() {
        return {
            activeTab: @if(session('tab')) '{{ session('tab') }}' @else 'profile' @endif
        }
    }

    function passwordForm() {
        return {
            showCurrent: false,
            showNew: false,
            showConfirm: false
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
