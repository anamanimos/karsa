<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Daftar Pelanggan"
            subtitle="Kelola data pelanggan, petani, dan riwayat piutang penjualan"
            :searchAction="route('customers.index')"
            searchPlaceholder="Cari nama, no. HP, alamat..."
            :createRoute="route('customers.create')"
            createLabel="Tambah Pelanggan"
        />
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-procurement-subnav />

        {{-- Active Search Indicator --}}
        <x-active-search-indicator :resetUrl="route('customers.index')" />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($customers as $customer)
            <a href="{{ route('customers.show', $customer) }}" class="glass-card p-4 flex flex-col justify-between hover:bg-white/60 transition-all block rounded-lg group">
                <div class="space-y-1">
                    <p class="text-sm font-bold text-dark group-hover:text-primary-600 transition-colors">{{ $customer->name }}</p>
                    @if($customer->phone)
                        <p class="text-xs text-gray-500">📞 {{ $customer->phone }}</p>
                    @endif
                    @if($customer->address)
                        <p class="text-xs text-gray-400 line-clamp-1">📍 {{ $customer->address }}</p>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                    @php
                        $totalDue = $customer->sales->sum('due_amount');
                    @endphp
                    @if($totalDue > 0)
                        <span class="text-[10px] px-2 py-0.5 rounded bg-accent-100 text-accent-700 font-semibold whitespace-nowrap">
                            Piutang: Rp {{ number_format($totalDue, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-[10px] px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold whitespace-nowrap">
                            Lunas
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 group-hover:text-primary-600 transition-colors">Detail &rarr;</span>
                </div>
            </a>
            @empty
            <div class="glass-card p-8 text-center text-gray-400 text-sm col-span-full rounded-lg">
                Belum ada pelanggan terdaftar.
            </div>
            @endforelse
        </div>

        @if($customers->hasPages())
        <div class="mt-4">
            {{ $customers->links() }}
        </div>
        @endif
    </div>

</x-app-layout>

