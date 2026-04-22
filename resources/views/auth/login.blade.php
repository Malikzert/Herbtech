<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIP Jamu Madura</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-cream { background-color: #FDFBF7; }
        .bg-emerald-custom { background-color: #2D5A27; }
        .text-emerald-custom { color: #2D5A27; }
        .hover-bg-emerald-custom:hover { background-color: #1e3d1a; }
        .border-emerald-custom { border-color: #2D5A27; }
        .focus-ring-emerald:focus { box-shadow: 0 0 0 2px rgba(45, 90, 39, 0.2); border-color: #2D5A27; }
        
        .bg-wallpaper {
            background-image: url('{{ asset("image/rempahwall.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .overlay {
            background-color: rgba(45, 90, 39, 0.4);
        }
        /* Form transparency 75% = 0.75 */
        .glass-panel {
            background-color: rgba(253, 251, 247, 0.75);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex m-0 p-0 overflow-x-hidden bg-wallpaper relative">
    
    <div class="absolute inset-0 overlay"></div>

    <div class="flex w-full flex-col lg:flex-row min-h-screen relative z-10">
        
        <!-- Right Side (Text Area) -->
        <div class="w-full lg:w-1/2 lg:order-last min-h-[300px] lg:min-h-screen relative flex flex-col justify-end p-8 lg:p-16">
            <div class="relative z-10 text-white mt-auto">
                <h1 class="text-3xl lg:text-5xl font-bold mb-4 leading-tight">Sari Tradisi Madura untuk Kesehatan Anda</h1>
                <p class="text-base lg:text-lg text-gray-100 max-w-lg">
                    Masuk untuk memantau stok simplisia, jadwal produksi, dan laporan Quality Control secara real-time.
                </p>
            </div>
        </div>

        <!-- Left Side (Form Area) -->
        <div class="w-full lg:w-1/2 lg:order-first flex items-center justify-center glass-panel p-8 sm:p-12 lg:p-16 relative">
            <div class="w-full max-w-md">
                
                <!-- Header -->
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-28 h-28 mb-4">
                        <img src="{{ asset('image/logoht.png') }}" alt="HerbTech Logo" class="w-full h-full object-contain drop-shadow-md">
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">HerbTech</h2>
                    <p class="text-gray-700 mt-2 text-sm font-medium">Dandani Raga, Rawat Tradisi.</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm border border-red-100">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-amber-50 text-amber-700 p-4 rounded-lg text-sm border border-amber-200 mb-4 shadow-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <div>
                                    <span class="font-bold block">Masalah Autentikasi</span>
                                    <p class="mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" id="email" name="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus-ring-emerald transition" placeholder="Masukkan email Anda" required autofocus>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input type="password" id="password" name="password" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus-ring-emerald transition" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-emerald-custom focus:ring-emerald-custom border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                Ingat Saya
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="{{ route('password.request') }}" class="font-medium text-emerald-custom hover:text-emerald-700">Lupa password?</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-custom hover-bg-emerald-custom focus:outline-none focus-ring-emerald transition duration-150">
                        Masuk Sekarang
                    </button>

                    <div class="relative mt-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2  text-gray-500">Atau masuk dengan</span>
                        </div>
                    </div>

                    <a href="{{ route('google.redirect') }}" class="mt-6 w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus-ring-emerald transition duration-150">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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

    </div>

</body>
</html>
