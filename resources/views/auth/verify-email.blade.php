<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SIP Jamu Madura</title>
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
                    "Berkah alam yang diberikan kepada kita, menjaga tubuh dan jiwa dengan kearifan lokal yang telah teruji."
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
                    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Verifikasi Email</h2>
                </div>

                <!-- Info Message -->
                <div class="bg-blue-50 text-blue-600 p-4 rounded-lg text-sm border border-blue-100 mb-6 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-700 font-medium mb-2">Terima kasih telah mendaftar!</p>
                    <p class="text-sm">Harap verifikasi email Anda melalui link yang baru saja kami kirimkan ke alamat email Anda.</p>
                </div>

                <!-- Success Message -->
                @if (session('status'))
                    <div class="bg-green-50 text-green-600 p-4 rounded-lg text-sm border border-green-100 mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Resend Verification Form -->
                <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-custom hover-bg-emerald-custom focus:outline-none focus-ring-emerald transition duration-150">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus-ring-emerald transition duration-150">
                        Logout
                    </button>
                </form>

            </div>
        </div>

    </div>

</body>
</html>