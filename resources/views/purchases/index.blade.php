<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Riwayat Pembelian"
            subtitle="Daftar transaksi pengadaan barang dan nota pembelian dari pemasok"
            :searchAction="route('purchases.index')"
            searchPlaceholder="Cari no. invoice, nama tengkulak..."
            :createRoute="route('purchases.create')"
            createLabel="Catat Pembelian"
        >
            <x-slot name="actions">
                <button type="button" 
                        @click="showFilterModal = true"
                        class="btn-secondary text-xs py-2 px-3 rounded-lg flex items-center gap-1.5 shadow-2xs font-semibold shrink-0 relative"
                        title="Filter Tanggal">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Filter</span>
                    @if(request('date_from') || request('date_to') || request('supplier_id'))
                        <span class="w-2 h-2 rounded-full bg-primary-600"></span>
                    @endif
                </button>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-5 pb-24 space-y-4" x-data="{ showFilterModal: false }">
        <x-procurement-subnav />

        {{-- Active Search Indicator --}}
        <x-active-search-indicator :resetUrl="route('purchases.index')" />

        {{-- Purchase List --}}
        <div class="glass-card overflow-hidden">
            <div class="divide-y divide-gray-100">
                @forelse($purchases as $purchase)
                <div class="p-4 flex items-center justify-between hover:bg-white/40 transition-colors">
                    <a href="{{ route('purchases.show', $purchase) }}" class="flex-1">
                        <p class="text-sm font-semibold text-dark">{{ $purchase->invoice_number }}</p>
                        <p class="text-xs text-gray-400">Tengkulak: <span class="font-medium text-dark">{{ $purchase->supplier->name }}</span></p>
                        <p class="text-xs text-gray-400">{{ $purchase->purchase_date->locale('id')->isoFormat('D MMM Y') }}</p>
                    </a>
                    <div class="text-right flex items-center gap-2">
                        <div>
                            <p class="text-sm font-bold text-dark">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
                            @if($purchase->payment_status === 'paid')
                                <span class="badge-paid">Lunas</span>
                            @elseif($purchase->payment_status === 'partial')
                                <span class="badge-partial">Sebagian</span>
                            @else
                                <span class="badge-unpaid">Belum Lunas</span>
                            @endif
                        </div>
                        <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="inline confirm-delete" data-confirm="Apakah Anda yakin ingin menghapus data pembelian {{ $purchase->invoice_number }}? Stok barang akan disesuaikan secara otomatis.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Pembelian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400 text-sm">
                    Belum ada transaksi pembelian.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pagination --}}
        @if($purchases->hasPages())
        <div class="mt-4">
            {{ $purchases->appends(request()->query())->links() }}
        </div>
        @endif
        
        <!-- Modal Filter Tanggal -->
        <div x-show="showFilterModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
             
             {{-- Backdrop --}}
             <div class="fixed inset-0 bg-black/40 backdrop-blur-sm pointer-events-auto" @click="showFilterModal = false"></div>
             
             {{-- Modal content wrapper (aligned to bottom for mobile look) --}}
             <div class="flex items-end justify-center min-h-screen p-4 sm:items-center">
                  <div class="bg-white rounded-t-2xl sm:rounded-2xl max-w-sm w-full p-5 shadow-2xl relative z-10 transform transition-all duration-300 ease-out border border-gray-150 max-h-[70vh] flex flex-col pointer-events-auto"
                       x-show="showFilterModal"
                       x-transition:enter="transition ease-out duration-300 transform"
                       x-transition:enter-start="translate-y-full sm:scale-95"
                       x-transition:enter-end="translate-y-0 sm:scale-100"
                       x-transition:leave="transition ease-in duration-200 transform"
                       x-transition:leave-start="translate-y-0 sm:scale-100"
                       x-transition:leave-end="translate-y-full sm:scale-95">
                       
                       <div class="flex justify-between items-center pb-3 border-b border-gray-100 mb-4 flex-shrink-0">
                            <h3 class="text-sm font-bold text-dark">Filter Riwayat Pembelian</h3>
                            <button @click="showFilterModal = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                       </div>
                       
                       <form action="{{ route('purchases.index') }}" method="GET" class="space-y-4">
                           <div class="grid grid-cols-2 gap-3">
                               <div>
                                   <label class="block text-[10px] font-semibold text-gray-500 mb-1">Mulai Tanggal</label>
                                   <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input-glass py-1.5 px-3">
                               </div>
                               <div>
                                   <label class="block text-[10px] font-semibold text-gray-500 mb-1">Sampai Tanggal</label>
                                   <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input-glass py-1.5 px-3">
                               </div>
                           </div>
                           
                           <div class="pt-2 flex gap-2">
                               <button type="submit" class="btn-primary py-2 text-xs flex-1">Terapkan Filter</button>
                               <a href="{{ route('purchases.index') }}" class="btn-secondary py-2 text-xs text-center flex-1">Reset</a>
                           </div>
                       </form>
                  </div>
             </div>
        </div>

    </div>
</x-app-layout>

