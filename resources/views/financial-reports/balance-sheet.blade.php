<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-dark">Laporan Neraca (Balance Sheet)</h2>
                <p class="text-xs text-gray-500">Posisi kekayaan, piutang, persediaan, kewajiban, dan ekuitas bisnis</p>
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

        {{-- Filter Per Tanggal --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('financial-reports.balance-sheet') }}" class="flex flex-col sm:flex-row items-end gap-3">
                <div class="w-full sm:w-64">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Posisi Neraca Per Tanggal</label>
                    <input type="date" name="as_of_date" value="{{ $asOfDate }}" class="form-input-glass text-xs" required>
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-4 flex-1 sm:flex-none">
                        Tampilkan Neraca
                    </button>
                    <a href="{{ route('financial-reports.balance-sheet', ['as_of_date' => now()->toDateString()]) }}" class="btn-secondary text-xs py-2.5 px-3">
                        Hari Ini
                    </a>
                </div>
            </form>
        </div>

        {{-- Balance Status Banner --}}
        <div class="glass-card p-4 flex items-center justify-between {{ abs($totalAssets - $totalLiabilitiesAndEquity) < 1 ? 'bg-emerald-50/70 border border-emerald-200 text-emerald-900' : 'bg-amber-50/70 border border-amber-200 text-amber-900' }}">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg {{ abs($totalAssets - $totalLiabilitiesAndEquity) < 1 ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center font-bold">
                    @if(abs($totalAssets - $totalLiabilitiesAndEquity) < 1)
                        ✓
                    @else
                        !
                    @endif
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider">Status Keseimbangan Neraca (Balance Check)</h4>
                    <p class="text-xs">
                        @if(abs($totalAssets - $totalLiabilitiesAndEquity) < 1)
                            Neraca Sempurna Seimbang (Total Aktiva = Total Pasiva).
                        @else
                            Terdapat penyesuaian modal berjalan untuk menyelaraskan neraca.
                        @endif
                    </p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs text-gray-500 block">Total Valuasi</span>
                <span class="text-sm font-extrabold text-dark">Rp {{ number_format($totalAssets, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- 2-Column Balance Sheet (Aktiva vs Pasiva) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">
            {{-- AKTIVA (ASSETS) --}}
            <div class="glass-card overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 bg-emerald-700 text-white flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-sm">AKTIVA (ASET USAHA)</h3>
                            <p class="text-[11px] text-emerald-100">Segala sumber daya ekonomis milik bisnis</p>
                        </div>
                        <span class="text-sm font-black text-emerald-200">AKTIVA</span>
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- Aset Lancar --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">Aset Lancar (Current Assets)</h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Kas & Saldo Bank</p>
                                        <p class="text-[10px] text-gray-400">Kas kasir, brankas toko, dan rekening bank</p>
                                    </div>
                                    <span class="font-bold text-dark">Rp {{ number_format($totalCashBank, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Piutang Usaha (Pelanggan)</p>
                                        <p class="text-[10px] text-gray-400">Tagihan penjualan tempo yang belum lunas</p>
                                    </div>
                                    <span class="font-bold text-dark">Rp {{ number_format($totalReceivable, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Persediaan Barang Dagang</p>
                                        <p class="text-[10px] text-gray-400">Valuasi stok fisik di gudang & toko (HPP)</p>
                                    </div>
                                    <span class="font-bold text-dark">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    {{-- Total Aktiva --}}
                    <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex justify-between items-center text-sm font-bold text-emerald-900 mt-2">
                        <span>TOTAL AKTIVA:</span>
                        <span class="text-base text-emerald-700">Rp {{ number_format($totalAssets, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- PASIVA (LIABILITIES & EQUITY) --}}
            <div class="glass-card overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="p-4 bg-slate-800 text-white flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-sm">PASIVA (KEWAJIBAN & EKUITAS)</h3>
                            <p class="text-[11px] text-slate-300">Kewajiban hutang dan modal pemilik usaha</p>
                        </div>
                        <span class="text-sm font-black text-slate-300">PASIVA</span>
                    </div>

                    <div class="p-5 space-y-4">
                        {{-- Kewajiban --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1">1. Kewajiban / Hutang (Liabilities)</h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Hutang Usaha (Supplier)</p>
                                        <p class="text-[10px] text-gray-400">Tagihan pembelian stok tempo dari supplier</p>
                                    </div>
                                    <span class="font-bold text-rose-600">Rp {{ number_format($totalSupplierDebt, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Ekuitas --}}
                        <div>
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-100 pb-1 mt-4">2. Ekuitas / Modal (Equity)</h4>
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Modal Awal Disetor</p>
                                        <p class="text-[10px] text-gray-400">Modal disetor pemilik pada awal usaha</p>
                                    </div>
                                    <span class="font-bold text-dark">Rp {{ number_format($initialEquity, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between py-1.5 border-b border-gray-50">
                                    <div>
                                        <p class="font-bold text-dark">Laba Berjalan / Ditahan</p>
                                        <p class="text-[10px] text-gray-400">Akumulasi keuntungan usaha hingga saat ini</p>
                                    </div>
                                    <span class="font-bold {{ $retainedEarnings >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Rp {{ number_format($retainedEarnings, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 pt-0">
                    {{-- Total Pasiva --}}
                    <div class="p-4 rounded-lg bg-slate-100 border border-slate-300 flex justify-between items-center text-sm font-bold text-slate-900 mt-2">
                        <span>TOTAL PASIVA (HUTANG + MODAL):</span>
                        <span class="text-base text-slate-800">Rp {{ number_format($totalLiabilitiesAndEquity, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
