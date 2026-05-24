@if(session('success'))
<div class="relative overflow-hidden border border-sky-500/30 bg-sky-500/10 backdrop-blur-md p-4 shadow-[0_4px_24px_rgba(0,0,0,0.2)]" style="border-radius:0;">
    <div class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-sky-500/60 via-sky-400/30 to-transparent"></div>
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-sky-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm font-bold text-sky-200">{{ session('success') }}</p>
        </div>
        <button onclick="this.closest('.relative').remove()" class="w-6 h-6 flex items-center justify-center text-sky-400/40 hover:text-sky-300 hover:bg-sky-500/15 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    <div class="absolute bottom-0 right-0 w-8 h-[2px] bg-sky-500/30"></div>
    <div class="absolute bottom-0 right-0 w-[2px] h-8 bg-sky-500/30"></div>
</div>
@endif
