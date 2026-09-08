<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-dark">Tambah Pengguna Baru</h2>
                <p class="text-xs text-gray-500">Daftarkan akun Super Administrator, Pemilik Usaha (Owner), atau Kasir</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('admin.users.store') }}" method="POST" class="glass-card p-6 space-y-4">
            @csrf

            <div class="border-b border-gray-100 pb-3">
                <h3 class="font-bold text-dark text-sm">👤 Data Akun Pengguna</h3>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input-glass text-xs" placeholder="Nama lengkap pengguna">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input-glass text-xs" placeholder="email@domain.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor WhatsApp / HP</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input-glass text-xs" placeholder="0812-xxxx-xxxx">
                    <x-input-error :messages="$errors->get('phone')" class="mt-1" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Peran / Hak Akses (Role) <span class="text-red-500">*</span></label>
                    <select name="role" required class="form-input-glass text-xs">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Pemilik Usaha (Owner / User)</option>
                        <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Petugas Kasir (Cashier)</option>
                        <option value="superadmin" {{ old('role') === 'superadmin' ? 'selected' : '' }}>👑 Super Administrator</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tugaskan ke Bisnis (Opsional)</label>
                    <select name="active_business_id" class="form-input-glass text-xs">
                        <option value="">-- Tanpa Bisnis Default --</option>
                        @foreach($businesses as $biz)
                            <option value="{{ $biz->id }}" {{ old('active_business_id') == $biz->id ? 'selected' : '' }}>
                                {{ $biz->name }} ({{ $biz->owner->name ?? 'User' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Plan & Quota Limits Section --}}
            <div class="p-4 rounded-xl bg-purple-50/60 border border-purple-100 space-y-3">
                <div>
                    <h4 class="text-xs font-bold text-purple-900 flex items-center gap-1.5">
                        <span>📦</span> Paket Berlangganan & Batasan Kuota
                    </h4>
                    <p class="text-[10px] text-purple-600">Tetapkan paket standar SaaS atau tentukan kuota khusus untuk pengguna baru ini</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Paket SaaS</label>
                        <select name="plan_id" class="form-input-glass text-xs bg-white">
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" {{ (old('plan_id') == $plan->id || ($plan->is_default && !old('plan_id'))) ? 'selected' : '' }}>
                                    {{ $plan->name }} ({{ $plan->max_businesses >= 999999 ? '∞' : $plan->max_businesses }} Toko, {{ $plan->max_employees_per_business >= 999999 ? '∞' : $plan->max_employees_per_business }} Karyawan)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Custom Max Toko
                            <span class="text-[10px] text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="number" name="custom_max_businesses" min="1" value="{{ old('custom_max_businesses') }}" class="form-input-glass text-xs bg-white" placeholder="Kosongkan jika ikuti paket">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Custom Max Karyawan
                            <span class="text-[10px] text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="number" name="custom_max_employees" min="1" value="{{ old('custom_max_employees') }}" class="form-input-glass text-xs bg-white" placeholder="Kosongkan jika ikuti paket">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required class="form-input-glass text-xs" placeholder="Min. 8 karakter">
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Konfirmasi Sandi <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="form-input-glass text-xs" placeholder="Ulangi kata sandi">
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2 cursor-pointer pt-2">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm">
                    <span class="text-xs font-semibold text-dark">Status Akun Aktif (Dapat Login)</span>
                </label>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary text-xs py-2 px-4">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs py-2.5 px-5 font-bold shadow-md">
                    + Simpan Pengguna Baru
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
