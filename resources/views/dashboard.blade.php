@extends('layouts.app')

@section('title', 'Dashboard ERP')

@section('content')
<div class="space-y-4">
    {{-- Header Banner & Quick Shift --}}
    <div class="glass-card-solid p-4 rounded-xl border border-white/60 shadow-sm relative overflow-hidden bg-gradient-to-r from-emerald-600 via-primary-600 to-teal-700 text-white">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <p class="text-xs text-emerald-100 font-medium">Selamat Datang,</p>
                <h2 class="text-lg font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-[11px] text-emerald-100 mt-0.5">{{ \App\Models\Setting::get('company_name', 'KarsaERP') }} &bull; {{ date('d F Y') }}</p>
            </div>
            <div>
                @if($currentRegister)
                    <a href="{{ route('cash-registers.show', $currentRegister->id) }}" class="px-3 py-1.5 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md text-xs font-semibold border border-white/30 flex items-center gap-1.5 transition-all">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                        <span>Shift Terbuka</span>
                    </a>
                @else
                    <a href="{{ route('cash-registers.index') }}" class="px-3 py-1.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-gray-900 text-xs font-bold shadow transition-all flex items-center gap-1">
                        <span>🔓 Buka Kasir</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Financial KPI Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        {{-- Penjualan Hari Ini --}}
        <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100/80 shadow-sm min-w-0">
            <div class="flex items-center justify-between text-gray-500 mb-1">
                <span class="text-xs font-medium truncate">Penjualan Hari Ini</span>
                <span class="text-xs p-1 rounded-lg bg-emerald-50 text-emerald-600 flex-shrink-0">🛒</span>
            </div>
            <p class="text-base font-extrabold text-dark truncate" title="Rp {{ number_format($todaySales, 0, ',', '.') }}">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $todayTransactions }} transaksi hari ini</p>
        </div>

        {{-- Kas & Bank Berjalan --}}
        <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100/80 shadow-sm min-w-0">
            <div class="flex items-center justify-between text-gray-500 mb-1">
                <span class="text-xs font-medium truncate">Total Kas & Bank</span>
                <span class="text-xs p-1 rounded-lg bg-blue-50 text-blue-600 flex-shrink-0">🏦</span>
            </div>
            <p class="text-base font-extrabold text-dark truncate" title="Rp {{ number_format($totalCash, 0, ',', '.') }}">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
            <a href="{{ route('financial-reports.general-ledger') }}" class="text-[10px] text-primary-600 font-semibold hover:underline mt-0.5 block truncate">Lihat Buku Kas &rarr;</a>
        </div>

        {{-- Piutang Pelanggan --}}
        <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100/80 shadow-sm min-w-0">
            <div class="flex items-center justify-between text-gray-500 mb-1">
                <span class="text-xs font-medium truncate">Piutang Pelanggan</span>
                <span class="text-xs p-1 rounded-lg bg-amber-50 text-amber-600 flex-shrink-0">⏳</span>
            </div>
            <p class="text-base font-extrabold text-amber-600 truncate" title="Rp {{ number_format($totalReceivables, 0, ',', '.') }}">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</p>
            <a href="{{ route('payments.customers') }}" class="text-[10px] text-gray-400 hover:text-primary-600 mt-0.5 block truncate">Penagihan Piutang &rarr;</a>
        </div>

        {{-- Hutang Supplier --}}
        <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100/80 shadow-sm min-w-0">
            <div class="flex items-center justify-between text-gray-500 mb-1">
                <span class="text-xs font-medium truncate">Hutang ke Supplier</span>
                <span class="text-xs p-1 rounded-lg bg-red-50 text-red-600 flex-shrink-0">📑</span>
            </div>
            <p class="text-base font-extrabold text-red-600 truncate" title="Rp {{ number_format($totalPayables, 0, ',', '.') }}">Rp {{ number_format($totalPayables, 0, ',', '.') }}</p>
            <a href="{{ route('payments.suppliers') }}" class="text-[10px] text-gray-400 hover:text-primary-600 mt-0.5 block truncate">Pelunasan Hutang &rarr;</a>
        </div>
    </div>

    {{-- Lower Dashboard Responsive 2-Column Section on Desktop --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
        {{-- Left Column (Shortcuts & Recent Transactions) --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Quick Shortcuts Grid --}}
            <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100 shadow-sm">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Akses Cepat Modul ERP</h3>
                <div class="grid grid-cols-4 gap-2 text-center">
                    <a href="{{ route('sales.create') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-emerald-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg mb-1 group-hover:scale-105 transition-transform">
                            🛒
                        </div>
                        <span class="text-[11px] font-semibold text-gray-700">Kasir</span>
                    </a>

                    <a href="{{ route('stock-adjustments.create') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-blue-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-lg mb-1 group-hover:scale-105 transition-transform">
                            📋
                        </div>
                        <span class="text-[11px] font-semibold text-gray-700">Opname</span>
                    </a>

                    <a href="{{ route('payrolls.index') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-purple-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center text-lg mb-1 group-hover:scale-105 transition-transform">
                            👥
                        </div>
                        <span class="text-[11px] font-semibold text-gray-700">Gaji SDM</span>
                    </a>

                    <a href="{{ route('financial-reports.profit-loss') }}" class="flex flex-col items-center p-2 rounded-lg hover:bg-amber-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-lg mb-1 group-hover:scale-105 transition-transform">
                            📈
                        </div>
                        <span class="text-[11px] font-semibold text-gray-700">Laba Rugi</span>
                    </a>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="glass-card-solid p-3.5 rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Penjualan Terakhir</h3>
                    <a href="{{ route('sales.index') }}" class="text-[10px] text-primary-600 font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentSales as $sale)
                        <div class="py-2 flex items-center justify-between">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-xs text-dark">{{ $sale->invoice_number }}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold {{ $sale->payment_method === 'cash' ? 'bg-emerald-50 text-emerald-700' : ($sale->payment_method === 'credit' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">
                                        {{ strtoupper($sale->payment_method) }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-400 mt-0.5">{{ $sale->customer?->name ?? 'Pelanggan Umum' }} &bull; {{ $sale->sale_date->format('H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-extrabold text-dark">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
                                <a href="{{ route('sales.show', $sale) }}" class="text-[10px] text-primary-600 hover:underline">Detail</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 text-center py-3">Belum ada transaksi penjualan hari ini.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column (HR / Payroll Banner & Low Stock Warning) --}}
        <div class="space-y-4">
            {{-- HR & Payroll Notification Banner --}}
            <div class="glass-card-solid p-3 rounded-xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                        {{ $activeEmployees }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Karyawan Aktif</p>
                        <p class="text-[10px] text-gray-400">Gaji Pending: Rp {{ number_format($payrollPending, 0, ',', '.') }}</p>
                    </div>
                </div>
                <a href="{{ route('payrolls.index') }}" class="px-2.5 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 text-xs font-bold transition-colors">
                    Kelola Gaji &rarr;
                </a>
            </div>

            {{-- Low Stock Warning Alert --}}
            @if($lowStockProducts->isNotEmpty())
                <div class="glass-card-solid p-3.5 rounded-xl border border-amber-200/80 bg-amber-50/40 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-bold text-amber-900 flex items-center gap-1.5">
                            <span>⚠️</span> Peringatan Stok Menipis ({{ $lowStockProducts->count() }})
                        </h3>
                        <a href="{{ route('stock-adjustments.create', ['type' => 'in_manual']) }}" class="text-[10px] text-amber-800 font-bold hover:underline">+ Tambah Stok</a>
                    </div>
                    <div class="divide-y divide-amber-100">
                        @foreach($lowStockProducts->take(4) as $prod)
                            <div class="py-1.5 flex items-center justify-between text-xs">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $prod->name }}</p>
                                    <p class="text-[10px] text-gray-400">Min. {{ $prod->min_stock }} {{ $prod->sellUnit?->symbol }}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 font-extrabold text-xs">
                                    Sisa: {{ $prod->stock }} {{ $prod->sellUnit?->symbol }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
