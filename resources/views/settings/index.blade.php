<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-dark">Pengaturan Profil Usaha & Sistem KarsaERP</h2>
    </x-slot>

    <div class="py-5 pb-24 space-y-4">
        <x-settings-subnav />

        {{-- Manajemen Pengguna Shortcut Card --}}
        <a href="{{ route('users.index') }}" class="glass-card p-4 flex items-center justify-between hover:bg-white/80 transition-all block group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-600 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" fill="currentColor"/>
                        <path d="M6 21C6 17.134 9.13401 14 13 14H11C7.13401 14 4 17.134 4 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-dark">Manajemen Pengguna (User Accounts)</h3>
                    <p class="text-xs text-gray-500">Kelola akun kasir, staff gudang, akuntan, dan administrator sistem</p>
                </div>
            </div>
            <span class="text-xs font-bold text-primary-600 group-hover:translate-x-0.5 transition-transform flex items-center gap-1">
                Kelola Pengguna →
            </span>
        </a>

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Profil Bisnis --}}
            <div class="glass-card p-4 space-y-3">
                <h3 class="text-sm font-bold text-dark border-b border-gray-100 pb-2">🏢 Profil Bisnis & Perusahaan</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Nama Usaha / Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $settings->get('company_name', $settings->get('store_name', 'KarsaERP'))) }}" required class="form-input-glass">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Kategori / Model Bisnis</label>
                        <select name="business_type" class="form-input-glass">
                            @php
                                $currentType = old('business_type', $settings->get('business_type', 'general'));
                            @endphp
                            <option value="general" {{ $currentType == 'general' ? 'selected' : '' }}>UMKM & Usaha Dagang Serbaguna</option>
                            <option value="retail" {{ $currentType == 'retail' ? 'selected' : '' }}>Retail & Minimarket</option>
                            <option value="fnb" {{ $currentType == 'fnb' ? 'selected' : '' }}>F&B, Resto, Cafe & Kuliner</option>
                            <option value="service" {{ $currentType == 'service' ? 'selected' : '' }}>Jasa, Bengkel & Rental</option>
                            <option value="agriculture" {{ $currentType == 'agriculture' ? 'selected' : '' }}>Pertanian, Peternakan & Saprotan</option>
                            <option value="wholesale" {{ $currentType == 'wholesale' ? 'selected' : '' }}>Grosir & Distribusi</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">No. WhatsApp / Telepon Usaha</label>
                        <input type="text" name="company_phone" value="{{ old('company_phone', $settings->get('company_phone', $settings->get('store_phone', ''))) }}" class="form-input-glass" placeholder="0812-xxxx-xxxx">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Email Usaha</label>
                        <input type="email" name="company_email" value="{{ old('company_email', $settings->get('company_email', '')) }}" class="form-input-glass" placeholder="kontak@usaha.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Alamat Lengkap Toko / Kantor</label>
                    <textarea name="company_address" rows="2" class="form-input-glass" placeholder="Alamat lengkap usaha...">{{ old('company_address', $settings->get('company_address', $settings->get('store_address', ''))) }}</textarea>
                </div>
            </div>

            {{-- Keuangan & Pajak --}}
            <div class="glass-card p-4 space-y-3">
                <h3 class="text-sm font-bold text-dark border-b border-gray-100 pb-2">💰 Keuangan, Pajak & Margin</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Simbol Mata Uang</label>
                        <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings->get('currency_symbol', 'Rp')) }}" class="form-input-glass">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Tarif Pajak PPN (%) Default</label>
                        <input type="number" step="any" min="0" max="100" name="tax_percentage" value="{{ old('tax_percentage', $settings->get('tax_percentage', '0')) }}" class="form-input-glass">
                        <p class="text-[10px] text-gray-400 mt-1">Isi 0 jika usaha bebas PPN atau belum PKP.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Margin Minimum Produk (Rupiah)</label>
                        <input type="number" name="min_margin" value="{{ old('min_margin', $settings->get('min_margin', '1000')) }}" required class="form-input-glass">
                        <p class="text-[10px] text-gray-400 mt-1">Otomatisasi margin keuntungan saat kulakan stok baru.</p>
                    </div>
                </div>
            </div>

            {{-- Kasir & Struk POS --}}
            <div class="glass-card p-4 space-y-3">
                <h3 class="text-sm font-bold text-dark border-b border-gray-100 pb-2">🧾 Pengaturan Kasir & Struk POS</h3>
                
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="require_shift_for_pos" value="1" {{ old('require_shift_for_pos', $settings->get('require_shift_for_pos', '0')) == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500">
                        <span class="text-xs font-semibold text-dark">Wajibkan Kasir Buka Shift (Modal Awal) Sebelum Transaksi POS</span>
                    </label>
                    <p class="text-[10px] text-gray-400 mt-0.5 ml-6">Jika diaktifkan, menu kasir hanya dapat memproses penjualan setelah shift kasir dibuka.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Catatan Kaki Struk (Receipt Footer)</label>
                    <textarea name="receipt_footer" rows="2" class="form-input-glass" placeholder="Terima kasih atas kunjungan Anda...">{{ old('receipt_footer', $settings->get('receipt_footer', 'Terima kasih atas kunjungan Anda!')) }}</textarea>
                </div>
            </div>

            {{-- Backup Telegram --}}
            <div class="glass-card p-4 space-y-3">
                <h3 class="text-sm font-bold text-dark border-b border-gray-100 pb-2">🤖 Notifikasi & Backup Otomatis Telegram</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Telegram Bot Token</label>
                        <input type="text" name="telegram_bot_token" value="{{ old('telegram_bot_token', $settings->get('telegram_bot_token', '')) }}" class="form-input-glass" placeholder="123456789:ABCdefGhIJKlmNoPQRsT...">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Telegram Chat ID</label>
                        <input type="text" name="telegram_chat_id" value="{{ old('telegram_chat_id', $settings->get('telegram_chat_id', '')) }}" class="form-input-glass" placeholder="-100123456789">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base font-bold shadow-float flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Seluruh Pengaturan KarsaERP
            </button>
        </form>
    </div>
</x-app-layout>
