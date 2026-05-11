<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIP Jamu Madura</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        .bg-login {
            background-image: url('{{ asset("image/bgwelcome.jpg") }}');
            background-size: cover;
            background-position: center;
        }

        :root {
            --accent: #059669;
            --accent-hover: #047857;
            --accent-glow: rgba(5, 150, 105, 0.3);
            --accent-light: rgba(5, 150, 105, 0.12);
        }

        .text-shadow {
            text-shadow: 0 2px 10px rgba(0,0,0,0.45);
        }

        .glass-layer {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(255,255,255,0.40), rgba(255,255,255,0.15));
            -webkit-mask-image: linear-gradient(to right, black 80%, transparent 100%);
            mask-image: linear-gradient(to right, black 80%, transparent 100%);
            pointer-events: none;
        }

        .shimmer-overlay {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }
        .shimmer-overlay::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                90deg,
                transparent 40%,
                rgba(255, 255, 255, 0.018) 45%,
                var(--shimmer-clr, rgba(64, 245, 242, 0.07)) 50%,
                rgba(255, 255, 255, 0.018) 55%,
                transparent 60%
            );
            animation: shimmerSweep 15s linear infinite;
        }
        @keyframes shimmerSweep {
            0%   { transform: translateX(-30%); }
            100% { transform: translateX(30%); }
        }

        .btn-masuk {
            background: var(--accent);
            transition: all 0.3s ease;
        }
        .btn-masuk:hover {
            background: var(--accent-hover);
            box-shadow: 0 0 30px var(--accent-glow);
            transform: scale(1.04);
        }
        .btn-masuk:active {
            transform: scale(0.98);
        }

        .btn-google {
            border: 1px solid rgba(0, 0, 0, 0.12);
            transition: all 0.3s ease;
        }
        .btn-google:hover {
            background: #1f2937;
            border-color: #1f2937;
            color: white;
        }

        .focus-ring-accent:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
    </style>
</head>
<body class="antialiased min-h-screen m-0 p-0 overflow-x-hidden bg-login">

    <div class="min-h-screen w-full flex flex-col md:flex-row">

        {{-- LEFT: 50% Glass Input Area --}}
        <div class="w-full md:w-1/2 min-h-screen relative overflow-hidden flex items-center justify-center">

            <div class="glass-layer"></div>

            <div class="shimmer-overlay" id="shimmerOverlay"></div>

            <div class="relative z-10 w-full max-w-xl px-10 sm:px-14 py-12">

                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-24 h-24 mb-4 p-1 rounded-2xl"
                         style="background: var(--accent-light); backdrop-filter: blur(4px); box-shadow: 0 0 30px var(--accent-glow);">
                        <img src="{{ asset('image/logoht.png') }}" alt="HerbTech Logo" class="w-full h-full object-contain brightness-0 invert opacity-90 drop-shadow-md">
                    </div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight text-shadow">HerbTech</h2>
                    <p class="text-white/70 mt-2 text-sm md:text-base font-medium">Dandani Raga, Rawat Tradisi.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-500/10 backdrop-blur text-red-200 p-4 rounded-lg text-sm border border-red-500/20">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-amber-500/10 backdrop-blur text-amber-200 p-4 rounded-lg text-sm border border-amber-500/20">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <span class="font-bold block">Masalah Autentikasi</span>
                                    <p class="mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-medium text-white/80 mb-2">Email</label>
                        <input type="email" id="email" name="email"
                               class="block w-full px-4 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus-ring-accent transition text-base"
                               placeholder="Masukkan email Anda" required autofocus>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-white/80 mb-2">Password</label>
                        <input type="password" id="password" name="password"
                               class="block w-full px-4 py-3.5 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus-ring-accent transition text-base"
                               placeholder="••••••••" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox"
                                   class="h-4 w-4 rounded border-white/30 bg-white/10 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-white/70 cursor-pointer">Ingat Saya</label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-white/80 hover:text-white transition">Lupa password?</a>
                        </div>
                    </div>

                    <button type="submit" class="btn-masuk w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-base font-semibold text-white focus:outline-none focus-ring-accent">
                        Masuk Sekarang
                    </button>

                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/15"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 text-white/50">Atau masuk dengan</span>
                        </div>
                    </div>

                    <a href="{{ route('google.redirect') }}"
                       class="btn-google w-full flex justify-center py-3.5 px-4 rounded-xl shadow-sm text-base font-medium bg-white/80 text-gray-800 focus:outline-none focus-ring-accent transition">
                        <svg class="h-5 w-5 mr-2.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Google
                    </a>
                </form>
            </div>
        </div>

        {{-- RIGHT: 50% Info — langsung di atas background, tanpa container --}}
        <div class="hidden md:flex md:w-1/2 min-h-screen relative flex-col justify-end">

            <div class="p-6 md:p-10 lg:p-14 max-w-2xl ml-auto text-right">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white text-shadow leading-tight">
                    Produksi Tradisional<br>
                    <span style="color: var(--accent); text-shadow: 0 0 40px var(--accent-glow);">Sepenuh Hati</span>
                </h1>
                <p class="text-sm md:text-base text-white/80 text-shadow leading-relaxed mt-3 max-w-lg ml-auto">
                    Nikmati mengakses sistem manajemen produksi Jamu Madura. Kelola resep, pantau stok, dan optimasi jadwal produksi dalam satu platform.
                </p>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var shimmerEl = document.getElementById('shimmerOverlay');
            if (emailInput && shimmerEl) {
                emailInput.addEventListener('input', function() {
                    var val = this.value.toLowerCase();
                    var color;
                    if (val.includes('admin')) {
                        color = 'rgba(5, 150, 105, 0.08)';
                    } else if (val.includes('operator')) {
                        color = 'rgba(37, 99, 235, 0.08)';
                    } else {
                        color = 'rgba(64, 245, 242, 0.10)';
                    }
                    shimmerEl.style.setProperty('--shimmer-clr', color);
                });
            }
        });
    </script>

</body>
</html>
