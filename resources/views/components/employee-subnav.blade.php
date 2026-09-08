{{-- Employee / HR Sub Navigation Tabs (Product Chip Style) --}}
<div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap mb-2">
    <a href="{{ route('employees.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('employees.index') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Daftar Pegawai
    </a>
    <a href="{{ route('employee-attendances.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('employee-attendances.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Presensi Bulanan
    </a>
    <a href="{{ route('employee-bonuses.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('employee-bonuses.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Bonus & Komisi
    </a>
    <a href="{{ route('payrolls.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('payrolls.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Penggajian (Slip)
    </a>
</div>
