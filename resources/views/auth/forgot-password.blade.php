<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SIP Jamu Madura</title>
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
                    "Jamu bukan sekadar obat, melainkan warisan nenek moyang yang menjaga kesehatan generasi penerus dengan alam."
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
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Lupa Password</h2>
                    <p class="text-gray-700 mt-2 text-sm font-medium">Masukkan email Anda untuk menerima link pemulihan.</p>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="bg-green-50 text-green-600 p-4 rounded-lg text-sm border border-green-100 mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm border border-red-100 mb-6">
                        @if ($errors->has('email'))
                            {{ $errors->first('email') }}
                        @else
                            Terjadi kesalahan. Silakan coba lagi.
                        @endif
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input type="email" id="email" name="email" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus-ring-emerald transition" placeholder="Masukkan email Anda" required autofocus value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-custom hover-bg-emerald-custom focus:outline-none focus-ring-emerald transition duration-150">
                        Kirim Link Pemulihan
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-emerald-custom hover:text-emerald-700 inline-flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
</html>