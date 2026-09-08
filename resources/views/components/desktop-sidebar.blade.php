@php
    // Helper: detect which module group is active
    $isSales = request()->routeIs('sales.*', 'cash-registers.*');
    $isInventory = request()->routeIs('products.*', 'stock-adjustments.*', 'reports.stock_movements', 'categories.*', 'units.*');
    $isProcurement = request()->routeIs('purchases.*', 'suppliers.*', 'customers.*', 'payments.*', 'cash-transactions.*');
    $isFinance = request()->routeIs('financial-reports.*');
    $isHR = request()->routeIs('employees.*', 'employee-attendances.*', 'employee-bonuses.*', 'payrolls.*');
    $isSettings = request()->routeIs('businesses.*', 'settings.*', 'users.*', 'galleries.*');
    $isAdmin = request()->routeIs('admin.*');
@endphp

<aside 
    x-show="sidebarOpen"
    x-cloak
    x-transition:enter="transition-all duration-300 ease-out"
    x-transition:enter-start="-ml-56 opacity-0"
    x-transition:enter-end="ml-0 opacity-100"
    x-transition:leave="transition-all duration-200 ease-in"
    x-transition:leave-start="ml-0 opacity-100"
    x-transition:leave-end="-ml-56 opacity-0"
    class="hidden lg:flex flex-col w-56 h-screen sticky top-0 z-40 bg-white/95 backdrop-blur-md border-r border-gray-200/80 shadow-xs flex-shrink-0"
    style="display: none;">

    {{-- 1. Header: Brand & Collapse --}}
    <div class="h-14 px-3 border-b border-gray-100 flex items-center justify-between bg-white/80">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 overflow-hidden group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-black text-[10px] shadow-md shadow-primary-500/20 group-hover:scale-105 transition-transform flex-shrink-0">
                ERP
            </div>
            <div class="truncate">
                <h2 class="font-bold text-xs text-dark leading-tight truncate group-hover:text-primary-600 transition-colors">
                    {{ \App\Helpers\TenantHelper::currentBusiness()->name ?? \App\Models\Setting::get('company_name', 'KarsaERP') }}
                </h2>
                <p class="text-[9px] text-gray-400 font-medium">Cloud ERP & POS</p>
            </div>
        </a>

        <button type="button" 
                @click="toggleSidebar()" 
                class="p-1 rounded-lg text-gray-400 hover:text-primary-600 hover:bg-gray-100 active:scale-95 transition cursor-pointer"
                title="Sembunyikan Sidebar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- 2. Simplified Navigation (1 link per module) --}}
    <nav class="flex-1 px-2 py-3 space-y-0.5 overflow-y-auto admin-scrollbar text-[13px]">
        
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </a>

        <div class="pt-2 pb-1"><hr class="border-gray-100"></div>

        {{-- Kasir & Penjualan --}}
        <a href="{{ route('sales.create') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isSales ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isSales ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <span>Kasir & Penjualan</span>
        </a>

        {{-- Stok & Inventori --}}
        <a href="{{ route('products.index') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isInventory ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isInventory ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span>Stok & Inventori</span>
        </a>

        {{-- Pengadaan & Pembelian --}}
        <a href="{{ route('purchases.index') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isProcurement ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isProcurement ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Pengadaan</span>
        </a>

        {{-- Laporan Keuangan --}}
        <a href="{{ route('financial-reports.profit-loss') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isFinance ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isFinance ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Laporan Keuangan</span>
        </a>

        {{-- SDM & Penggajian --}}
        <a href="{{ route('employees.index') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isHR ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isHR ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>SDM & Penggajian</span>
        </a>

        <div class="pt-2 pb-1"><hr class="border-gray-100"></div>

        {{-- Pengaturan --}}
        <a href="{{ route('settings.index') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isSettings ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isSettings ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.573-1.066z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span>Pengaturan</span>
        </a>

        {{-- Buku Panduan / Docs --}}
        <a href="{{ route('docs') }}" 
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ request()->routeIs('docs') ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0 {{ request()->routeIs('docs') ? 'text-primary-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span>Buku Panduan</span>
        </a>

        {{-- Super Admin --}}
        @if(Auth::check() && Auth::user()->isSuperAdmin())
            <div class="pt-2 pb-1"><hr class="border-purple-100"></div>
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-lg font-medium transition-all duration-150 {{ $isAdmin ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-purple-600 hover:bg-purple-50/60 hover:text-purple-800' }}">
                <svg class="w-[18px] h-[18px] flex-shrink-0 {{ $isAdmin ? 'text-purple-600' : 'text-purple-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Super Admin</span>
            </a>
        @endif
    </nav>

    {{-- 3. Footer: User & Logout --}}
    <div class="px-3 py-2.5 border-t border-gray-100 bg-gray-50/80 flex items-center justify-between">
        @if(Auth::check())
            <div class="flex items-center gap-2 truncate max-w-[130px]">
                <div class="w-7 h-7 rounded-lg bg-primary-100 text-primary-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-dark truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[9px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-0.5">
                <a href="{{ route('profile.edit') }}" class="p-1 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-white transition" title="Profil">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="p-1 text-red-500 hover:text-red-700 rounded-lg hover:bg-red-50 transition cursor-pointer" title="Keluar">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        @endif
    </div>
</aside>
