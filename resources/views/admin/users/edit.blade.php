<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-dark">Edit Pengguna: {{ $user->name }}</h2>
                <p class="text-xs text-gray-500">Perbarui profil, hak akses (role), atau ganti kata sandi</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="glass-card p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-dark text-sm">👤 Data Akun Pengguna</h3>
                <span class="text-xs text-gray-400">ID #{{ $user->id }}</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input-glass text-xs" placeholder="Nama lengkap pengguna">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input-glass text-xs" placeholder="email@domain.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-input-glass text-xs" placeholder="0812-xxxx-xxxx">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Peran / Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" required class="form-input-glass text-xs">
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Pemilik Usaha (Owner / User)</option>
                        <option value="kasir" {{ old('role', $user->role) === 'kasir' ? 'selected' : '' }}>Petugas Kasir (Cashier)</option>
                        <option value="superadmin" {{ old('role', $user->role) === 'superadmin' ? 'selected' : '' }}>👑 Super Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tugaskan ke Bisnis (Opsional)</label>
                    <select name="active_business_id" class="form-input-glass text-xs">
                        <option value="">-- Tanpa Bisnis Default --</option>
                        @foreach($businesses as $biz)
                            <option value="{{ $biz->id }}" {{ old('active_business_id', $user->active_business_id) == $biz->id ? 'selected' : '' }}>
                                {{ $biz->name }} ({{ $biz->owner->name ?? 'User' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Plan & Quota Limits Section --}}
            <div class="p-4 rounded-xl bg-purple-50/60 border border-purple-100 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-purple-900 flex items-center gap-1.5">
                            <span>📦</span> Paket Berlangganan & Kuota Tenant
                        </h4>
                        <p class="text-[10px] text-purple-600">Pilih paket standar atau tentukan limit khusus (override) untuk pengguna ini</p>
                    </div>
                    @if($user->plan)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-purple-200 text-purple-800">
                            {{ $user->plan->name }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Paket SaaS</label>
                        <select name="plan_id" class="form-input-glass text-xs bg-white">
                            <option value="">-- Tanpa Paket --</option>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ old('plan_id', $user->plan_id) == $plan->id ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->max_businesses >= 999999 ? '∞' : $plan->max_businesses }} Toko, {{ $plan->max_employees_per_business >= 999999 ? '∞' : $plan->max_employees_per_business }} Karyawan)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Custom Max Toko
                            <span class="text-[10px] text-gray-400 font-normal">(Override)</span>
                        </label>
                        <input type="number" name="custom_max_businesses" min="1" value="{{ old('custom_max_businesses', $user->custom_max_businesses) }}" class="form-input-glass text-xs bg-white" placeholder="Ikuti paket ({{ $user->plan?->max_businesses ?? 1 }})">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Custom Max Karyawan
                            <span class="text-[10px] text-gray-400 font-normal">(Override)</span>
                        </label>
                        <input type="number" name="custom_max_employees" min="1" value="{{ old('custom_max_employees', $user->custom_max_employees) }}" class="form-input-glass text-xs bg-white" placeholder="Ikuti paket ({{ $user->plan?->max_employees_per_business ?? 3 }})">
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100">
                <p class="text-[11px] text-gray-500 mb-2 italic">Kosongkan sandi jika tidak ingin mengubah kata sandi pengguna ini.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Kata Sandi Baru</label>
                        <input type="password" name="password" class="form-input-glass text-xs" placeholder="Minimal 8 karakter">
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Sandi Baru</label>
                        <input type="password" name="password_confirmation" class="form-input-glass text-xs" placeholder="Ulangi kata sandi baru">
                    </div>
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer pt-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm">
                    <span class="text-xs font-semibold text-dark">Status Akun Aktif (Dapat Login)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs py-2 px-4">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs py-2.5 px-5 font-bold shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
