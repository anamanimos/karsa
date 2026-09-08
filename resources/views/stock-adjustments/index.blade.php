<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Manajemen Stok"
            subtitle="Stok masuk mandiri, stok keluar rusak/expired, dan opname fisik"
            :createRoute="route('stock-adjustments.create')"
            createLabel="Penyesuaian / Opname"
        />
    </x-slot>

    <div class="space-y-4">
        <x-inventory-subnav />

    {{-- Filter by Type --}}
    <form method="GET" action="{{ route('stock-adjustments.index') }}" class="glass-card-solid p-3 rounded-lg border border-gray-150 flex gap-2">
        <select name="type" class="text-xs rounded-lg border border-gray-200 px-3 py-2 bg-white/80 w-full" onchange="this.form.submit()">
            <option value="">Semua Jenis Penyesuaian</option>
            <option value="opname" {{ request('type') === 'opname' ? 'selected' : '' }}>Stok Opname Fisik</option>
            <option value="in_manual" {{ request('type') === 'in_manual' ? 'selected' : '' }}>Stok Masuk Mandiri</option>
            <option value="out_manual" {{ request('type') === 'out_manual' ? 'selected' : '' }}>Stok Keluar Manual</option>
            <option value="damaged" {{ request('type') === 'damaged' ? 'selected' : '' }}>Barang Rusak</option>
            <option value="expired" {{ request('type') === 'expired' ? 'selected' : '' }}>Barang Kadaluwarsa</option>
            <option value="internal_use" {{ request('type') === 'internal_use' ? 'selected' : '' }}>Pemakaian Internal / Toko</option>
        </select>
        <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-lg shrink-0">Filter</button>
    </form>

    {{-- Adjustments List --}}
    <div class="space-y-3">
        @forelse($adjustments as $adj)
            <div class="glass-card-solid p-3.5 rounded-lg border border-gray-150 shadow-xs space-y-2">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-bold text-gray-800">{{ $adj->adjustment_number }}</h3>
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase {{ in_array($adj->type, ['opname', 'in_manual']) ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ str_replace('_', ' ', $adj->type) }}
                            </span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $adj->adjustment_date->format('d F Y') }} &bull; Oleh: {{ $adj->creator?->name ?? 'Admin' }}</p>
                    </div>
                    <a href="{{ route('stock-adjustments.show', $adj) }}" class="text-xs text-primary-600 font-bold hover:underline">
                        Lihat Rincian &rarr;
                    </a>
                </div>

                @if($adj->reason)
                    <p class="text-[11px] text-gray-500 bg-gray-50/80 px-2.5 py-1.5 rounded-lg border border-gray-100">{{ $adj->reason }}</p>
                @endif

                <div class="divide-y divide-gray-100 pt-1 text-xs">
                    @foreach($adj->items->take(3) as $item)
                        <div class="py-1 flex items-center justify-between text-[11px]">
                            <span class="text-gray-700 truncate max-w-[200px]">{{ $item->product->name }}</span>
                            <span class="font-extrabold {{ $item->difference >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }} {{ $item->product->sellUnit?->symbol }}
                            </span>
                        </div>
                    @endforeach
                    @if($adj->items->count() > 3)
                        <p class="text-[10px] text-gray-400 pt-1 text-center">+ {{ $adj->items->count() - 3 }} item lainnya</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="glass-card-solid p-6 rounded-lg text-center text-gray-400">
                <p class="text-sm">Belum ada riwayat penyesuaian stok.</p>
                <a href="{{ route('stock-adjustments.create') }}" class="text-xs text-primary-600 font-bold mt-2 inline-block">+ Lakukan Penyesuaian Sekarang</a>
            </div>
        @endforelse

        {{ $adjustments->links() }}
    </div>
</x-app-layout>
