<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-dark">Laporan Hutang & Piutang</h2>
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-reports-subnav />
        {{-- Summaries --}}
        <div class="grid grid-cols-2 gap-3">
            <div class="glass-card p-4 border-l-4 border-red-500 min-w-0">
                <p class="text-[10px] text-gray-400 mb-0.5 truncate">Total Hutang (Toko)</p>
                <p class="text-sm sm:text-base font-extrabold text-red-600 truncate" title="Rp {{ number_format($totalPayable ?? 0, 0, ',', '.') }}">Rp {{ number_format($totalPayable ?? 0, 0, ',', '.') }}</p>
                <a href="{{ route('payments.suppliers') }}" class="inline-block mt-2 text-[9px] text-red-500 font-semibold underline">Detail Hutang →</a>
            </div>
            <div class="glass-card p-4 border-l-4 border-accent-500 min-w-0">
                <p class="text-[10px] text-gray-400 mb-0.5 truncate">Total Piutang (Petani)</p>
                <p class="text-sm sm:text-base font-extrabold text-accent-600 truncate" title="Rp {{ number_format($totalReceivable ?? 0, 0, ',', '.') }}">Rp {{ number_format($totalReceivable ?? 0, 0, ',', '.') }}</p>
                <a href="{{ route('payments.customers') }}" class="inline-block mt-2 text-[9px] text-accent-500 font-semibold underline">Detail Piutang →</a>
            </div>
        </div>

        {{-- 2-Column Grid on Desktop for Hutang & Piutang Lists --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Top Debts --}}
            <div class="glass-card overflow-hidden">
                <div class="px-4 py-3 border-b border-white/30">
                    <h3 class="text-sm font-semibold text-dark">Hutang Toko ke Tengkulak</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($suppliersWithDebt as $supplier)
                    <div class="px-4 py-3 flex items-center justify-between text-xs">
                        <span>{{ $supplier->name }}</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($supplier->total_due, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-400 text-xs">
                        Tidak ada hutang ke tengkulak.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Top Receivables --}}
            <div class="glass-card overflow-hidden">
                <div class="px-4 py-3 border-b border-white/30">
                    <h3 class="text-sm font-semibold text-dark">Piutang Pelanggan (Petani)</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($customersWithDebt as $customer)
                    <div class="px-4 py-3 flex items-center justify-between text-xs">
                        <span>{{ $customer->name }}</span>
                        <span class="font-bold text-accent-600">Rp {{ number_format($customer->total_due, 0, ',', '.') }}</span>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-400 text-xs">
                        Tidak ada piutang pelanggan.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

