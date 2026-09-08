<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('businesses.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-dark">Tambah Unit Usaha Baru</h2>
                <p class="text-xs text-gray-500">Buat entitas bisnis baru dengan Chart of Accounts dan pengaturan terpisah</p>
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24 max-w-2xl mx-auto">
        <form action="{{ route('businesses.store') }}" method="POST" class="glass-card p-6 space-y-4">
            @csrf

            <div class="border-b border-gray-100 pb-3">
                <h3 class="font-bold text-dark text-sm">🏢 Informasi Usaha Baru</h3>
                <p class="text-xs text-gray-400">Sistem akan otomatis menginisialisasi bagan akun akuntansi dan satuan standar untuk usaha ini.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Usaha / Toko / Cabang <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input-glass" placeholder="Contoh: Toko Cabang Sudirman / Kopi Mantap 2">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Model / Kategori Bisnis <span class="text-red-500">*</span></label>
                    <select name="business_type" required class="form-input-glass text-xs">
                        <option value="general">UMKM & Usaha Dagang Serbaguna</option>
                        <option value="retail">Retail & Minimarket</option>
                        <option value="fnb">F&B, Resto, Cafe & Kuliner</option>
                        <option value="service">Jasa, Bengkel & Rental</option>
                        <option value="agriculture">Pertanian & Peternakan</option>
                        <option value="wholesale">Grosir & Distribusi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Simbol Mata Uang</label>
                    <input type="text" name="currency_symbol" value="Rp" class="form-input-glass text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input-glass text-xs" placeholder="0812-xxxx-xxxx">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Usaha</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input-glass text-xs" placeholder="kontak@usaha.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Usaha</label>
                <textarea name="address" rows="2" class="form-input-glass text-xs" placeholder="Alamat lengkap toko/kantor..."></textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Tarif PPN (%) Default</label>
                    <input type="number" step="any" min="0" max="100" name="tax_percentage" value="0" class="form-input-glass text-xs">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Margin Minimum Produk (Rp)</label>
                    <input type="number" name="min_margin" value="1000" class="form-input-glass text-xs">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('businesses.index') }}" class="btn-secondary text-xs py-2 px-4">
                    Batal
                </a>
                <button type="submit" class="btn-primary text-xs py-2.5 px-5 font-bold shadow-md">
                    + Buat Usaha & Aktifkan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
