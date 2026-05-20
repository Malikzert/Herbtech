<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIP Jamu Madura')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(5, 150, 105, 0.3); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(5, 150, 105, 0.5); }
        
        /* Modern Select - Hide default appearance */
        .modern-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4m0 0l-4 4m4-4H2'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.25em 1.25em;
            padding-right: 2.5rem;
        }
        
        /* Glass Effect Classes - 20% Opacity with Strong Blur */
        .bg-glass { 
            background-color: rgba(255, 255, 255, 0.2); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px); 
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .bg-glass-dark { 
            background-color: rgba(0, 0, 0, 0.2); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px); 
        }
        
        /* Card Hover Effect */
        .glass-card { transition: all 0.3s ease; }
        .glass-card:hover { 
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); 
            transform: translateY(-2px); 
            background-color: rgba(255, 255, 255, 0.25);
        }
        
        /* Background Image */
        .bg-admin-wall {
            background-image: url('{{ asset("image/bgadmin.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        /* Fallback to original if bgadmin.png doesn't exist */
        .bg-wallpaper {
            background-image: url('{{ asset("image/rempahwall.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        
        .glass-sidebar {
            background: linear-gradient(to right, rgba(5, 150, 105, 0.8), rgba(255, 255, 255, 0.12));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-topbar {
            background: 
                linear-gradient(to bottom, transparent 40%, rgba(255, 255, 255, 0.25) 100%),
                linear-gradient(to right, rgba(5, 150, 105, 0.05), rgba(5, 150, 105, 0.8));
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* Glass Button */
        .btn-glass {
            background-color: rgba(5, 150, 105, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        .btn-glass:hover {
            background-color: rgba(5, 150, 105, 1);
            box-shadow: 0 4px 20px rgba(5, 150, 105, 0.4);
        }
        
        /* Glass Input */
        .input-glass {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(5, 150, 105, 0.6);
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
        }
        
        /* Glass Table */
        .glass-table {
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .glass-table thead {
            background-color: rgba(5, 150, 105, 0.5);
        }
        .glass-table thead th {
            color: white;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .glass-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        /* Text Shadow for Glass UI */
        .text-shadow {
            text-shadow: 0 1px 3px rgba(0,0,0,0.3);
        }
        .text-shadow-sm {
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        .text-shadow-md {
            text-shadow: 0 0 4px rgba(5, 150, 105, 0.7), 0 1px 3px rgba(5, 150, 105, 0.4);
        }
        .text-glow-green {
            text-shadow: 0 0 8px rgba(5, 150, 105, 0.6), 0 0 20px rgba(5, 150, 105, 0.3);
        }

        /* ===== VALORANT NOTIFICATION ===== */
        .v-backdrop {
            position: fixed; inset: 0; z-index: 99999;
            background: rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(16px) saturate(0.6);
            -webkit-backdrop-filter: blur(16px) saturate(0.6);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }
        .v-box {
            position: relative; min-width: 560px; padding: 3.5rem 5rem;
            background: rgba(0,0,0,0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            text-align: center; overflow: hidden;
        }
        .v-box.created { border: 2px solid rgba(0,255,65,0.5); }
        .v-box.updated { border: 2px solid rgba(255,215,0,0.5); }
        .v-box.deleted { border: 2px solid rgba(255,0,64,0.5); }

        .v-bar { position: absolute; left: 0; right: 0; height: 3px; background: currentColor; }
        .v-bar--t { top: 0; transform-origin: right; }
        .v-bar--b { bottom: 0; transform-origin: left; }
        .v-box.show .v-bar { animation: vBarIn 0.55s cubic-bezier(0.19,1,0.22,1) forwards; }
        .v-box.show .v-bar--b { animation-delay: 0.1s; }
        @keyframes vBarIn { 0% { transform: scaleX(0); } 100% { transform: scaleX(1); } }

        .v-cnr { position: absolute; width: 16px; height: 16px; border-color: currentColor; }
        .v-cnr--tl { top: -2px; left: -2px; border-width: 2px 0 0 2px; border-style: solid; }
        .v-cnr--tr { top: -2px; right: -2px; border-width: 2px 2px 0 0; border-style: solid; }
        .v-cnr--bl { bottom: -2px; left: -2px; border-width: 0 0 2px 2px; border-style: solid; }
        .v-cnr--br { bottom: -2px; right: -2px; border-width: 0 2px 2px 0; border-style: solid; }

        .v-scroll { position: absolute; inset: 0; pointer-events: none;
            background: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.025) 2px, rgba(255,255,255,0.025) 4px); }
        .v-icon { width: 3.5rem; height: 3.5rem; margin: 0 auto 1.25rem; }
        .v-msg { font-size: 1.75rem; font-weight: 900; letter-spacing: 0.15em; text-transform: uppercase; color: #fff; line-height: 1.3; }
        .v-sub { margin-top: 0.85rem; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.35em; text-transform: uppercase; opacity: 0.45; }

        .v-box.created .v-bar, .v-box.created .v-cnr { color: #00ff41; }
        .v-box.created .v-sub { color: #00ff41; }
        .v-box.updated .v-bar, .v-box.updated .v-cnr { color: #ffd700; }
        .v-box.updated .v-sub { color: #ffd700; }
        .v-box.deleted .v-bar, .v-box.deleted .v-cnr { color: #ff0040; }
        .v-box.deleted .v-sub { color: #ff0040; }

        @keyframes glitchG { 0%,100%{text-shadow:none} 4%{text-shadow:-2px 0 #00ff41,2px 0 #f0f;clip-path:inset(40% 0 0 0)} 8%{clip-path:inset(20% 0 60% 0)} 12%{clip-path:inset(60% 0 10% 0)} 16%{clip-path:inset(0 0 80% 0)} 20%{text-shadow:2px 0 #00ff41,-2px 0 #f0f;clip-path:inset(30% 0 30% 0)} 24%{clip-path:inset(10% 0 70% 0)} 28%{text-shadow:none;clip-path:inset(0 0 0 0)} }
        @keyframes glitchY { 0%,100%{text-shadow:none} 4%{text-shadow:-2px 0 #ffd700,2px 0 #f0f;clip-path:inset(40% 0 0 0)} 8%{clip-path:inset(20% 0 60% 0)} 12%{clip-path:inset(60% 0 10% 0)} 16%{clip-path:inset(0 0 80% 0)} 20%{text-shadow:2px 0 #ffd700,-2px 0 #f0f;clip-path:inset(30% 0 30% 0)} 24%{clip-path:inset(10% 0 70% 0)} 28%{text-shadow:none;clip-path:inset(0 0 0 0)} }
        @keyframes glitchR { 0%,100%{text-shadow:none} 3%{text-shadow:-3px 0 #ff0040,3px 0 #0ff;clip-path:inset(60% 0 0 0)} 6%{clip-path:inset(10% 0 70% 0)} 9%{clip-path:inset(40% 0 30% 0)} 12%{clip-path:inset(0 0 80% 0)} 15%{text-shadow:3px 0 #ff0040,-3px 0 #0ff;clip-path:inset(20% 0 50% 0)} 18%{clip-path:inset(70% 0 0 0)} 21%{clip-path:inset(0 0 40% 0)} 24%{text-shadow:none;clip-path:inset(0 0 0 0)} }

        .v-glitch.created .v-msg { animation: glitchG 1.4s ease forwards; }
        .v-glitch.updated .v-msg { animation: glitchY 1.4s ease forwards; }
        .v-glitch.deleted .v-msg { animation: glitchR 2s ease forwards; }

        /* ===== SIDEBAR VALORANT HOVER ===== */
        .sidebar-link {
            position: relative; overflow: hidden;
            transition: color 0.25s ease;
        }
        .sidebar-link::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(5,150,105,0.3), rgba(5,150,105,0.1));
            opacity: 0; transition: opacity 0.3s ease;
            pointer-events: none; border-radius: inherit;
        }
        .sidebar-link:hover::before { opacity: 1; }
        .sidebar-link::after {
            content: ''; position: absolute;
            left: 0; top: 4px; bottom: 4px; width: 3px;
            background: currentColor;
            transform: scaleY(0);
            transition: transform 0.3s cubic-bezier(0.19,1,0.22,1);
            transform-origin: center top;
            border-radius: 0 2px 2px 0;
        }
        .sidebar-link:hover::after { transform: scaleY(1); }
        .sidebar-link > * { position: relative; z-index: 1; }

        /* Active state - permanent accent bar + glow */
        .sidebar-link.text-emerald-300::after {
            transform: scaleY(1);
            color: #6ee7b7;
            box-shadow: 0 0 6px currentColor;
        }
        .sidebar-link.text-emerald-300::before {
            opacity: 1;
            background: linear-gradient(135deg, rgba(5,150,105,0.45), rgba(5,150,105,0.2));
        }
        .sidebar-link.text-emerald-300 span {
            text-shadow: 0 0 12px rgba(110,231,183,0.3);
        }

        .sidebar-link:hover span { animation: sideGlitch 0.7s ease forwards; }
        @keyframes sideGlitch {
            0%,100%{text-shadow:none}
            12%{text-shadow:-2px 0 #00ff41,2px 0 #f0f}
            24%{text-shadow:2px 0 #00ff41,-2px 0 #f0f}
            36%{text-shadow:-1px 0 #00ff41,1px 0 #f0f}
            48%{text-shadow:1px 0 #00ff41,-1px 0 #f0f}
            60%{text-shadow:none}
        }

        /* ===== WIDGET GRID ===== */
        .widget-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
            padding: 1.25rem;
        }
        .widget-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(5, 150, 105, 0.2);
            background: rgba(6, 78, 59, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            transition: all 0.35s cubic-bezier(0.19,1,0.22,1);
            display: flex;
            flex-direction: column;
        }
        .widget-card:hover {
            transform: translateY(-6px);
            box-shadow: 
                0 0 25px rgba(5, 150, 105, 0.4),
                0 0 60px rgba(5, 150, 105, 0.12),
                0 0 100px rgba(5, 150, 105, 0.05),
                0 12px 48px rgba(0, 0, 0, 0.25);
            border-color: rgba(5, 150, 105, 0.6);
        }
        .widget-card-header {
            position: relative;
            height: 160px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(5,150,105,0.15), rgba(6,78,59,0.3));
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
            color: rgba(255,255,255,0.92);
            font-size: 0.9rem;
            line-height: 1.3;
            margin-bottom: 0.15rem;
        }
        .widget-card-subtitle {
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.35);
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
            border: 1px solid rgba(5, 150, 105, 0.18);
            background: rgba(5, 150, 105, 0.08);
            color: rgba(255,255,255,0.6);
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
        .widget-card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.15rem;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            border-top: 1px solid rgba(5, 150, 105, 0.1);
        }
        .widget-card-actions button {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.25);
            transition: all 0.2s ease;
            border-radius: 0;
            cursor: pointer;
        }
        .widget-card-actions button:hover {
            color: rgba(255,255,255,0.8);
            background: rgba(5, 150, 105, 0.15);
        }
        .widget-card-actions .btn-delete:hover {
            color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.15) !important;
        }
    </style>
    @stack('styles')
</head>
@php
    $__flash = session()->only(['success','error','warning','info']);
    $__flash['_flavor'] = '';
    if ($__flash['success'] ?? null) {
        $m = $__flash['success'];
        if (str_contains($m, 'dibuat') || str_contains($m, 'ditambahkan') || str_contains($m, 'disimpan')) $__flash['_flavor'] = 'created';
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
<body class="text-gray-800 antialiased" x-data="{ 
    sidebarOpen: false, 
    userMenu: false, 
    notif: { show: false, flavor: 'created', message: '' },
    viewMode: localStorage.getItem('adminViewMode') || 'list'
}"
      x-on:notify.window="notif.show = true; notif.flavor = $event.detail.flavor || 'created'; notif.message = $event.detail.message; setTimeout(function(){ notif.show = false; }, 5000);"
      x-init="
        window.adminViewMode = localStorage.getItem('adminViewMode') || 'list';
        $watch('viewMode', val => {
            localStorage.setItem('adminViewMode', val);
            window.adminViewMode = val;
            window.dispatchEvent(new CustomEvent('admin-view-change', { detail: val }));
        });
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
    <!-- Main Background Wrapper -->
    <div class="bg-admin-wall min-h-screen">
        <div class="min-h-screen bg-black/5">
            <div class="flex h-screen overflow-hidden">
                <!-- Sidebar -->
                <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 glass-sidebar text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
                    <div class="relative h-full">
                        <canvas id="sidebarStars" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>
                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex items-center justify-center h-16 border-b border-white/10 shrink-0">
                                <div class="flex items-center gap-3">
                                    <img src="{{ asset('image/logoht.png') }}" alt="Logo" class="w-8 h-8 object-contain filter brightness-0 invert">
                                    <span class="text-xl font-bold tracking-wider">HerbTech</span>
                                </div>
                            </div>

                            <nav class="flex-1 mt-3 px-4 space-y-1 overflow-y-auto overflow-x-hidden">
                                <p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Menu Utama</p>
                                
                                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.dashboard') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2H4V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6H4v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6h-4v-6z"></path></svg>
                                    <span class="text-shadow-sm">Dashboard</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Master Data</p></div>
                                
                                <a href="{{ route('admin.products.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.products.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                    <span class="text-shadow-sm">Produk</span>
                                </a>
                                
                                <a href="{{ route('admin.raw-materials.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.raw-materials.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                                    <span class="text-shadow-sm">Bahan Baku</span>
                                </a>

                                <a href="{{ route('admin.recipes.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.recipes.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <span class="text-shadow-sm">Resep</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Monitoring</p></div>
                                
                                <a href="{{ route('admin.productions.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.productions.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                    <span class="text-shadow-sm">Produksi</span>
                                </a>
                                
                                <a href="{{ route('admin.scheduling.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.scheduling.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-shadow-sm">Penjadwalan</span>
                                </a>
                                
                                <a href="{{ route('admin.qc.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.qc.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-shadow-sm">Quality Control</span>
                                </a>
                                
                                <a href="{{ route('admin.reports.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.reports.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-shadow-sm">Laporan</span>
                                </a>

                                <div class="pt-4 pb-2"><p class="text-xs font-semibold text-emerald-200 uppercase tracking-wider mb-2 px-4 text-shadow-sm">Pengaturan</p></div>
                                
                                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.users.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <span class="text-shadow-sm">Manajemen User</span>
                                </a>
                                
                                <a href="{{ route('admin.profile.edit') }}" class="sidebar-link flex items-center px-4 py-2.5 rounded-lg transition text-white {{ request()->routeIs('admin.profile.*') ? 'text-emerald-300' : '' }}">
                                    <svg class="w-5 h-5 mr-3 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-shadow-sm">Profil Saya</span>
                                </a>
                            </nav>

                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="flex-1 flex flex-col overflow-hidden">
                    <!-- Topbar -->
                    <header class="glass-topbar z-20 h-16 relative">
                        <canvas id="navbarStars" class="absolute inset-0 w-full h-full pointer-events-none z-0"></canvas>
                        <div class="relative z-10 flex items-center justify-between h-full px-6">
                            <div class="flex items-center">
                                <button @click="sidebarOpen = !sidebarOpen" class="text-white/80 hover:text-white focus:outline-none lg:hidden">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                </button>
                                <h2 class="text-xl font-bold text-white ml-4 lg:ml-5 text-glow-green">@yield('header', 'Dashboard')</h2>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                {{-- View Toggle --}}
                                <button @click="viewMode = viewMode === 'list' ? 'widget' : 'list'"
                                    class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/10 transition-all duration-200 group"
                                    title="Ganti tampilan konten">
                                    <svg x-show="viewMode === 'list'" class="w-5 h-5 text-white/60 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                    <svg x-show="viewMode === 'widget'" class="w-5 h-5 text-white/60 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                    </svg>
                                    <div class="relative w-9 h-4 rounded-full transition-colors duration-300" :class="viewMode === 'widget' ? 'bg-emerald-500' : 'bg-white/20'">
                                        <div x-show="viewMode === 'list'" class="absolute top-0.5 left-0.5 w-3 h-3 rounded-full bg-white shadow-md transition-all"></div>
                                        <div x-show="viewMode === 'widget'" class="absolute top-0.5 right-0.5 w-3 h-3 rounded-full bg-white shadow-md transition-all" style="display:none"></div>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/50 group-hover:text-white/80 transition-colors hidden sm:inline">
                                        <span x-show="viewMode === 'list'">Daftar</span>
                                        <span x-show="viewMode === 'widget'" style="display:none">Widget</span>
                                    </span>
                                </button>

                                <div class="w-px h-6 bg-white/10 mx-1"></div>

                                <div class="relative" x-data>
                                    <button @click="userMenu = !userMenu" class="flex items-center space-x-3 focus:outline-none p-2 rounded-xl hover:bg-white/10 transition">
                                        <div class="text-right hidden md:block">
                                            <p class="text-sm font-bold text-white text-shadow-sm">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-emerald-200 font-medium text-shadow-sm">{{ ucfirst(auth()->user()->role) }}</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white flex items-center justify-center font-bold shadow-md">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    </button>
                                    
                                    <div x-show="userMenu" @click.away="userMenu = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                        class="absolute right-0 mt-3 w-64 rounded-xl border border-emerald-500/25 bg-emerald-900/95 backdrop-blur-xl shadow-[0_8px_32px_rgba(0,0,0,0.4)] z-50 overflow-hidden">
                                        {{-- Top accent --}}
                                        <div class="h-[2px] bg-gradient-to-r from-emerald-500/60 via-emerald-400/30 to-transparent"></div>
                                        {{-- User preview --}}
                                        <div class="px-5 py-4 border-b border-emerald-500/15">
                                            <p class="text-sm font-bold text-emerald-50">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-emerald-200/60 mt-0.5">{{ ucfirst(auth()->user()->role) }}</p>
                                        </div>
                                        <div class="py-2">
                                            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm text-emerald-200/70 hover:text-emerald-50 hover:bg-emerald-500/10 transition-all duration-150">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                <span class="font-medium">Profil Saya</span>
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="flex items-center gap-3 w-full text-left px-5 py-2.5 text-sm text-red-400/80 hover:text-red-300 hover:bg-red-500/10 transition-all duration-150">
                                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                                    <span class="font-medium">Keluar</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
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
                    <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-8 bg-white/5 text-gray-800">
                        @yield('content')
                    </main>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>
    (function() {
        function initConstellation(canvas, opts) {
            if (!canvas) return;
            var ctx = canvas.getContext('2d');
            var particles = [];
            var num = opts.num || 25;
            var maxDist = opts.maxDist || 120;
            var speed = opts.speed || 0.15;
            var opacity = opts.opacity || 0.25;
            var animId;

            function resize() {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            }

            function init() {
                particles.length = 0;
                var w = canvas.width;
                var h = canvas.height;
                for (var i = 0; i < num; i++) {
                    particles.push({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: (Math.random() - 0.5) * speed,
                        vy: (Math.random() - 0.5) * speed,
                        r: Math.random() * 1.5 + 0.5,
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                var w = canvas.width, h = canvas.height;

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;
                    if (p.x < 0) p.x = w;
                    if (p.x > w) p.x = 0;
                    if (p.y < 0) p.y = h;
                    if (p.y > h) p.y = 0;
                }

                for (var i = 0; i < particles.length; i++) {
                    for (var j = i + 1; j < particles.length; j++) {
                        var dx = particles[i].x - particles[j].x;
                        var dy = particles[i].y - particles[j].y;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < maxDist) {
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.strokeStyle = 'rgba(255,255,255,' + (opacity * (1 - dist / maxDist)) + ')';
                            ctx.lineWidth = 0.5;
                            ctx.stroke();
                        }
                    }
                }

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(255,255,255,' + (opacity + 0.1) + ')';
                    ctx.fill();
                }

                animId = requestAnimationFrame(draw);
            }

            resize();
            init();
            draw();

            window.addEventListener('resize', function() { resize(); init(); });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initConstellation(document.getElementById('sidebarStars'), { num: 22, opacity: 0.2, maxDist: 130 });
            initConstellation(document.getElementById('navbarStars'), { num: 15, opacity: 0.15, maxDist: 110 });
        });
    })();
    </script>
</body>
</html>