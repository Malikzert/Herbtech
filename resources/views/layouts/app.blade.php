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
            background-image: url('{{ asset("image/rempahwall.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .glass-sidebar {
            background-color: rgba(5, 150, 105, 0.95); /* Emerald 600 */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .glass-topbar {
            background-color: rgba(253, 251, 247, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-wallpaper text-gray-800 antialiased relative" x-data="{ sidebarOpen: false }">

    <!-- Global Subtle Overlay -->
    <div class="fixed inset-0 bg-cream opacity-90 backdrop-blur-sm z-0 pointer-events-none"></div>

    <div class="flex h-screen overflow-hidden relative z-10">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 glass-sidebar text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-2xl border-r border-emerald-800/30">
            <div class="flex items-center justify-center h-20 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-8 h-8 object-contain filter brightness-0 invert">
                    <span class="text-xl font-bold tracking-wider">HerbTech</span>
                </div>
            </div>

            <nav class="mt-6 px-4 space-y-2">
                <!-- Role-based Navigation -->
                @if(auth()->check() && auth()->user()->role == 'admin')
                    <!-- ADMIN ROLE -->
                    <div class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2 mt-4">Manajemen</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.productions.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.productions.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Produksi
                    </a>
                    <a href="{{ route('admin.qc.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.qc.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <a href="{{ route('admin.raw-materials.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.raw-materials.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Bahan Baku
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Produk
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Manajemen Akun
                    </a>
                @else
                    <!-- OPERATOR ROLE -->
                    <div class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 mt-4 px-2">Operasional</div>
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('operator.dashboard') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('operator.productions.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('operator.productions.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Input Produksi
                    </a>
                    <a href="{{ route('operator.qc.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('operator.qc.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <div class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 mt-4 px-2">Informasi</div>
                    <a href="{{ route('operator.raw-materials.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('operator.raw-materials.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Monitoring Stok
                    </a>
                    <a href="{{ route('operator.products.index') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('operator.products.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        List Produk
                    </a>
                    <div class="mt-auto pt-4 border-t border-white/10">
                        <div class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-2">Akun</div>
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center px-4 py-2.5 rounded-lg transition {{ request()->routeIs('admin.profile.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                            <svg class="w-5 h-5 mr-3 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2.5 rounded-lg text-red-400 hover:bg-red-500/20 hover:text-red-300 transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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
            <header class="h-20 glass-topbar shadow-sm border-b border-gray-200/50 flex items-center justify-between px-6 z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800 ml-4 lg:ml-0 tracking-tight">@yield('header')</h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-3 focus:outline-none p-2 rounded-xl hover:bg-gray-100/50 transition">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-gray-800 leading-tight">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                                <p class="text-xs text-emerald-600 font-medium">{{ auth()->check() ? ucfirst(auth()->user()->role) : 'Role' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-bold shadow-md border-2 border-white">
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
</body>
</html>
