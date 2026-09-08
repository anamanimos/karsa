{{-- Reports Sub Navigation Tabs (Product Chip Style) --}}
<div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap mb-3">
    <a href="{{ route('reports.sales') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('reports.sales') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Laporan Penjualan
    </a>
    <a href="{{ route('reports.purchases') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('reports.purchases') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Laporan Pembelian
    </a>
    <a href="{{ route('reports.profit') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('reports.profit') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Laba Kotor / Rugi
    </a>
    <a href="{{ route('reports.debts') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('reports.debts') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Buku Hutang
    </a>
    <a href="{{ route('reports.stock_movements') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('reports.stock_movements') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Riwayat Stok
    </a>
</div>
