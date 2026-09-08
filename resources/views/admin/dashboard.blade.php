<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-purple-100 text-purple-700 tracking-wider">
                    👑 Platform Super Administrator
                </span>
                <h2 class="text-xl font-bold text-dark mt-1">Ringkasan Platform KarsaERP</h2>
                <p class="text-xs text-gray-500">Monitoring metrik platform, seluruh pengguna, dan seluruh unit usaha/tenants</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.create') }}" class="btn-primary text-xs py-2.5 px-4 flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    + Tambah Pengguna Baru
                </a>
                <a href="{{ route('businesses.create') }}" class="btn-secondary text-xs py-2.5 px-3 flex items-center gap-1.5">
                    <span>🏢</span> + Tenant
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- KPI Cards Platform --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-card p-4 border-l-4 border-purple-500">
                <span class="text-xs text-gray-500 font-medium block">Total Pengguna Terdaftar</span>
                <span class="text-2xl font-black text-dark mt-1 block">{{ $stats['total_users'] }} User</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block">
                    {{ $stats['total_owners'] }} Pemilik • {{ $stats['total_cashiers'] }} Kasir • {{ $stats['total_superadmins'] }} Superadmin
                </span>
            </div>

            <div class="glass-card p-4 border-l-4 border-blue-500">
                <span class="text-xs text-gray-500 font-medium block">Total Unit Usaha (Tenants)</span>
                <span class="text-2xl font-black text-blue-600 mt-1 block">{{ $stats['total_businesses'] }} Bisnis</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block">
                    {{ $stats['active_businesses'] }} Aktif Berjalan
                </span>
            </div>

            <div class="glass-card p-4 border-l-4 border-emerald-500">
                <span class="text-xs text-gray-500 font-medium block">Total Produk di Seluruh Platform</span>
                <span class="text-2xl font-black text-emerald-600 mt-1 block">{{ $stats['total_products'] }} SKU</span>
                <span class="text-[10px] text-gray-400 mt-0.5 block">Katalog master produk global</span>
            </div>

            <div class="glass-card-solid p-4 bg-gradient-to-br from-purple-900 to-indigo-950 text-white shadow-xl">
                <span class="text-xs font-semibold text-purple-200 block">Total Omset Platform SaaS</span>
                <span class="text-xl font-black mt-1 block">Rp {{ number_format($stats['total_sales_amount'], 0, ',', '.') }}</span>
                <span class="text-[10px] text-purple-300 mt-0.5 block">{{ $stats['total_sales_count'] }} total transaksi penjualan POS</span>
            </div>
        </div>

        {{-- Quick Navigation & Actions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.users.index') }}" class="glass-card p-4 flex items-center justify-between hover:bg-white/80 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 group-hover:scale-105 transition-transform font-bold text-lg">
                        👥
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-dark">Kelola Seluruh Pengguna</h3>
                        <p class="text-xs text-gray-500">Tambah user, reset kata sandi, ubah peran & hak akses</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-purple-600 group-hover:translate-x-1 transition-transform">
                    Buka User →
                </span>
            </a>

            <a href="{{ route('admin.businesses.index') }}" class="glass-card p-4 flex items-center justify-between hover:bg-white/80 transition-all group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-105 transition-transform font-bold text-lg">
                        🏢
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-dark">Kelola Seluruh Bisnis (Tenants)</h3>
                        <p class="text-xs text-gray-500">Pantau usaha yang dibuat pengguna & status aktivasi</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                    Buka Bisnis →
                </span>
            </a>
        </div>

        {{-- Two Columns: Recent Users & Recent Businesses --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {{-- Recent Users --}}
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-dark text-sm">Pengguna Terbaru</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-primary-600 hover:underline">Lihat Semua &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($recentUsers as $user)
                        <div class="p-3.5 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-dark">{{ $user->name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $user->role === 'superadmin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'user' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                                <span class="text-[10px] text-gray-400 block mt-0.5">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Businesses --}}
            <div class="glass-card overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-bold text-dark text-sm">Unit Usaha Terbaru</h3>
                    <a href="{{ route('admin.businesses.index') }}" class="text-xs font-semibold text-primary-600 hover:underline">Lihat Semua &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100 text-xs">
                    @foreach($recentBusinesses as $biz)
                        <div class="p-3.5 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div>
                                <p class="font-bold text-dark">{{ $biz->name }}</p>
                                <p class="text-[10px] text-gray-400">Pemilik: {{ $biz->owner->name ?? 'User' }} • {{ ucfirst($biz->business_type) }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $biz->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $biz->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </span>
                                <span class="text-[10px] text-gray-400 block mt-0.5">{{ $biz->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
