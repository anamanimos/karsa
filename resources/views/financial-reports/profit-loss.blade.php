<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-dark">Laporan Laba Rugi (Income Statement)</h2>
                <p class="text-xs text-gray-500">Ikhtisar pendapatan, HPP, beban operasional, dan laba bersih usaha</p>
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
            <form method="GET" action="{{ route('financial-reports.profit-loss') }}" class="flex flex-col sm:flex-row items-end gap-3">
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
                    <a href="{{ route('financial-reports.profit-loss', ['start_date' => now()->startOfMonth()->toDateString(), 'end_date' => now()->endOfMonth()->toDateString()]) }}" class="btn-secondary text-xs py-2.5 px-3">
                        Bulan Ini
                    </a>
                </div>
            </form>
        </div>

        {{-- Executive KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-card p-4 border-l-4 border-primary-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Total Pendapatan (Revenue)</span>
                <span class="text-lg sm:text-xl lg:text-xl xl:text-2xl font-extrabold text-dark mt-1 block truncate" title="Rp {{ number_format($totalRevenue, 0, ',', '.') }}">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Penjualan + Pendapatan Lain</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-amber-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Beban Pokok Penjualan (HPP)</span>
                <span class="text-lg sm:text-xl lg:text-xl xl:text-2xl font-extrabold text-amber-600 mt-1 block truncate" title="Rp {{ number_format($cogs, 0, ',', '.') }}">Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Modal Pokok Barang Terjual</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-blue-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Laba Kotor (Gross Profit)</span>
                <span class="text-lg sm:text-xl lg:text-xl xl:text-2xl font-extrabold {{ $grossProfit >= 0 ? 'text-blue-600' : 'text-rose-600' }} mt-1 block truncate" title="Rp {{ number_format($grossProfit, 0, ',', '.') }}">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block truncate">Margin: {{ $totalRevenue > 0 ? round(($grossProfit / $totalRevenue) * 100, 1) : 0 }}%</span>
            </div>

            <div class="glass-card-solid p-4 bg-gradient-to-br {{ $netProfit >= 0 ? 'from-emerald-700 to-teal-900' : 'from-rose-700 to-red-950' }} text-white shadow-xl min-w-0">
                <span class="text-xs font-semibold text-emerald-200 block truncate">Laba Bersih (Net Profit)</span>
                <span class="text-lg sm:text-xl lg:text-xl xl:text-2xl font-black mt-1 block truncate" title="Rp {{ number_format($netProfit, 0, ',', '.') }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
                <span class="text-[11px] text-white/80 mt-0.5 block truncate">Net Margin: {{ $totalRevenue > 0 ? round(($netProfit / $totalRevenue) * 100, 1) : 0 }}%</span>
            </div>
        </div>

        {{-- Detailed Financial Statement Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Rincian Laba Rugi Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</h3>
            </div>

            <div class="p-5 space-y-6">
                {{-- 1. PENDAPATAN --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between font-bold text-dark text-sm border-b border-gray-200 pb-1.5 bg-gray-50/50 p-2 rounded-lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                            1. PENDAPATAN USAHA (REVENUE)
                        </span>
                        <span class="text-emerald-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-6 pr-2 space-y-1 text-xs">
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Penjualan Kotor Produk (Gross Sales)</span>
                            <span class="font-medium">Rp {{ number_format($grossSales, 0, ',', '.') }}</span>
                        </div>
                        @if($totalDiscount > 0)
                            <div class="flex justify-between py-1 text-rose-600 border-b border-gray-50">
                                <span>Potongan & Diskon Penjualan (-)</span>
                                <span class="font-medium">- Rp {{ number_format($totalDiscount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Pendapatan Jasa / Operasional Lainnya</span>
                            <span class="font-medium">Rp {{ number_format($totalOtherRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 font-bold text-dark border-t border-gray-200">
                            <span>Total Pendapatan Bersih:</span>
                            <span class="text-emerald-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- 2. HPP --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between font-bold text-dark text-sm border-b border-gray-200 pb-1.5 bg-gray-50/50 p-2 rounded-lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            2. HARGA POKOK PENJUALAN (HPP / COGS)
                        </span>
                        <span class="text-amber-700">Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-6 pr-2 space-y-1 text-xs">
                        <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                            <span>Beban Pokok Barang Terjual</span>
                            <span class="font-medium">Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-1.5 font-bold text-dark border-t border-gray-200">
                            <span>Total Beban Pokok:</span>
                            <span class="text-amber-600">Rp {{ number_format($cogs, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- LABA KOTOR SUB-TOTAL --}}
                <div class="p-3 rounded-lg bg-blue-50/70 border border-blue-200 flex justify-between items-center text-sm font-bold text-blue-900">
                    <span>LABA KOTOR (GROSS PROFIT):</span>
                    <span class="text-base text-blue-700">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>

                {{-- 3. BEBAN OPERASIONAL --}}
                <div class="space-y-2">
                    <div class="flex items-center justify-between font-bold text-dark text-sm border-b border-gray-200 pb-1.5 bg-gray-50/50 p-2 rounded-lg">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded bg-rose-500 inline-block"></span>
                            3. BEBAN OPERASIONAL & BIAYA USAHA
                        </span>
                        <span class="text-rose-700">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                    </div>
                    <div class="pl-6 pr-2 space-y-1 text-xs">
                        @forelse($expensesByCategory as $expense)
                            <div class="flex justify-between py-1 text-gray-600 border-b border-gray-50">
                                <span>{{ $expense->category ?: 'Beban Lain-lain' }} ({{ $expense->total_count }} transaksi)</span>
                                <span class="font-medium text-rose-600">Rp {{ number_format($expense->total_amount, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="py-2 text-center text-gray-400 italic">
                                Belum ada catatan beban operasional pada periode ini.
                            </div>
                        @endforelse
                        <div class="flex justify-between py-1.5 font-bold text-dark border-t border-gray-200">
                            <span>Total Beban Operasional:</span>
                            <span class="text-rose-600">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- LABA BERSIH TOTAL --}}
                <div class="p-4 rounded-lg {{ $netProfit >= 0 ? 'bg-emerald-50 border-emerald-300 text-emerald-900' : 'bg-rose-50 border-rose-300 text-rose-900' }} border flex justify-between items-center text-base font-extrabold">
                    <div class="flex flex-col">
                        <span>LABA / (RUGI) BERSIH USAHA (NET PROFIT)</span>
                        <span class="text-xs font-medium text-gray-500">Laba Kotor dikurangi Total Beban Operasional</span>
                    </div>
                    <span class="text-xl {{ $netProfit >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                        Rp {{ number_format($netProfit, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
