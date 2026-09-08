<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-dark">Laporan Arus Kas (Cash Flow Statement)</h2>
                <p class="text-xs text-gray-500">Mutasi penerimaan dan pengeluaran kas riil selama periode berjalan</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="btn-secondary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-4 pb-12 space-y-5">
        <x-financial-report-subnav />

        {{-- Filter Periode Form --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('financial-reports.cash-flow') }}" class="flex flex-col sm:flex-row items-end gap-3">
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-input-glass text-xs" required>
                </div>
                <div class="w-full sm:w-48">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-input-glass text-xs" required>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-4 flex-1 sm:flex-none">
                        Filter Laporan
                    </button>
                    <a href="{{ route('financial-reports.cash-flow', ['start_date' => now()->startOfMonth()->toDateString(), 'end_date' => now()->endOfMonth()->toDateString()]) }}" class="btn-secondary text-xs py-2.5 px-3">
                        Bulan Ini
                    </a>
                </div>
            </form>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="glass-card p-4 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Saldo Kas Awal</span>
                <span class="text-base sm:text-lg font-bold text-dark mt-1 block truncate" title="Rp {{ number_format($beginningCash, 0, ',', '.') }}">Rp {{ number_format($beginningCash, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Sebelum {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-emerald-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Total Kas Masuk (+)</span>
                <span class="text-base sm:text-lg font-bold text-emerald-600 mt-1 block truncate" title="Rp {{ number_format($totalInflow, 0, ',', '.') }}">Rp {{ number_format($totalInflow, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Penjualan & Pemasukan</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-rose-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Total Kas Keluar (-)</span>
                <span class="text-base sm:text-lg font-bold text-rose-600 mt-1 block truncate" title="Rp {{ number_format($totalOutflow, 0, ',', '.') }}">Rp {{ number_format($totalOutflow, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Belanja, Gaji & Beban</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-blue-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Perubahan Kas Bersih</span>
                <span class="text-base sm:text-lg font-bold {{ $netCashChange >= 0 ? 'text-blue-600' : 'text-rose-600' }} mt-1 block truncate" title="{{ ($netCashChange > 0 ? '+' : '') }}Rp {{ number_format($netCashChange, 0, ',', '.') }}">
                    {{ ($netCashChange > 0 ? '+' : '') }}Rp {{ number_format($netCashChange, 0, ',', '.') }}
                </span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Net Cash Flow</span>
            </div>

            <div class="glass-card-solid p-4 bg-gradient-to-br from-primary-900 to-slate-900 text-white shadow-xl min-w-0 sm:col-span-2 lg:col-span-1">
                <span class="text-xs font-semibold text-emerald-300 block truncate">Saldo Kas Akhir</span>
                <span class="text-lg sm:text-xl font-extrabold mt-1 block truncate" title="Rp {{ number_format($endingCash, 0, ',', '.') }}">Rp {{ number_format($endingCash, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-300 mt-0.5 block truncate">Posisi Kas Berjalan</span>
            </div>
        </div>

        {{-- Detailed Cash Flow Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Laporan Mutasi Arus Kas Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h3>
            </div>

            <div class="p-5 space-y-6">
                {{-- SALDO AWAL --}}
                <div class="p-3.5 rounded-lg bg-gray-50 border border-gray-200 flex justify-between items-center text-sm font-bold text-gray-800">
                    <span>SALDO AWAL KAS & BANK (Per {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }})</span>
                    <span class="text-base text-dark">Rp {{ number_format($beginningCash, 0, ',', '.') }}</span>
                </div>

                {{-- 1. ARUS KAS MASUK --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between font-bold text-dark text-sm border-b border-gray-200 pb-1.5 bg-emerald-50/50 p-2 rounded-lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded bg-emerald-500 inline-block"></span>
                            1. ARUS KAS MASUK (INFLOWS)
                        </span>
                        <span class="text-emerald-700">Rp {{ number_format($totalInflow, 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-6 pr-2 space-y-1 text-xs">
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Penerimaan Kas Penjualan POS (Tunai, QRIS, Transfer)</span>
                            <span class="font-medium text-emerald-600">+ Rp {{ number_format($cashSalesIn, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pelunasan Piutang dari Pelanggan</span>
                            <span class="font-medium text-emerald-600">+ Rp {{ number_format($debtCollectionsIn, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Penerimaan Kas & Pendapatan Operasional Lainnya</span>
                            <span class="font-medium text-emerald-600">+ Rp {{ number_format($otherInflows, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 font-bold text-dark border-t border-gray-200">
                            <span>Total Arus Kas Masuk:</span>
                            <span class="text-emerald-600">Rp {{ number_format($totalInflow, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- 2. ARUS KAS KELUAR --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between font-bold text-dark text-sm border-b border-gray-200 pb-1.5 bg-rose-50/50 p-2 rounded-lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded bg-rose-500 inline-block"></span>
                            2. ARUS KAS KELUAR (OUTFLOWS)
                        </span>
                        <span class="text-rose-700">Rp {{ number_format($totalOutflow, 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-6 pr-2 space-y-1 text-xs">
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pembelian Stok Barang Tunai</span>
                            <span class="font-medium text-rose-600">- Rp {{ number_format($cashPurchasesOut, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pembayaran Hutang ke Supplier</span>
                            <span class="font-medium text-rose-600">- Rp {{ number_format($supplierDebtPaymentsOut, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pembayaran Gaji & Bonus Karyawan (Payroll)</span>
                            <span class="font-medium text-rose-600">- Rp {{ number_format($payrollOut, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pengeluaran Kas Beban Operasional Lainnya</span>
                            <span class="font-medium text-rose-600">- Rp {{ number_format($otherExpensesOut, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 font-bold text-dark border-t border-gray-200">
                            <span>Total Arus Kas Keluar:</span>
                            <span class="text-rose-600">Rp {{ number_format($totalOutflow, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- PERUBAHAN BERSIH --}}
                <div class="p-3.5 rounded-lg bg-blue-50/70 border border-blue-200 flex justify-between items-center text-sm font-bold text-blue-900">
                    <span>KENAIKAN / (PENURUNAN) KAS BERSIH</span>
                    <span class="text-base {{ $netCashChange >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        {{ ($netCashChange > 0 ? '+' : '') }}Rp {{ number_format($netCashChange, 0, ',', '.') }}
                    </span>
                </div>

                {{-- SALDO AKHIR --}}
                <div class="p-4 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-md flex justify-between items-center text-base font-extrabold">
                    <div class="flex flex-col">
                        <span>SALDO AKHIR KAS & BANK</span>
                        <span class="text-xs font-normal text-emerald-100">Per Tanggal {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</span>
                    </div>
                    <span class="text-2xl">
                        Rp {{ number_format($endingCash, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
