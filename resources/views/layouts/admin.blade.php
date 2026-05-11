<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Jamu Madura')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
        
        /* Modern Select - Hide default appearance */
        .modern-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4m0 0l-4 4m4-4H2'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
        }
        
        /* Glass Effect Classes - 20% Opacity with Strong Blur */
        .bg-glass { 
            background-color: rgba(255, 255, 255, 0.2); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px); 
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .bg-glass-dark { 
            background-color: rgba(0, 0, 0, 0.2); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px); 
        }
        
        /* Card Hover Effect */
        .glass-card { transition: all 0.3s ease; }
        .glass-card:hover { 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); 
            transform: translateY(-2px); 
            background-color: rgba(255, 255, 255, 0.25);
        }
        
        /* Background Image */
        .bg-admin-wall {
            background-image: url('{{ asset("image/bgadmin.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Fallback to original if bgadmin.png doesn't exist */
        .bg-wallpaper {
            background-image: url('{{ asset("image/rempahwall.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .glass-sidebar {
            background: linear-gradient(to right, rgba(5, 150, 105, 0.8), rgba(255, 255, 255, 0.12));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-topbar {
            background: 
                linear-gradient(to bottom, transparent 40%, rgba(255, 255, 255, 0.25) 100%),
                linear-gradient(to right, rgba(5, 150, 105, 0.05), rgba(5, 150, 105, 0.8));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* Glass Button */
        .btn-glass {
            background-color: rgba(5, 150, 105, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .btn-glass:hover {
            background-color: rgba(5, 150, 105, 1);
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.4);
        }
        
        /* Glass Input */
        .input-glass {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(5, 150, 105, 0.6);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        
        /* Glass Table */
        .glass-table {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-table thead {
            background-color: rgba(5, 150, 105, 0.5);
        }
        .glass-table thead th {
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .glass-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Text Shadow for Glass UI */
        .text-shadow {
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        .text-shadow-sm {
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .text-shadow-md {
            text-shadow: 0 0 4px rgba(5, 150, 105, 0.7), 0 1px 3px rgba(5, 150, 105, 0.4);
        }
        .text-glow-green {
            text-shadow: 0 0 8px rgba(5, 150, 105, 0.6), 0 0 20px rgba(5, 150, 105, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="text-gray-800 antialiased" x-data="{ sidebarOpen: false, userMenu: false, notif: { show: false, type: 'success', message: '' } }"
      x-on:notify.window="notif.show = true; notif.type = $event.detail.type; notif.message = $event.detail.message; setTimeout(() => notif.show = false, 5000);"
      x-init="
        let n = @json(session('success'));
        if (n) { notif.show = true; notif.type = 'success'; notif.message = n; setTimeout(() => notif.show = false, 5000); }
        n = @json(session('error'));
        if (n) { notif.show = true; notif.type = 'error'; notif.message = n; setTimeout(() => notif.show = false, 5000); }
        n = @json(session('warning'));
        if (n) { notif.show = true; notif.type = 'warning'; notif.message = n; setTimeout(() => notif.show = false, 5000); }
        n = @json(session('info'));
        if (n) { notif.show = true; notif.type = 'info'; notif.message = n; setTimeout(() => notif.show = false, 5000); }
      ">
    <!-- Main Background Wrapper -->
    <div class="bg-admin-wall min-h-screen">
        <div class="min-h-screen bg-black/5">
            <div class="flex h-screen overflow-hidden">
                <!-- Sidebar -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 glass-sidebar text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
                    <div class="relative h-full">
                        <canvas id="sidebarStars" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>
                        <div class="relative z-10">
                            <div class="flex items-center justify-center h-16 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-8 h-8 object-contain filter brightness-0 invert">
                                    <span class="text-xl font-bold tracking-wider">HerbTech</span>
                                </div>
                            </div>

                            <nav class="mt-3 px-4 space-y-1">
                                <p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Menu Utama</p>
                                
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2H4V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6H4v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                                    <span class="text-shadow-sm">Dashboard</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Master Data</p></div>
                                
                                <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.products.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <span class="text-shadow-sm">Produk</span>
                                </a>
                                
                                <a href="{{ route('admin.raw-materials.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.raw-materials.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                    <span class="text-shadow-sm">Bahan Baku</span>
                                </a>

                                <a href="{{ route('admin.recipes.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.recipes.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <span class="text-shadow-sm">Resep</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Monitoring</p></div>
                                
                                <a href="{{ route('admin.productions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.productions.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    <span class="text-shadow-sm">Produksi</span>
                                </a>
                                
                                <a href="{{ route('admin.scheduling.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.scheduling.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-shadow-sm">Penjadwalan</span>
                                </a>
                                
                                <a href="{{ route('admin.qc.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.qc.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-shadow-sm">Quality Control</span>
                                </a>
                                
                                <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.reports.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-shadow-sm">Laporan</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Pengaturan</p></div>
                                
                                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.users.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-shadow-sm">Manajemen User</span>
                                </a>
                                
                                <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-white/10 transition text-white {{ request()->routeIs('admin.profile.*') ? 'bg-white/20 text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-shadow-sm">Profil Saya</span>
                                </a>
                            </nav>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Topbar -->
                    <header class="glass-topbar z-20 h-16 relative">
                        <canvas id="navbarStars" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>
                        <div class="relative z-10 flex items-center justify-between h-full px-6">
                            <div class="flex items-center">
                                <button @click="sidebarOpen = !sidebarOpen" class="text-white/80 hover:text-white focus:outline-none lg:hidden">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </button>
                                <h2 class="text-xl font-bold text-white ml-4 lg:ml-5 text-glow-green">@yield('header', 'Dashboard')</h2>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="relative" x-data>
                                    <button @click="userMenu = !userMenu" class="flex items-center space-x-3 focus:outline-none p-2 rounded-xl hover:bg-white/10 transition">
                                        <div class="text-right hidden md:block">
                                            <p class="text-sm font-bold text-white text-shadow-sm">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-emerald-200 font-medium text-shadow-sm">{{ ucfirst(auth()->user()->role) }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-bold shadow-md">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    </button>
                                    
                                    <div x-show="userMenu" @click.away="userMenu = false" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50">
                                        <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- Flash Notification Toast -->
                    <template x-teleport="body">
                        <div x-show="notif.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-8 opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-8 opacity-0" class="fixed top-5 right-5 z-[9999] max-w-md w-full pointer-events-auto" style="display: none;">
                            <div class="rounded-xl shadow-2xl border overflow-hidden backdrop-blur-xl" :class="notif.type === 'success' ? 'bg-emerald-600/95 border-emerald-400/50' : notif.type === 'error' ? 'bg-red-600/95 border-red-400/50' : notif.type === 'warning' ? 'bg-amber-600/95 border-amber-400/50' : 'bg-blue-600/95 border-blue-400/50'">
                                <div class="flex items-start gap-3 p-4">
                                    <template x-if="notif.type === 'success'">
                                        <svg class="w-6 h-6 text-emerald-200 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </template>
                                    <template x-if="notif.type === 'error'">
                                        <svg class="w-6 h-6 text-red-200 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </template>
                                    <template x-if="notif.type === 'warning'">
                                        <svg class="w-6 h-6 text-amber-200 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </template>
                                    <template x-if="notif.type === 'info'">
                                        <svg class="w-6 h-6 text-blue-200 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </template>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-white" x-text="notif.message"></p>
                                    </div>
                                    <button @click="notif.show = false" class="shrink-0 p-1 rounded-lg hover:bg-white/20 transition">
                                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Page Content -->
                    <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 bg-white/5 text-gray-800">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>
    (function() {
        function initConstellation(canvas, opts) {
            if (!canvas) return;
            var ctx = canvas.getContext('2d');
            var particles = [];
            var num = opts.num || 25;
            var maxDist = opts.maxDist || 120;
            var speed = opts.speed || 0.15;
            var opacity = opts.opacity || 0.25;
            var animId;

            function resize() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }

            function init() {
                particles.length = 0;
                var w = canvas.width;
                var h = canvas.height;
                for (var i = 0; i < num; i++) {
                    particles.push({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: (Math.random() - 0.5) * speed,
                        vy: (Math.random() - 0.5) * speed,
                        r: Math.random() * 1.5 + 0.5,
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                var w = canvas.width, h = canvas.height;

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;
                    if (p.x < 0) p.x = w;
                    if (p.x > w) p.x = 0;
                    if (p.y < 0) p.y = h;
                    if (p.y > h) p.y = 0;
                }

                for (var i = 0; i < particles.length; i++) {
                    for (var j = i + 1; j < particles.length; j++) {
                        var dx = particles[i].x - particles[j].x;
                        var dy = particles[i].y - particles[j].y;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < maxDist) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = 'rgba(255,255,255,' + (opacity * (1 - dist / maxDist)) + ')';
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255,255,255,' + (opacity + 0.1) + ')';
                    ctx.fill();
                }

                animId = requestAnimationFrame(draw);
            }

            resize();
            init();
            draw();

            window.addEventListener('resize', function() { resize(); init(); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initConstellation(document.getElementById('sidebarStars'), { num: 22, opacity: 0.2, maxDist: 130 });
            initConstellation(document.getElementById('navbarStars'), { num: 15, opacity: 0.15, maxDist: 110 });
        });
    })();
    </script>
</body>
</html>