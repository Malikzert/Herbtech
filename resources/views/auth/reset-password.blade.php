@extends('layouts.auth')

@section('title', 'Reset Password - SIP Jamu Madura')

@section('content')
    <div class="text-center mb-10">
        <div class="inline-flex items-center justify-center w-24 h-24 mb-4 p-1 rounded-2xl"
             style="background: var(--accent-light); box-shadow: 0 0 30px var(--accent-glow);">
            <img src="{{ asset('image/logoht.png') }}" alt="HerbTech" class="w-full h-full object-contain brightness-0 invert opacity-90 drop-shadow-md">
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight text-shadow">Perbarui Password</h2>
        <p class="text-white/70 mt-2 text-sm md:text-base">Buat password baru yang kuat untuk akun Anda.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-500/10 backdrop-blur text-red-200 p-4 rounded-lg text-sm border border-red-500/20 mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Email</label>
            <input type="email" value="{{ $email }}" class="input-glass" readonly>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-white/80 mb-2">Password Baru</label>
            <input type="password" id="password" name="password" class="input-glass" placeholder="••••••••" required>
            <p class="mt-1.5 text-xs text-red-300/80 font-mono">WAJIB: min 8 karakter, huruf besar/kecil, angka, dan simbol (@, #, $, dll)</p>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-white/80 mb-2">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input-glass" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-primary w-full flex justify-center py-3.5 px-4 rounded-xl text-base font-semibold text-white focus:outline-none focus-ring-accent">
            Perbarui Password
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="link-back">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>
        </div>
    </form>
@endsection
