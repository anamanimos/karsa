<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#16a34a">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ \App\Models\Setting::get('company_name', 'KarsaERP') }}">
        <link rel="apple-touch-icon" href="/pwa-icon.png">
        <link rel="manifest" href="/manifest.json">

        <title>{{ \App\Models\Setting::get('company_name', 'KarsaERP') }} - @yield('title', 'Dashboard')</title>
        <meta name="description" content="KarsaERP - Platform Cloud ERP & POS Multi-Usaha, Manajemen Stok, SDM & Payroll, Laporan Keuangan">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Flatpickr CSS & Select2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

        <style>
            .swal2-container {
                z-index: 999999 !important;
            }
            .select2-container {
                max-width: 100% !important;
            }
            /* Custom styling for Select2 to match Clean Glassmorphism theme */
            .select2-container--default .select2-selection--single {
                background-color: rgba(255, 255, 255, 0.7) !important;
                border: 1.5px solid rgba(209, 213, 219, 0.6) !important;
                border-radius: 12px !important;
                height: 46px !important;
                backdrop-filter: blur(8px) !important;
                display: flex !important;
                align-items: center !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #1F2937 !important;
                font-size: 0.875rem !important;
                padding-left: 12px !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 44px !important;
                right: 8px !important;
            }
            .select2-dropdown {
                background-color: rgba(255, 255, 255, 0.95) !important;
                border: 1px solid rgba(255, 255, 255, 0.5) !important;
                border-radius: 12px !important;
                box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.08) !important;
                backdrop-filter: blur(20px) !important;
                padding: 4px !important;
                z-index: 9999 !important;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background-color: #16a34a !important;
                border-radius: 8px !important;
            }
            .select2-container--default .select2-results__option {
                padding: 8px 12px !important;
                font-size: 0.875rem !important;
                border-radius: 8px !important;
            }
            .select2-search--dropdown .select2-search__field {
                border: 1.5px solid rgba(209, 213, 219, 0.6) !important;
                border-radius: 8px !important;
                padding: 6px 10px !important;
                outline: none !important;
            }
            /* Flatpickr customization */
            .flatpickr-calendar {
                background: #FFFFFF !important;
                border: 1px solid #D1D5DB !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
                border-radius: 12px !important;
            }
            .flatpickr-months {
                background-color: #F3F4F6 !important;
                border-bottom: 1px solid #E5E7EB !important;
                border-radius: 12px 12px 0 0 !important;
            }
            .flatpickr-current-month {
                color: #1F2937 !important;
            }
            .flatpickr-current-month .flatpickr-monthDropdown-months {
                font-weight: 700 !important;
                color: #1F2937 !important;
            }
            .flatpickr-current-month .flatpickr-monthDropdown-months option {
                color: #1F2937 !important;
                background-color: #FFFFFF !important;
            }
            .flatpickr-current-month input.cur-year {
                font-weight: 700 !important;
                color: #1F2937 !important;
            }
            .flatpickr-months .flatpickr-prev-month, 
            .flatpickr-months .flatpickr-next-month {
                color: #4B5563 !important;
                fill: #4B5563 !important;
            }
            .flatpickr-months .flatpickr-prev-month:hover, 
            .flatpickr-months .flatpickr-next-month:hover {
                color: #16A34A !important;
                fill: #16A34A !important;
            }
            .flatpickr-months .flatpickr-prev-month:hover svg, 
            .flatpickr-months .flatpickr-next-month:hover svg {
                fill: #16A34A !important;
            }
            .flatpickr-weekdaycontainer {
                padding: 4px 0 !important;
                background-color: #F9FAFB !important;
                border-bottom: 1px solid #E5E7EB !important;
            }
            span.flatpickr-weekday {
                color: #4B5563 !important;
                font-weight: 600 !important;
            }
            .flatpickr-day {
                color: #1F2937 !important;
                border-radius: 6px !important;
            }
            .flatpickr-day.today {
                border-color: #16A34A !important;
                color: #16A34A !important;
                font-weight: 700 !important;
            }
            .flatpickr-day.today:hover {
                background-color: #F0FDF4 !important;
                color: #16A34A !important;
            }
            .flatpickr-day.selected, 
            .flatpickr-day.selected:focus, 
            .flatpickr-day.selected:hover {
                background-color: #16A34A !important;
                border-color: #16A34A !important;
                color: #FFFFFF !important;
                font-weight: 700 !important;
            }
            .flatpickr-day:hover {
                background-color: #F3F4F6 !important;
            }
            /* Custom scrollbar for offcanvas menu */
            .admin-scrollbar::-webkit-scrollbar {
                width: 5px;
            }
            .admin-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(156, 163, 175, 0.4);
                border-radius: 9999px;
            }
            [x-cloak] { display: none !important; }
        </style>

        @stack('styles')
    </head>
    <body class="font-sans antialiased" 
          x-data="{ 
              erpMenuOpen: false,
              sidebarOpen: localStorage.getItem('erp_sidebar_open') !== null ? (localStorage.getItem('erp_sidebar_open') === 'true') : true,
              toggleSidebar() {
                  this.sidebarOpen = !this.sidebarOpen;
                  localStorage.setItem('erp_sidebar_open', this.sidebarOpen);
              }
          }">
        {{-- ================= ERP OFFCANVAS SIDEBAR (SISI KIRI) ================= --}}
        <div x-show="erpMenuOpen" 
             class="relative z-50" 
             style="display: none;" 
             role="dialog" 
             aria-modal="true">
             
            {{-- Backdrop --}}
            <div x-show="erpMenuOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="erpMenuOpen = false"
                 class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"></div>

            <div class="fixed inset-0 flex">
                <div x-show="erpMenuOpen"
                     x-transition:enter="transition ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs sm:max-w-sm flex-1">
                     
                    <div class="flex flex-col w-full bg-white/95 backdrop-blur-2xl shadow-2xl border-r border-gray-200/80">
                        {{-- Header Drawer --}}
                        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-emerald-500/10 via-teal-500/5 to-transparent flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-white font-black text-sm shadow-md shadow-primary-500/20">
                                    ERP
                                </div>
                                <div class="truncate max-w-[190px]">
                                    <h2 class="font-bold text-sm text-dark leading-tight truncate">
                                        {{ \App\Helpers\TenantHelper::currentBusiness()->name ?? \App\Models\Setting::get('company_name', 'KarsaERP') }}
                                    </h2>
                                    <p class="text-[10px] text-primary-600 font-semibold tracking-wide">Navigasi Modul ERP</p>
                                </div>
                            </div>
                            <button type="button" @click="erpMenuOpen = false" class="p-2 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-white/80 transition shadow-xs" title="Tutup Menu">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Nav Links List --}}
                        <div class="flex-1 p-4 space-y-5 overflow-y-auto admin-scrollbar">
                            {{-- Modul: Operasional Kasir & Toko --}}
                            <div>
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>🏪</span> Operasional Kasir & Toko
                                </p>
                                <div class="space-y-1">
                                    <a href="{{ route('sales.create') }}" class="flex items-center justify-between p-2.5 rounded-lg text-xs font-bold transition {{ request()->routeIs('sales.create') ? 'bg-primary-50 text-primary-700 font-black' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-sm">🛒</span>
                                            <span>Kasir POS Penjualan</span>
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700">POS</span>
                                    </a>
                                    <a href="{{ route('sales.index') }}" class="flex items-center gap-2.5 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('sales.index', 'sales.show') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="text-sm">🧾</span>
                                        <span>Riwayat Transaksi Jual</span>
                                    </a>
                                    <a href="{{ route('cash-registers.index') }}" class="flex items-center gap-2.5 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('cash-registers.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span class="text-sm">💼</span>
                                        <span>Buka / Tutup Shift Kasir</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: Laporan Keuangan Sederhana --}}
                            <div class="border-t border-gray-100 pt-3">
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>📊</span> Laporan Keuangan Akuntansi
                                </p>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ route('financial-reports.profit-loss') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financial-reports.profit-loss') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📈</span>
                                        <span>Laba Rugi</span>
                                    </a>
                                    <a href="{{ route('financial-reports.cash-flow') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financial-reports.cash-flow') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🌊</span>
                                        <span>Arus Kas</span>
                                    </a>
                                    <a href="{{ route('financial-reports.balance-sheet') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financial-reports.balance-sheet') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>⚖️</span>
                                        <span>Neraca</span>
                                    </a>
                                    <a href="{{ route('financial-reports.general-ledger') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('financial-reports.general-ledger') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🏦</span>
                                        <span>Buku Kas/Bank</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: SDM & Penggajian --}}
                            <div class="border-t border-gray-100 pt-3">
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>👥</span> SDM & Penggajian (Payroll)
                                </p>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ route('employees.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('employees.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>👤</span>
                                        <span>Karyawan</span>
                                    </a>
                                    <a href="{{ route('employee-attendances.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('employee-attendances.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📅</span>
                                        <span>Presensi</span>
                                    </a>
                                    <a href="{{ route('employee-bonuses.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('employee-bonuses.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🎁</span>
                                        <span>Bonus/Komisi</span>
                                    </a>
                                    <a href="{{ route('payrolls.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('payrolls.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>💵</span>
                                        <span>Slip Gaji</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: Stok & Inventori --}}
                            <div class="border-t border-gray-100 pt-3">
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>📦</span> Stok & Inventori Barang
                                </p>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ route('products.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('products.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📦</span>
                                        <span>Master Produk</span>
                                    </a>
                                    <a href="{{ route('stock-adjustments.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('stock-adjustments.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📋</span>
                                        <span>Stok Opname</span>
                                    </a>
                                    <a href="{{ route('reports.stock_movements') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('reports.stock_movements') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📜</span>
                                        <span>Kartu Stok</span>
                                    </a>
                                    <a href="{{ route('categories.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('categories.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🗂️</span>
                                        <span>Kategori</span>
                                    </a>
                                    <a href="{{ route('units.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('units.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>⚖️</span>
                                        <span>Satuan (Unit)</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: Pengadaan & Mitra Bisnis --}}
                            <div class="border-t border-gray-100 pt-3">
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>📥</span> Pengadaan & Mitra Bisnis
                                </p>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ route('purchases.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📥</span>
                                        <span>Pembelian</span>
                                    </a>
                                    <a href="{{ route('suppliers.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🤝</span>
                                        <span>Supplier</span>
                                    </a>
                                    <a href="{{ route('customers.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('customers.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>👥</span>
                                        <span>Pelanggan</span>
                                    </a>
                                    <a href="{{ route('payments.suppliers') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('payments.suppliers*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>💸</span>
                                        <span>Hutang Usaha</span>
                                    </a>
                                    <a href="{{ route('payments.customers') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('payments.customers*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>💰</span>
                                        <span>Piutang</span>
                                    </a>
                                    <a href="{{ route('cash-transactions.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('cash-transactions.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>💵</span>
                                        <span>Kas In / Out</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: Konfigurasi Bisnis --}}
                            <div class="border-t border-gray-100 pt-3">
                                <p class="px-2 text-[10px] font-black uppercase tracking-wider text-gray-400 mb-1.5 flex items-center gap-1">
                                    <span>⚙️</span> Bisnis & Konfigurasi
                                </p>
                                <div class="grid grid-cols-2 gap-1.5">
                                    <a href="{{ route('businesses.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('businesses.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🏢</span>
                                        <span>Unit Usaha</span>
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>⚙️</span>
                                        <span>Pengaturan</span>
                                    </a>
                                    <a href="{{ route('users.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('users.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>👤</span>
                                        <span>Staf / Kasir</span>
                                    </a>
                                    <a href="{{ route('galleries.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('galleries.*') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>🖼️</span>
                                        <span>Galeri Foto</span>
                                    </a>
                                    <a href="{{ route('docs') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('docs') ? 'bg-primary-50 text-primary-700 font-bold' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <span>📖</span>
                                        <span>Buku Panduan</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Modul: Super Administrator Platform --}}
                            @if(Auth::user()->isSuperAdmin())
                                <div class="border-t border-purple-100 pt-3 bg-purple-50/50 -mx-4 px-4 py-3 rounded-xl">
                                    <p class="px-2 text-[10px] font-black uppercase tracking-wider text-purple-700 mb-1.5 flex items-center gap-1">
                                        <span>👑</span> Super Admin Platform
                                    </p>
                                    <div class="space-y-1">
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 p-2 rounded-lg text-xs font-bold text-purple-800 bg-purple-100/70 hover:bg-purple-200/80 transition">
                                            <span>📊</span>
                                            <span>Dashboard Platform Global</span>
                                        </a>
                                        <div class="grid grid-cols-2 gap-1.5">
                                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-semibold text-purple-700 hover:bg-purple-100/60 transition">
                                                <span>👥</span>
                                                <span>Semua User</span>
                                            </a>
                                            <a href="{{ route('admin.businesses.index') }}" class="flex items-center gap-2 p-2 rounded-lg text-xs font-semibold text-purple-700 hover:bg-purple-100/60 transition">
                                                <span>🏢</span>
                                                <span>Semua Tenant</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Footer Offcanvas (Profil Pengguna & Logout) --}}
                        <div class="p-3 border-t border-gray-100 bg-gray-50/90 flex items-center justify-between">
                            <div class="flex items-center gap-2.5 truncate">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 text-primary-700 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <p class="text-xs font-bold text-dark truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('profile.edit') }}" class="p-1.5 text-gray-400 hover:text-gray-700 rounded-lg hover:bg-white transition" title="Profil">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 rounded-lg hover:bg-red-50 transition" title="Keluar">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="min-h-screen flex" style="background: linear-gradient(135deg, #FAFAF9 0%, #f0fdf4 30%, #FAFAF9 60%, #fff7ed 100%);">

            {{-- Desktop Left Sidebar (Collapsible) --}}
            <x-desktop-sidebar />

            {{-- Right Main Area Wrapper --}}
            <div class="flex-1 flex flex-col min-w-0">

                {{-- Top Header --}}
                <header class="sticky top-0 z-30 glass-nav transition-all duration-300"
                        x-data="{ scrolled: false, pageTitle: 'KarsaERP' }"
                        x-init="
                            window.addEventListener('scroll', () => {
                                scrolled = window.scrollY > 20;
                            });
                            
                            // Extract page title dynamically
                            setTimeout(() => {
                                const headerContainer = document.querySelector('main')?.previousElementSibling;
                                const h2El = headerContainer ? headerContainer.querySelector('h2') : null;
                                if (h2El) {
                                    pageTitle = h2El.textContent.trim();
                                } else {
                                    const parts = document.title.split('-');
                                    const rawTitle = parts[parts.length - 1]?.trim() || '';
                                    if (rawTitle && rawTitle !== 'KarsaERP') {
                                        pageTitle = rawTitle;
                                    }
                                }
                            }, 100);
                        ">
                    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-2.5 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            {{-- Mobile ERP Drawer Button --}}
                            <button type="button" @click="erpMenuOpen = true" class="lg:hidden w-9 h-9 rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-float hover:opacity-90 active:scale-95 transition cursor-pointer" title="Buka Menu Navigasi ERP">
                                <span class="text-white font-black text-xs">ERP</span>
                            </button>

                            {{-- Desktop Sidebar Toggle Button --}}
                            <button type="button" 
                                    @click="toggleSidebar()" 
                                    class="hidden lg:flex w-9 h-9 items-center justify-center rounded-xl bg-white/80 border border-gray-200/80 text-gray-600 hover:text-primary-600 hover:border-primary-300 shadow-xs active:scale-95 transition cursor-pointer" 
                                    title="Toggle Sidebar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </button>
                            <div class="relative h-9 min-w-[150px] overflow-hidden">
                            {{-- KarsaERP (Default State) --}}
                            <div x-show="!scrolled" 
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="absolute inset-y-0 left-0 flex flex-col justify-center">
                                <h1 class="text-base font-bold text-dark leading-tight">{{ \App\Models\Setting::get('company_name', 'KarsaERP') }}</h1>
                                <p class="text-[10px] text-gray-400 font-medium">Platform Cloud ERP & POS</p>
                            </div>

                            {{-- Page Title (Scrolled State) --}}
                            <div x-show="scrolled" 
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-2"
                                 class="absolute inset-y-0 left-0 flex flex-col justify-center"
                                 style="display: none;">
                                <h1 class="text-base font-bold text-primary-600 leading-tight truncate max-w-[200px]" x-text="pageTitle"></h1>
                                <p class="text-[10px] text-gray-400 font-medium">Halaman Aktif</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @auth
                            @php
                                $currBiz = \App\Helpers\TenantHelper::currentBusiness();
                                $userBusinesses = Auth::user()->allBusinesses();
                            @endphp

                            {{-- Active Business Switcher Dropdown --}}
                            <div x-data="{ bizOpen: false }" class="relative">
                                <button @click="bizOpen = !bizOpen" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white/80 hover:bg-white border border-gray-200 text-xs font-semibold text-dark transition shadow-xs max-w-[130px] sm:max-w-[170px]" title="Unit Usaha Aktif">
                                    <span class="text-sm">🏢</span>
                                    <span class="truncate">{{ $currBiz ? $currBiz->name : 'Pilih Usaha' }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="bizOpen" @click.away="bizOpen = false"
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-64 glass-card-solid p-2 z-50 rounded-xl shadow-xl border border-gray-100 divide-y divide-gray-100">
                                    <div class="px-2 py-1.5 pb-2">
                                        <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">Ganti Unit Usaha</p>
                                        <p class="text-[11px] text-gray-500 truncate">Aktif: <strong class="text-dark">{{ $currBiz->name ?? 'Belum dipilih' }}</strong></p>
                                    </div>
                                    <div class="max-h-48 overflow-y-auto py-1 space-y-0.5">
                                        @forelse($userBusinesses as $ub)
                                             <form action="{{ route('businesses.switch', $ub) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full text-left flex items-center justify-between p-2 rounded-lg text-xs transition {{ ($currBiz && $currBiz->id === $ub->id) ? 'bg-primary-50 text-primary-700 font-bold' : 'hover:bg-gray-50 text-gray-700' }}">
                                                    <div class="flex items-center gap-2 truncate">
                                                        <span>🏪</span>
                                                        <span class="truncate">{{ $ub->name }}</span>
                                                    </div>
                                                    @if($currBiz && $currBiz->id === $ub->id)
                                                        <span class="text-primary-600 text-xs font-black">✓</span>
                                                    @endif
                                                </button>
                                            </form>
                                        @empty
                                            <p class="text-xs text-gray-400 p-2 text-center italic">Belum ada usaha</p>
                                        @endforelse
                                    </div>
                                    <div class="pt-1.5 space-y-1">
                                        <a href="{{ route('businesses.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-semibold text-gray-600 hover:bg-gray-50">
                                            <span>📂</span> Kelola Semua Usaha
                                        </a>
                                        <a href="{{ route('businesses.create') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-bold text-primary-600 hover:bg-primary-50">
                                            <span>➕</span> Buat Usaha Baru
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @php
                                $activeRegister = \App\Models\CashRegister::currentOpenRegister();
                            @endphp
                            @if($activeRegister)
                                <a href="{{ route('cash-registers.show', $activeRegister->id) }}" class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-100 text-emerald-700 text-xs font-semibold border border-emerald-200 shadow-sm animate-pulse" title="Shift Kasir Aktif">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>Shift Aktif</span>
                                </a>
                            @endif

                            {{-- Profile Menu --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="w-9 h-9 rounded-lg bg-white/60 border border-white/40 flex items-center justify-center transition-all hover:bg-white/80">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-52 glass-card-solid p-2 z-50 rounded-xl shadow-xl border border-gray-100">
                                    <div class="px-3 py-2 border-b border-gray-100 mb-1">
                                        <p class="text-sm font-semibold text-dark">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 text-[9px] font-bold uppercase rounded-md {{ Auth::user()->isSuperAdmin() ? 'bg-purple-100 text-purple-700' : 'bg-primary-100 text-primary-700' }}">
                                            {{ Auth::user()->role }}
                                        </span>
                                    </div>

                                    @if(Auth::user()->isSuperAdmin())
                                        <div class="py-1 border-b border-gray-100 mb-1 space-y-0.5">
                                            <p class="px-3 py-0.5 text-[9px] font-bold text-purple-600 uppercase tracking-wider">Super Admin Platform</p>
                                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-1.5 text-xs text-purple-700 font-semibold rounded-lg hover:bg-purple-50 transition-colors">
                                                <span>📊</span> Admin Dashboard
                                            </a>
                                        </div>
                                    @endif

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Profil Akun
                                    </a>
                                    <a href="{{ route('businesses.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                        <span>🏢</span> Kelola Bisnis Saya
                                    </a>
                                    <a href="{{ route('settings.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        Pengaturan Toko
                                    </a>
                                    <a href="{{ route('docs') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-gray-600 rounded-lg hover:bg-primary-50 hover:text-primary-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        Buku Panduan (Docs)
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-gray-100 mt-1">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs text-red-600 rounded-lg hover:bg-red-50 transition-colors font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                            Keluar / Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- Flash Messages (SweetAlert2) --}}
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
                            customClass: {
                                popup: 'rounded-2xl font-sans shadow-lg'
                            }
                        });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: "{!! addslashes(session('error')) !!}",
                            customClass: {
                                popup: 'rounded-2xl font-sans shadow-lg'
                            }
                        });
                    });
                </script>
            @endif

            @if (isset($header) || View::hasSection('header'))
                <div class="max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 pt-4">
                    {{ $header ?? '' }}
                    @yield('header')
                </div>
            @endif

            {{-- Page Content --}}
            <main class="max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 pb-36 lg:pb-16 pt-2 page-enter flex-1">
                @yield('content')
                {{ $slot ?? '' }}
            </main>

            {{-- Floating Bottom Navigation (Mobile Only) --}}
            <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-4 pointer-events-none">
                <div class="max-w-md sm:max-w-lg mx-auto glass-nav rounded-xl px-2 py-1 pointer-events-auto shadow-lg">
                    <div class="flex items-center justify-around">
                        {{-- Dashboard --}}
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-2 px-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-primary-50/80' : 'hover:bg-gray-50/50' }}">
                            <div class="relative">
                                <svg class="w-6 h-6 transition-colors {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('dashboard') ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                @if(request()->routeIs('dashboard'))
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                                @endif
                            </div>
                            <span class="text-[10px] mt-1 font-semibold {{ request()->routeIs('dashboard') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}">Beranda</span>
                        </a>

                        {{-- Kasir POS --}}
                        <a href="{{ route('sales.create') }}" class="flex flex-col items-center py-2 px-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('sales.create') ? 'bg-primary-50/80' : 'hover:bg-gray-50/50' }}">
                            <div class="relative">
                                <svg class="w-6 h-6 transition-colors {{ request()->routeIs('sales.create') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('sales.create') ? '2.5' : '2' }}" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                </svg>
                                @if(request()->routeIs('sales.create'))
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                                @endif
                            </div>
                            <span class="text-[10px] mt-1 font-semibold {{ request()->routeIs('sales.create') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}">Kasir POS</span>
                        </a>

                        {{-- Stok & Opname --}}
                        <a href="{{ route('stock-adjustments.index') }}" class="flex flex-col items-center py-2 px-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('stock-adjustments.*', 'products.*') ? 'bg-primary-50/80' : 'hover:bg-gray-50/50' }}">
                            <div class="relative">
                                <svg class="w-6 h-6 transition-colors {{ request()->routeIs('stock-adjustments.*', 'products.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('stock-adjustments.*') ? '2.5' : '2' }}" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                @if(request()->routeIs('stock-adjustments.*'))
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                                @endif
                            </div>
                            <span class="text-[10px] mt-1 font-semibold {{ request()->routeIs('stock-adjustments.*', 'products.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}">Stok</span>
                        </a>

                        {{-- SDM & Penggajian --}}
                        <a href="{{ route('payrolls.index') }}" class="flex flex-col items-center py-2 px-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('payrolls.*', 'employees.*') ? 'bg-primary-50/80' : 'hover:bg-gray-50/50' }}">
                            <div class="relative">
                                <svg class="w-6 h-6 transition-colors {{ request()->routeIs('payrolls.*', 'employees.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ request()->routeIs('payrolls.*', 'employees.*') ? '2.5' : '2' }}" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                @if(request()->routeIs('payrolls.*', 'employees.*'))
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                                @endif
                            </div>
                            <span class="text-[10px] mt-1 font-semibold {{ request()->routeIs('payrolls.*', 'employees.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}">SDM / Gaji</span>
                        </a>

                        {{-- Menu Utama / ERP Offcanvas Trigger --}}
                        <button type="button" @click="erpMenuOpen = true" class="flex flex-col items-center py-2 px-3 rounded-lg transition-all duration-200 group {{ request()->routeIs('financial-reports.*', 'cash-registers.*', 'purchases.*', 'suppliers.*', 'customers.*', 'categories.*', 'units.*', 'payments.*', 'settings.*', 'cash-transactions.*', 'reports.*', 'users.*') ? 'bg-primary-50/80' : 'hover:bg-gray-50/50' }}" title="Buka Menu ERP">
                            <div class="relative">
                                <svg class="w-6 h-6 transition-colors {{ request()->routeIs('financial-reports.*', 'cash-registers.*', 'purchases.*', 'suppliers.*', 'customers.*', 'categories.*', 'units.*', 'payments.*', 'settings.*', 'cash-transactions.*', 'reports.*', 'users.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                                @if(request()->routeIs('financial-reports.*', 'cash-registers.*', 'purchases.*', 'suppliers.*', 'customers.*', 'categories.*', 'units.*', 'payments.*', 'settings.*', 'cash-transactions.*', 'reports.*', 'users.*'))
                                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-500 rounded-full"></span>
                                @endif
                            </div>
                            <span class="text-[10px] mt-1 font-semibold {{ request()->routeIs('financial-reports.*', 'cash-registers.*', 'purchases.*', 'suppliers.*', 'customers.*', 'categories.*', 'units.*', 'payments.*', 'settings.*', 'cash-transactions.*', 'reports.*', 'users.*') ? 'text-primary-600' : 'text-gray-400 group-hover:text-gray-600' }}">Menu ERP</span>
                        </button>
                    </div>
                </div>
            </nav>
        </div> {{-- Close right main area wrapper --}}
    </div> {{-- Close min-h-screen flex --}}

        {{-- Global Alpine.js helper for currency formatting --}}
        <script>
            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }

            function formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }
        </script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize Select2 globally on all standard select elements
                function initSelect2() {
                    $('select:not(.no-select2):not(.swal2-select)').each(function() {
                        if (!$(this).hasClass("select2-hidden-accessible")) {
                            $(this).select2({
                                width: '100%'
                            });
                            // Fix Safari/iOS validation bug for hidden required selects
                            if ($(this).attr('required')) {
                                $(this).removeAttr('required');
                            }
                        }
                    });
                }
                
                const d = new Date();
                const clientTodayStr = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
                const serverTodayStr = '{{ date('Y-m-d') }}';

                // Initialize Flatpickr globally on all date input fields
                function initFlatpickr() {
                    $('.datepicker:not([x-init])').each(function() {
                        if (!$(this).hasClass("flatpickr-input")) {
                            let defaultDateVal = $(this).val();

                            // If it matches server today (possibly wrong clock) or is empty and required, default to client today
                            if (defaultDateVal === serverTodayStr || (!defaultDateVal && $(this).prop('required'))) {
                                defaultDateVal = clientTodayStr;
                                $(this).val(clientTodayStr);
                            }

                            flatpickr(this, {
                                locale: 'id',
                                dateFormat: 'Y-m-d',
                                allowInput: true,
                                altInput: true,
                                altFormat: 'd F Y',
                                defaultDate: defaultDateVal || null,
                                disableMobile: true
                            });
                        }
                    });
                }

                initSelect2();
                initFlatpickr();

                // Make these globally accessible to run on demand (e.g. dynamic elements)
                window.reinitSelect2 = initSelect2;
                window.reinitFlatpickr = initFlatpickr;
            });
        </script>

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js')
                        .then(reg => console.log('Service Worker registered', reg))
                        .catch(err => console.error('Service Worker registration failed', err));
                });
            }
        </script>

        <!-- Cropper JS CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

        <!-- Global Image Crop Modal -->
        <div id="global-crop-modal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500/75 backdrop-blur-sm"></div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white/95 backdrop-blur-xl border border-white/50 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-4 w-full">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-dark" id="modal-title">Sesuaikan Gambar (Crop)</h3>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-center bg-gray-50 border border-gray-100 rounded-xl overflow-hidden max-h-[50vh]">
                        <img id="global-crop-img-element" class="max-w-full max-h-[50vh] block">
                    </div>
                    
                    {{-- Rotation & Flip Toolbar --}}
                    <div class="mt-3 flex items-center justify-center gap-1.5">
                        <button type="button" id="global-crop-rotate-left" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors border border-gray-200" title="Putar Kiri 90°">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a5 5 0 015 5v2M3 10l4-4M3 10l4 4"/></svg>
                        </button>
                        <button type="button" id="global-crop-rotate-right" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors border border-gray-200" title="Putar Kanan 90°">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10H11a5 5 0 00-5 5v2M21 10l-4-4M21 10l-4 4"/></svg>
                        </button>
                        <div class="w-px h-6 bg-gray-200 mx-1"></div>
                        <button type="button" id="global-crop-flip-h" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors border border-gray-200" title="Cermin Horizontal">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4l-4 4M17 16V4l4 4M12 2v20"/></svg>
                        </button>
                        <button type="button" id="global-crop-flip-v" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors border border-gray-200" title="Cermin Vertikal">
                            <svg class="w-4 h-4 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4l-4 4M17 16V4l4 4M12 2v20"/></svg>
                        </button>
                        <div class="w-px h-6 bg-gray-200 mx-1"></div>
                        <button type="button" id="global-crop-reset" class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors border border-gray-200" title="Reset">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0115.36-4.36M20 15a9 9 0 01-15.36 4.36"/></svg>
                        </button>
                    </div>
                    
                    <div class="mt-5 flex gap-2 justify-between">
                        <button type="button" id="global-crop-cancel" class="px-3 py-2 text-xs font-semibold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors border border-gray-200">
                            Batal
                        </button>
                        <div class="flex gap-2">
                            <button type="button" id="global-crop-skip" class="px-3 py-2 text-xs font-semibold text-primary-700 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors border border-primary-200">
                                Lewati Potong
                            </button>
                            <button type="button" id="global-crop-save" class="px-4 py-2 text-xs font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors shadow">
                                Potong & Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let currentCropper = null;

            // Pre-resize large images to prevent mobile browser freeze
            function preResizeImage(file, maxDim) {
                maxDim = maxDim || 2048;
                return new Promise(function(resolve) {
                    // Skip non-images
                    if (!file || !file.type.startsWith('image/')) {
                        resolve(file);
                        return;
                    }
                    
                    var img = new Image();
                    var url = URL.createObjectURL(file);
                    img.onload = function() {
                        // If image is already small enough, return original
                        if (img.naturalWidth <= maxDim && img.naturalHeight <= maxDim) {
                            URL.revokeObjectURL(url);
                            resolve(file);
                            return;
                        }
                        
                        // Calculate new dimensions
                        var ratio = Math.min(maxDim / img.naturalWidth, maxDim / img.naturalHeight);
                        var newW = Math.round(img.naturalWidth * ratio);
                        var newH = Math.round(img.naturalHeight * ratio);
                        
                        // Draw to offscreen canvas
                        var canvas = document.createElement('canvas');
                        canvas.width = newW;
                        canvas.height = newH;
                        var ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, newW, newH);
                        URL.revokeObjectURL(url);
                        
                        canvas.toBlob(function(blob) {
                            if (blob) {
                                var resizedFile = new File([blob], file.name, { type: 'image/jpeg' });
                                resolve(resizedFile);
                            } else {
                                resolve(file);
                            }
                            // Free canvas memory
                            canvas.width = 0;
                            canvas.height = 0;
                        }, 'image/jpeg', 0.85);
                    };
                    img.onerror = function() {
                        URL.revokeObjectURL(url);
                        resolve(file);
                    };
                    img.src = url;
                });
            }

            window.cropImage = function(file, successCallback, skipCallback, cancelCallback) {
                if (!file || !file.type.startsWith('image/')) {
                    if (skipCallback) skipCallback(file);
                    return;
                }

                // Show loading while pre-processing large images
                Swal.fire({
                    title: 'Memproses gambar...',
                    text: 'Mengoptimalkan ukuran gambar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: function() { Swal.showLoading(); },
                    customClass: { popup: 'rounded-2xl font-sans' }
                });

                preResizeImage(file, 2048).then(function(processedFile) {
                    Swal.close();

                    var modal = document.getElementById('global-crop-modal');
                    var imgElement = document.getElementById('global-crop-img-element');
                    var saveBtn = document.getElementById('global-crop-save');
                    var skipBtn = document.getElementById('global-crop-skip');
                    var cancelBtn = document.getElementById('global-crop-cancel');

                    // Use createObjectURL instead of readAsDataURL (much less memory)
                    var objectUrl = URL.createObjectURL(processedFile);
                    imgElement.src = objectUrl;
                    
                    imgElement.onload = function() {
                        modal.classList.remove('hidden');
                        
                        if (currentCropper) {
                            currentCropper.destroy();
                        }
                        
                        currentCropper = new Cropper(imgElement, {
                            aspectRatio: NaN,
                            viewMode: 2,
                            autoCropArea: 0.9,
                            responsive: true,
                            restore: false,
                            checkCrossOrigin: false,
                            rotateable: true
                        });
                    };

                    function closeModal() {
                        modal.classList.add('hidden');
                        if (currentCropper) {
                            currentCropper.destroy();
                            currentCropper = null;
                        }
                        URL.revokeObjectURL(objectUrl);
                    }

                    saveBtn.onclick = function() {
                        if (!currentCropper) return;
                        currentCropper.getCroppedCanvas({
                            maxWidth: 1920,
                            maxHeight: 1920,
                            imageSmoothingQuality: 'high'
                        }).toBlob(function(blob) {
                            if (blob) {
                                successCallback(blob);
                            } else {
                                skipCallback(processedFile);
                            }
                            closeModal();
                        }, 'image/jpeg', 0.85);
                    };

                    skipBtn.onclick = function() {
                        skipCallback(processedFile);
                        closeModal();
                    };

                    cancelBtn.onclick = function() {
                        if (cancelCallback) cancelCallback();
                        closeModal();
                    };

                    // Rotation & Flip handlers
                    var flipH = 1, flipV = 1;
                    document.getElementById('global-crop-rotate-left').onclick = function() {
                        if (currentCropper) currentCropper.rotate(-90);
                    };
                    document.getElementById('global-crop-rotate-right').onclick = function() {
                        if (currentCropper) currentCropper.rotate(90);
                    };
                    document.getElementById('global-crop-flip-h').onclick = function() {
                        if (currentCropper) { flipH = flipH * -1; currentCropper.scaleX(flipH); }
                    };
                    document.getElementById('global-crop-flip-v').onclick = function() {
                        if (currentCropper) { flipV = flipV * -1; currentCropper.scaleY(flipV); }
                    };
                    document.getElementById('global-crop-reset').onclick = function() {
                        if (currentCropper) { flipH = 1; flipV = 1; currentCropper.reset(); }
                    };
                });
            };

            // Global loading overlay on form submit (except GET filter forms or AJAX)
            document.addEventListener('submit', function(e) {
                const form = e.target;
                
                // Do not show loading for GET requests (like filters or search forms)
                if (form.getAttribute('method')?.toLowerCase() === 'get') {
                    return;
                }
                
                // If it is a confirm-delete form and not yet confirmed
                if (form.classList.contains('confirm-delete') && form.dataset.confirmed !== 'true') {
                    e.preventDefault();
                    const message = form.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
                    
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#9ca3af',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-2xl font-sans shadow-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = 'true';
                            // Show loading SweetAlert manually
                            Swal.fire({
                                title: 'Memproses...',
                                text: 'Mohon tunggu sebentar.',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                customClass: {
                                    popup: 'rounded-2xl font-sans'
                                }
                            });
                            form.submit();
                        }
                    });
                    return;
                }
                
                // Do not show loading for forms that are flagged with 'no-loading'
                if (form.classList.contains('no-loading')) {
                    return;
                }

                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    customClass: {
                        popup: 'rounded-2xl font-sans'
                    }
                });
            });
        </script>

        @stack('scripts')
    </body>
</html>
