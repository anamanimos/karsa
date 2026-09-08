{{-- Financial Reports Sub Navigation Tabs (Product Chip Style) --}}
<div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap mb-3">
    <a href="{{ route('financial-reports.profit-loss') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('financial-reports.profit-loss') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Laba Rugi
    </a>
    <a href="{{ route('financial-reports.cash-flow') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('financial-reports.cash-flow') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Arus Kas
    </a>
    <a href="{{ route('financial-reports.balance-sheet') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('financial-reports.balance-sheet') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Neraca Keuangan
    </a>
    <a href="{{ route('financial-reports.general-ledger') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('financial-reports.general-ledger') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Buku Kas & Bank
    </a>
</div>
