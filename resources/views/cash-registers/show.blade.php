<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('cash-registers.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-dark">Rekonsiliasi Shift Kasir</h2>
                    <p class="text-xs text-gray-500">Kasir: {{ $cashRegister->user->name ?? 'User' }} • {{ \Carbon\Carbon::parse($cashRegister->opened_at)->translatedFormat('d F Y, H:i') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="btn-secondary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Rekonsiliasi
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24 space-y-5">
        {{-- Status & Meta Card --}}
        <div class="glass-card p-5">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Kasir Penanggung Jawab</span>
                    <span class="text-sm font-bold text-dark">{{ $cashRegister->user->name ?? 'User' }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Waktu Buka Shift</span>
                    <span class="text-sm font-semibold text-gray-700">{{ \Carbon\Carbon::parse($cashRegister->opened_at)->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Waktu Tutup Shift</span>
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $cashRegister->closed_at ? \Carbon\Carbon::parse($cashRegister->closed_at)->translatedFormat('d M Y, H:i') : 'Masih Aktif' }}
                    </span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Status Shift</span>
                    @if($cashRegister->status === 'open')
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">
                            SEDANG AKTIF
                        </span>
                    @else
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 text-xs font-bold rounded-full bg-gray-100 text-gray-700">
                            SUDAH DITUTUP
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Cash Breakdown & Reconciliation --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {{-- Rekonsiliasi Kas Tunai Laci --}}
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-primary-50/50">
                    <h3 class="font-bold text-dark text-sm">💵 Rekonsiliasi Kas Tunai di Laci</h3>
                    <span class="text-xs font-bold text-primary-700">Cash Drawer</span>
                </div>

                <div class="p-5 space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-gray-50 text-gray-600">
                        <span>(+) Modal Awal Kas Kecil (Cash Float):</span>
                        <span class="font-bold text-dark">Rp {{ number_format($cashRegister->initial_cash, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between py-1.5 border-b border-gray-50 text-gray-600">
                        <span>(+) Penjualan Kas Tunai (POS):</span>
                        <span class="font-bold text-emerald-600">+ Rp {{ number_format($cashRegister->total_cash_sales, 0, ',', '.') }}</span>
                    </div>

                    @if($cashRegister->total_cash_in > 0)
                        <div class="flex justify-between py-1.5 border-b border-gray-50 text-gray-600">
                            <span>(+) Kas Masuk Tambahan:</span>
                            <span class="font-bold text-emerald-600">+ Rp {{ number_format($cashRegister->total_cash_in, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($cashRegister->total_cash_out > 0)
                        <div class="flex justify-between py-1.5 border-b border-gray-50 text-gray-600">
                            <span>(-) Pengeluaran Kas dari Laci:</span>
                            <span class="font-bold text-rose-600">- Rp {{ number_format($cashRegister->total_cash_out, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-2 border-t-2 border-gray-200 font-bold text-dark text-sm bg-gray-50 px-2 rounded-lg">
                        <span>(=) Total Kas Diharapkan di Laci:</span>
                        <span>Rp {{ number_format($cashRegister->expected_cash, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between py-2 font-bold text-dark text-sm bg-primary-50 px-2 rounded-lg">
                        <span>(vs) Kas Fisik Dihitung Aktual:</span>
                        <span class="text-primary-700">
                            {{ $cashRegister->actual_cash !== null ? 'Rp ' . number_format($cashRegister->actual_cash, 0, ',', '.') : 'Belum Dihitung' }}
                        </span>
                    </div>

                    {{-- Selisih --}}
                    @if($cashRegister->difference !== null)
                        <div class="p-3 rounded-xl {{ $cashRegister->difference == 0 ? 'bg-emerald-100 text-emerald-900' : ($cashRegister->difference > 0 ? 'bg-blue-100 text-blue-900' : 'bg-rose-100 text-rose-900') }} flex justify-between items-center font-extrabold text-sm">
                            <div>
                                <span>SELISIH KAS LACI:</span>
                                <span class="text-[11px] font-normal block">
                                    {{ $cashRegister->difference == 0 ? 'Kas Pas & Sesuai Sempurna' : ($cashRegister->difference > 0 ? 'Kelebihan Kas' : 'Kekurangan Kas (Defisit)') }}
                                </span>
                            </div>
                            <span class="text-base">
                                {{ ($cashRegister->difference > 0 ? '+' : '') }}Rp {{ number_format($cashRegister->difference, 0, ',', '.') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Non-Cash & Notes --}}
            <div class="space-y-4">
                {{-- Penjualan Non-Tunai --}}
                <div class="glass-card p-5 space-y-3">
                    <h3 class="font-bold text-dark text-sm border-b border-gray-100 pb-2">💳 Penjualan Non-Tunai & Piutang</h3>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between py-1 border-b border-gray-50 text-gray-600">
                            <span>Total Penjualan Non-Tunai (QRIS / Transfer):</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($cashRegister->total_non_cash_sales, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between py-2 font-bold text-dark border-t border-gray-200">
                            <span>Total Omset Penjualan Shift Ini:</span>
                            <span class="text-emerald-700 text-sm">Rp {{ number_format($cashRegister->total_cash_sales + $cashRegister->total_non_cash_sales, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="glass-card p-5 space-y-2">
                    <h3 class="font-bold text-dark text-sm border-b border-gray-100 pb-2">📝 Catatan Kasir / Memo</h3>
                    <p class="text-xs text-gray-600 italic">
                        {{ $cashRegister->notes ?: 'Tidak ada catatan khusus pada shift ini.' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Sales list during this register --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Daftar Transaksi Penjualan Shift Ini</h3>
                <span class="text-xs text-gray-500">{{ $cashRegister->sales->count() }} Transaksi</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">No. Faktur</th>
                            <th class="p-3">Waktu</th>
                            <th class="p-3">Pelanggan</th>
                            <th class="p-3 text-center">Metode Bayar</th>
                            <th class="p-3 text-right">Total Transaksi</th>
                            <th class="p-3 text-right">Dibayar</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cashRegister->sales as $sale)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3 font-bold text-dark">{{ $sale->invoice_number }}</td>
                                <td class="p-3 text-gray-500">{{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i:s') }}</td>
                                <td class="p-3">{{ $sale->customer->name ?? 'Umum' }}</td>
                                <td class="p-3 text-center">
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $sale->payment_method === 'cash' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ strtoupper($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-bold text-dark">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-right font-bold text-emerald-600">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="p-1.5 rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 transition inline-block">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-400">
                                    Belum ada transaksi penjualan pada shift ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
