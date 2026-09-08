<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.businesses.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-xl font-bold text-dark">{{ $business->name }}</h2>
                        @if($business->is_active)
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">Tenant ID: #{{ $business->id }} • Dibuat pada {{ $business->created_at->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Impersonate / Switch to this business --}}
                <form action="{{ route('admin.businesses.impersonate', $business) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-primary text-xs py-2 px-3 flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Masuk Sebagai Tenant Ini
                    </button>
                </form>

                {{-- Toggle Status --}}
                <form action="{{ route('admin.businesses.toggle-status', $business) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-xs py-2 px-3 rounded-xl font-medium transition {{ $business->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' }}">
                        {{ $business->is_active ? 'Bekukan Tenant' : 'Aktifkan Tenant' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Metrics Quick Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="glass-card p-4">
                <span class="text-[11px] text-gray-500 font-medium block">Total Katalog Produk</span>
                <span class="text-xl font-black text-dark mt-0.5 block">{{ number_format($productsCount) }} item</span>
            </div>
            <div class="glass-card p-4 border-l-4 border-emerald-500">
                <span class="text-[11px] text-gray-500 font-medium block">Total Transaksi POS</span>
                <span class="text-xl font-black text-emerald-600 mt-0.5 block">{{ number_format($salesCount) }} struk</span>
            </div>
            <div class="glass-card p-4 border-l-4 border-blue-500">
                <span class="text-[11px] text-gray-500 font-medium block">Omzet Akumulasi</span>
                <span class="text-xl font-black text-blue-600 mt-0.5 block">Rp {{ number_format($salesTotal, 0, ',', '.') }}</span>
            </div>
            <div class="glass-card p-4 border-l-4 border-purple-500">
                <span class="text-[11px] text-gray-500 font-medium block">Pengguna & Staf</span>
                <span class="text-xl font-black text-purple-600 mt-0.5 block">{{ $business->users->count() + ($business->owner ? 1 : 0) }} orang</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Business Details --}}
            <div class="glass-card p-5 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        {{ strtoupper(substr($business->name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-dark">{{ $business->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $business->business_type ?? 'Usaha Umum' }}</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-3 space-y-2.5 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Pemilik Utama (Owner)</span>
                        @if($business->owner)
                            <a href="{{ route('admin.users.show', $business->owner) }}" class="font-bold text-primary-600 hover:underline">
                                {{ $business->owner->name }} ({{ $business->owner->email }})
                            </a>
                        @else
                            <span class="text-gray-500 italic">Tidak ada owner</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Telepon Toko</span>
                            <span class="font-semibold text-dark">{{ $business->phone ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Email Bisnis</span>
                            <span class="font-semibold text-dark">{{ $business->email ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="pt-1">
                        <span class="text-gray-400 block text-[10px] uppercase font-bold">Alamat & Lokasi</span>
                        <span class="text-gray-700 block">{{ $business->address ?? '-' }}</span>
                        @if($business->city)
                            <span class="text-gray-500 text-[11px] block">{{ $business->city }}, {{ $business->postal_code }}</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Mata Uang</span>
                            <span class="font-semibold text-dark">{{ $business->currency ?? 'IDR' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">Zona Waktu</span>
                            <span class="font-semibold text-dark">{{ $business->timezone ?? 'Asia/Jakarta' }}</span>
                        </div>
                    </div>

                    @if($business->tax_id)
                        <div class="pt-1">
                            <span class="text-gray-400 block text-[10px] uppercase font-bold">NPWP / Tax ID</span>
                            <span class="font-semibold text-dark">{{ $business->tax_id }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Users & Staff List --}}
            <div class="md:col-span-2 glass-card p-5">
                <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-2">
                    <h3 class="text-xs font-black uppercase tracking-wider text-gray-500">👥 Anggota & Staf Terdaftar di Bisnis Ini</h3>
                    <span class="text-xs font-bold text-emerald-600">{{ $business->users->count() + ($business->owner ? 1 : 0) }} Total Pengguna</span>
                </div>

                <div class="space-y-2">
                    {{-- Owner Entry --}}
                    @if($business->owner)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 border border-emerald-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">
                                    👑
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-dark">{{ $business->owner->name }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ $business->owner->email }} • {{ $business->owner->phone ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">
                                    Owner Bisnis
                                </span>
                                <a href="{{ route('admin.users.show', $business->owner) }}" class="text-xs text-primary-600 hover:underline ml-1">
                                    Profil &rarr;
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Staff / Assigned Users --}}
                    @forelse($business->users as $user)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50/70 border border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-700 flex items-center justify-center font-bold text-xs">
                                    👤
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-dark">{{ $user->name }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-gray-200 text-gray-700 capitalize">
                                    {{ $user->pivot->role ?? 'Staf' }}
                                </span>
                                <a href="{{ route('admin.users.show', $user) }}" class="text-xs text-primary-600 hover:underline ml-1">
                                    Profil &rarr;
                                </a>
                            </div>
                        </div>
                    @empty
                        @if(!$business->owner)
                            <p class="text-xs text-gray-400 py-4 text-center italic">Belum ada pengguna yang terhubung dengan bisnis ini.</p>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
