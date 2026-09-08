<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-dark">Laporan Riwayat Stok</h2>
    </x-slot>

    <div class="py-4 pb-12 space-y-4">
        <x-inventory-subnav />
        {{-- Filter Card --}}
        <div class="glass-card p-4">
            <form action="{{ route('reports.stock_movements') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Produk</label>
                        <select name="product_id" class="form-input-glass py-1.5 px-3 text-xs">
                            <option value="">-- Semua Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Tipe Mutasi</label>
                        <select name="type" class="form-input-glass py-1.5 px-3 text-xs">
                            <option value="">-- Semua Tipe --</option>
                            <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Pembelian (+)</option>
                            <option value="sale" {{ request('type') == 'sale' ? 'selected' : '' }}>Penjualan (-)</option>
                            <option value="purchase_delete" {{ request('type') == 'purchase_delete' ? 'selected' : '' }}>Hapus Pembelian (-)</option>
                            <option value="sale_delete" {{ request('type') == 'sale_delete' ? 'selected' : '' }}>Hapus Penjualan (+)</option>
                            <option value="manual_adjustment" {{ request('type') == 'manual_adjustment' ? 'selected' : '' }}>Penyesuaian Manual</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Mulai Tanggal</label>
                        <input type="text" name="date_from" value="{{ request('date_from') }}" placeholder="YYYY-MM-DD" class="datepicker form-input-glass py-1.5 px-3 text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                        <input type="text" name="date_to" value="{{ request('date_to') }}" placeholder="YYYY-MM-DD" class="datepicker form-input-glass py-1.5 px-3 text-xs">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-500 mb-1">Urutan Tanggal</label>
                        <select name="sort" class="form-input-glass py-1.5 px-3 text-xs">
                            <option value="desc" {{ request('sort', 'desc') === 'desc' ? 'selected' : '' }}>Terbaru (Descending)</option>
                            <option value="asc" {{ request('sort') === 'asc' ? 'selected' : '' }}>Terlama (Ascending)</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2 justify-end pt-1">
                    <button type="submit" class="btn-primary py-2 px-6 text-xs">Filter</button>
                    <a href="{{ route('reports.stock_movements') }}" class="btn-secondary py-2 px-4 text-xs text-center">Reset</a>
                </div>
            </form>
        </div>

        {{-- Movements Table / List --}}
        <div class="glass-card overflow-hidden">
            <div class="px-4 py-3 border-b border-white/30 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-dark">Daftar Pergerakan Stok</h3>
                <span class="text-xs text-gray-400">Total: {{ $movements->total() }} catatan</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($movements as $m)
                <div class="p-3 hover:bg-white/40 text-xs transition-colors flex flex-col md:flex-row md:items-center justify-between gap-2">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="font-bold text-dark text-sm">{{ $m->product->name ?? 'Produk Dihapus' }}</span>
                            @if(in_array($m->type, ['purchase', 'sale_delete']))
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">
                                    +{{ number_format($m->quantity, 2) }} {{ $m->product->buyUnit->symbol ?? '' }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">
                                    {{ number_format($m->quantity, 2) }} {{ $m->product->buyUnit->symbol ?? '' }}
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 font-medium">{{ $m->notes }}</p>
                        <div class="flex items-center gap-2 text-[10px] text-gray-400 flex-wrap pt-0.5">
                            <span class="font-semibold text-primary-700 bg-primary-50 px-2 py-0.5 rounded border border-primary-200">
                                📅 Tgl Pembelian / Transaksi: {{ $m->transaction_date ? \Carbon\Carbon::parse($m->transaction_date)->locale('id')->isoFormat('D MMMM Y') : $m->created_at->locale('id')->isoFormat('D MMMM Y') }}
                            </span>
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded">
                                ⏱️ Input: {{ $m->created_at->format('d/m/Y H:i') }}
                            </span>
                            @if($m->creator)
                                <span class="text-gray-400">· oleh {{ $m->creator->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-right self-end md:self-auto bg-gray-50/60 p-2 rounded-lg border border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400">Sebelum</p>
                            <p class="font-semibold text-gray-600">{{ number_format($m->stock_before, 2) }}</p>
                        </div>
                        <span class="text-gray-300">➔</span>
                        <div>
                            <p class="text-[10px] text-gray-400">Sesudah</p>
                            <p class="font-bold text-primary-600">{{ number_format($m->stock_after, 2) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    Belum ada riwayat pergerakan stok.
                </div>
                @endforelse
            </div>

            @if($movements->hasPages())
            <div class="p-3 border-t border-gray-100">
                {{ $movements->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
