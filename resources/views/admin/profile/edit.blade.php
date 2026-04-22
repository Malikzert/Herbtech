@extends('layouts.admin')

@section('title', 'Profil Saya')
@section('header', 'Profil Saya')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Update Profile Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-6">Informasi Profil</h3>
            
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-6">Ubah Password</h3>
            
            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Preview -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
            <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h3 class="font-bold text-gray-800 text-xl">{{ $user->name }}</h3>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                {{ ucfirst($user->role) }}
            </span>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h4 class="font-bold text-gray-800 mb-4">Info Akun</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">ID User</span>
                    <span class="text-gray-800 font-medium">#{{ $user->id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="text-emerald-600 font-medium">Aktif</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Terdaftar</span>
                    <span class="text-gray-800">{{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection