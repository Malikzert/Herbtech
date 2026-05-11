@extends('layouts.admin')

@section('title', 'Detail User')
@section('header', 'Detail User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-glass rounded-xl border border-white/50 overflow-hidden shadow-sm glass-card">
        <div class="p-6">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    @if($user->role === 'admin')
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full bg-purple-100/60 text-purple-700 border border-purple-200">Admin</span>
                    @else
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full bg-blue-100/60 text-blue-700 border border-blue-200">Operator</span>
                    @endif
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-gray-100/50">
                    <span class="text-gray-500">ID User</span>
                    <span class="font-medium text-gray-800">#{{ $user->id }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100/50">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-800">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100/50">
                    <span class="text-gray-500">Role</span>
                    <span class="font-medium text-gray-800">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-gray-100/50">
                    <span class="text-gray-500">Terdaftar</span>
                    <span class="font-medium text-gray-800">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                    Kembali
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-lg hover:bg-emerald-700 transition">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection