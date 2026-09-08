<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Daftar Tengkulak"
            subtitle="Kelola data pemasok, tengkulak, dan riwayat hutang usaha"
            :searchAction="route('suppliers.index')"
            searchPlaceholder="Cari nama, no. HP, alamat..."
            :createRoute="route('suppliers.create')"
            createLabel="Tambah Tengkulak"
        />
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-procurement-subnav />

        {{-- Active Search Indicator --}}
        <x-active-search-indicator :resetUrl="route('suppliers.index')" />

        {{-- Search or List --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @forelse($suppliers as $supplier)
            <a href="{{ route('suppliers.show', $supplier) }}" class="glass-card p-4 flex flex-col justify-between hover:bg-white/60 transition-all block rounded-lg group">
                <div class="space-y-1">
                    <p class="text-sm font-bold text-dark group-hover:text-primary-600 transition-colors">{{ $supplier->name }}</p>
                    @if($supplier->phone)
                        <p class="text-xs text-gray-500">📞 {{ $supplier->phone }}</p>
                    @endif
                    @if($supplier->address)
                        <p class="text-xs text-gray-400 line-clamp-1">📍 {{ $supplier->address }}</p>
                    @endif
                </div>
                <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                    @php
                        $totalDue = $supplier->purchases->sum('due_amount');
                    @endphp
                    @if($totalDue > 0)
                        <span class="text-[10px] px-2 py-0.5 rounded bg-red-100 text-red-700 font-semibold whitespace-nowrap">
                            Hutang: Rp {{ number_format($totalDue, 0, ',', '.') }}
                        </span>
                    @else
                        <span class="text-[10px] px-2 py-0.5 rounded bg-green-100 text-green-700 font-semibold whitespace-nowrap">
                            Lunas / Aman
                        </span>
                    @endif
                    <span class="text-[10px] text-gray-400 group-hover:text-primary-600 transition-colors">Detail &rarr;</span>
                </div>
            </a>
            @empty
            <div class="glass-card p-8 text-center text-gray-400 text-sm col-span-full rounded-lg">
                Belum ada tengkulak/supplier.
            </div>
            @endforelse
        </div>

        @if($suppliers->hasPages())
        <div class="mt-4">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</x-app-layout>

