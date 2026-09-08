<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('stock-adjustments.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-dark">Detail Penyesuaian Stok</h2>
                    <p class="text-xs text-gray-500">{{ $stockAdjustment->adjustment_number }} • {{ \Carbon\Carbon::parse($stockAdjustment->adjustment_date)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="btn-secondary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Cetak Dokumen
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24 space-y-5">
        {{-- Header Summary Card --}}
        <div class="glass-card p-5">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">No. Dokumen</span>
                    <span class="text-sm font-bold text-dark">{{ $stockAdjustment->adjustment_number }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Tipe Penyesuaian</span>
                    @php
                        $typeLabels = [
                            'opname' => ['Stok Opname', 'bg-blue-100 text-blue-700'],
                            'in_manual' => ['Stok Masuk (+)', 'bg-emerald-100 text-emerald-700'],
                            'out_manual' => ['Stok Keluar (-)', 'bg-amber-100 text-amber-700'],
                            'damaged' => ['Barang Rusak', 'bg-rose-100 text-rose-700'],
                            'expired' => ['Kadaluarsa', 'bg-red-100 text-red-700'],
                            'internal_use' => ['Pemakaian Internal', 'bg-purple-100 text-purple-700'],
                            'initial_stock' => ['Stok Awal', 'bg-indigo-100 text-indigo-700'],
                        ];
                        $badge = $typeLabels[$stockAdjustment->type] ?? [$stockAdjustment->type, 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="inline-block mt-0.5 px-2.5 py-0.5 text-xs font-bold rounded-full {{ $badge[1] }}">
                        {{ $badge[0] }}
                    </span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Dibuat Oleh</span>
                    <span class="text-sm font-bold text-dark">{{ $stockAdjustment->creator->name ?? 'Sistem' }}</span>
                </div>
                <div>
                    <span class="text-[11px] font-semibold text-gray-400 block uppercase tracking-wider">Keterangan / Alasan</span>
                    <span class="text-sm text-gray-600">{{ $stockAdjustment->reason ?: '-' }}</span>
                </div>
            </div>
        </div>

        {{-- KPI Quick Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="glass-card p-4">
                <span class="text-xs text-gray-500 block font-medium">Total Item Produk</span>
                <span class="text-xl font-extrabold text-dark mt-1 block">{{ $stockAdjustment->items->count() }} Produk</span>
            </div>
            <div class="glass-card p-4">
                <span class="text-xs text-gray-500 block font-medium">Total Selisih Kuantitas</span>
                @php
                    $totalQtyDiff = $stockAdjustment->items->sum('difference');
                @endphp
                <span class="text-xl font-extrabold {{ $totalQtyDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1 block">
                    {{ ($totalQtyDiff > 0 ? '+' : '') . $totalQtyDiff }} Unit
                </span>
            </div>
            <div class="glass-card p-4">
                <span class="text-xs text-gray-500 block font-medium">Total Dampak Biaya (HPP)</span>
                @php
                    $totalCostImpact = $stockAdjustment->items->sum('total_cost_impact');
                @endphp
                <span class="text-xl font-extrabold {{ $totalCostImpact >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1 block">
                    Rp {{ number_format($totalCostImpact, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="glass-card overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">Rincian Produk Disesuaikan</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Nama Produk</th>
                            <th class="p-3 text-center">Stok Sistem</th>
                            <th class="p-3 text-center">Stok Aktual (Fisik)</th>
                            <th class="p-3 text-center">Selisih</th>
                            <th class="p-3 text-right">Biaya Pokok (HPP)</th>
                            <th class="p-3 text-right">Dampak Finansial</th>
                            <th class="p-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($stockAdjustment->items as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-3 text-gray-400 font-semibold">{{ $index + 1 }}</td>
                                <td class="p-3">
                                    <span class="font-bold text-dark block">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                    <span class="text-[10px] text-gray-400">SKU: {{ $item->product->sku ?? '-' }}</span>
                                </td>
                                <td class="p-3 text-center font-medium text-gray-600">
                                    {{ $item->system_stock }} {{ $item->product->sellUnit->symbol ?? '' }}
                                </td>
                                <td class="p-3 text-center font-bold text-dark">
                                    {{ $item->actual_stock }} {{ $item->product->sellUnit->symbol ?? '' }}
                                </td>
                                <td class="p-3 text-center font-bold">
                                    <span class="{{ $item->difference >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ ($item->difference > 0 ? '+' : '') . $item->difference }}
                                    </span>
                                </td>
                                <td class="p-3 text-right font-medium text-gray-600">
                                    Rp {{ number_format($item->unit_cost, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-right font-bold {{ $item->total_cost_impact >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($item->total_cost_impact, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-gray-500 text-[11px]">
                                    {{ $item->notes ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50/80 font-bold border-t border-gray-200">
                        <tr>
                            <td colspan="4" class="p-3 text-right text-gray-600 uppercase text-[11px]">Total:</td>
                            <td class="p-3 text-center {{ $totalQtyDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ ($totalQtyDiff > 0 ? '+' : '') . $totalQtyDiff }}
                            </td>
                            <td></td>
                            <td class="p-3 text-right text-sm {{ $totalCostImpact >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                Rp {{ number_format($totalCostImpact, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
