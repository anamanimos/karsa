{{-- Settings & Business Configuration Sub Navigation Tabs (Product Chip Style) --}}
<div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap mb-3">
    <a href="{{ route('businesses.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('businesses.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Unit Usaha
    </a>
    <a href="{{ route('settings.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('settings.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Pengaturan
    </a>
    <a href="{{ route('users.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('users.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Staf / Kasir
    </a>
    <a href="{{ route('galleries.index') }}" 
       class="px-3 py-1.5 rounded-lg text-xs font-semibold shrink-0 transition-all {{ request()->routeIs('galleries.*') ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900' }}">
        Galeri Foto
    </a>
</div>
