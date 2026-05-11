<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Jamu Madura')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }

        .bg-auth {
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

        .vintage-layer::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(139, 90, 43, 0.08);
            mix-blend-mode: multiply;
            pointer-events: none;
        }

        .btn-primary {
            background: var(--accent);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: var(--accent-hover);
            box-shadow: 0 0 30px var(--accent-glow);
            transform: scale(1.04);
        }
        .btn-primary:active {
            transform: scale(0.98);
        }

        .focus-ring-accent:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        .input-glass {
            display: block;
            width: 100%;
            padding: 0.875rem 1rem;
            border-width: 1px;
            border-radius: 0.75rem;
            font-size: 1rem;
            line-height: 1.5rem;
            transition: all 0.15s ease;
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: white;
        }
        .input-glass::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .input-glass:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }
        .input-glass:read-only {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .link-back {
            display: inline-flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.15s ease;
        }
        .link-back:hover {
            color: white;
        }
        .link-back svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.375rem;
        }
    </style>
</head>
<body class="antialiased min-h-screen m-0 p-0 overflow-x-hidden bg-auth">

    <div class="min-h-screen w-full flex flex-col md:flex-row">

        {{-- LEFT: 50% Glass Area --}}
        <div class="w-full md:w-1/2 min-h-screen relative overflow-hidden flex items-center justify-center">

            <div class="glass-layer"></div>

            <div class="shimmer-overlay" id="shimmerOverlay"></div>

            <div class="relative z-10 w-full max-w-xl px-10 sm:px-14 py-12">
                @yield('content')
            </div>
        </div>

        {{-- RIGHT: 50% Vintage Info --}}
        <div class="hidden md:flex md:w-1/2 min-h-screen relative flex-col justify-end overflow-hidden vintage-layer">

            <div class="relative z-10 p-6 md:p-10 lg:p-14 max-w-2xl ml-auto text-right">
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

    @stack('scripts')
</body>
</html>
