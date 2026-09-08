<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Daftar Usaha & Bisnis Saya"
            subtitle="Kelola dan beralih antar unit usaha yang Anda miliki"
            :createRoute="auth()->user()->canCreateBusiness() ? route('businesses.create') : null"
            :createLabel="auth()->user()->canCreateBusiness() ? 'Buat Usaha Baru' : null"
        >
            @if(!auth()->user()->canCreateBusiness())
                <x-slot name="actions">
                    <button type="button" onclick="Swal.fire({title: 'Batas Kuota Tercapai', text: 'Anda telah mencapai batas maksimal unit usaha ({{ auth()->user()->ownedBusinesses()->count() }} dari {{ auth()->user()->maxBusinesses() }} toko). Silakan hubungi Super Admin untuk menambah kuota toko Anda.', icon: 'warning', confirmButtonText: 'Siap'})" class="px-3 py-2 rounded-lg bg-gray-200 text-gray-500 text-xs font-bold cursor-not-allowed flex items-center gap-1.5">
                        <span>🔒</span> Kuota Penuh
                    </button>
                </x-slot>
            @endif
        </x-page-header>
    </x-slot>

    <div class="py-5 pb-24 space-y-4">
        <x-settings-subnav />

        @php
            $currUser = auth()->user();
            $ownedCount = $currUser->ownedBusinesses()->count();
            $maxBiz = $currUser->maxBusinesses();
            $canCreate = $currUser->canCreateBusiness();
            $planName = $currUser->plan ? $currUser->plan->name : 'Standar';
        @endphp

        {{-- Store Quota Banner --}}
        <div class="glass-card p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-l-4 {{ $canCreate ? 'border-primary-500' : 'border-amber-500 bg-amber-50/40' }}">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl {{ $canCreate ? 'bg-primary-100 text-primary-700' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center font-bold text-lg flex-shrink-0">
                    {{ $canCreate ? '🏢' : '⚠️' }}
                </div>
                <div>
                    <h4 class="text-xs font-bold text-dark">
                        Status Kuota Toko: <span class="text-primary-700 font-extrabold">{{ $ownedCount }} dari {{ $maxBiz >= 999999 ? 'Tanpa Batas (∞)' : $maxBiz }} Toko Terpakai</span>
                    </h4>
                    <p class="text-[11px] text-gray-500">
                        Paket: <strong class="text-dark">{{ $planName }}</strong>
                        @if($currUser->custom_max_businesses)
                            <span class="text-purple-600 font-semibold">(Limit Override: {{ $currUser->custom_max_businesses }} Toko)</span>
                        @endif
                        @if(!$canCreate)
                            • <span class="text-amber-700 font-bold">Batas toko telah tercapai. Hubungi Admin untuk upgrade.</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($businesses as $biz)
                @php
                    $isActive = auth()->user()->active_business_id == $biz->id;
                    $productsCount = \App\Models\Product::withoutGlobalScope('business')->where('business_id', $biz->id)->count();
                    $salesCount = \App\Models\Sale::withoutGlobalScope('business')->where('business_id', $biz->id)->count();
                @endphp
                <div class="glass-card p-5 relative overflow-hidden flex flex-col justify-between transition-all hover:shadow-md {{ $isActive ? 'border-2 border-primary-500 bg-primary-50/20' : '' }}">
                    @if($isActive)
                        <div class="absolute top-0 right-0 bg-primary-600 text-white text-[10px] font-extrabold uppercase px-3 py-1 rounded-bl-xl shadow-sm flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Aktif Sekarang
                        </div>
                    @endif

                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary-500 to-teal-600 text-white flex items-center justify-center font-black text-lg shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($biz->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1 pr-12">
                                <h3 class="text-sm font-bold text-dark truncate">{{ $biz->name }}</h3>
                                <span class="inline-block text-[10px] font-semibold text-primary-700 bg-primary-100/70 px-2 py-0.5 rounded-full mt-0.5">
                                    {{ ucfirst($biz->business_type) }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-100 text-xs">
                            <div class="bg-gray-50 p-2 rounded-xl">
                                <span class="text-[10px] text-gray-400 block font-medium">Total Produk</span>
                                <span class="font-bold text-dark">{{ $productsCount }} Item</span>
                            </div>
                            <div class="bg-gray-50 p-2 rounded-xl">
                                <span class="text-[10px] text-gray-400 block font-medium">Transaksi POS</span>
                                <span class="font-bold text-dark">{{ $salesCount }} Faktur</span>
                            </div>
                        </div>

                        @if($biz->address || $biz->phone)
                            <div class="text-[11px] text-gray-500 space-y-0.5 pt-1">
                                @if($biz->phone)
                                    <p class="truncate">📞 {{ $biz->phone }}</p>
                                @endif
                                @if($biz->address)
                                    <p class="truncate">📍 {{ $biz->address }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-4 mt-3 border-t border-gray-100">
                        @if(!$isActive)
                            <form action="{{ route('businesses.switch', $biz->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn-primary w-full text-xs py-2">
                                    Beralih ke Usaha Ini
                                </button>
                            </form>
                        @else
                            <span class="flex-1 text-center py-2 text-xs font-bold text-primary-700 bg-primary-100 rounded-xl">
                                Sedang Digunakan
                            </span>
                        @endif

                        <a href="{{ route('businesses.edit', $biz->id) }}" class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition" title="Pengaturan Usaha">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full glass-card p-8 text-center text-gray-400">
                    <p class="text-sm">Belum ada usaha yang terdaftar.</p>
                    <a href="{{ route('businesses.create') }}" class="btn-primary text-xs py-2 px-4 inline-block mt-3">
                        + Buat Usaha Pertama
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
