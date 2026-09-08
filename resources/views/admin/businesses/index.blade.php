<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-purple-100 text-purple-700 tracking-wider">
                    👑 Platform Super Administrator
                </span>
                <h2 class="text-xl font-bold text-dark mt-1">Kelola Seluruh Usaha (Platform Tenants)</h2>
                <p class="text-xs text-gray-500">Daftar seluruh unit bisnis / toko yang beroperasi di platform KarsaERP</p>
            </div>
            <a href="{{ route('businesses.create') }}" class="btn-primary text-xs py-2.5 px-4 flex items-center gap-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                + Buat Bisnis Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- KPI Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="glass-card p-4">
                <span class="text-[11px] text-gray-500 font-medium block">Total Tenant / Usaha Terdaftar</span>
                <span class="text-2xl font-black text-dark mt-0.5 block">{{ $stats['total'] }}</span>
            </div>
            <div class="glass-card p-4 border-l-4 border-emerald-500">
                <span class="text-[11px] text-gray-500 font-medium block">Usaha Aktif Beroperasi</span>
                <span class="text-2xl font-black text-emerald-600 mt-0.5 block">{{ $stats['active'] }}</span>
            </div>
            <div class="glass-card p-4 border-l-4 border-red-500">
                <span class="text-[11px] text-gray-500 font-medium block">Usaha Dibekukan / Nonaktif</span>
                <span class="text-2xl font-black text-red-600 mt-0.5 block">{{ $stats['inactive'] }}</span>
            </div>
        </div>

        {{-- Filter & Search Form --}}
        <div class="glass-card p-4">
            <form method="GET" action="{{ route('admin.businesses.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Cari Usaha</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bisnis, email, atau telepon..." class="form-input-glass text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status Usaha</label>
                    <select name="status" class="form-input-glass text-xs">
                        <option value="">-- Semua Status --</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-primary text-xs py-2.5 px-4 flex-1">
                        Filter
                    </button>
                    <a href="{{ route('admin.businesses.index') }}" class="btn-secondary text-xs py-2.5 px-3">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Businesses Table --}}
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/50 text-gray-500 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-4">Nama Usaha / Tenant</th>
                            <th class="py-3 px-4">Pemilik (Owner)</th>
                            <th class="py-3 px-4 text-center">Produk</th>
                            <th class="py-3 px-4 text-center">Transaksi Penjualan</th>
                            <th class="py-3 px-4 text-center">Staf/Kasir</th>
                            <th class="py-3 px-4 text-center">Status</th>
                            <th class="py-3 px-4 text-right">Aksi Platform</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($businesses as $business)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold text-sm shadow-sm flex-shrink-0">
                                            {{ strtoupper(substr($business->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.businesses.show', $business) }}" class="font-bold text-dark hover:text-primary-600 transition">
                                                {{ $business->name }}
                                            </a>
                                            <div class="text-[11px] text-gray-400">
                                                {{ $business->business_type ?? 'Usaha Umum' }} • {{ $business->city ?? 'Indonesia' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    @if($business->owner)
                                        <a href="{{ route('admin.users.show', $business->owner) }}" class="font-medium text-dark hover:text-primary-600 block">
                                            {{ $business->owner->name }}
                                        </a>
                                        <span class="text-[11px] text-gray-400 block">{{ $business->owner->email }}</span>
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-gray-700">
                                    {{ number_format($business->products_count) }}
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-gray-700">
                                    {{ number_format($business->sales_count) }}
                                </td>
                                <td class="py-3 px-4 text-center font-semibold text-gray-700">
                                    {{ number_format($business->users_count) }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @if($business->is_active)
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Impersonate / Enter Tenant View --}}
                                        <form action="{{ route('admin.businesses.impersonate', $business) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Masuk & Beralih ke Tampilan Usaha Ini" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition flex items-center gap-1 text-[11px] font-medium border border-blue-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat Toko
                                            </button>
                                        </form>

                                        {{-- Detail Link --}}
                                        <a href="{{ route('admin.businesses.show', $business) }}" title="Detail Usaha" class="p-1.5 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </a>

                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.businesses.toggle-status', $business) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ $business->is_active ? 'Bekukan Usaha' : 'Aktifkan Usaha' }}" class="p-1.5 {{ $business->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-lg transition">
                                                @if($business->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400">
                                    Belum ada data unit usaha yang terdaftar sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($businesses->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $businesses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
