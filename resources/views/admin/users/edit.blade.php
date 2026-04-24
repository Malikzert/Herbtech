@extends('layouts.admin')

@section('title', 'Edit User')
@section('header', 'Edit User')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-glass rounded-xl border border-white/50 p-6 shadow-sm glass-card">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full h-11 px-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full h-11 px-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                    <select name="role" required
                        class="modern-select w-full h-11 px-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="operator" {{ $user->role === 'operator' ? 'selected' : '' }}>Operator</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru (opsional)</label>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ubah"
                        class="w-full h-11 px-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                        class="w-full h-11 px-4 bg-white/50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:outline-none transition">
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="flex-1 px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition text-center">
                    Batal
                </a>
                <button type="submit" class="flex-1 px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection