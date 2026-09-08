<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-dark">Laporan Laba Kotor</h2>
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-reports-subnav />
        {{-- Filter --}}
        <div class="glass-card p-4">
            <form action="{{ route('reports.profit') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end gap-3">
                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-semibold text-gray-500 mb-1">Mulai</label>
                    <input type="text" name="date_from" value="{{ request('date_from', date('Y-m-d')) }}" class="datepicker form-input-glass py-1.5 px-3">
                </div>
                <div class="w-full sm:w-48">
                    <label class="block text-[10px] font-semibold text-gray-500 mb-1">Sampai</label>
                    <input type="text" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}" class="datepicker form-input-glass py-1.5 px-3">
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" class="btn-primary py-2 px-4 text-xs flex-1 sm:flex-none">Filter</button>
                    <a href="{{ route('reports.profit') }}" class="btn-secondary py-2 px-3 text-xs text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="glass-card p-4 min-w-0">
                <p class="text-[10px] text-gray-400 mb-0.5 truncate">Total Omset</p>
                <p class="text-sm sm:text-base font-extrabold text-primary-600 truncate" title="Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="glass-card p-4 min-w-0">
                <p class="text-[10px] text-gray-400 mb-0.5 truncate">Estimasi Laba Kotor</p>
                <p class="text-sm sm:text-base font-extrabold text-accent-600 truncate" title="Rp {{ number_format($totalProfit ?? 0, 0, ',', '.') }}">Rp {{ number_format($totalProfit ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Details list --}}
        <div class="glass-card overflow-hidden">
            <div class="px-4 py-3 border-b border-white/30">
                <h3 class="text-sm font-semibold text-dark">Rincian Laba per Barang</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($saleItems as $item)
                @php
                    // profit = subtotal - (last_purchase_price * conversion_factor * quantity)
                    $cost = $item->product->last_purchase_price * $item->product->conversion_factor * $item->quantity;
                    $itemProfit = $item->subtotal - $cost;
                @endphp
                <div class="p-3 flex items-center justify-between text-xs">
                    <div>
                        <p class="font-semibold text-dark">{{ $item->product->name }}</p>
                        <p class="text-gray-400">Qty: {{ $item->quantity }} {{ $item->product->sellUnit->symbol }} · Omset: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-gray-400">HPP: Rp {{ number_format($cost, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="font-bold text-accent-600">Rp {{ number_format($itemProfit, 0, ',', '.') }}</span>
                        <p class="text-[9px] text-gray-400">{{ $item->sale->invoice_number }}</p>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    Tidak ada data penjualan pada periode ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

