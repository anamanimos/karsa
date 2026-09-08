<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('users.index') }}" class="w-10 h-10 rounded-full bg-white/60 border border-white/40 flex items-center justify-center active:scale-95 transition-transform">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-dark">Detail Pengguna</h2>
                    <p class="text-xs text-gray-500">{{ $user->name }}</p>
                </div>
            </div>
            <a href="{{ route('users.edit', $user) }}" class="w-10 h-10 rounded-full bg-primary-600 text-white flex items-center justify-center shadow-lg active:scale-95 transition-transform" title="Ubah Pengguna">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>
        </div>
    </x-slot>

    <div class="py-5 pb-32 space-y-4 max-w-lg mx-auto">
        {{-- Profile Card --}}
        <div class="glass-card p-5 space-y-4 relative overflow-hidden bg-white/80">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 font-black text-xl shadow-inner {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1 space-y-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-base font-extrabold text-dark">{{ $user->name }}</h1>
                        @if(auth()->id() === $user->id)
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-primary-100 text-primary-700 font-extrabold">Akun Anda</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-0.5">
                        @if($user->role === 'admin')
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                👑 Administrator
                            </span>
                        @else
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                🛒 Kasir
                            </span>
                        @endif

                        @if($user->is_active)
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">
                                🟢 Aktif
                            </span>
                        @else
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                ⚪ Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-3 border-t border-gray-100 text-xs text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">📧 Email:</span>
                    <span class="font-semibold text-dark truncate">{{ $user->email }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">📞 Telepon:</span>
                    <span class="font-semibold text-dark">{{ $user->phone ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">📅 Terdaftar:</span>
                    <span class="font-medium text-dark">{{ $user->created_at ? $user->created_at->locale('id')->isoFormat('D MMMM Y') : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 gap-3">
            {{-- Total Penjualan --}}
            <div class="glass-card p-4 space-y-1 bg-white/70">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wide">Penjualan Kasir</span>
                    <span class="text-xs">🛒</span>
                </div>
                <p class="text-base font-extrabold text-dark">Rp {{ number_format($totalSalesAmount ?? 0, 0, ',', '.') }}</p>
                <p class="text-[10px] text-gray-400">{{ $user->sales_count }} Nota Penjualan</p>
            </div>

            {{-- Total Pembelian --}}
            <div class="glass-card p-4 space-y-1 bg-white/70">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wide">Pembelian Kasir</span>
                    <span class="text-xs">📦</span>
                </div>
                <p class="text-base font-extrabold text-dark">Rp {{ number_format($totalPurchasesAmount ?? 0, 0, ',', '.') }}</p>
                <p class="text-[10px] text-gray-400">{{ $user->purchases_count }} Nota Pembelian</p>
            </div>
        </div>

        {{-- Recent Sales Tab/List --}}
        <div class="glass-card overflow-hidden bg-white/80" x-data="{ tab: 'sales' }">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button type="button" 
                            @click="tab = 'sales'" 
                            :class="tab === 'sales' ? 'bg-primary-600 text-white shadow-sm font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'" 
                            class="text-xs px-3 py-1.5 rounded-lg transition-all">
                        Penjualan Terakhir ({{ $recentSales->count() }})
                    </button>
                    <button type="button" 
                            @click="tab = 'purchases'" 
                            :class="tab === 'purchases' ? 'bg-primary-600 text-white shadow-sm font-bold' : 'text-gray-600 hover:bg-gray-100 font-medium'" 
                            class="text-xs px-3 py-1.5 rounded-lg transition-all">
                        Pembelian Terakhir ({{ $recentPurchases->count() }})
                    </button>
                </div>
            </div>

            {{-- Sales List --}}
            <div x-show="tab === 'sales'" class="divide-y divide-gray-100">
                @forelse($recentSales as $sale)
                <a href="{{ route('sales.show', $sale) }}" class="px-4 py-3 flex items-center justify-between hover:bg-gray-50/60 transition-colors block">
                    <div>
                        <p class="text-xs font-bold text-dark">{{ $sale->invoice_number }}</p>
                        <p class="text-[11px] text-gray-400">
                            {{ $sale->sale_date ? $sale->sale_date->locale('id')->isoFormat('D MMM Y, HH:mm') : $sale->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                            @if($sale->customer)
                                · {{ $sale->customer->name }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-primary-600">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-full {{ $sale->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $sale->payment_status === 'paid' ? 'Lunas' : 'Hutang' }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="p-6 text-center text-gray-400 text-xs">
                    Belum ada riwayat penjualan yang dicatat oleh pengguna ini.
                </div>
                @endforelse
            </div>

            {{-- Purchases List --}}
            <div x-show="tab === 'purchases'" class="divide-y divide-gray-100" style="display: none;">
                @forelse($recentPurchases as $purchase)
                <a href="{{ route('purchases.show', $purchase) }}" class="px-4 py-3 flex items-center justify-between hover:bg-gray-50/60 transition-colors block">
                    <div>
                        <p class="text-xs font-bold text-dark">{{ $purchase->invoice_number }}</p>
                        <p class="text-[11px] text-gray-400">
                            {{ $purchase->purchase_date ? $purchase->purchase_date->locale('id')->isoFormat('D MMM Y') : $purchase->created_at->locale('id')->isoFormat('D MMM Y') }}
                            @if($purchase->supplier)
                                · {{ $purchase->supplier->name }}
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-dark">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                        <span class="text-[9px] px-1.5 py-0.5 rounded-full {{ $purchase->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $purchase->payment_status === 'paid' ? 'Lunas' : 'Sebagian/Hutang' }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="p-6 text-center text-gray-400 text-xs">
                    Belum ada riwayat pembelian yang dicatat oleh pengguna ini.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Delete / Action Button Section --}}
        <div class="pt-2">
            @if(auth()->id() === $user->id)
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-center text-xs text-amber-700 font-medium">
                    ℹ️ Anda sedang login dengan akun ini. Anda tidak dapat menghapus akun Anda sendiri.
                </div>
            @elseif($user->sales_count > 0 || $user->purchases_count > 0)
                <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-700 space-y-1">
                    <p class="font-bold">💡 Informasi Penghapusan Akun</p>
                    <p>Pengguna ini memiliki riwayat transaksi kasir ({{ $user->sales_count + $user->purchases_count }} transaksi). Untuk menjaga keutuhan data pembukuan, silakan ubah status akun menjadi <strong>Nonaktif</strong> melalui menu Edit jika kasir sudah tidak bekerja.</p>
                </div>
            @else
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus pengguna '{{ $user->name }}'?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 border-2 border-red-200 text-red-600 font-bold rounded-xl text-xs hover:bg-red-50 transition-colors active:scale-98">
                        🗑️ Hapus Pengguna Ini
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
