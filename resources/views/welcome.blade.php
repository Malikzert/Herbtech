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
        
        .bg-wallpaper {
            background-image: url('{{ asset("image/rempahwall.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Dynamic colors */
        .bg-guest-color { background-color: #2D5A27; }
        .bg-admin-color { background-color: #8B4513; }
        .bg-operator-color { background-color: #1E3A8A; }
    </style>
</head>
<body class="antialiased min-h-screen m-0 p-0 overflow-hidden bg-wallpaper relative flex flex-col">

    <!-- White Translucent Overlay -->
    <div class="absolute inset-0 bg-white/70 backdrop-blur-sm z-0"></div>

    <!-- Transparent Navbar -->
    <header class="relative z-10 w-full p-6 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-10 h-10 object-contain drop-shadow-sm">
            <span class="text-2xl font-extrabold text-gray-800 tracking-tight">HerbTech</span>
        </div>
        <div class="flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-emerald-700 transition">Log in</a>
                <!-- Registration removed as per previous instruction, but structurally kept clean -->
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl shadow-sm transition">Mulai Sekarang</a>
            @else
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2.5 bg-amber-700 hover:bg-amber-800 text-white font-semibold rounded-xl shadow-sm transition">Dasbor Saya</a>
                @else
                    <a href="{{ route('operator.dashboard') }}" class="px-5 py-2.5 bg-blue-800 hover:bg-blue-900 text-white font-semibold rounded-xl shadow-sm transition">Area Produksi</a>
                @endif
            @endguest
        </div>
    </header>

    <!-- Main Content (The Card) -->
    <main class="relative z-10 flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-5xl bg-white/90 backdrop-blur-md rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row border border-gray-100">
            
            <!-- Left Side (Dynamic Content) -->
            @guest
                <div class="w-full md:w-1/2 p-12 flex flex-col justify-center bg-guest-color text-white relative overflow-hidden transition-colors duration-500">
            @else
                <div class="w-full md:w-1/2 p-12 flex flex-col justify-center text-white relative overflow-hidden transition-colors duration-500 {{ Auth::user()->role == 'admin' ? 'bg-admin-color' : 'bg-operator-color' }}">
            @endguest
                
                <!-- Decorative Circle -->
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <!-- Icon Box -->
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm border border-white/30 rounded-2xl flex items-center justify-center mb-8 shadow-inner">
                        <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>

                    @guest
                        <h2 class="text-4xl font-extrabold mb-4 leading-tight tracking-tight">Produksi Tradisional Sepenuh Hati</h2>
                        <p class="text-lg text-white/80 font-medium max-w-sm">
                            Silahkan login untuk mengakses sistem manajemen produksi Jamu Madura.
                        </p>
                    @else
                        <h2 class="text-4xl font-extrabold mb-4 leading-tight tracking-tight">Halo, {{ Auth::user()->name }}!</h2>
                        <p class="text-lg text-white/80 font-medium max-w-sm">
                            Anda sedang login sebagai <span class="font-bold capitalize">{{ Auth::user()->role }}</span>. Silahkan lanjut ke Dashboard untuk mulai bekerja.
                        </p>
                    @endguest
                </div>
            </div>

            <!-- Right Side (Action Area) -->
            <div class="w-full md:w-1/2 p-12 flex flex-col justify-center bg-white relative">
                <div class="text-center max-w-sm mx-auto w-full">
                    <h3 class="text-3xl font-extrabold text-gray-800 mb-2">Selamat Datang</h3>
                    <p class="text-gray-500 mb-8 font-medium">Sistem Informasi Produksi (SIP) Jamu Madura</p>
                    
                    @guest
                        <a href="{{ route('login') }}" class="block w-full py-4 px-6 bg-guest-color hover:opacity-90 text-white font-bold text-lg rounded-2xl shadow-lg transition transform hover:-translate-y-1">
                            Masuk ke Sistem
                        </a>
                    @else
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block w-full py-4 px-6 bg-admin-color hover:opacity-90 text-white font-bold text-lg rounded-2xl shadow-lg transition transform hover:-translate-y-1">
                                Buka Dashboard
                            </a>
                        @else
                            <a href="{{ route('operator.dashboard') }}" class="block w-full py-4 px-6 bg-operator-color hover:opacity-90 text-white font-bold text-lg rounded-2xl shadow-lg transition transform hover:-translate-y-1">
                                Buka Dashboard
                            </a>
                        @endif
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition flex items-center justify-center w-full">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Bukan {{ Auth::user()->name }}? Logout
                                </button>
                            </form>
                        </div>
                    @endguest
                </div>
            </div>
            
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 w-full p-6 text-center text-sm font-semibold text-gray-500">
        © 2026 SIP Jamu Madura. IT Department Team.
    </footer>

</body>
</html>
