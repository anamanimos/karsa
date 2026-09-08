<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <span class="px-2.5 py-0.5 text-[10px] font-black uppercase rounded-full bg-purple-100 text-purple-700 tracking-wider">
                    👑 Platform Subscription & Quotas
                </span>
                <h2 class="text-xl font-bold text-dark mt-1">Paket Berlangganan & Batasan Kuota (Plans)</h2>
                <p class="text-xs text-gray-500">Atur batasan jumlah toko dan karyawan untuk masing-masing tingkatan paket SaaS</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        {{-- Plan Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($plans as $plan)
                <div class="glass-card p-5 relative flex flex-col justify-between border-2 {{ $plan->slug === 'pro' ? 'border-purple-500 shadow-purple-500/10' : ($plan->slug === 'enterprise' ? 'border-amber-400' : 'border-gray-200') }}">
                    @if($plan->slug === 'pro')
                        <span class="absolute -top-3 right-4 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-purple-600 text-white shadow-sm">
                            ⭐ Paling Populer
                        </span>
                    @elseif($plan->is_default)
                        <span class="absolute -top-3 right-4 px-3 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                            ✓ Paket Default Register
                        </span>
                    @endif

                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-dark text-base">{{ $plan->name }}</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $plan->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        <p class="text-xs text-gray-500 mt-1 min-h-[32px]">{{ $plan->description }}</p>

                        <div class="mt-4 pb-4 border-b border-gray-100">
                            <span class="text-2xl font-black text-dark">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium">/ {{ $plan->billing_cycle === 'monthly' ? 'bulan' : $plan->billing_cycle }}</span>
                        </div>

                        {{-- Quota Metrics --}}
                        <div class="mt-4 space-y-2.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 flex items-center gap-1.5">
                                    <span>🏢</span> Batas Unit Usaha (Toko)
                                </span>
                                <span class="font-bold text-dark">
                                    {{ $plan->max_businesses >= 999999 ? 'Tanpa Batas (∞)' : $plan->max_businesses . ' Toko' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 flex items-center gap-1.5">
                                    <span>👥</span> Batas Karyawan per Toko
                                </span>
                                <span class="font-bold text-dark">
                                    {{ $plan->max_employees_per_business >= 999999 ? 'Tanpa Batas (∞)' : $plan->max_employees_per_business . ' Karyawan' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500 flex items-center gap-1.5">
                                    <span>📦</span> Batas Produk Master
                                </span>
                                <span class="font-bold text-dark">
                                    {{ $plan->max_products_per_business >= 999999 ? 'Tanpa Batas (∞)' : $plan->max_products_per_business . ' SKU' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1">
                                <span class="text-gray-500 flex items-center gap-1.5">
                                    <span>👤</span> Pengguna Berlangganan
                                </span>
                                <span class="font-bold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-lg">
                                    {{ $plan->users_count }} Pengguna
                                </span>
                            </div>
                        </div>

                        {{-- Feature Bullets --}}
                        @if(!empty($plan->features))
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Fitur Termasuk:</p>
                                <ul class="space-y-1 text-xs text-gray-600">
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-center gap-2">
                                            <span class="text-emerald-500 font-bold">✓</span>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- Edit Form Accordion --}}
                    <div x-data="{ editOpen: false }" class="mt-5 pt-3 border-t border-gray-100">
                        <button @click="editOpen = !editOpen" type="button" class="w-full btn-secondary text-xs py-2 flex items-center justify-center gap-1.5 font-semibold">
                            <span x-text="editOpen ? 'Tutup Pengaturan' : '⚙️ Ubah Parameter Kuota'"></span>
                        </button>

                        <div x-show="editOpen" class="mt-3 space-y-3" style="display: none;">
                            <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="space-y-2.5">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Nama Paket</label>
                                    <input type="text" name="name" value="{{ $plan->name }}" required class="form-input-glass text-xs py-1.5">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Harga (Rp/bln)</label>
                                    <input type="number" name="price" value="{{ (int)$plan->price }}" min="0" required class="form-input-glass text-xs py-1.5">
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Maks Toko</label>
                                        <input type="number" name="max_businesses" value="{{ $plan->max_businesses }}" min="1" required class="form-input-glass text-xs py-1.5">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-500 uppercase">Maks Karyawan</label>
                                        <input type="number" name="max_employees_per_business" value="{{ $plan->max_employees_per_business }}" min="1" required class="form-input-glass text-xs py-1.5">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-500 uppercase">Maks Produk (SKU)</label>
                                    <input type="number" name="max_products_per_business" value="{{ $plan->max_products_per_business }}" min="1" required class="form-input-glass text-xs py-1.5">
                                </div>

                                <div>
                                    <label class="flex items-center gap-2 cursor-pointer pt-1">
                                        <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600">
                                        <span class="text-xs text-dark">Status Paket Aktif</span>
                                    </label>
                                </div>

                                <button type="submit" class="w-full btn-primary text-xs py-2 mt-2 font-bold shadow-sm">
                                    Simpan Perubahan Paket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>
