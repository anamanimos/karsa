<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#7c3aed">

    <title>Super Admin Platform - {{ config('app.name', 'KarsaERP') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-container {
            z-index: 999999 !important;
        }
        /* Custom scrollbar for sidebar */
        .admin-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .admin-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.4);
            border-radius: 9999px;
        }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-800 selection:bg-purple-500 selection:text-white"
      x-data="{ mobileSidebarOpen: false }"
      style="background: linear-gradient(135deg, #FAF5FF 0%, #F3E8FF 25%, #F5F3FF 50%, #EEF2FF 100%); min-height: 100vh;">

    <div class="flex min-h-screen">
        {{-- ================= DESKTOP SIDEBAR (Visible on lg and above) ================= --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-72 lg:fixed lg:inset-y-0 z-40 bg-white/80 backdrop-blur-xl border-r border-purple-100 shadow-sm">
            {{-- Logo Header --}}
            <div class="p-6 border-b border-purple-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-purple-600 via-indigo-600 to-violet-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-purple-500/20">
                        👑
                    </div>
                    <div>
                        <h1 class="font-black text-dark text-base tracking-tight leading-tight">Super Admin</h1>
                        <p class="text-[10px] font-bold text-purple-600 uppercase tracking-wider">Platform Portal</p>
                    </div>
                </div>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-1 px-4 py-5 space-y-6 overflow-y-auto admin-scrollbar">
                {{-- Platform Section --}}
                <div>
                    <p class="px-3 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Manajemen Platform</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <span>Dashboard Platform</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span>Kelola Seluruh Pengguna</span>
                        </a>

                        <a href="{{ route('admin.businesses.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.businesses.*') ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.businesses.*') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Kelola Seluruh Tenant/Usaha</span>
                        </a>

                        <a href="{{ route('admin.plans.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.plans.*') ? 'bg-purple-600 text-white shadow-md shadow-purple-500/30' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('admin.plans.*') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Paket & Kuota SaaS (Plans)</span>
                        </a>
                    </div>
                </div>

                {{-- Quick Actions Section --}}
                <div>
                    <p class="px-3 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Pintasan Cepat</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition">
                            <span class="text-sm">➕</span>
                            <span>Tambah Pengguna Baru</span>
                        </a>
                        <a href="{{ route('businesses.create') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition">
                            <span class="text-sm">🏢</span>
                            <span>Daftarkan Usaha Baru</span>
                        </a>
                    </div>
                </div>

                {{-- Tenant / ERP Store Portal Link --}}
                <div class="pt-2 border-t border-purple-50">
                    <p class="px-3 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">Operasional Toko / ERP</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 transition border border-emerald-200/80">
                        <div class="flex items-center gap-2.5">
                            <span class="text-base">🏪</span>
                            <div>
                                <p class="text-xs font-bold leading-tight">Masuk Tampilan Toko</p>
                                <p class="text-[10px] text-emerald-600 truncate max-w-[130px]">
                                    {{ \App\Helpers\TenantHelper::currentBusiness()->name ?? 'Pilih Bisnis' }}
                                </p>
                            </div>
                        </div>
                        <span class="text-emerald-600 font-bold">&rarr;</span>
                    </a>
                </div>
            </nav>

            {{-- Sidebar Footer (Logged-in Super Admin Profile) --}}
            <div class="p-4 border-t border-purple-100 bg-purple-50/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 truncate">
                        <div class="w-8 h-8 rounded-full bg-purple-600 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-dark truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" title="Logout" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ================= MOBILE SLIDEOUT SIDEBAR DRAWER (< lg) ================= --}}
        <div x-show="mobileSidebarOpen" class="relative z-50 lg:hidden" style="display: none;" role="dialog" aria-modal="true">
            <div x-show="mobileSidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="mobileSidebarOpen = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"></div>

            <div class="fixed inset-0 flex">
                <div x-show="mobileSidebarOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    
                    <div class="flex flex-col w-full bg-white shadow-2xl">
                        {{-- Mobile Drawer Header --}}
                        <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-purple-50/50">
                            <div class="flex items-center gap-2.5">
                                <span class="text-2xl">👑</span>
                                <div>
                                    <h2 class="font-bold text-sm text-dark">Super Admin KarsaERP</h2>
                                    <p class="text-[10px] text-purple-600 font-semibold">Portal Administrator</p>
                                </div>
                            </div>
                            <button @click="mobileSidebarOpen = false" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 bg-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Mobile Nav Links --}}
                        <div class="flex-1 p-4 space-y-4 overflow-y-auto">
                            <div class="space-y-1">
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 p-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>📊</span> Dashboard Platform
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.users.*') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>👥</span> Kelola Pengguna
                                </a>
                                <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.businesses.*') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>🏢</span> Kelola Seluruh Tenant
                                </a>
                                <a href="{{ route('admin.plans.index') }}" class="flex items-center gap-3 p-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.plans.*') ? 'bg-purple-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span>📦</span> Paket & Kuota SaaS
                                </a>
                            </div>

                            <div class="border-t border-gray-100 pt-3 space-y-1">
                                <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50">
                                    <span>➕</span> Tambah Pengguna Baru
                                </a>
                                <a href="{{ route('businesses.create') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50">
                                    <span>🏢</span> Tambah Tenant Baru
                                </a>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-bold text-emerald-600 hover:bg-emerald-50">
                                    <span>🏪</span> Masuk ke Tampilan Toko/POS
                                </a>
                            </div>
                        </div>

                        {{-- Mobile Profile Footer --}}
                        <div class="p-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                            <div class="truncate">
                                <p class="text-xs font-bold text-dark truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 font-bold px-2 py-1 bg-red-50 rounded-lg hover:bg-red-100">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= MAIN CONTENT WRAPPER ================= --}}
        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            {{-- Top Header Bar --}}
            <header class="sticky top-0 z-30 bg-white/70 backdrop-blur-xl border-b border-purple-100/60 shadow-xs">
                <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-3">
                    {{-- Left Side: Mobile Hamburger & Desktop Breadcrumb/Brand --}}
                    <div class="flex items-center gap-3">
                        <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-purple-700 hover:underline flex items-center gap-1">
                                <span>👑</span>
                                <span class="hidden sm:inline">Platform Admin</span>
                            </a>
                            <span class="text-gray-300">/</span>
                            <span class="text-xs font-semibold text-gray-500">
                                @if(request()->routeIs('admin.dashboard'))
                                    Dashboard
                                @elseif(request()->routeIs('admin.users.*'))
                                    Pengguna
                                @elseif(request()->routeIs('admin.businesses.*'))
                                    Tenant Usaha
                                @else
                                    Sistem
                                @endif
                            </span>
                        </div>
                    </div>

                    {{-- Right Side: Fast Actions & Shortcut to Store --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        {{-- Store Shortcut Button --}}
                        <a href="{{ route('dashboard') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition border border-emerald-200/80 shadow-2xs" title="Beralih ke Tampilan Toko Aktif">
                            <span>🏪</span>
                            <span>Lihat Toko ERP</span>
                        </a>

                        {{-- Active Business Pill --}}
                        @php
                            $currBiz = \App\Helpers\TenantHelper::currentBusiness();
                        @endphp
                        @if($currBiz)
                            <span class="hidden md:inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-gray-100 text-gray-600 text-xs font-medium">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="truncate max-w-[140px]">{{ $currBiz->name }}</span>
                            </span>
                        @endif

                        {{-- Profile Avatar Dropdown --}}
                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-purple-50 transition border border-transparent hover:border-purple-100">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-purple-600 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <span class="hidden sm:inline text-xs font-bold text-dark max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-56 bg-white/95 backdrop-blur-xl p-2 z-50 rounded-2xl shadow-xl border border-gray-100 divide-y divide-gray-100"
                                 style="display: none;">
                                <div class="px-3 py-2">
                                    <p class="text-xs font-bold text-dark">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-black uppercase rounded-full bg-purple-100 text-purple-700">Super Administrator</span>
                                </div>
                                <div class="py-1 space-y-0.5">
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs text-gray-700 rounded-lg hover:bg-purple-50 hover:text-purple-700 transition font-medium">
                                        <span>⚙️</span> Profil Saya
                                    </a>
                                </div>
                                <div class="pt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 rounded-lg hover:bg-red-50 transition font-semibold">
                                            <span>🚪</span> Keluar / Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Optional Header Slot --}}
            @if(isset($header))
                <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
                    {{ $header }}
                </div>
            @endif

            {{-- Main Page Content --}}
            <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    {{-- SweetAlert2 Flash Notifications --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{!! addslashes(session('success')) !!}",
                    timer: 2500,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    customClass: { popup: 'rounded-2xl font-sans shadow-lg' }
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Perhatian',
                    text: "{!! addslashes(session('error')) !!}",
                    customClass: { popup: 'rounded-2xl font-sans shadow-lg' }
                });
            });
        </script>
    @endif

    {{-- Global Confirmation and Form Loading Listener --}}
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.getAttribute('method')?.toLowerCase() === 'get') {
                return;
            }

            if (form.classList.contains('confirm-delete') && form.dataset.confirmed !== 'true') {
                e.preventDefault();
                const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin ingin menghapus data ini?';

                Swal.fire({
                    title: 'Konfirmasi Aksi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl font-sans shadow-xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => { Swal.showLoading(); },
                            customClass: { popup: 'rounded-2xl font-sans' }
                        });
                        form.submit();
                    }
                });
                return;
            }

            if (form.classList.contains('no-loading')) {
                return;
            }

            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => { Swal.showLoading(); },
                customClass: { popup: 'rounded-2xl font-sans' }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
