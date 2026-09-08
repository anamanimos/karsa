{{-- Sales & POS Sub Navigation Tabs (Product Chip Style) --}}
<div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap mb-3">
    <a href="{{ route('sales.create') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('sales.create') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Kasir POS
    </a>
    <a href="{{ route('sales.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('sales.index', 'sales.show') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Riwayat Penjualan
    </a>
    <a href="{{ route('cash-registers.index', 'cash-registers.*') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('cash-registers.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Shift Kasir
    </a>
</div>
