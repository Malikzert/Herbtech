@if(session('error'))
<div class="relative overflow-hidden border border-red-500/40 bg-red-900/40 backdrop-blur-md p-4 shadow-[0_4px_24px_rgba(220,38,38,0.15)]" style="border-radius:0;">
    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-red-500/60 via-red-400/30 to-transparent"></div>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-sm font-bold text-red-200 font-mono">{{ session('error') }}</p>
        </div>
        <button onclick="this.closest('.relative').remove()" class="w-6 h-6 flex items-center justify-center text-red-400/40 hover:text-red-300 hover:bg-red-500/15 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="absolute bottom-0 right-0 w-8 h-[2px] bg-red-500/30"></div>
    <div class="absolute bottom-0 right-0 w-[2px] h-8 bg-red-500/30"></div>
</div>
@endif

@if($errors->any())
<div class="relative overflow-hidden border border-red-500/40 bg-red-900/40 backdrop-blur-md p-4 shadow-[0_4px_24px_rgba(220,38,38,0.15)]" style="border-radius:0;">
    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-red-500/60 via-red-400/30 to-transparent"></div>
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div class="flex-1">
            <p class="font-bold text-xs uppercase tracking-wider text-red-300 font-mono">Terjadi kesalahan validasi:</p>
            <ul class="list-disc list-inside text-sm text-red-200/80 mt-1 font-mono">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        <button onclick="this.closest('.relative').remove()" class="w-6 h-6 flex items-center justify-center text-red-400/40 hover:text-red-300 hover:bg-red-500/15 transition-all shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="absolute bottom-0 right-0 w-8 h-[2px] bg-red-500/30"></div>
    <div class="absolute bottom-0 right-0 w-[2px] h-8 bg-red-500/30"></div>
</div>
@endif
