<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP Jamu Madura - HerbTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        .bg-welcome {
            background-image: url('{{ asset("image/bgwelcome.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .theme-admin { --theme-color: #059669; --theme-glow: rgba(5,150,105,0.4); --theme-light: rgba(5,150,105,0.12); }
        .theme-user  { --theme-color: #0891b2; --theme-glow: rgba(8,145,178,0.4); --theme-light: rgba(8,145,178,0.12); }
        .theme-guest { --theme-color: #94a3b8; --theme-glow: rgba(148,163,184,0.4); --theme-light: rgba(148,163,184,0.12); }

        .glass-layer {
            position: absolute;
            inset: 0;
            background: linear-gradient(to left, rgba(255,255,255,0.30), rgba(255,255,255,0.10));
            clip-path: url(#waveClip);
            pointer-events: none;
            z-index: 1;
        }

        .wave-accent {
            d: path("M0.96,0 C0.98,0.03 0.93,0.07 0.94,0.10 C0.91,0.15 0.97,0.20 0.96,0.25 C0.99,0.30 0.95,0.34 0.97,0.38 C0.92,0.42 0.95,0.48 0.93,0.55 C0.97,0.60 0.98,0.65 0.95,0.68 C0.90,0.73 0.94,0.78 0.96,0.82 C0.98,0.87 0.93,0.93 0.95,1.0");
            animation: waveD 10s ease-in-out infinite;
        }

        @keyframes waveD {
            0%, 100% {
                d: path("M0.96,0 C0.98,0.03 0.93,0.07 0.94,0.10 C0.91,0.15 0.97,0.20 0.96,0.25 C0.99,0.30 0.95,0.34 0.97,0.38 C0.92,0.42 0.95,0.48 0.93,0.55 C0.97,0.60 0.98,0.65 0.95,0.68 C0.90,0.73 0.94,0.78 0.96,0.82 C0.98,0.87 0.93,0.93 0.95,1.0");
            }
            33% {
                d: path("M0.96,0 C0.97,0.04 0.94,0.08 0.95,0.11 C0.92,0.16 0.98,0.21 0.97,0.26 C0.98,0.31 0.94,0.35 0.95,0.39 C0.93,0.44 0.96,0.49 0.94,0.56 C0.98,0.61 0.97,0.66 0.96,0.69 C0.91,0.74 0.95,0.79 0.95,0.83 C0.97,0.88 0.94,0.94 0.96,1.0");
            }
            66% {
                d: path("M0.96,0 C0.99,0.02 0.92,0.07 0.93,0.10 C0.92,0.14 0.96,0.19 0.95,0.24 C0.97,0.29 0.96,0.33 0.98,0.37 C0.91,0.43 0.94,0.47 0.92,0.54 C0.99,0.59 0.98,0.64 0.96,0.67 C0.92,0.72 0.96,0.77 0.97,0.81 C0.99,0.86 0.92,0.92 0.94,1.0");
            }
        }

        .shimmer-svg {
            position: absolute;
            top: -30%;
            left: 0;
            width: 150%;
            height: 160%;
            pointer-events: none;
            z-index: 2;
            animation: shimmerSweep 18s ease-in-out infinite;
        }

        @keyframes shimmerSweep {
            0%   { transform: translateX(0%); opacity: 0.6; }
            30%  { opacity: 1; }
            60%  { opacity: 1; }
            100% { transform: translateX(-40%); opacity: 0.3; }
        }

        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .btn-primary {
            background: var(--theme-color);
            transition: all 0.3s ease;
            animation: pulseGlow 3s ease-in-out infinite;
        }
        .btn-primary:hover {
            box-shadow: 0 0 40px var(--theme-glow), 0 0 80px var(--theme-glow);
            transform: scale(1.05);
        }
        .btn-primary:active {
            transform: scale(0.97);
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 15px var(--theme-glow), 0 0 30px var(--theme-glow); }
            50% { box-shadow: 0 0 25px var(--theme-glow), 0 0 50px var(--theme-glow), 0 0 70px var(--theme-glow); }
        }

        .btn-outline {
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.3s ease;
        }
        .btn-outline:hover {
            border-color: var(--theme-color);
            background: var(--theme-light);
            box-shadow: 0 0 25px var(--theme-glow);
            transform: scale(1.03);
        }

        .vintage-hd {
            filter: sepia(0.2) contrast(1.08) brightness(0.95);
        }
        .vintage-hd::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 50% 50%, rgba(139,90,43,0.06) 0%, transparent 70%),
                repeating-conic-gradient(rgba(0,0,0,0.008) 0% 25%, transparent 0% 50%) 0 0 / 2px 2px;
            pointer-events: none;
            mix-blend-mode: multiply;
        }

        @media (max-width: 767px) {
            .glass-layer {
                clip-path: none;
            }
            .shimmer-svg { display: none; }
            .wave-accent-container { display: none; }
        }
    </style>
</head>
<body class="antialiased min-h-screen m-0 p-0 overflow-x-hidden bg-welcome">

    @php
        $theme = 'theme-guest';
        $userName = null;
        $userRole = null;

        if (Auth::check()) {
            $userName = Auth::user()->name;
            $userRole = Auth::user()->role;
            if ($userRole === 'admin') {
                $theme = 'theme-admin';
            } else {
                $theme = 'theme-user';
            }
        }
    @endphp

    <svg width="0" height="0" style="position: absolute;">
        <defs>
            <clipPath id="waveClip" clipPathUnits="objectBoundingBox">
                <path d="M0,0 L0.96,0 C0.98,0.03 0.93,0.07 0.94,0.10 C0.91,0.15 0.97,0.20 0.96,0.25 C0.99,0.30 0.95,0.34 0.97,0.38 C0.92,0.42 0.95,0.48 0.93,0.55 C0.97,0.60 0.98,0.65 0.95,0.68 C0.90,0.73 0.94,0.78 0.96,0.82 C0.98,0.87 0.93,0.93 0.95,1.0 L0,1 Z"/>
            </clipPath>
            <filter id="glow">
                <feGaussianBlur stdDeviation="0.008" result="blur"/>
                <feMerge>
                    <feMergeNode in="blur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            <linearGradient id="shimmerFill" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%"   stop-color="transparent" />
                <stop offset="40%"  stop-color="transparent" />
                <stop offset="48%"  stop-color="rgba(255,255,255,0.04)" />
                <stop offset="50%"  stop-color="rgba(255,255,255,0.18)" />
                <stop offset="52%"  stop-color="rgba(255,255,255,0.04)" />
                <stop offset="60%"  stop-color="transparent" />
                <stop offset="100%" stop-color="transparent" />
            </linearGradient>
        </defs>
    </svg>

    <div class="min-h-screen w-full flex flex-col md:flex-row relative {{ $theme }}">

        {{-- LEFT PANEL: Glass with wave clip + content --}}
        <div class="w-full md:w-1/2 min-h-screen relative flex items-center justify-center overflow-hidden">

            <div class="glass-layer"></div>

            {{-- Thin animated wave accent line at boundary --}}
            <svg class="wave-accent-container absolute inset-0 w-full h-full pointer-events-none z-10"
                 viewBox="0 0 1 1" preserveAspectRatio="none"
                 style="overflow: visible;">
                <path class="wave-accent" fill="none" stroke="var(--theme-color)" stroke-width="0.004" opacity="0.8"
                      filter="url(#glow)" />
                <path class="wave-accent" fill="none" stroke="var(--theme-color)" stroke-width="0.001" opacity="0.3" />
            </svg>

            {{-- Wavy shimmer from center to left --}}
            <svg viewBox="0 0 900 1000" preserveAspectRatio="none" class="shimmer-svg">
                <path d="M0,150 Q200,80 400,150 T800,150"
                      stroke="url(#shimmerFill)" stroke-width="10" fill="none" opacity="0.7">
                    <animate attributeName="d" dur="9s" repeatCount="indefinite"
                        values="
                            M0,150 Q200,80 400,150 T800,150;
                            M0,150 Q200,220 400,150 T800,150;
                            M0,150 Q200,100 400,170 T800,130;
                            M0,150 Q200,80 400,150 T800,150;" />
                </path>
                <path d="M0,380 Q200,300 400,380 T800,380"
                      stroke="url(#shimmerFill)" stroke-width="6" fill="none" opacity="0.5">
                    <animate attributeName="d" dur="11s" repeatCount="indefinite"
                        values="
                            M0,380 Q200,300 400,380 T800,380;
                            M0,380 Q200,460 400,380 T800,380;
                            M0,380 Q200,340 400,400 T800,360;
                            M0,380 Q200,300 400,380 T800,380;" />
                </path>
                <path d="M0,620 Q200,700 400,620 T800,620"
                      stroke="url(#shimmerFill)" stroke-width="8" fill="none" opacity="0.6">
                    <animate attributeName="d" dur="7s" repeatCount="indefinite"
                        values="
                            M0,620 Q200,700 400,620 T800,620;
                            M0,620 Q200,540 400,620 T800,620;
                            M0,620 Q200,660 400,600 T800,640;
                            M0,620 Q200,700 400,620 T800,620;" />
                </path>
                <path d="M0,850 Q200,800 400,850 T800,850"
                      stroke="url(#shimmerFill)" stroke-width="5" fill="none" opacity="0.4">
                    <animate attributeName="d" dur="10s" repeatCount="indefinite"
                        values="
                            M0,850 Q200,800 400,850 T800,850;
                            M0,850 Q200,920 400,850 T800,850;
                            M0,850 Q200,830 400,870 T800,830;
                            M0,850 Q200,800 400,850 T800,850;" />
                </path>
            </svg>

            {{-- CONTENT --}}
            <div class="relative z-20 w-full max-w-xl px-10 sm:px-14 py-12">

                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-24 h-24 mb-4 p-1 rounded-2xl"
                         style="background: var(--theme-light); backdrop-filter: blur(4px); box-shadow: 0 0 30px var(--theme-glow);">
                        <img src="{{ asset('image/logoht.png') }}" alt="HerbTech Logo"
                             class="w-full h-full object-contain brightness-0 invert opacity-90"
                             style="filter: drop-shadow(0 8px 20px rgba(0,0,0,0.4));">
                    </div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight drop-shadow-lg">HerbTech</h2>
                    <p class="text-white/70 mt-2 text-sm md:text-base font-medium">Dandani Raga, Rawat Tradisi.</p>
                </div>

                @guest
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-lg mb-2">Selamat Datang</h2>
                        <p class="text-base md:text-lg text-white/70 leading-relaxed">
                            Sistem Informasi Produksi (SIP) Jamu Madura — Kelola produksi jamu tradisional secara digital, transparan, dan efisien.
                        </p>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('login') }}"
                           class="flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl text-base font-bold text-white btn-primary shadow-lg transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                            Masuk ke Sistem
                        </a>
                    </div>
                @else
                    <div class="text-center mb-8">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-white drop-shadow-lg mb-2">Halo, {{ $userName }}!</h2>
                        <p class="text-base text-white/70">
                            Anda login sebagai <span class="font-bold text-white capitalize">{{ $userRole }}</span>.
                        </p>
                    </div>

                    <div class="space-y-3 pt-2">
                        @if($userRole === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl text-base font-bold text-white btn-primary shadow-lg transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                Buka Dashboard Admin
                            </a>
                        @elseif($userRole === 'qc')
                            <a href="{{ route('operator.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl text-base font-bold text-white btn-primary shadow-lg transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Buka Dashboard QC
                            </a>
                        @else
                            <a href="{{ route('operator.dashboard') }}" class="flex items-center justify-center gap-2 w-full py-3.5 px-6 rounded-2xl text-base font-bold text-white btn-primary shadow-lg transition duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Buka Area Produksi
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="flex items-center justify-center gap-2 w-full py-3 px-6 rounded-2xl text-sm font-semibold text-white/60 btn-outline transition duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Bukan {{ $userName }}? Keluar
                            </button>
                        </form>
                    </div>
                @endguest

                <p class="text-xs text-white/40 pt-6 mt-8 border-t border-white/10">
                    &copy; 2026 SIP Jamu Madura &mdash; IT Department Team.
                </p>
            </div>
        </div>

        {{-- RIGHT PANEL: Vintage HD, text directly on background --}}
        <div class="hidden md:flex md:w-1/2 min-h-screen relative flex-col justify-end vintage-hd">

            <div class="p-6 md:p-10 lg:p-14 max-w-2xl ml-auto text-right">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white text-shadow leading-tight"
                    style="-webkit-text-stroke: 0.5px rgba(255,255,255,0.15); text-stroke: 0.5px rgba(255,255,255,0.15);">
                    Produksi Tradisional<br>
                    <span style="color: var(--theme-color); text-shadow: 0 0 40px var(--theme-glow), 2px 2px 4px rgba(0,0,0,0.5); -webkit-text-stroke: 0.5px rgba(255,255,255,0.1); text-stroke: 0.5px rgba(255,255,255,0.1);">Sepenuh Hati</span>
                </h1>
                <p class="text-sm md:text-base text-white/90 text-shadow leading-relaxed mt-3 max-w-lg ml-auto"
                   style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5), 0 0 30px rgba(255,255,255,0.08);">
                    Nikmati mengakses sistem manajemen produksi Jamu Madura. Kelola resep, pantau stok, dan optimasi jadwal produksi dalam satu platform.
                </p>
                <div class="mt-4 md:mt-5 ml-auto w-16 md:w-20 h-1 rounded-full" style="background: var(--theme-color); box-shadow: 0 0 20px var(--theme-glow);"></div>
            </div>
        </div>

    </div>

</body>
</html>
