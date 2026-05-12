@extends('layouts.admin')

@section('title', 'Detail User')
@section('header', 'DETAIL USER')

@section('content')
<div class="max-w-2xl">
    <div class="relative overflow-hidden rounded-xl border border-white/20 bg-emerald-900/40 backdrop-blur-md p-6 shadow-[0_8px_32px_rgba(0,0,0,0.25)]">
        <div class="absolute inset-0 pointer-events-none opacity-5" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(5,150,105,0.3) 20px, rgba(5,150,105,0.3) 21px);"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-6 mb-6">
                <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-emerald-500/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-emerald-50 uppercase tracking-wide">{{ $user->name }}</h2>
                    <p class="text-emerald-200/60 text-sm">{{ $user->email }}</p>
                    @if($user->role === 'admin')
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-bold uppercase tracking-wider rounded bg-purple-500/20 text-purple-300 border border-purple-500/30">Admin</span>
                    @else
                    <span class="inline-block mt-2 px-3 py-1 text-xs font-bold uppercase tracking-wider rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Operator</span>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between py-3 border-b border-white/10">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">ID User</span>
                    <span class="text-sm font-medium text-emerald-50">#{{ $user->id }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-white/10">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Email</span>
                    <span class="text-sm font-medium text-emerald-50">{{ $user->email }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-white/10">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Role</span>
                    <span class="text-sm font-medium text-emerald-50">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="flex justify-between py-3 border-b border-white/10">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-200/60">Terdaftar</span>
                    <span class="text-sm font-medium text-emerald-50">{{ $user->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-white/5 border border-white/20 text-emerald-200/70 hover:text-emerald-200 text-sm font-medium rounded-lg hover:bg-white/10 transition">
                    Kembali
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 active:scale-[0.97]">
                    Edit
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
