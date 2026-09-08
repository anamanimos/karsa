<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-dark">Buku Kas & Bank (General Ledger)</h2>
                <p class="text-xs text-gray-500">Mutasi detail transaksi keuangan seluruh akun kas, bank, dan beban</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cash-transactions.create') }}" class="btn-primary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Catat Kas Masuk / Keluar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 pb-12 space-y-5">
        <x-financial-report-subnav />

        {{-- Filter Form --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('financial-reports.general-ledger') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-input-glass text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-input-glass text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Filter Akun Keuangan</label>
                    <select name="account_id" class="form-input-glass text-xs">
                        <option value="">-- Semua Akun Keuangan --</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $accountId == $acc->id ? 'selected' : '' }}>
                                [{{ $acc->code }}] {{ $acc->name }} ({{ strtoupper($acc->type) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-4 flex-1">
                        Filter Mutasi
                    </button>
                    <a href="{{ route('financial-reports.general-ledger') }}" class="btn-secondary text-xs py-2.5 px-3">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="glass-card p-4 border-l-4 border-emerald-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Total Kas Masuk (Debet)</span>
                <span class="text-lg sm:text-xl font-extrabold text-emerald-600 mt-1 block truncate" title="Rp {{ number_format($totalIn, 0, ',', '.') }}">Rp {{ number_format($totalIn, 0, ',', '.') }}</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-rose-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Total Kas Keluar (Kredit)</span>
                <span class="text-lg sm:text-xl font-extrabold text-rose-600 mt-1 block truncate" title="Rp {{ number_format($totalOut, 0, ',', '.') }}">Rp {{ number_format($totalOut, 0, ',', '.') }}</span>
            </div>

            <div class="glass-card p-4 border-l-4 border-blue-500 min-w-0">
                <span class="text-xs text-gray-500 font-medium block truncate">Perubahan Bersih Periode Ini</span>
                <span class="text-lg sm:text-xl font-extrabold {{ $netChange >= 0 ? 'text-blue-600' : 'text-rose-600' }} mt-1 block truncate" title="{{ ($netChange > 0 ? '+' : '') }}Rp {{ number_format($netChange, 0, ',', '.') }}">
                    {{ ($netChange > 0 ? '+' : '') }}Rp {{ number_format($netChange, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Transaction Ledger Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Daftar Jurnal & Mutasi Keuangan</h3>
                <span class="text-xs text-gray-500">{{ $transactions->total() }} Transaksi Ditemukan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Akun / Rekening</th>
                            <th class="p-3">Kategori & Keterangan</th>
                            <th class="p-3 text-center">Tipe</th>
                            <th class="p-3 text-right">Masuk (In)</th>
                            <th class="p-3 text-right">Keluar (Out)</th>
                            <th class="p-3">Oleh / Ref</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3 font-medium text-gray-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($trx->transaction_date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="p-3">
                                    @if($trx->account)
                                        <span class="font-bold text-dark block">[{{ $trx->account->code }}] {{ $trx->account->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ strtoupper($trx->account->type) }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Akun Umum</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <span class="font-semibold text-dark block">{{ $trx->category ?: 'Operasional' }}</span>
                                    <span class="text-[11px] text-gray-500">{{ $trx->description ?: '-' }}</span>
                                </td>
                                <td class="p-3 text-center">
                                    @if($trx->type === 'in')
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700">
                                            MASUK
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-rose-100 text-rose-700">
                                            KELUAR
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3 text-right font-bold text-emerald-600">
                                    {{ $trx->type === 'in' ? 'Rp ' . number_format($trx->amount, 0, ',', '.') : '-' }}
                                </td>
                                <td class="p-3 text-right font-bold text-rose-600">
                                    {{ $trx->type === 'out' ? 'Rp ' . number_format($trx->amount, 0, ',', '.') : '-' }}
                                </td>
                                <td class="p-3 text-[11px] text-gray-500">
                                    <div>{{ $trx->creator->name ?? 'Sistem' }}</div>
                                    @if($trx->reference_type)
                                        <span class="text-[10px] text-primary-600 font-semibold">
                                            Ref: {{ class_basename($trx->reference_type) }} #{{ $trx->reference_id }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-gray-400">
                                    Tidak ada mutasi kas/bank pada periode atau akun yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
