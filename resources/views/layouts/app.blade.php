<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Jamu Madura')</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AlpineJS for interactive components like sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (Using Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-emerald-custom { background-color: #2D5A27; }
        .text-emerald-custom { color: #2D5A27; }
        .border-emerald-custom { border-color: #2D5A27; }
        .hover-bg-emerald-light:hover { background-color: #3e7b36; }
        .bg-cream { background-color: #FDFBF7; }
        
        .bg-wallpaper {
            background-image: url('{{ asset("image/p.avif") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-color: #0f172a;
        }
        
        .glass-sidebar {
            background-color: rgba(5, 150, 105, 0.95); /* Emerald 600 */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .glass-sidebar-operator {
            background-color: rgba(51, 30, 12, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: background 0.5s ease;
        }
        
        .glass-topbar {
            background: linear-gradient(to right, rgba(51,30,12,0.92), rgba(70,40,20,0.55) 50%, rgba(255,255,255,0.10));
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: background 0.5s ease;
        }

        :root {
            --valo-brown: #2c1810;
            --valo-brown-medium: #3d2b1f;
            --valo-brown-dark: #1a1210;
            --valo-gold: #8B6914;
            --valo-tan: #D4B896;
            --valo-tan-light: #F5EDE0;
            --valo-accent: #A0845C;
        }

        /* ===== WIDGET GRID (Operator Theme) ===== */
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
            padding: 1.25rem;
        }
        .widget-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(61, 43, 31, 0.6);
            background: rgba(26, 18, 16, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            transition: all 0.35s cubic-bezier(0.19,1,0.22,1);
            display: flex;
            flex-direction: column;
        }
        .widget-card:hover {
            transform: translateY(-6px);
            box-shadow: 
                0 0 25px rgba(139, 105, 20, 0.35),
                0 0 60px rgba(139, 105, 20, 0.1),
                0 0 100px rgba(139, 105, 20, 0.04),
                0 12px 48px rgba(0, 0, 0, 0.35);
            border-color: rgba(139, 105, 20, 0.6);
        }
        .widget-card-header {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(44,24,16,0.8), rgba(26,18,16,0.6));
            flex-shrink: 0;
        }
        .widget-card-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.19,1,0.22,1);
        }
        .widget-card:hover .widget-card-header img {
            transform: scale(1.1);
        }
        .widget-card-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.2rem 0.7rem;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            border-radius: 0;
            backdrop-filter: blur(4px);
        }
        .widget-card-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .widget-card-title {
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            line-height: 1.3;
            margin-bottom: 0.15rem;
        }
        .widget-card-subtitle {
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            color: rgba(212,184,150,0.4);
            margin-bottom: 0.75rem;
        }
        .widget-card-details {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-bottom: 0.75rem;
        }
        .widget-card-detail {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.55rem;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border: 1px solid rgba(61, 43, 31, 0.5);
            background: rgba(44, 24, 16, 0.4);
            color: rgba(212,184,150,0.7);
            border-radius: 0;
        }
        .widget-card-detail svg {
            width: 0.7rem;
            height: 0.7rem;
            opacity: 0.6;
        }
        .widget-card-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 0;
        }
        .widget-card-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 0;
        }
        .widget-card-spacer {
            flex: 1;
        }
    </style>
    <script>
        window.adminViewMode = localStorage.getItem('adminViewMode') || 'list';
    </script>
    @stack('styles')
</head>
<body class="bg-wallpaper text-gray-800 antialiased relative" x-data="{ sidebarOpen: false, viewMode: (localStorage.getItem('adminViewMode') || 'list') }"
      x-init="
        $watch('viewMode', val => {
            localStorage.setItem('adminViewMode', val);
            window.adminViewMode = val;
            window.dispatchEvent(new CustomEvent('admin-view-change', { detail: val }));
        });
      ">

    <!-- Global Subtle Overlay -->
    <div class="fixed inset-0 bg-[#0f172a] opacity-10 backdrop-blur-sm z-0 pointer-events-none"></div>

    <div class="flex h-screen overflow-hidden relative z-10">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-56 {{ (auth()->check() && auth()->user()->role == 'operator') ? 'glass-sidebar-operator' : 'glass-sidebar border-emerald-800/30' }} text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-xl">
            <div class="flex items-center justify-center h-14 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-6 h-6 object-contain filter brightness-0 invert">
                    <span class="text-base font-bold tracking-wider">HerbTech</span>
                </div>
            </div>

            <nav class="mt-2 px-3 space-y-1">
                <!-- Role-based Navigation -->
                @if(auth()->check() && auth()->user()->role == 'admin')
                    <!-- ADMIN ROLE -->
                    <div class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1 mt-3">Manajemen</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.productions.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.productions.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Produksi
                    </a>
                    <a href="{{ route('admin.qc.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.qc.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <a href="{{ route('admin.raw-materials.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.raw-materials.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Bahan Baku
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Produk
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Manajemen Akun
                    </a>
                @else
                    <!-- OPERATOR ROLE -->
                    <div class="text-xs font-semibold text-[#D4B896] uppercase tracking-wider mb-1 mt-3 px-2">Operasional</div>
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.dashboard') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('operator.productions.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.productions.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Input Produksi
                    </a>
                    <a href="{{ route('operator.schedules.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.schedules.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Jadwal Produksi
                    </a>
                    <a href="{{ route('operator.qc.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.qc.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <div class="text-xs font-semibold text-[#D4B896] uppercase tracking-wider mb-1 mt-3 px-2">Informasi</div>
                    <a href="{{ route('operator.raw-materials.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.raw-materials.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Monitoring Stok
                    </a>
                    <a href="{{ route('operator.products.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.products.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        List Produk
                    </a>
                    <div class="mt-auto pt-3 border-t border-white/10">
                        <div class="text-xs font-semibold text-[#D4B896] uppercase tracking-wider mb-1 px-2">Akun</div>
                        <a href="{{ route('operator.profile.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.profile.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                            <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-1.5 rounded-lg text-[#D4B896]/60 hover:bg-[#D4B896]/10 hover:text-[#D4B896] transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Topbar -->
            <header class="h-14 glass-topbar shadow-sm border-b border-gray-200/50 flex items-center justify-between px-4 z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white/70 focus:outline-none lg:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-bold text-white ml-3 lg:ml-0 tracking-tight">@yield('header')</h2>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- View Toggle --}}
                    <button @click="viewMode = viewMode === 'list' ? 'widget' : 'list'"
                        class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all duration-200 group"
                        title="Ganti tampilan konten">
                        <svg x-show="viewMode === 'list'" class="w-4 h-4 text-[#D4B896]/60 group-hover:text-[#D4B896] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                        <svg x-show="viewMode === 'widget'" class="w-4 h-4 text-[#D4B896]/60 group-hover:text-[#D4B896] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                        </svg>
                        <div class="relative w-8 h-4 rounded-full transition-colors duration-300" :class="viewMode === 'widget' ? 'bg-[#8B6914]' : 'bg-white/15'">
                            <div x-show="viewMode === 'list'" class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full bg-white/80 shadow-md transition-all"></div>
                            <div x-show="viewMode === 'widget'" class="absolute top-0.5 right-0.5 w-3 h-3 rounded-full bg-white/80 shadow-md transition-all" style="display:none"></div>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-wider text-[#D4B896]/50 group-hover:text-[#D4B896]/80 transition-colors hidden sm:inline">
                            <span x-show="viewMode === 'list'">Daftar</span>
                            <span x-show="viewMode === 'widget'" style="display:none">Widget</span>
                        </span>
                    </button>

                    <div class="w-px h-5 bg-white/10"></div>

                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-2 focus:outline-none p-1.5 rounded-xl hover:bg-gray-100/50 transition">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-white leading-tight">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                                <p class="text-xs {{ (auth()->check() && auth()->user()->role == 'operator') ? 'text-[#D4B896]' : 'text-emerald-200' }} font-medium">{{ auth()->check() ? ucfirst(auth()->user()->role) : 'Role' }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ (auth()->check() && auth()->user()->role == 'operator') ? 'from-[#8B6914] to-[#5C4A1E]' : 'from-emerald-400 to-emerald-600' }} text-white flex items-center justify-center font-bold shadow-md border-2 border-white text-sm">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                            </div>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="userMenu" @click.away="userMenu = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-md rounded-xl shadow-xl py-2 border border-gray-100 z-50" style="display: none;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 font-medium hover:bg-red-50 transition">
                                    Keluar / Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 z-10">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg flex items-center justify-between" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-emerald-600 hover:text-emerald-800 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center justify-between" x-data="{ show: true }" x-show="show">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-600 hover:text-red-800 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
    (function() {
        function drawLeaf(ctx, x, y, size, rot, color, alpha) {
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(rot);
            ctx.scale(size, size);
            ctx.globalAlpha = alpha;

            ctx.shadowColor = 'rgba(139, 105, 20, 0.3)';
            ctx.shadowBlur = 6;

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.bezierCurveTo(4, -3, 8, -2, 10, 0);
            ctx.bezierCurveTo(8, 2, 4, 3, 0, 0);
            ctx.fillStyle = color;
            ctx.fill();

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(8, 0);
            ctx.strokeStyle = 'rgba(255,255,255,0.15)';
            ctx.lineWidth = 0.3;
            ctx.stroke();

            ctx.shadowBlur = 0;
            ctx.restore();
        }

        function initHerbEffect(container, opts) {
            if (!container) return;
            opts = opts || {};

            var canvas = document.createElement('canvas');
            canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:1;';
            container.style.position = 'relative';
            container.insertBefore(canvas, container.firstChild);
            for (var i = 0; i < container.children.length; i++) {
                var c = container.children[i];
                if (c !== canvas) { c.style.position = 'relative'; c.style.zIndex = '2'; }
            }

            var ctx = canvas.getContext('2d');
            var particles = [];
            var num = opts.num || 15;
            var speed = opts.speed || 0.2;
            var animId;

            var colors = [
                'rgba(139,105,20,0.5)',
                'rgba(212,184,150,0.35)',
                'rgba(160,132,92,0.45)',
                'rgba(107,87,64,0.4)',
                'rgba(245,237,224,0.25)',
                'rgba(92,74,30,0.35)',
            ];

            function resize() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }

            function init() {
                particles.length = 0;
                var w = canvas.width, h = canvas.height;
                for (var i = 0; i < num; i++) {
                    particles.push({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: Math.random() * speed + 0.03,
                        baseY: Math.random() * h,
                        amp: Math.random() * 30 + 8,
                        freq: Math.random() * 0.006 + 0.003,
                        size: Math.random() * 1.5 + 0.8,
                        rot: Math.random() * Math.PI * 2,
                        rotSpeed: (Math.random() - 0.5) * 0.02,
                        color: colors[Math.floor(Math.random() * colors.length)],
                        alpha: Math.random() * 0.35 + 0.08,
                        phase: Math.random() * Math.PI * 2,
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                var w = canvas.width, h = canvas.height;

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    p.phase += p.freq;

                    p.x += p.vx;
                    p.y = p.baseY + Math.sin(p.phase) * p.amp;
                    p.rot += p.rotSpeed;

                    if (p.x > w + 30) {
                        p.x = -15;
                        p.baseY = Math.random() * h;
                    }

                    drawLeaf(ctx, p.x, p.y, p.size, p.rot, p.color, p.alpha);
                }

                animId = requestAnimationFrame(draw);
            }

            resize();
            init();
            draw();
            window.addEventListener('resize', function() { resize(); init(); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.querySelector('.glass-sidebar-operator');
            if (sidebar) {
                initHerbEffect(sidebar, { num: 16, speed: 0.2 });
            }

            var navbar = document.querySelector('.glass-topbar');
            if (navbar) {
                initHerbEffect(navbar, { num: 10, speed: 0.12 });
            }
        });
    })();
    </script>
</body>
</html>
