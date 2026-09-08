<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-dark">Shift Kasir & Cash Drawer</h2>
                <p class="text-xs text-gray-500">Kelola buka/tutup shift kasir, modal kas kecil, dan rekonsiliasi laci uang</p>
            </div>
            <div class="flex items-center gap-2">
                @if(!$currentRegister)
                    <button @click="$dispatch('open-modal', 'modal-open-shift')" class="btn-primary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buka Shift Kasir Baru
                    </button>
                @else
                    <a href="{{ route('sales.create') }}" class="btn-primary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Buka Menu Kasir POS
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24 space-y-5" x-data="{ closeShiftModal: false, closeRegisterId: null, expectedCashPreview: 0 }">
        <x-sales-subnav />

        {{-- Current Active Shift Banner --}}
        @if($currentRegister)
            @php
                $liveSales = \App\Models\Sale::where('cash_register_id', $currentRegister->id)->get();
                $liveCashSales = $liveSales->where('payment_method', 'cash')->sum('paid_amount');
                $liveNonCashSales = $liveSales->whereIn('payment_method', ['qris', 'transfer', 'credit'])->sum('paid_amount');
                $currentExpected = $currentRegister->initial_cash + $liveCashSales + $currentRegister->total_cash_in - $currentRegister->total_cash_out;
            @endphp
            <div class="glass-card-solid p-5 bg-gradient-to-r from-emerald-800 via-teal-900 to-slate-900 text-white shadow-xl">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">Shift Kasir Aktif</span>
                            <span class="text-xs text-gray-300">• Kasir: <strong class="text-white">{{ $currentRegister->user->name ?? 'User' }}</strong></span>
                        </div>
                        <p class="text-sm font-medium text-gray-200">
                            Dibuka sejak: {{ \Carbon\Carbon::parse($currentRegister->opened_at)->translatedFormat('d M Y, H:i') }} ({{ \Carbon\Carbon::parse($currentRegister->opened_at)->diffForHumans() }})
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-left">
                        <div class="bg-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                            <span class="text-[10px] text-gray-300 uppercase block font-semibold">Modal Awal Kas</span>
                            <span class="text-sm font-bold text-white">Rp {{ number_format($currentRegister->initial_cash, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-white/10 rounded-xl p-2.5 backdrop-blur-sm">
                            <span class="text-[10px] text-gray-300 uppercase block font-semibold">Penjualan Tunai</span>
                            <span class="text-sm font-bold text-emerald-300">Rp {{ number_format($liveCashSales, 0, ',', '.') }}</span>
                        </div>
                        <div class="bg-white/10 rounded-xl p-2.5 backdrop-blur-sm col-span-2 sm:col-span-1">
                            <span class="text-[10px] text-gray-300 uppercase block font-semibold">Estimasi Kas di Laci</span>
                            <span class="text-sm font-black text-white">Rp {{ number_format($currentExpected, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('sales.create') }}" class="btn-primary text-xs py-2.5 px-4 font-bold bg-white text-dark hover:bg-gray-100 shadow-md">
                            Kasir POS →
                        </a>
                        <button @click="closeRegisterId = {{ $currentRegister->id }}; expectedCashPreview = {{ $currentExpected }}; closeShiftModal = true;" class="px-4 py-2.5 rounded-xl bg-rose-600/90 hover:bg-rose-700 text-white font-bold text-xs transition shadow-md flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Tutup Shift
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="glass-card p-4 bg-amber-50/80 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-lg">
                        ⚠️
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900">Tidak Ada Shift Kasir yang Sedang Aktif</h4>
                        <p class="text-xs text-amber-700">Silakan buka shift kasir baru dengan menginput modal awal kas kecil sebelum memulai transaksi kasir.</p>
                    </div>
                </div>
                <button @click="$dispatch('open-modal', 'modal-open-shift')" class="btn-primary text-xs py-2 px-4 whitespace-nowrap">
                    + Buka Shift Sekarang
                </button>
            </div>
        @endif

        {{-- Shift History Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Riwayat Shift Kasir & Rekonsiliasi</h3>
                <span class="text-xs text-gray-500">{{ $registers->total() }} Shift Tercatat</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">Waktu Buka / Tutup</th>
                            <th class="p-3">Kasir</th>
                            <th class="p-3 text-right">Modal Awal</th>
                            <th class="p-3 text-right">Penjualan Tunai</th>
                            <th class="p-3 text-right">Kas Diharapkan</th>
                            <th class="p-3 text-right">Kas Fisik Aktual</th>
                            <th class="p-3 text-center">Selisih</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($registers as $reg)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3">
                                    <span class="font-bold text-dark block">{{ \Carbon\Carbon::parse($reg->opened_at)->translatedFormat('d M Y, H:i') }}</span>
                                    <span class="text-[10px] text-gray-400">
                                        {{ $reg->closed_at ? 'Tutup: ' . \Carbon\Carbon::parse($reg->closed_at)->translatedFormat('d M Y, H:i') : 'Masih Berjalan' }}
                                    </span>
                                </td>
                                <td class="p-3 font-medium text-gray-700">
                                    {{ $reg->user->name ?? 'User' }}
                                </td>
                                <td class="p-3 text-right font-medium text-gray-600">
                                    Rp {{ number_format($reg->initial_cash, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right font-bold text-emerald-600">
                                    Rp {{ number_format($reg->total_cash_sales, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right font-bold text-dark">
                                    Rp {{ number_format($reg->expected_cash, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right font-bold text-dark">
                                    {{ $reg->actual_cash !== null ? 'Rp ' . number_format($reg->actual_cash, 0, ',', '.') : '-' }}
                                </td>
                                <td class="p-3 text-center font-bold">
                                    @if($reg->difference !== null)
                                        <span class="{{ $reg->difference == 0 ? 'text-emerald-600' : ($reg->difference > 0 ? 'text-blue-600' : 'text-rose-600') }}">
                                            {{ ($reg->difference > 0 ? '+' : '') . 'Rp ' . number_format($reg->difference, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center">
                                    @if($reg->status === 'open')
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                                            AKTIF
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-gray-100 text-gray-700">
                                            DITUTUP
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('cash-registers.show', $reg->id) }}" class="p-1.5 rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 transition" title="Lihat Rekonsiliasi">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        @if($reg->status === 'open')
                                            <button @click="closeRegisterId = {{ $reg->id }}; expectedCashPreview = {{ $reg->expected_cash }}; closeShiftModal = true;" class="p-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition" title="Tutup Shift">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-gray-400">
                                    Belum ada catatan shift kasir. Klik "Buka Shift Kasir Baru" untuk memulai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($registers->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $registers->links() }}
                </div>
            @endif
        </div>

        {{-- Modal Buka Shift --}}
        <x-modal name="modal-open-shift" focusable>
            <form action="{{ route('cash-registers.open') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-dark">🔓 Buka Shift Kasir Baru</h3>
                    <button type="button" @click="$dispatch('close-modal', 'modal-open-shift')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Modal Awal Kas Kecil (Cash Float) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs text-gray-500 font-bold">Rp</span>
                        <input type="number" name="initial_cash" value="100000" min="0" step="1000" class="form-input-glass pl-9 font-bold text-base" required>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">Uang kembalian yang disiapkan di laci kasir saat membuka toko.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan / Keterangan (Opsional)</label>
                    <textarea name="notes" rows="2" class="form-input-glass text-xs" placeholder="Contoh: Shift Pagi (08:00 - 16:00)..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button type="button" @click="$dispatch('close-modal', 'modal-open-shift')" class="btn-secondary text-xs py-2 px-4">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary text-xs py-2 px-4 font-bold shadow-md">
                        Buka Shift & Lanjut POS
                    </button>
                </div>
            </form>
        </x-modal>

        {{-- Modal Tutup Shift Kasir --}}
        <div x-show="closeShiftModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 space-y-4" @click.outside="closeShiftModal = false">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h3 class="text-base font-bold text-dark">🔒 Tutup Shift Kasir & Rekonsiliasi</h3>
                    <button type="button" @click="closeShiftModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="'/cash-registers/' + closeRegisterId + '/close'" method="POST" class="space-y-4">
                    @csrf

                    <div class="p-3 bg-gray-50 rounded-xl space-y-1 text-xs">
                        <div class="flex justify-between text-gray-500">
                            <span>Estimasi Kas Diharapkan di Laci:</span>
                            <span class="font-bold text-dark text-sm" x-text="'Rp ' + Number(expectedCashPreview).toLocaleString('id-ID')"></span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Kas Fisik Aktual Dihitung (Di Laci) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs text-gray-500 font-bold">Rp</span>
                            <input type="number" name="actual_cash" min="0" step="1000" class="form-input-glass pl-9 font-bold text-base text-dark" placeholder="0" required>
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Hitung uang fisik di laci kasir dan masukkan totalnya di sini.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan Penutupan Shift</label>
                        <textarea name="notes" rows="2" class="form-input-glass text-xs" placeholder="Catatan selisih atau serah terima kas..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="closeShiftModal = false" class="btn-secondary text-xs py-2 px-4">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-md">
                            Tutup Shift Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
