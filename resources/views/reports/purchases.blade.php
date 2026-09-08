<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-dark">Laporan Pembelian</h2>
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-reports-subnav />
        {{-- Filter --}}
        <div class="glass-card p-4">
            <form action="{{ route('reports.purchases') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 mb-1">Mulai</label>
                    <input type="text" name="date_from" value="{{ request('date_from', date('Y-m-d')) }}" class="datepicker form-input-glass py-1.5 px-3">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 mb-1">Sampai</label>
                    <input type="text" name="date_to" value="{{ request('date_to', date('Y-m-d')) }}" class="datepicker form-input-glass py-1.5 px-3">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-500 mb-1">Tengkulak</label>
                    <select name="supplier_id" class="form-input-glass py-1.5 px-3">
                        <option value="">Semua Tengkulak</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary py-2 px-4 text-xs flex-1">Filter</button>
                    <a href="{{ route('reports.purchases') }}" class="btn-secondary py-2 px-3 text-xs text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- Summary Card --}}
        <div class="glass-card p-4 flex items-center justify-between bg-primary-50/10">
            <div>
                <p class="text-xs text-gray-500">Total Pengeluaran Pembelian</p>
                <p class="text-lg font-bold text-primary-600">Rp {{ number_format($totalPurchasesAmount ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Jumlah Transaksi</p>
                <p class="text-base font-bold text-dark">{{ $totalTransactions ?? 0 }}</p>
            </div>
        </div>

        {{-- List --}}
        <div class="glass-card overflow-hidden">
            <div class="px-4 py-3 border-b border-white/30">
                <h3 class="text-sm font-semibold text-dark">Rincian Pembelian</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($purchases as $purchase)
                <a href="{{ route('purchases.show', $purchase) }}" class="p-3 flex items-center justify-between hover:bg-white/40 block text-xs">
                    <div>
                        <p class="font-semibold text-dark">{{ $purchase->invoice_number }}</p>
                        <p class="text-gray-400">{{ $purchase->purchase_date->format('d/m/Y') }} · Tengkulak: {{ $purchase->supplier->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-dark">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                        @if($purchase->payment_status === 'paid')
                            <span class="text-[9px] px-1 bg-green-100 text-green-700 rounded">Lunas</span>
                        @else
                            <span class="text-[9px] px-1 bg-red-100 text-red-700 rounded">Hutang</span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    Tidak ada transaksi pembelian pada periode ini.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

