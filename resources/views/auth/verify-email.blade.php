@extends('layouts.auth')

@section('title', 'Verifikasi Email - SIP Jamu Madura')

@section('content')
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-24 h-24 mb-4 p-1 rounded-2xl"
             style="background: var(--accent-light); box-shadow: 0 0 30px var(--accent-glow);">
            <img src="{{ asset('image/logoht.png') }}" alt="HerbTech" class="w-full h-full object-contain brightness-0 invert opacity-90 drop-shadow-md">
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight text-shadow">Verifikasi Email</h2>
    </div>

    <div class="bg-white/10 backdrop-blur text-white/80 p-6 rounded-xl text-sm border border-white/10 mb-6 text-center">
        <svg class="w-12 h-12 mx-auto mb-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        <p class="font-medium mb-2">Terima kasih telah mendaftar!</p>
        <p class="text-white/60">Harap verifikasi email Anda melalui link yang baru saja kami kirimkan ke alamat email Anda.</p>
    </div>

    @if (session('error'))
        <div class="bg-amber-500/10 backdrop-blur text-amber-200 p-4 rounded-lg text-sm border border-amber-500/20 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div>
                    <span class="font-bold block">Perhatian</span>
                    <p class="mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="bg-green-500/10 backdrop-blur text-green-200 p-4 rounded-lg text-sm border border-green-500/20 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary w-full flex justify-center py-3.5 px-4 rounded-xl text-base font-semibold text-white focus:outline-none focus-ring-accent">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-xl text-sm font-medium text-white/70 border border-white/20 hover:bg-white/10 transition">
                Logout
            </button>
        </form>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="link-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>
        </div>
    </div>
@endsection
