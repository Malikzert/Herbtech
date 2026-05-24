<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Jamu Madura')</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AlpineJS for interactive components like sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (Using Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-emerald-custom { background-color: #2D5A27; }
        .text-emerald-custom { color: #2D5A27; }
        .border-emerald-custom { border-color: #2D5A27; }
        .hover-bg-emerald-light:hover { background-color: #3e7b36; }
        .bg-cream { background-color: #FDFBF7; }
        
        .bg-wallpaper {
            background-image: white;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-color: #0f172a;
        }
        
        .glass-sidebar {
            background-color: rgba(5, 150, 105, 0.95); /* Emerald 600 */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        .glass-sidebar-operator {
            background-color: rgba(51, 30, 12, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: background 0.5s ease;
        }
        
        .glass-topbar {
            background: linear-gradient(to right, rgba(51,30,12,0.92), rgba(70,40,20,0.55) 50%, rgba(255,255,255,0.10));
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            transition: background 0.5s ease;
        }

        :root {
            --valo-dark: #0f172a;
            --valo-dark-medium: #1e293b;
            --valo-dark-dark: #020617;
            --valo-accent: #1DA1F2;
            --valo-accent-light: #93C5FD;
            --valo-accent-lighter: #DBEAFE;
            --valo-accent: #3B82F6;
        }

        /* ===== WIDGET GRID (Operator Theme) ===== */
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
            padding: 1.25rem;
        }
        .widget-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(30, 58, 138, 0.6);
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            transition: all 0.35s cubic-bezier(0.19,1,0.22,1);
            display: flex;
            flex-direction: column;
        }
        .widget-card:hover {
            transform: translateY(-6px);
            box-shadow: 
                0 0 25px rgba(29, 161, 242, 0.35),
                0 0 60px rgba(29, 161, 242, 0.1),
                0 0 100px rgba(29, 161, 242, 0.04),
                0 12px 48px rgba(0, 0, 0, 0.35);
            border-color: rgba(29, 161, 242, 0.6);
        }
        .widget-card-header {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(44,24,16,0.8), rgba(26,18,16,0.6));
            flex-shrink: 0;
        }
        .widget-card-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.19,1,0.22,1);
        }
        .widget-card:hover .widget-card-header img {
            transform: scale(1.1);
        }
        .widget-card-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.2rem 0.7rem;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            border-radius: 0;
            backdrop-filter: blur(4px);
        }
        .widget-card-body {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .widget-card-title {
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
            line-height: 1.3;
            margin-bottom: 0.15rem;
        }
        .widget-card-subtitle {
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            color: rgba(147,197,253,0.4);
            margin-bottom: 0.75rem;
        }
        .widget-card-details {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-bottom: 0.75rem;
        }
        .widget-card-detail {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.55rem;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border: 1px solid rgba(30, 58, 138, 0.5);
            background: rgba(15, 23, 42, 0.4);
            color: rgba(147,197,253,0.7);
            border-radius: 0;
        }
        .widget-card-detail svg {
            width: 0.7rem;
            height: 0.7rem;
            opacity: 0.6;
        }
        .widget-card-status {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            border-radius: 0;
        }
        .widget-card-status-dot {
            width: 5px;
            height: 5px;
            border-radius: 0;
        }
        .widget-card-spacer {
            flex: 1;
        }

        /* ===== SETTINGS GEAR SPIN ===== */
        @keyframes spinSettings {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .settings-gear-icon {
            animation: spinSettings 0.8s ease-in-out;
        }

        /* ===== THEME-DARK: Malam (hitam + biru) ===== */
        .theme-dark .bg-wallpaper {
            background-color: #000000 !important;
            background-image: none !important;
        }
        .theme-dark .glass-sidebar-operator {
            background-color: rgba(0, 0, 0, 0.95) !important;
        }
        .theme-dark .glass-topbar {
            background: rgba(0, 0, 0, 0.9) !important;
        }
        .theme-dark .glass-topbar h2,
        .theme-dark .glass-topbar p,
        .theme-dark .glass-topbar button:not([class*="bg-"]),
        .theme-dark .glass-topbar div {
            color: #ffffff !important;
        }
        .theme-dark main {
            background: #000000 !important;
            color: #ffffff !important;
        }
        .theme-dark main * {
            color: #ffffff !important;
        }
        .theme-dark .widget-card {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(29, 161, 242, 0.3) !important;
        }
        .theme-dark .widget-card-title {
            color: #ffffff !important;
        }
        .theme-dark .widget-card-subtitle {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .theme-dark .widget-card-detail {
            color: rgba(255, 255, 255, 0.6) !important;
            border-color: rgba(29, 161, 242, 0.3) !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }
        .theme-dark .fixed.inset-0.bg-\[\#0f172a\] {
            background: #000000 !important;
            opacity: 0.3 !important;
        }

        /* Dark mode - tabel biru tua + teks putih */
        .theme-dark main table thead {
            background: #0f172a !important;
            border-bottom: 1px solid rgba(29, 161, 242, 0.2) !important;
        }
        .theme-dark main table thead th span,
        .theme-dark main table thead th {
            color: #93C5FD !important;
        }
        .theme-dark main table tbody tr {
            border-bottom: 1px solid rgba(29, 161, 242, 0.1) !important;
        }
        .theme-dark main table tbody tr:hover {
            background: rgba(29, 161, 242, 0.08) !important;
        }
        .theme-dark main table tbody td {
            color: #ffffff !important;
        }
        .theme-dark .glass-sidebar-operator {
            background-color: rgba(0, 0, 0, 0.95) !important;
        }
        .theme-dark .glass-topbar {
            background: rgba(0, 0, 0, 0.9) !important;
        }
        .theme-dark .glass-topbar h2,
        .theme-dark .glass-topbar p,
        .theme-dark .glass-topbar button:not([class*="bg-"]) {
            color: #ffffff !important;
        }
        .theme-dark main {
            background: #000000 !important;
            color: #ffffff !important;
        }
        .theme-dark main * {
            color: #ffffff !important;
        }
        .theme-dark .widget-card {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(29, 161, 242, 0.3) !important;
        }
        .theme-dark .widget-card-title {
            color: #ffffff !important;
        }
        .theme-dark .widget-card-subtitle {
            color: rgba(255, 255, 255, 0.5) !important;
        }
        .theme-dark .widget-card-detail {
            color: rgba(255, 255, 255, 0.6) !important;
            border-color: rgba(29, 161, 242, 0.3) !important;
            background: rgba(0, 0, 0, 0.3) !important;
        }
        .theme-dark .fixed.inset-0.bg-\[\#0f172a\] {
            background: #000000 !important;
            opacity: 0.3 !important;
        }

        /* Dark mode - tabel biru tua + teks putih */
        .theme-dark main table thead {
            background: #0f172a !important;
            border-bottom: 1px solid rgba(29, 161, 242, 0.2) !important;
        }
        .theme-dark main table thead th span,
        .theme-dark main table thead th {
            color: #93C5FD !important;
        }
        .theme-dark main table tbody tr {
            border-bottom: 1px solid rgba(29, 161, 242, 0.1) !important;
        }
        .theme-dark main table tbody tr:hover {
            background: rgba(29, 161, 242, 0.08) !important;
        }
        .theme-dark main table tbody td {
            color: #ffffff !important;
        }

        /* Light mode containers: putih + biru twitter */
        .theme-light .bg-wallpaper {
            background-image: none !important;
            background-color: #ffffff !important;
        }
        .theme-light .fixed.inset-0.bg-\[\#0f172a\] {
            background: transparent !important;
        }
        .theme-light .glass-sidebar-operator {
            background-color: #ffffff !important;
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(29, 161, 242, 0.15) !important;
        }
        .theme-light .glass-sidebar-operator img {
            filter: none !important;
        }
        .theme-light .glass-sidebar-operator span,
        .theme-light .glass-sidebar-operator a,
        .theme-light .glass-sidebar-operator div {
            color: #000000 !important;
        }
        .theme-light .glass-sidebar-operator .text-\[#93C5FD\] {
            color: #1DA1F2 !important;
        }
        .theme-light .glass-sidebar-operator .hover\:bg-white\/15:hover {
            background-color: rgba(29, 161, 242, 0.08) !important;
        }
        .theme-light .glass-sidebar-operator .bg-white\/20 {
            background-color: rgba(29, 161, 242, 0.15) !important;
        }
        .theme-light .glass-topbar {
            background: #ffffff !important;
            border-bottom: 1px solid rgba(29, 161, 242, 0.12) !important;
        }
        .theme-light .glass-topbar h2 {
            color: #1a8cd8 !important;
        }
        .theme-light .glass-topbar button svg {
            color: rgba(29, 161, 242, 0.6) !important;
        }
        .theme-light .glass-topbar button:hover svg {
            color: #1a8cd8 !important;
        }
        .theme-light .glass-topbar p.text-white,
        .theme-light .glass-topbar p.text-sm,
        .theme-light .glass-topbar p.text-xs {
            color: #000000 !important;
        }
        .theme-light .glass-topbar .text-\[#93C5FD\] {
            color: #1DA1F2 !important;
        }
        .theme-light .glass-topbar .text-\[#93C5FD\]\/60 {
            color: rgba(29, 161, 242, 0.6) !important;
        }
        .theme-light .glass-topbar .text-\[#93C5FD\]\/50 {
            color: rgba(29, 161, 242, 0.5) !important;
        }
        .theme-light .glass-topbar .hover\:bg-white\/5:hover {
            background-color: rgba(29, 161, 242, 0.06) !important;
        }
        .theme-light .glass-topbar .hover\:bg-gray-100\/50:hover {
            background-color: rgba(29, 161, 242, 0.06) !important;
        }
        .theme-light main {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .theme-light main * {
            color: #000000 !important;
        }
        .theme-light main .btn-glass,
        .theme-light main .btn-glass * {
            color: #ffffff !important;
        }
        .theme-light .widget-card {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: rgba(29, 161, 242, 0.2) !important;
        }
        .theme-light .widget-card:hover {
            background: #ffffff !important;
            border-color: rgba(29, 161, 242, 0.4) !important;
            box-shadow: 0 0 25px rgba(29, 161, 242, 0.15) !important;
        }
        .theme-light .widget-card-title {
            color: #000000 !important;
        }
        .theme-light .widget-card-subtitle {
            color: rgba(0, 0, 0, 0.4) !important;
        }
        .theme-light .widget-card-detail {
            color: rgba(0, 0, 0, 0.6) !important;
            border-color: rgba(29, 161, 242, 0.2) !important;
            background: rgba(29, 161, 242, 0.05) !important;
        }
        .theme-light .text-shadow,
        .theme-light .text-shadow-sm {
            text-shadow: none !important;
        }
        /* Light mode - tabel biru muda + teks gelap */
        .theme-light [class*="bg-[#0f172a]/80"],
        .theme-light [class*="bg-[#0f172a]"].overflow-hidden {
            background: #ffffff !important;
            border-color: rgba(29, 161, 242, 0.15) !important;
        }
        .theme-light [class*="bg-[#1e293b]/"] {
            background: #ffffff !important;
            border-color: rgba(29, 161, 242, 0.15) !important;
        }
        .theme-light option[class*="bg-[#0f172a]"] {
            background: #ffffff !important;
        }
        .theme-light main table thead {
            background: #DBEAFE !important;
            border-bottom: 1px solid rgba(29, 161, 242, 0.2) !important;
        }
        .theme-light thead th {
            color: #1DA1F2 !important;
        }
        .theme-light thead th span {
            color: #1DA1F2 !important;
        }
        .theme-light main table tbody tr:hover {
            background: rgba(219, 234, 254, 0.5) !important;
        }
        .theme-light main table tbody td {
            color: #000000 !important;
        }
        .theme-light .btn-glass {
            background: #1DA1F2 !important;
            color: #ffffff !important;
        }
        .theme-light .btn-glass:hover {
            background: #1a8cd8 !important;
            box-shadow: 0 4px 20px rgba(29, 161, 242, 0.3) !important;
        }
        .theme-light .active\:bg-\[\#1DA1F2\] {
            background-color: #1DA1F2 !important;
        }

        /* ===== VALORANT NOTIFICATION (Operator) ===== */
        .v-backdrop {
            position: fixed; inset: 0; z-index: 99999;
            background: rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(16px) saturate(0.6);
            -webkit-backdrop-filter: blur(16px) saturate(0.6);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }
        .v-box {
            position: relative; min-width: 480px; padding: 3rem 4rem;
            background: rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-align: center; overflow: hidden;
        }
        .v-box.created { border: 2px solid rgba(0,255,65,0.5); }
        .v-box.updated { border: 2px solid rgba(56,189,248,0.5); }
        .v-box.deleted { border: 2px solid rgba(255,0,64,0.5); }

        .v-bar { position: absolute; left: 0; right: 0; height: 3px; background: currentColor; }
        .v-bar--t { top: 0; transform-origin: right; }
        .v-bar--b { bottom: 0; transform-origin: left; }
        .v-box.show .v-bar { animation: vBarIn 0.55s cubic-bezier(0.19,1,0.22,1) forwards; }
        .v-box.show .v-bar--b { animation-delay: 0.1s; }
        @keyframes vBarIn { 0% { transform: scaleX(0); } 100% { transform: scaleX(1); } }

        .v-cnr { position: absolute; width: 14px; height: 14px; border-color: currentColor; }
        .v-cnr--tl { top: -2px; left: -2px; border-width: 2px 0 0 2px; border-style: solid; }
        .v-cnr--tr { top: -2px; right: -2px; border-width: 2px 2px 0 0; border-style: solid; }
        .v-cnr--bl { bottom: -2px; left: -2px; border-width: 0 0 2px 2px; border-style: solid; }
        .v-cnr--br { bottom: -2px; right: -2px; border-width: 0 2px 2px 0; border-style: solid; }

        .v-scroll { position: absolute; inset: 0; pointer-events: none;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.025) 2px, rgba(255,255,255,0.025) 4px); }
        .v-icon { width: 3rem; height: 3rem; margin: 0 auto 1rem; }
        .v-msg { font-size: 1.5rem; font-weight: 900; letter-spacing: 0.12em; text-transform: uppercase; color: #fff; line-height: 1.3; }
        .v-sub { margin-top: 0.75rem; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.35em; text-transform: uppercase; opacity: 0.45; }

        .v-box.created .v-bar, .v-box.created .v-cnr { color: #00ff41; }
        .v-box.created .v-sub { color: #00ff41; }
        .v-box.updated .v-bar, .v-box.updated .v-cnr { color: #38bdf8; }
        .v-box.updated .v-sub { color: #38bdf8; }
        .v-box.deleted .v-bar, .v-box.deleted .v-cnr { color: #ff0040; }
        .v-box.deleted .v-sub { color: #ff0040; }

        @keyframes glitchG { 0%,100%{text-shadow:none} 4%{text-shadow:-2px 0 #00ff41,2px 0 #f0f;clip-path:inset(40% 0 0 0)} 8%{clip-path:inset(20% 0 60% 0)} 12%{clip-path:inset(60% 0 10% 0)} 16%{clip-path:inset(0 0 80% 0)} 20%{text-shadow:2px 0 #00ff41,-2px 0 #f0f;clip-path:inset(30% 0 30% 0)} 24%{clip-path:inset(10% 0 70% 0)} 28%{text-shadow:none;clip-path:inset(0 0 0 0)} }
        @keyframes glitchB { 0%,100%{text-shadow:none} 4%{text-shadow:-2px 0 #38bdf8,2px 0 #f0f;clip-path:inset(40% 0 0 0)} 8%{clip-path:inset(20% 0 60% 0)} 12%{clip-path:inset(60% 0 10% 0)} 16%{clip-path:inset(0 0 80% 0)} 20%{text-shadow:2px 0 #38bdf8,-2px 0 #f0f;clip-path:inset(30% 0 30% 0)} 24%{clip-path:inset(10% 0 70% 0)} 28%{text-shadow:none;clip-path:inset(0 0 0 0)} }
        @keyframes glitchR { 0%,100%{text-shadow:none} 3%{text-shadow:-3px 0 #ff0040,3px 0 #0ff;clip-path:inset(60% 0 0 0)} 6%{clip-path:inset(10% 0 70% 0)} 9%{clip-path:inset(40% 0 30% 0)} 12%{clip-path:inset(0 0 80% 0)} 15%{text-shadow:3px 0 #ff0040,-3px 0 #0ff;clip-path:inset(20% 0 50% 0)} 18%{clip-path:inset(70% 0 0 0)} 21%{clip-path:inset(0 0 40% 0)} 24%{text-shadow:none;clip-path:inset(0 0 0 0)} }

        .v-glitch.created .v-msg { animation: glitchG 1.4s ease forwards; }
        .v-glitch.updated .v-msg { animation: glitchB 1.4s ease forwards; }
        .v-glitch.deleted .v-msg { animation: glitchR 2s ease forwards; }
    </style>
    @php
        $__flash = session()->only(['success','error','warning','info']);
        $__flash['_flavor'] = '';
        if ($__flash['success'] ?? null) {
            $m = $__flash['success'];
            if (str_contains($m, 'dibuat') || str_contains($m, 'ditambahkan') || str_contains($m, 'disimpan') || str_contains($m, 'dimulai')) $__flash['_flavor'] = 'created';
            elseif (str_contains($m, 'diperbarui') || str_contains($m, 'disetujui')) $__flash['_flavor'] = 'updated';
            elseif (str_contains($m, 'dihapus')) $__flash['_flavor'] = 'deleted';
            else $__flash['_flavor'] = 'created';
        } elseif ($__flash['error'] ?? null) {
            $__flash['_flavor'] = 'deleted';
        } elseif ($__flash['warning'] ?? null) {
            $__flash['_flavor'] = 'updated';
        } elseif ($__flash['info'] ?? null) {
            $__flash['_flavor'] = 'created';
        }
    @endphp
    <script>window.__flash=@json($__flash);</script>
    <script>
        window.adminViewMode = localStorage.getItem('adminViewMode') || 'list';
    </script>
    @stack('styles')
</head>
<body :class="darkMode ? 'theme-dark' : 'theme-light'" class="bg-wallpaper text-gray-800 antialiased relative" x-data="{ sidebarOpen: false, viewMode: (localStorage.getItem('adminViewMode') || 'list'), darkMode: localStorage.getItem('operatorDarkMode') !== 'false', notif: { show: false, flavor: 'created', message: '' } }"
      x-on:notify.window="notif.show = true; notif.flavor = $event.detail.flavor || 'created'; notif.message = $event.detail.message; setTimeout(function(){ notif.show = false; }, 5000);"
      x-init="
        $watch('viewMode', val => {
            localStorage.setItem('adminViewMode', val);
            window.adminViewMode = val;
            window.dispatchEvent(new CustomEvent('admin-view-change', { detail: val }));
        });
        $watch('darkMode', val => localStorage.setItem('operatorDarkMode', val));
        (function() {
            var f = window.__flash || {};
            if (f.success || f.error || f.warning || f.info) {
                notif.flavor = f._flavor || 'created';
                notif.message = f.success || f.error || f.warning || f.info;
                notif.show = true;
                setTimeout(function(){ notif.show = false; }, 5000);
            }
        })();
      ">

    <!-- Global Subtle Overlay -->
    <div class="fixed inset-0 bg-[#0f172a] opacity-10 backdrop-blur-sm z-0 pointer-events-none"></div>

    <div class="flex h-screen overflow-hidden relative z-10">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-56 {{ (auth()->check() && auth()->user()->role == 'operator') ? 'glass-sidebar-operator' : 'glass-sidebar border-emerald-800/30' }} text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-xl">
            <div class="flex items-center justify-center h-14 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-6 h-6 object-contain filter brightness-0 invert">
                    <span class="text-base font-bold tracking-wider">HerbTech</span>
                </div>
            </div>

            <nav class="mt-2 px-3 space-y-1">
                <!-- Role-based Navigation -->
                @if(auth()->check() && auth()->user()->role == 'admin')
                    <!-- ADMIN ROLE -->
                    <div class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-1 mt-3">Manajemen</div>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.productions.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.productions.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Produksi
                    </a>
                    <a href="{{ route('admin.qc.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.qc.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <a href="{{ route('admin.raw-materials.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.raw-materials.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Bahan Baku
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.products.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        Produk
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.reports.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20 font-bold' : 'hover:bg-white hover:bg-opacity-10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Manajemen Akun
                    </a>
                @else
                    <!-- OPERATOR ROLE -->
                    <div class="text-xs font-semibold text-[#93C5FD] uppercase tracking-wider mb-1 mt-3 px-2">Operasional</div>
                    <a href="{{ route('operator.dashboard') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.dashboard') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('operator.productions.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.productions.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Input Produksi
                    </a>
                    <a href="{{ route('operator.schedules.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.schedules.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Jadwal Produksi
                    </a>
                    <a href="{{ route('operator.qc.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.qc.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Quality Control
                    </a>
                    <div class="text-xs font-semibold text-[#93C5FD] uppercase tracking-wider mb-1 mt-3 px-2">Informasi</div>
                    <a href="{{ route('operator.raw-materials.qc.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.raw-materials.qc.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        QC Bahan Baku
                    </a>
                    <a href="{{ route('operator.raw-materials.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.raw-materials.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        Monitoring Stok
                    </a>
                    <a href="{{ route('operator.products.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.products.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                        <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        List Produk
                    </a>
                    <div class="mt-auto pt-3 border-t border-white/10">
                        <div class="text-xs font-semibold text-[#93C5FD] uppercase tracking-wider mb-1 px-2">Akun</div>
                        <a href="{{ route('operator.profile.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition {{ request()->routeIs('operator.profile.*') ? 'bg-white/20 font-bold' : 'hover:bg-white/15' }}">
                            <svg class="w-4 h-4 mr-2 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-3 py-1.5 rounded-lg text-[#93C5FD]/60 hover:bg-[#93C5FD]/10 hover:text-[#93C5FD] transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Topbar -->
            <header class="h-14 glass-topbar shadow-sm border-b border-gray-200/50 flex items-center justify-between px-4 z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white/70 focus:outline-none lg:hidden">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h2 class="text-lg font-bold text-white ml-3 lg:ml-0 tracking-tight">@yield('header')</h2>
                </div>
                
                <div class="flex items-center gap-3">
                    {{-- Settings Gear with Dropdown --}}
                    <div class="relative" x-data="{ settingsOpen: false }">
                        <button @click="settingsOpen = !settingsOpen"
                            class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-white/5 transition-all duration-200 group"
                            title="Pengaturan Tampilan">
                            <svg class="w-4 h-4 text-[#93C5FD]/60 group-hover:text-[#93C5FD] transition-colors settings-gear-icon"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>

                        <div x-show="settingsOpen" @click.away="settingsOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                            class="absolute right-0 mt-2 w-56 rounded-xl border border-[#1DA1F2]/25 bg-white/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.15)] z-50 overflow-hidden">
                            <div class="h-[2px] bg-gradient-to-r from-[#1DA1F2]/60 via-[#93C5FD]/30 to-transparent"></div>
                            <div class="px-4 py-3 border-b border-[#1DA1F2]/10">
                                <p class="text-sm font-bold text-[#1a8cd8]">Pengaturan Tampilan</p>
                            </div>
                            <div class="py-1 space-y-0.5">
                                <button @click="viewMode = viewMode === 'list' ? 'widget' : 'list'"
                                    class="flex items-center justify-between w-full px-4 py-2 text-sm text-[#1a8cd8]/70 hover:text-[#1a8cd8] hover:bg-[#1DA1F2]/5 transition-all duration-150">
                                    <span class="flex items-center gap-2.5">
                                        <svg x-show="viewMode === 'list'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                        </svg>
                                        <svg x-show="viewMode === 'widget'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                        </svg>
                                        <span class="font-medium">Tampilan Konten</span>
                                    </span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-[#1DA1F2]/60">
                                        <span x-show="viewMode === 'list'">Daftar</span>
                                        <span x-show="viewMode === 'widget'" style="display:none">Widget</span>
                                    </span>
                                </button>
                                <button @click="darkMode = !darkMode"
                                    class="flex items-center justify-between w-full px-4 py-2 text-sm text-[#1a8cd8]/70 hover:text-[#1a8cd8] hover:bg-[#1DA1F2]/5 transition-all duration-150">
                                    <span class="flex items-center gap-2.5">
                                        <svg x-show="darkMode" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                        </svg>
                                        <svg x-show="!darkMode" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <span class="font-medium">Mode Tampilan</span>
                                    </span>
                                    <span class="text-[9px] font-bold uppercase tracking-wider" :class="darkMode ? 'text-[#1DA1F2]' : 'text-[#1DA1F2]'">
                                        <span x-show="darkMode">Gelap</span>
                                        <span x-show="!darkMode" style="display:none">Cerah</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="w-px h-5 bg-white/10"></div>

                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" class="flex items-center space-x-2 focus:outline-none p-1.5 rounded-xl hover:bg-gray-100/50 transition">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-white leading-tight">{{ auth()->check() ? auth()->user()->name : 'User' }}</p>
                                <p class="text-xs {{ (auth()->check() && auth()->user()->role == 'operator') ? 'text-[#93C5FD]' : 'text-emerald-200' }} font-medium">{{ auth()->check() ? ucfirst(auth()->user()->role) : 'Role' }}</p>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br {{ (auth()->check() && auth()->user()->role == 'operator') ? 'from-[#1DA1F2] to-[#1a8cd8]' : 'from-emerald-400 to-emerald-600' }} text-white flex items-center justify-center font-bold shadow-md border-2 border-white text-sm">
                                {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'U' }}
                            </div>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="userMenu" @click.away="userMenu = false" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-md rounded-xl shadow-xl py-2 border border-gray-100 z-50" style="display: none;">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 font-medium hover:bg-red-50 transition">
                                    Keluar / Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ===== VALORANT NOTIFICATION ===== -->
            <div x-show="notif.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="v-backdrop" @click="notif.show = false" style="display: none;">
                <div class="v-box" :class="notif.flavor + (notif.show ? ' show' : '')">
                    <div class="v-bar v-bar--t"></div>
                    <div class="v-bar v-bar--b"></div>
                    <div class="v-cnr v-cnr--tl"></div>
                    <div class="v-cnr v-cnr--tr"></div>
                    <div class="v-cnr v-cnr--bl"></div>
                    <div class="v-cnr v-cnr--br"></div>
                    <div class="v-scroll"></div>
                    <div :class="'v-glitch ' + notif.flavor">
                        <svg class="v-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             x-show="notif.flavor === 'created'" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <svg class="v-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             x-show="notif.flavor === 'updated'" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <svg class="v-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             x-show="notif.flavor === 'deleted'" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <p class="v-msg" x-text="notif.message"></p>
                        <p class="v-sub" x-text="notif.flavor === 'created' ? 'BERHASIL' : (notif.flavor === 'updated' ? 'TERPERBARUI' : 'TERHAPUS')"></p>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 z-10">
                {{-- Flash & Validation Alerts --}}
                <div class="space-y-4 mb-6">
                    <x-alert-success />
                    <x-alert-error />
                    <x-alert-warning />
                </div>
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
    (function() {
        function drawLeaf(ctx, x, y, size, rot, color, alpha, dark) {
            ctx.save();
            ctx.translate(x, y);
            ctx.rotate(rot);
            ctx.scale(size, size);
            ctx.globalAlpha = alpha;

            if (dark) {
                ctx.shadowColor = 'rgba(29, 161, 242, 0.3)';
                ctx.shadowBlur = 6;
            } else {
                ctx.shadowColor = 'rgba(29, 161, 242, 0.35)';
                ctx.shadowBlur = 5;
            }

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.bezierCurveTo(4, -3, 8, -2, 10, 0);
            ctx.bezierCurveTo(8, 2, 4, 3, 0, 0);
            ctx.fillStyle = color;
            ctx.fill();

            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(8, 0);
            ctx.strokeStyle = dark ? 'rgba(255,255,255,0.15)' : 'rgba(29,161,242,0.4)';
            ctx.lineWidth = 0.3;
            ctx.stroke();

            ctx.shadowBlur = 0;
            ctx.restore();
        }

        function initHerbEffect(container, opts) {
            if (!container) return;
            opts = opts || {};

            var canvas = document.createElement('canvas');
            canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:1;';
            container.style.position = 'relative';
            container.insertBefore(canvas, container.firstChild);
            for (var i = 0; i < container.children.length; i++) {
                var c = container.children[i];
                if (c !== canvas) { c.style.position = 'relative'; c.style.zIndex = '2'; }
            }

            var ctx = canvas.getContext('2d');
            var particles = [];
            var num = opts.num || 15;
            var speed = opts.speed || 0.2;
            var animId;

            var colors = [
                'rgba(29,161,242,0.5)',
                'rgba(147,197,253,0.35)',
                'rgba(59,130,246,0.45)',
                'rgba(29,161,242,0.4)',
                'rgba(219,234,254,0.25)',
                'rgba(29,161,242,0.35)',
            ];
            var lightColors = [
                'rgba(29,161,242,0.7)',
                'rgba(147,197,253,0.65)',
                'rgba(59,130,246,0.65)',
                'rgba(29,161,242,0.6)',
                'rgba(96,165,250,0.55)',
                'rgba(59,130,246,0.5)',
            ];

            function isDark() {
                return document.body.classList.contains('theme-dark');
            }

            function resize() {
                    canvas.width = canvas.offsetWidth;
                    canvas.height = canvas.offsetHeight;
                }

                var lastDark = null;

                function init(dark) {
                particles.length = 0;
                var w = canvas.width, h = canvas.height;
                for (var i = 0; i < num; i++) {
                    particles.push({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: Math.random() * speed + 0.03,
                        baseY: Math.random() * h,
                        amp: Math.random() * 30 + 8,
                        freq: Math.random() * 0.006 + 0.003,
                        size: Math.random() * 1.5 + 0.8,
                        rot: Math.random() * Math.PI * 2,
                        rotSpeed: (Math.random() - 0.5) * 0.02,
                        color: colors[Math.floor(Math.random() * colors.length)],
                        alpha: Math.random() * 0.4 + 0.25,
                        phase: Math.random() * Math.PI * 2,
                        color2: lightColors[Math.floor(Math.random() * lightColors.length)],
                    });
                }
            }

            function draw() {
                var dark = isDark();
                if (dark !== lastDark) {
                    lastDark = dark;
                    init(dark);
                }

                ctx.clearRect(0, 0, canvas.width, canvas.height);
                var w = canvas.width, h = canvas.height;
                var dark = isDark();

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    p.phase += p.freq;

                    p.x += p.vx;
                    p.y = p.baseY + Math.sin(p.phase) * p.amp;
                    p.rot += p.rotSpeed;

                    if (p.x > w + 30) {
                        p.x = -15;
                        p.baseY = Math.random() * h;
                    }

                    var c = dark ? p.color : p.color2;
                    drawLeaf(ctx, p.x, p.y, p.size, p.rot, c, p.alpha, dark);
                }

                animId = requestAnimationFrame(draw);
            }

            lastDark = isDark();
            resize();
            init(lastDark);
            draw();
            window.addEventListener('resize', function() { resize(); init(lastDark); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.querySelector('.glass-sidebar-operator');
            if (sidebar) {
                initHerbEffect(sidebar, { num: 24, speed: 0.2 });
            }

            var navbar = document.querySelector('.glass-topbar');
            if (navbar) {
                initHerbEffect(navbar, { num: 14, speed: 0.12 });
            }
        });
    })();
    </script>
</body>
</html>
