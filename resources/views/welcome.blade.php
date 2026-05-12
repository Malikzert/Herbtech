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

        .ocean-layer {
            position: absolute;
            inset: 0;
            background: linear-gradient(to left,
                rgba(5, 80, 120, 0.30) 0%,
                rgba(0, 130, 165, 0.18) 40%,
                rgba(0, 180, 195, 0.08) 75%,
                rgba(255, 255, 255, 0.03) 100%
            );
            clip-path: url(#waveClip);
            pointer-events: none;
            z-index: 1;
        }

        .ocean-fill-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            filter: blur(10px);
            -webkit-filter: blur(10px);
        }

        .ocean-lines {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
            overflow: visible;
            filter: blur(6px);
            -webkit-filter: blur(6px);
        }

        .shimmer-svg {
            position: absolute;
            top: -30%;
            left: 0;
            width: 150%;
            height: 160%;
            pointer-events: none;
            z-index: 3;
            animation: shimmerSweep 18s ease-in-out infinite;
        }

        @keyframes shimmerSweep {
            0%   { transform: translateX(0%); opacity: 0.4; }
            30%  { opacity: 0.8; }
            60%  { opacity: 0.6; }
            100% { transform: translateX(-40%); opacity: 0.2; }
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
            .ocean-layer {
                clip-path: none;
                background: rgba(255,255,255,0.10);
            }
            .ocean-fill-layer { display: none; }
            .ocean-lines { display: none; }
            .shimmer-svg { display: none; }
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
                <path d="M0,0 L0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0 L0,1 Z"/>
            </clipPath>
            <filter id="foamGlow">
                <feGaussianBlur stdDeviation="0.003" result="blur"/>
                <feMerge>
                    <feMergeNode in="blur"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            <linearGradient id="oceanFill" x1="1" y1="0" x2="0" y2="0">
                <stop offset="0%"   stop-color="rgba(5,100,150,0.25)" />
                <stop offset="60%"  stop-color="rgba(5,100,150,0.10)" />
                <stop offset="100%" stop-color="transparent" />
            </linearGradient>
            <linearGradient id="oceanTeal" x1="1" y1="0" x2="0" y2="0">
                <stop offset="0%"   stop-color="rgba(0,170,190,0.20)" />
                <stop offset="50%"  stop-color="rgba(0,170,190,0.06)" />
                <stop offset="100%" stop-color="transparent" />
            </linearGradient>
            <linearGradient id="sunlightFill" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%"   stop-color="transparent" />
                <stop offset="35%"  stop-color="transparent" />
                <stop offset="45%"  stop-color="rgba(255,255,255,0.03)" />
                <stop offset="50%"  stop-color="rgba(180,230,255,0.15)" />
                <stop offset="55%"  stop-color="rgba(255,255,255,0.03)" />
                <stop offset="65%"  stop-color="transparent" />
                <stop offset="100%" stop-color="transparent" />
            </linearGradient>
        </defs>
    </svg>

    <div class="min-h-screen w-full flex flex-col md:flex-row relative {{ $theme }}">

        {{-- LEFT PANEL: Glass with wave clip + content --}}
        <div class="w-full md:w-1/2 min-h-screen relative flex items-center justify-center overflow-hidden">

            <div class="ocean-layer"></div>

            {{-- Ocean wave fills behind the edge --}}
            <svg class="ocean-fill-layer absolute inset-0 w-full h-full"
                 viewBox="0 0 1 1" preserveAspectRatio="none">
                {{-- Deep ocean fill --}}
                <path fill="url(#oceanFill)" opacity="0.6"
                      d="M0.96,0 L0.5,0 L0.5,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z">
                    <animate attributeName="d" dur="8s" repeatCount="indefinite"
                        values="
                            M0.96,0 L0.5,0 L0.5,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z;
                            M0.96,0 L0.5,0 L0.5,1 L0.95,1 C0.98,0.99 0.92,0.95 0.96,0.91 C0.93,0.86 0.97,0.81 0.94,0.76 C0.95,0.72 0.90,0.67 0.95,0.62 C0.97,0.59 0.96,0.54 0.93,0.49 C0.96,0.44 0.91,0.39 0.95,0.34 C0.94,0.30 0.98,0.26 0.96,0.21 C0.98,0.17 0.92,0.13 0.95,0.09 C0.93,0.05 0.97,0.02 0.96,0 Z;
                            M0.96,0 L0.5,0 L0.5,1 L0.93,1 C0.96,0.97 0.90,0.93 0.94,0.89 C0.91,0.84 0.97,0.79 0.92,0.74 C0.95,0.70 0.88,0.65 0.93,0.60 C0.97,0.57 0.96,0.52 0.91,0.47 C0.94,0.42 0.89,0.37 0.95,0.32 C0.92,0.28 0.98,0.24 0.94,0.19 C0.96,0.15 0.90,0.11 0.93,0.07 C0.91,0.04 0.97,0.01 0.96,0 Z;
                            M0.96,0 L0.5,0 L0.5,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z" />
                </path>
                {{-- Teal wave fill --}}
                <path fill="url(#oceanTeal)" opacity="0.5"
                      d="M0.96,0 L0.7,0 L0.7,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z">
                    <animate attributeName="d" dur="6s" repeatCount="indefinite"
                        values="
                            M0.96,0 L0.7,0 L0.7,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z;
                            M0.96,0 L0.7,0 L0.7,1 L0.95,1 C0.98,0.99 0.92,0.95 0.96,0.91 C0.93,0.86 0.97,0.81 0.94,0.76 C0.95,0.72 0.90,0.67 0.95,0.62 C0.97,0.59 0.96,0.54 0.93,0.49 C0.96,0.44 0.91,0.39 0.95,0.34 C0.94,0.30 0.98,0.26 0.96,0.21 C0.98,0.17 0.92,0.13 0.95,0.09 C0.93,0.05 0.97,0.02 0.96,0 Z;
                            M0.96,0 L0.7,0 L0.7,1 L0.93,1 C0.96,0.97 0.90,0.93 0.94,0.89 C0.91,0.84 0.97,0.79 0.92,0.74 C0.95,0.70 0.88,0.65 0.93,0.60 C0.97,0.57 0.96,0.52 0.91,0.47 C0.94,0.42 0.89,0.37 0.95,0.32 C0.92,0.28 0.98,0.24 0.94,0.19 C0.96,0.15 0.90,0.11 0.93,0.07 C0.91,0.04 0.97,0.01 0.96,0 Z;
                            M0.96,0 L0.7,0 L0.7,1 L0.94,1 C0.97,0.98 0.91,0.94 0.95,0.90 C0.92,0.85 0.98,0.80 0.93,0.75 C0.96,0.71 0.89,0.66 0.94,0.61 C0.98,0.58 0.97,0.53 0.92,0.48 C0.95,0.43 0.90,0.38 0.96,0.33 C0.93,0.29 0.99,0.25 0.95,0.20 C0.97,0.16 0.91,0.12 0.94,0.08 C0.92,0.05 0.98,0.02 0.96,0 Z" />
                </path>
            </svg>

            {{-- Ocean wave lines at boundary --}}
            <svg class="ocean-lines absolute inset-0 w-full h-full"
                 viewBox="0 0 1 1" preserveAspectRatio="none">
                {{-- Deep blue wave --}}
                <path fill="none" stroke="rgba(5,100,155,0.5)" stroke-width="0.005" opacity="0.6"
                      d="M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0">
                    <animate attributeName="d" dur="10s" repeatCount="indefinite"
                        values="
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0;
                            M0.96,0 C0.97,0.02 0.93,0.05 0.95,0.08 C0.92,0.12 0.98,0.16 0.96,0.21 C0.98,0.26 0.94,0.30 0.95,0.34 C0.91,0.39 0.96,0.44 0.93,0.49 C0.98,0.54 0.97,0.59 0.95,0.62 C0.90,0.67 0.95,0.72 0.94,0.76 C0.97,0.81 0.93,0.86 0.96,0.91 C0.92,0.95 0.98,0.99 0.95,1.0;
                            M0.96,0 C0.97,0.01 0.91,0.04 0.93,0.07 C0.90,0.11 0.96,0.15 0.94,0.19 C0.98,0.24 0.92,0.28 0.95,0.32 C0.89,0.37 0.94,0.42 0.91,0.47 C0.96,0.52 0.97,0.57 0.93,0.60 C0.88,0.65 0.95,0.70 0.92,0.74 C0.97,0.79 0.91,0.84 0.94,0.89 C0.90,0.93 0.96,0.97 0.93,1.0;
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0" />
                </path>
                {{-- Teal wave --}}
                <path fill="none" stroke="rgba(0,170,195,0.45)" stroke-width="0.004" opacity="0.7"
                      d="M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0">
                    <animate attributeName="d" dur="7s" repeatCount="indefinite"
                        values="
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0;
                            M0.96,0 C0.99,0.03 0.93,0.06 0.95,0.09 C0.92,0.13 0.98,0.17 0.96,0.22 C0.98,0.27 0.92,0.31 0.95,0.35 C0.91,0.40 0.96,0.45 0.93,0.50 C0.98,0.55 0.97,0.60 0.95,0.63 C0.90,0.68 0.95,0.73 0.92,0.77 C0.97,0.82 0.93,0.87 0.96,0.92 C0.92,0.96 0.98,0.99 0.95,1.0;
                            M0.96,0 C0.97,0.01 0.91,0.04 0.93,0.07 C0.90,0.11 0.96,0.15 0.94,0.19 C0.98,0.24 0.92,0.28 0.95,0.32 C0.89,0.37 0.94,0.42 0.91,0.47 C0.96,0.52 0.97,0.57 0.93,0.60 C0.88,0.65 0.95,0.70 0.92,0.74 C0.97,0.79 0.91,0.84 0.94,0.89 C0.90,0.93 0.96,0.97 0.93,1.0;
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0" />
                </path>
                {{-- Cyan/light wave --}}
                <path fill="none" stroke="rgba(0,200,210,0.35)" stroke-width="0.003" opacity="0.6"
                      d="M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0">
                    <animate attributeName="d" dur="5s" repeatCount="indefinite"
                        values="
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0;
                            M0.96,0 C0.99,0.03 0.93,0.06 0.95,0.09 C0.92,0.13 0.98,0.17 0.96,0.22 C0.98,0.27 0.92,0.31 0.95,0.35 C0.91,0.40 0.96,0.45 0.93,0.50 C0.98,0.55 0.97,0.60 0.95,0.63 C0.90,0.68 0.95,0.73 0.92,0.77 C0.97,0.82 0.93,0.87 0.96,0.92 C0.92,0.96 0.98,0.99 0.95,1.0;
                            M0.96,0 C0.97,0.01 0.91,0.04 0.93,0.07 C0.90,0.11 0.96,0.15 0.94,0.19 C0.98,0.24 0.92,0.28 0.95,0.32 C0.89,0.37 0.94,0.42 0.91,0.47 C0.96,0.52 0.97,0.57 0.93,0.60 C0.88,0.65 0.95,0.70 0.92,0.74 C0.97,0.79 0.91,0.84 0.94,0.89 C0.90,0.93 0.96,0.97 0.93,1.0;
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0" />
                </path>
                {{-- White foam line --}}
                <path fill="none" stroke="rgba(255,255,255,0.5)" stroke-width="0.002" opacity="0.7"
                      filter="url(#foamGlow)"
                      d="M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0">
                    <animate attributeName="d" dur="4s" repeatCount="indefinite"
                        values="
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0;
                            M0.96,0 C0.99,0.03 0.93,0.06 0.95,0.09 C0.92,0.13 0.98,0.17 0.96,0.22 C0.98,0.27 0.92,0.31 0.95,0.35 C0.91,0.40 0.96,0.45 0.93,0.50 C0.98,0.55 0.97,0.60 0.95,0.63 C0.90,0.68 0.95,0.73 0.92,0.77 C0.97,0.82 0.93,0.87 0.96,0.92 C0.92,0.96 0.98,0.99 0.95,1.0;
                            M0.96,0 C0.97,0.01 0.91,0.04 0.93,0.07 C0.90,0.11 0.96,0.15 0.94,0.19 C0.98,0.24 0.92,0.28 0.95,0.32 C0.89,0.37 0.94,0.42 0.91,0.47 C0.96,0.52 0.97,0.57 0.93,0.60 C0.88,0.65 0.95,0.70 0.92,0.74 C0.97,0.79 0.91,0.84 0.94,0.89 C0.90,0.93 0.96,0.97 0.93,1.0;
                            M0.96,0 C0.98,0.02 0.92,0.05 0.94,0.08 C0.91,0.12 0.97,0.16 0.95,0.20 C0.99,0.25 0.93,0.29 0.96,0.33 C0.90,0.38 0.95,0.43 0.92,0.48 C0.97,0.53 0.98,0.58 0.94,0.61 C0.89,0.66 0.96,0.71 0.93,0.75 C0.98,0.80 0.92,0.85 0.95,0.90 C0.91,0.94 0.97,0.98 0.94,1.0" />
                </path>
            </svg>

            {{-- Sunlight shimmer on ocean surface --}}
            <svg viewBox="0 0 900 1000" preserveAspectRatio="none" class="shimmer-svg">
                <path d="M0,150 Q200,80 400,150 T800,150"
                      stroke="url(#sunlightFill)" stroke-width="10" fill="none" opacity="0.6">
                    <animate attributeName="d" dur="9s" repeatCount="indefinite"
                        values="
                            M0,150 Q200,80 400,150 T800,150;
                            M0,150 Q200,220 400,150 T800,150;
                            M0,150 Q200,100 400,170 T800,130;
                            M0,150 Q200,80 400,150 T800,150;" />
                </path>
                <path d="M0,380 Q200,300 400,380 T800,380"
                      stroke="url(#sunlightFill)" stroke-width="6" fill="none" opacity="0.4">
                    <animate attributeName="d" dur="11s" repeatCount="indefinite"
                        values="
                            M0,380 Q200,300 400,380 T800,380;
                            M0,380 Q200,460 400,380 T800,380;
                            M0,380 Q200,340 400,400 T800,360;
                            M0,380 Q200,300 400,380 T800,380;" />
                </path>
                <path d="M0,620 Q200,700 400,620 T800,620"
                      stroke="url(#sunlightFill)" stroke-width="8" fill="none" opacity="0.5">
                    <animate attributeName="d" dur="7s" repeatCount="indefinite"
                        values="
                            M0,620 Q200,700 400,620 T800,620;
                            M0,620 Q200,540 400,620 T800,620;
                            M0,620 Q200,660 400,600 T800,640;
                            M0,620 Q200,700 400,620 T800,620;" />
                </path>
                <path d="M0,850 Q200,800 400,850 T800,850"
                      stroke="url(#sunlightFill)" stroke-width="5" fill="none" opacity="0.3">
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
