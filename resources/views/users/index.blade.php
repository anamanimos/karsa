<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Manajemen Pengguna"
            subtitle="Kelola akun administrator, staf toko, dan kasir"
            :searchAction="route('users.index')"
            searchPlaceholder="Cari nama, email, no. telepon..."
            :createRoute="route('users.create')"
            createLabel="Tambah Pengguna"
        />
    </x-slot>

    <div class="py-5 pb-24 space-y-4" x-data="{ 
        search: '{{ request('search', '') }}',
        role: '{{ request('role', '') }}',
        status: '{{ request('status', '') }}',
        applyFilter() {
            let params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.role) params.set('role', this.role);
            if (this.status) params.set('status', this.status);
            window.location.href = '{{ route('users.index') }}?' + params.toString();
        }
    }">
        <x-settings-subnav />

        {{-- Filters (Role & Status) --}}
        <div class="space-y-2.5">
            {{-- Role Filter Chips --}}
            <div class="flex items-center gap-1.5 overflow-x-auto scrollbar-hide py-1 whitespace-nowrap">
                <button type="button" 
                        @click="role = ''; applyFilter()"
                        :class="role === '' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0">
                    Semua Role
                </button>
                <button type="button" 
                        @click="role = 'admin'; applyFilter()"
                        :class="role === 'admin' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0">
                    👑 Admin
                </button>
                <button type="button" 
                        @click="role = 'kasir'; applyFilter()"
                        :class="role === 'kasir' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0">
                    🛒 Kasir
                </button>

                <div class="h-4 w-[1px] bg-gray-300 mx-1 shrink-0"></div>

                <button type="button" 
                        @click="status = status === 'active' ? '' : 'active'; applyFilter()"
                        :class="status === 'active' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0">
                    ✅ Hanya Aktif
                </button>
                <button type="button" 
                        @click="status = status === 'inactive' ? '' : 'inactive'; applyFilter()"
                        :class="status === 'inactive' ? 'bg-primary-600 text-white shadow-xs' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs hover:bg-white hover:text-gray-900'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all shrink-0">
                    ⛔ Nonaktif
                </button>
            </div>
        </div>

        {{-- Active Filters Indicator --}}
        @if(request('search') || request('role') || request('status'))
            <div class="glass-card px-3.5 py-2 flex items-center justify-between text-xs font-semibold">
                <div class="flex items-center gap-1.5 text-gray-500 flex-wrap">
                    <span>Filter:</span>
                    @if(request('search'))
                        <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-lg text-[10px]">Cari: "{{ request('search') }}"</span>
                    @endif
                    @if(request('role'))
                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase">{{ request('role') }}</span>
                    @endif
                    @if(request('status'))
                        <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded-lg text-[10px]">{{ request('status') === 'active' ? 'Aktif' : 'Nonaktif' }}</span>
                    @endif
                </div>
                <a href="{{ route('users.index') }}" class="text-[11px] font-bold text-red-500 hover:text-red-700 transition-colors shrink-0">
                    Reset
                </a>
            </div>
        @endif

        {{-- Users List --}}
        <div class="space-y-3">
            @forelse($users as $user)
            <div class="card-solid p-4 hover:shadow-md transition-all bg-white relative">
                <div class="flex items-start justify-between gap-3">
                    {{-- User Avatar & Info --}}
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 font-extrabold text-sm {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-sm font-bold text-dark truncate">{{ $user->name }}</h3>
                                @if(auth()->id() === $user->id)
                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-primary-100 text-primary-700 font-extrabold">Anda</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-gray-500 truncate flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M3 8L10.89 13.26C11.56 13.71 12.44 13.71 13.11 13.26L21 8M5 19H19C20.1 19 21 18.1 21 17V7C21 5.9 20.1 5 19 5H5C3.9 5 3 5.9 3 7V17C3 18.1 3.9 19 5 19Z" fill="currentColor"/>
                                    <path d="M3 8L10.89 13.26C11.56 13.71 12.44 13.71 13.11 13.26L21 8M5 19H19C20.1 19 21 18.1 21 17V7C21 5.9 20.1 5 19 5H5C3.9 5 3 5.9 3 7V17C3 18.1 3.9 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                                <span>{{ $user->email }}</span>
                            </p>

                            @if($user->phone)
                            <p class="text-xs text-gray-400 truncate flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M3 5c0-1.1.9-2 2-2h3.5c.55 0 1.05.3 1.25.8l1.2 3c.2.5.05 1.1-.35 1.5L8.7 10.2c1.2 2.1 2.9 3.8 5 5l1.9-1.9c.4-.4 1-.55 1.5-.35l3 1.2c.5.2.8.7.8 1.25V19c0 1.1-.9 2-2 2-9.4 0-17-7.6-17-17z" fill="currentColor"/>
                                    <path d="M3 5c0-1.1.9-2 2-2h3.5c.55 0 1.05.3 1.25.8l1.2 3c.2.5.05 1.1-.35 1.5L8.7 10.2c1.2 2.1 2.9 3.8 5 5l1.9-1.9c.4-.4 1-.55 1.5-.35l3 1.2c.5.2.8.7.8 1.25V19c0 1.1-.9 2-2 2-9.4 0-17-7.6-17-17z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                                <span>{{ $user->phone }}</span>
                            </p>
                            @endif

                            {{-- Badges --}}
                            <div class="flex items-center gap-1.5 pt-1 flex-wrap">
                                @if($user->role === 'admin')
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                        👑 Administrator
                                    </span>
                                @else
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        🛒 Kasir
                                    </span>
                                @endif

                                @if($user->is_active)
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                        Nonaktif
                                    </span>
                                @endif

                                <span class="text-[10px] text-gray-400">
                                    · {{ $user->sales_count + $user->purchases_count }} Transaksi
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Bar --}}
                <div class="border-t border-gray-100 mt-3 pt-2.5 flex items-center justify-between">
                    <a href="{{ route('users.show', $user) }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700 flex items-center gap-1">
                        Lihat Riwayat Transaksi
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('users.edit', $user) }}" 
                           class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-colors active:scale-95" 
                           title="Ubah Data Pengguna">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>

                        @if(auth()->id() !== $user->id)
                        <form action="{{ route('users.destroy', $user) }}" 
                              method="POST" 
                              class="confirm-delete inline" 
                              data-confirm="Apakah Anda yakin ingin menghapus akun '{{ $user->name }}'?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 border border-red-150 flex items-center justify-center transition-colors active:scale-95" 
                                    title="Hapus Pengguna">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M6 7H18L17 21H7L6 7Z" fill="currentColor"/>
                                    <path d="M6 7H18L17 21H7L6 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M4 7H20M10 3H14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="card-solid p-8 text-center space-y-2 bg-white">
                <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto text-gray-400">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" fill="currentColor"/>
                        <path d="M6 21C6 17.134 9.13401 14 13 14H11C7.13401 14 4 17.134 4 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <h4 class="text-sm font-bold text-dark">Tidak ada pengguna ditemukan</h4>
                <p class="text-xs text-gray-400">Coba ubah kata kunci pencarian atau filter yang digunakan.</p>
                <div class="pt-2">
                    <a href="{{ route('users.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-600 text-white text-xs font-bold shadow-md active:scale-95 transition-transform">
                        + Tambah Pengguna Baru
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="mt-4">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
