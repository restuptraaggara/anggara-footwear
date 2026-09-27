<!DOCTYPE html>
<html lang="id" class="h-full bg-earth-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Anggara Footwear</title>

    {{-- Vite CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full antialiased text-gray-800 flex" x-data="{ sidebarOpen: false }">

    {{-- Mobile Sidebar Backdrop --}}
    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-earth-200 flex flex-col justify-between transition-transform duration-300 transform lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <div>
            {{-- Admin Brand --}}
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <span class="text-lg font-bold tracking-widest uppercase text-white">ANGGARA ADMIN</span>
                    <span class="text-[10px] tracking-wider uppercase px-2 py-0.5 bg-earth-800 text-earth-300 rounded font-medium">ADMIN</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation Links --}}
            <nav class="p-4 space-y-1.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors
                          {{ request()->routeIs('admin.dashboard') ? 'bg-earth-800 text-white shadow-sm' : 'text-earth-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors
                          {{ request()->routeIs('admin.products.*') ? 'bg-earth-800 text-white shadow-sm' : 'text-earth-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Katalog Produk
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors
                          {{ request()->routeIs('admin.orders.*') ? 'bg-earth-800 text-white shadow-sm' : 'text-earth-400 hover:bg-gray-800 hover:text-white' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Pesanan Masuk
                </a>
                <a href="{{ route('admin.coupons.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-xs font-semibold tracking-wider uppercase transition-all {{ request()->routeIs('admin.coupons.*') ? 'bg-earth-100 text-gray-900 shadow-sm' : 'text-gray-400 hover:bg-earth-50 hover:text-gray-900' }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <span>KUPON & DISKON</span>
                </a>

            </nav>
        </div>

        {{-- Sidebar Footer: User info & Store Switcher --}}
        <div class="p-4 border-t border-gray-800 space-y-3">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center justify-center gap-2 w-full py-2.5 px-3 rounded-xl bg-gray-800 hover:bg-gray-700 text-xs text-earth-300 hover:text-white font-medium transition-colors">
                <span>🌐 Buka Toko Publik</span>
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>

            <div class="flex items-center justify-between px-2 pt-1">
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                    <p class="text-[11px] text-earth-400 truncate">{{ auth()->user()->email ?? 'admin@allbirds.test' }}</p>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors" title="Logout">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Admin Content Area --}}
    <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">
        
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-earth-100 flex items-center justify-between px-6 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900 p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-sm font-semibold uppercase tracking-wider text-gray-900">
                    @yield('header', 'Dashboard')
                </h1>
            </div>

            <div class="flex items-center gap-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 border border-green-200 text-green-700 text-xs rounded-full font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    Sistem Aktif (SQLite)
                </span>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="mx-6 mt-6 bg-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded-r-xl text-sm shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-6 bg-red-50 border-l-4 border-red-500 text-red-800 p-4 rounded-r-xl text-sm shadow-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Body --}}
        <main class="flex-1 p-6 lg:p-8">
            @yield('content')
        </main>

        {{-- Admin Footer --}}
        <footer class="bg-white border-t border-earth-100 px-6 py-4 text-center text-xs text-gray-400 font-light">
            Anggara Footwear Management System &bull; Portfolio Full-Stack Project
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
