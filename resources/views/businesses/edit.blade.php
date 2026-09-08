<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('businesses.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-dark">Pengaturan Profil Usaha: {{ $business->name }}</h2>
                <p class="text-xs text-gray-500">Konfigurasi data identitas usaha, PPN, dan catatan struk kasir</p>
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24 max-w-2xl mx-auto">
        <form action="{{ route('businesses.update', $business->id) }}" method="POST" class="glass-card p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-100 pb-3">
                <h3 class="font-bold text-dark text-sm">🏢 Identitas Usaha</h3>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Usaha / Toko <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $business->name) }}" required class="form-input-glass">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Model / Kategori Bisnis <span class="text-red-500">*</span></label>
                    <select name="business_type" required class="form-input-glass text-xs">
                        <option value="general" {{ $business->business_type == 'general' ? 'selected' : '' }}>UMKM & Usaha Dagang Serbaguna</option>
                        <option value="retail" {{ $business->business_type == 'retail' ? 'selected' : '' }}>Retail & Minimarket</option>
                        <option value="fnb" {{ $business->business_type == 'fnb' ? 'selected' : '' }}>F&B, Resto, Cafe & Kuliner</option>
                        <option value="service" {{ $business->business_type == 'service' ? 'selected' : '' }}>Jasa, Bengkel & Rental</option>
                        <option value="agriculture" {{ $business->business_type == 'agriculture' ? 'selected' : '' }}>Pertanian & Peternakan</option>
                        <option value="wholesale" {{ $business->business_type == 'wholesale' ? 'selected' : '' }}>Grosir & Distribusi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Simbol Mata Uang</label>
                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $business->currency_symbol) }}" class="form-input-glass text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">No. Telepon / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $business->phone) }}" class="form-input-glass text-xs" placeholder="0812-xxxx-xxxx">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Usaha</label>
                    <input type="email" name="email" value="{{ old('email', $business->email) }}" class="form-input-glass text-xs" placeholder="kontak@usaha.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat Lengkap</label>
                <textarea name="address" rows="2" class="form-input-glass text-xs">{{ old('address', $business->address) }}</textarea>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <h3 class="font-bold text-dark text-sm mb-3">🧾 Aturan Kasir POS & Struk</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Tarif PPN (%) Default</label>
                        <input type="number" step="any" min="0" max="100" name="tax_percentage" value="{{ old('tax_percentage', $business->tax_percentage) }}" class="form-input-glass text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Margin Minimum Produk (Rp)</label>
                        <input type="number" name="min_margin" value="{{ old('min_margin', $business->min_margin) }}" class="form-input-glass text-xs">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="require_shift_for_pos" value="1" {{ old('require_shift_for_pos', $business->require_shift_for_pos) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm">
                        <span class="text-xs font-semibold text-dark">Wajibkan Kasir Buka Shift (Modal Awal) Sebelum Transaksi POS</span>
                    </label>
                </div>

                <div class="mt-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan Kaki Struk (Receipt Footer)</label>
                    <textarea name="receipt_footer" rows="2" class="form-input-glass text-xs">{{ old('receipt_footer', $business->receipt_footer) }}</textarea>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-3">
                <h3 class="font-bold text-dark text-sm mb-3">🤖 Notifikasi & Backup Telegram</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Telegram Bot Token</label>
                        <input type="text" name="telegram_bot_token" value="{{ old('telegram_bot_token', $business->telegram_bot_token) }}" class="form-input-glass text-xs" placeholder="Token bot...">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Telegram Chat ID</label>
                        <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $business->telegram_chat_id) }}" class="form-input-glass text-xs" placeholder="Chat ID...">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-2">
                <a href="{{ route('businesses.index') }}" class="btn-secondary text-xs py-2 px-4">
                    Kembali
                </a>
                <button type="submit" class="btn-primary text-xs py-2.5 px-5 font-bold shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
