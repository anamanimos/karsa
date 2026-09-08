<div x-data="{ 
    searchQuery: '',
    activeSection: 'pengantar',
    scrollTo(id) {
        this.activeSection = id;
        const el = document.getElementById(id);
        if (el) {
            const yOffset = -90; 
            const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        }
    },
    matches(text) {
        if (!this.searchQuery) return true;
        return text.toLowerCase().includes(this.searchQuery.toLowerCase());
    }
}" class="py-4 space-y-6">

    {{-- 1. Hero Search & Quick Jump Header --}}
    <div class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-gradient-to-r from-primary-900/5 via-primary-800/10 to-transparent relative overflow-hidden shadow-xs">
        <div class="max-w-3xl relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 border border-primary-200/60 text-primary-700 text-xs font-bold mb-3">
                <span>📚 Panduan Resmi KarsaERP v2.4</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] text-gray-500 font-normal">Diperbarui Maret 2026</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-dark tracking-tight mb-2">
                Pusat Bantuan & Panduan Operasional Sistem
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 leading-relaxed mb-5">
                Dokumentasi lengkap tata cara penggunaan KarsaERP untuk operasional kasir harian, manajemen inventori pergudangan, pencatatan pengadaan, akuntansi laba-rugi, hingga penggajian karyawan.
            </p>

            {{-- Instant Search inside Documentation --}}
            <div class="relative max-w-xl">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       x-model="searchQuery" 
                       placeholder="Ketik topik panduan (contoh: shift kasir, stok opname, slip gaji, hutang)..."
                       class="w-full pl-11 pr-9 py-3 rounded-xl border border-gray-200 bg-white text-xs sm:text-sm font-medium text-dark focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition shadow-xs">
                <button type="button" 
                        x-show="searchQuery" 
                        @click="searchQuery = ''" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-red-500 transition-colors"
                        style="display: none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Background Decoration Icon --}}
        <div class="absolute -right-6 -bottom-8 opacity-10 pointer-events-none hidden lg:block text-primary-900">
            <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
        </div>
    </div>

    {{-- 2. Main Two-Column Docs Layout: Sticky Sidebar Navigation & Content Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- Left Column: Sticky Table of Contents (TOC) --}}
        <aside class="lg:col-span-3 sticky top-20 z-20 space-y-3">
            <div class="glass-card p-4 rounded-xl border border-gray-200/80 bg-white/95 backdrop-blur-md shadow-xs">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-400 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    <span>Daftar Isi Panduan</span>
                </h3>
                
                <nav class="space-y-1 text-xs font-semibold">
                    <button type="button" @click="scrollTo('pengantar')" 
                            :class="activeSection === 'pengantar' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>🌟</span>
                        <span class="truncate">1. Pengenalan & Alur Bisnis</span>
                    </button>
                    <button type="button" @click="scrollTo('kasir-pos')" 
                            :class="activeSection === 'kasir-pos' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>🏪</span>
                        <span class="truncate">2. Kasir & POS Penjualan</span>
                    </button>
                    <button type="button" @click="scrollTo('stok-inventori')" 
                            :class="activeSection === 'stok-inventori' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>📦</span>
                        <span class="truncate">3. Stok & Manajemen Inventori</span>
                    </button>
                    <button type="button" @click="scrollTo('pengadaan-pembelian')" 
                            :class="activeSection === 'pengadaan-pembelian' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>📥</span>
                        <span class="truncate">4. Pengadaan & Pembelian</span>
                    </button>
                    <button type="button" @click="scrollTo('keuangan-laporan')" 
                            :class="activeSection === 'keuangan-laporan' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>📊</span>
                        <span class="truncate">5. Keuangan & Akuntansi</span>
                    </button>
                    <button type="button" @click="scrollTo('sdm-payroll')" 
                            :class="activeSection === 'sdm-payroll' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>👥</span>
                        <span class="truncate">6. SDM & Penggajian (Payroll)</span>
                    </button>
                    <button type="button" @click="scrollTo('pengaturan-bisnis')" 
                            :class="activeSection === 'pengaturan-bisnis' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>⚙️</span>
                        <span class="truncate">7. Pengaturan & Multi-Tenant</span>
                    </button>
                    <button type="button" @click="scrollTo('super-admin')" 
                            :class="activeSection === 'super-admin' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>👑</span>
                        <span class="truncate">8. Super Admin Platform</span>
                    </button>
                    <button type="button" @click="scrollTo('faq-troubleshooting')" 
                            :class="activeSection === 'faq-troubleshooting' ? 'bg-primary-50 text-primary-700 font-bold border-l-2 border-primary-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 border-l-2 border-transparent'"
                            class="w-full text-left px-2.5 py-1.5 rounded-r-lg transition flex items-center gap-2">
                        <span>❓</span>
                        <span class="truncate">9. FAQ & Kendala Umum</span>
                    </button>
                </nav>

                {{-- Quick Print Handbook Button --}}
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <button type="button" onclick="window.print()" class="w-full py-2 px-3 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold transition flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak Buku SOP</span>
                    </button>
                </div>
            </div>
        </aside>

        {{-- Right Column: Documentation Content Cards --}}
        <div class="lg:col-span-9 space-y-8 text-dark">
            
            {{-- ================= SECTION 1: PENGENALAN & ALUR BISNIS ================= --}}
            <section id="pengantar" x-show="matches('pengantar konsep alur bisnis multi-tenant karsaerp role')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-bold">
                        🌟
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">1. Pengenalan & Konsep Dasar KarsaERP</h2>
                        <p class="text-xs text-gray-500">Arsitektur Multi-Tenant SaaS, konsep toko, dan pembagian hak akses pengguna.</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs leading-relaxed text-gray-700">
                    <p>
                        <strong>KarsaERP</strong> adalah platform Enterprise Resource Planning (ERP) & Point of Sale (POS) berbasis cloud yang dirancang khusus untuk perdagangan ritel, toko pertanian, grosir sembako, dan UMKM multi-cabang di Indonesia.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="p-3.5 rounded-xl border border-gray-150 bg-gray-50/70">
                            <p class="font-bold text-dark text-xs mb-1">🏢 Multi-Unit Usaha (Multi-Tenant)</p>
                            <p class="text-[11px] text-gray-500">Satu akun pengguna dapat mengelola beberapa toko sekaligus secara terisolasi aman tanpa saling mencampur stok, keuangan, atau data pelanggan.</p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-gray-150 bg-gray-50/70">
                            <p class="font-bold text-dark text-xs mb-1">⚡ Akuntansi Otomatis Real-time</p>
                            <p class="text-[11px] text-gray-500">Setiap nota kasir dan nota beli otomatis memutasi stok fisik, membentuk jurnal kas/bank, dan memperbarui Laporan Laba Rugi seketika.</p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-gray-150 bg-gray-50/70">
                            <p class="font-bold text-dark text-xs mb-1">🔒 Kontrol Kuota & Paket</p>
                            <p class="text-[11px] text-gray-500">Sistem kuota otomatis menjaga batas jumlah pegawai aktif dan jumlah toko sesuai paket langganan (Starter, Pro, Enterprise).</p>
                        </div>
                    </div>

                    <h4 class="font-bold text-dark text-xs pt-3">Tabel Peran & Hak Akses (Roles & Permissions):</h4>
                    <div class="overflow-x-auto border border-gray-150 rounded-xl">
                        <table class="w-full text-left text-[11px]">
                            <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-150">
                                <tr>
                                    <th class="p-2.5">Peran (Role)</th>
                                    <th class="p-2.5">Fokus Utama</th>
                                    <th class="p-2.5">Fitur yang Dapat Diakses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr>
                                    <td class="p-2.5 font-bold text-purple-700">Super Admin</td>
                                    <td class="p-2.5">Pengelola Platform Cloud</td>
                                    <td class="p-2.5">Semua tenant toko, paket langganan, kuota global, impersonasi tenant, statistik sistem.</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-bold text-primary-700">Owner / Admin</td>
                                    <td class="p-2.5">Pemilik Usaha / Toko</td>
                                    <td class="p-2.5">Akses penuh modul penjualan, stok opname, laporan laba rugi, neraca, penggajian, dan tambah kasir.</td>
                                </tr>
                                <tr>
                                    <td class="p-2.5 font-bold text-emerald-700">Kasir / Staf</td>
                                    <td class="p-2.5">Pelayan Toko & Transaksi</td>
                                    <td class="p-2.5">Mesin Kasir POS, Buka/Tutup Shift, cetak struk nota belanja, dan pencarian katalog produk.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 2: KASIR & POS PENJUALAN ================= --}}
            <section id="kasir-pos" x-show="matches('kasir pos penjualan shift struk bayar qris kembalian nota')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold">
                        🏪
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">2. Kasir POS & Manajemen Shift Kas</h2>
                        <p class="text-xs text-gray-500">SOP harian kasir: Buka shift, pencatatan transaksi penjualan, metode pembayaran, hingga tutup shift.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    {{-- Langkah 1 --}}
                    <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/30 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-emerald-800">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs">1</span>
                            <span>Buka Shift Kasir (Wajib Sebelum Transaksi)</span>
                        </div>
                        <p class="text-gray-600 pl-8">
                            Sebelum melayani pembeli, kasir harus membuka shift melalui menu <strong>Shift Kasir</strong> (`/cash-registers`).
                            Masukkan nominal <strong>Modal Awal / Kas Kecil</strong> di laci kasir (misal: Rp 200.000 untuk uang kembalian pecahan kecil). Klik <em>Buka Shift Sekarang</em>.
                        </p>
                        <div class="pl-8 text-[11px] text-amber-700 font-semibold">
                            ⚠️ Penting: Tombol kasir POS akan menampilkan status <em>"Shift Aktif"</em> dengan lampu hijau berkedip.
                        </div>
                    </div>

                    {{-- Langkah 2 --}}
                    <div class="p-4 rounded-xl border border-gray-150 bg-gray-50/50 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-dark">
                            <span class="w-6 h-6 rounded-full bg-primary-600 text-white flex items-center justify-center text-xs">2</span>
                            <span>Melakukan Transaksi Penjualan (`/sales/create`)</span>
                        </div>
                        <ul class="list-disc pl-12 space-y-1 text-gray-600">
                            <li><strong>Pilih Produk</strong>: Klik kartu produk atau cari melalui kolom pencarian di bagian atas. Produk langsung masuk ke keranjang belanja.</li>
                            <li><strong>Atur Jumlah (Qty) & Diskon</strong>: Klik keranjang belanja di pojok kanan bawah untuk menambah kuantiti atau memberikan potongan harga per item.</li>
                            <li><strong>Pilih Pelanggan</strong>: Secara default terpilih <em>Pelanggan Umum (Cash)</em>. Jika pembeli adalah petani/pelanggan langganan dengan fasilitas tempo/hutang, pilih nama pelanggan yang terdaftar.</li>
                            <li><strong>Tanggal Transaksi</strong>: Dapat disesuaikan bila kasir sedang merekap nota penjualan offline terdahulu.</li>
                        </ul>
                    </div>

                    {{-- Langkah 3 --}}
                    <div class="p-4 rounded-xl border border-gray-150 bg-gray-50/50 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-dark">
                            <span class="w-6 h-6 rounded-full bg-primary-600 text-white flex items-center justify-center text-xs">3</span>
                            <span>Pembayaran & Kembalian</span>
                        </div>
                        <p class="text-gray-600 pl-8">
                            Sistem mendukung beragam metode transaksi:
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 pl-8 pt-1">
                            <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                                <span class="font-bold text-emerald-700">💵 Tunai (Cash)</span>
                                <p class="text-[10px] text-gray-500 mt-0.5">Ketik jumlah uang yang diserahkan pelanggan. Sistem menghitung uang kembalian secara otomatis.</p>
                            </div>
                            <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                                <span class="font-bold text-blue-700">📱 QRIS / Transfer</span>
                                <p class="text-[10px] text-gray-500 mt-0.5">Uang langsung tercatat masuk ke akun Bank/QRIS pada modul arus kas dan buku bank.</p>
                            </div>
                            <div class="p-2.5 bg-white border border-gray-200 rounded-lg">
                                <span class="font-bold text-amber-700">⏳ Tempo / Piutang</span>
                                <p class="text-[10px] text-gray-500 mt-0.5">Khusus pelanggan terdaftar. Nominal transaksi otomatis menambah saldo buku piutang pelanggan.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Langkah 4 --}}
                    <div class="p-4 rounded-xl border border-gray-150 bg-gray-50/50 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-dark">
                            <span class="w-6 h-6 rounded-full bg-primary-600 text-white flex items-center justify-center text-xs">4</span>
                            <span>Cetak Struk Belanja & Nota Thermal</span>
                        </div>
                        <p class="text-gray-600 pl-8">
                            Setelah transaksi disimpan, struk belanja langsung siap dicetak. Format struk telah dioptimasi untuk printer thermal ukuran <strong>58mm dan 80mm</strong>, memuat identitas toko, nomor invoice, daftar barang, nominal bayar, dan ucapan terima kasih.
                        </p>
                    </div>

                    {{-- Langkah 5 --}}
                    <div class="p-4 rounded-xl border border-red-150 bg-red-50/30 space-y-2">
                        <div class="flex items-center gap-2 font-bold text-red-800">
                            <span class="w-6 h-6 rounded-full bg-red-600 text-white flex items-center justify-center text-xs">5</span>
                            <span>Tutup Shift & Rekonsiliasi Kas Akhir Hari</span>
                        </div>
                        <p class="text-gray-600 pl-8">
                            Di akhir jam kerja kasir, klik tombol <strong>Shift Kasir</strong> lalu pilih <em>Tutup Shift</em>. Hitung seluruh uang fisik di laci kasir dan masukkan ke form <strong>Kas Aktual</strong>. Sistem akan membandingkan dengan Kas Harapan (*Expected Cash*). Bila ada selisih, sistem mencatat keterangan selisih kas untuk diaudit oleh pemilik toko.
                        </p>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 3: STOK & INVENTORI ================= --}}
            <section id="stok-inventori" x-show="matches('stok inventori produk barang opname mutasi kategori satuan unit')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-bold">
                        📦
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">3. Stok & Manajemen Inventori</h2>
                        <p class="text-xs text-gray-500">Pengelolaan katalog produk, konversi multi-satuan, riwayat kartu stok, dan stok opname fisik.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">A. Menambah Produk Baru & Konversi Satuan:</h3>
                        <p class="text-gray-600">
                            KarsaERP mendukung rasio satuan beli vs satuan jual (misal: Anda membeli pupuk dalam satuan <strong>Sak (50 Kg)</strong> tetapi dapat menjualnya eceran per <strong>Kg</strong> atau per <strong>Sak</strong>).
                        </p>
                        <div class="mt-2 p-3 bg-blue-50/40 rounded-xl border border-blue-150 text-[11px] text-blue-900">
                            <strong>💡 Contoh Konfigurasi Konversi Satuan:</strong><br>
                            Satuan Beli: <em>Sak</em> | Satuan Jual: <em>Kg</em> | Faktor Konversi: <em>50</em>.<br>
                            Bila Anda membeli 2 Sak, stok fisik bertambah 100 Kg secara otomatis.
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">B. Kartu Mutasi Stok (`/reports/stock_movements`):</h3>
                        <p class="text-gray-600">
                            Setiap perubahan stok tercatat rinci: tanggal, jenis mutasi (Penjualan, Pembelian, Opname, Penyesuaian Mandiri), jumlah keluar/masuk, dan sisa saldo stok terakhir.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">C. Stok Opname Fisik & Penyesuaian (`/stock-adjustments`):</h3>
                        <p class="text-gray-600">
                            Digunakan untuk audit fisik stok di gudang / etalase toko. Jenis penyesuaian yang didukung:
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center">
                            <div class="p-2 border border-gray-200 rounded-lg bg-gray-50">
                                <span class="font-bold text-dark">Stok Opname</span>
                                <p class="text-[10px] text-gray-500">Hitung fisik rutin bulanan</p>
                            </div>
                            <div class="p-2 border border-gray-200 rounded-lg bg-gray-50">
                                <span class="font-bold text-dark">Barang Rusak</span>
                                <p class="text-[10px] text-gray-500">Kemasan bocor/cacat</p>
                            </div>
                            <div class="p-2 border border-gray-200 rounded-lg bg-gray-50">
                                <span class="font-bold text-dark">Expired</span>
                                <p class="text-[10px] text-gray-500">Kadaluwarsa produk</p>
                            </div>
                            <div class="p-2 border border-gray-200 rounded-lg bg-gray-50">
                                <span class="font-bold text-dark">Internal Toko</span>
                                <p class="text-[10px] text-gray-500">Pemakaian sendiri</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 4: PENGADAAN & PEMBELIAN ================= --}}
            <section id="pengadaan-pembelian" x-show="matches('pengadaan pembelian tengkulak supplier hutang faktur modal')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center text-xl font-bold">
                        📥
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">4. Pengadaan & Pembelian (Procurement)</h2>
                        <p class="text-xs text-gray-500">Mencatat kulakan barang dari tengkulak, histori fluktuasi harga modal, dan manajemen hutang usaha.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">A. Mencatat Pembelian Barang (`/purchases/create`):</h3>
                        <ol class="list-decimal pl-6 space-y-1 text-gray-600">
                            <li>Pilih nama <strong>Tengkulak / Supplier</strong> dari daftar.</li>
                            <li>Ketik nomor nota / faktur fisik dari supplier.</li>
                            <li>Pilih barang-barang yang dibeli, masukkan kuantiti beli dan harga modal per unit.</li>
                            <li>Tentukan status pembayaran:
                                <ul class="list-disc pl-6 pt-0.5">
                                    <li><strong>Lunas</strong>: Kas toko langsung terpotong senilai total pembelian.</li>
                                    <li><strong>Hutang / Tempo</strong>: Masuk ke Buku Hutang Usaha dengan tanggal jatuh tempo.</li>
                                    <li><strong>Sebagian (DP)</strong>: Masukkan jumlah uang muka yang dibayarkan.</li>
                                </ul>
                            </li>
                        </ol>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">B. Histori Harga Modal (*Purchase Price History*):</h3>
                        <p class="text-gray-600">
                            Setiap kali Anda membeli barang dengan harga modal yang berbeda, KarsaERP otomatis menyimpan riwayat harga tersebut sehingga Anda dapat memantau grafik tren kenaikan harga komoditas dari waktu ke waktu.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">C. Pembayaran Hutang ke Supplier (`/payments/suppliers`):</h3>
                        <p class="text-gray-600">
                            Menu ini memuat seluruh tagihan jatuh tempo ke supplier. Anda dapat mencatat pelunasan bertahap maupun langsung lunas, dengan saldo hutang yang diperbarui seketika.
                        </p>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 5: KEUANGAN & AKUNTANSI ================= --}}
            <section id="keuangan-laporan" x-show="matches('keuangan laporan laba rugi arus kas neraca buku kas bank akuntansi')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl font-bold">
                        📊
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">5. Laporan Keuangan & Akuntansi Real-Time</h2>
                        <p class="text-xs text-gray-500">Laporan keuangan standar akuntansi bisnis untuk evaluasi performa toko dan perpajakan.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-1">
                            <span class="font-bold text-dark text-xs flex items-center gap-1.5">
                                <span>📈</span>
                                <span>Laporan Laba Rugi (`/financial-reports/profit-loss`)</span>
                            </span>
                            <p class="text-gray-600 text-[11px]">
                                Menghitung <strong>Penjualan Bersih - Harga Pokok Penjualan (HPP) = Laba Kotor</strong>, kemudian dikurangi <strong>Beban Operasional</strong> (gaji staf, biaya listrik, sewa) untuk menghasilkan <strong>Laba Bersih Usaha</strong>.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-1">
                            <span class="font-bold text-dark text-xs flex items-center gap-1.5">
                                <span>🌊</span>
                                <span>Laporan Arus Kas (`/financial-reports/cash-flow`)</span>
                            </span>
                            <p class="text-gray-600 text-[11px]">
                                Memantau perputaran uang tunai riil: arus kas operasional dari penerimaan tunai, pengeluaran kas belanja barang, dan pelunasan piutang/hutang.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-1">
                            <span class="font-bold text-dark text-xs flex items-center gap-1.5">
                                <span>⚖️</span>
                                <span>Neraca Keuangan (`/financial-reports/balance-sheet`)</span>
                            </span>
                            <p class="text-gray-600 text-[11px]">
                                Memperlihatkan kesehatan bisnis toko: <strong>Total Aset</strong> (Kas di laci, Saldo Bank, Piutang Petani, Nilai Fisik Stok Barang) wajib seimbang dengan <strong>Kewajiban & Ekuitas Toko</strong>.
                            </p>
                        </div>
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50/50 space-y-1">
                            <span class="font-bold text-dark text-xs flex items-center gap-1.5">
                                <span>🏦</span>
                                <span>Buku Kas & Bank (`/financial-reports/general-ledger`)</span>
                            </span>
                            <p class="text-gray-600 text-[11px]">
                                Buku catatan mutasi debit dan kredit seluruh akun keuangan dengan saldo berjalan (*running balance*).
                            </p>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl border border-primary-150 bg-primary-50/30 text-primary-900 text-[11px]">
                        <strong>💡 Kas Masuk / Keluar Cepat (`/cash-transactions`):</strong><br>
                        Gunakan menu ini untuk mencatat transaksi operasional harian yang tidak melalui kasir penjualan (misal: bayar tagihan listrik Rp 150.000, beli bensin motor toko Rp 30.000, atau setoran modal tambahan).
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 6: SDM & PAYROLL ================= --}}
            <section id="sdm-payroll" x-show="matches('sdm payroll gaji slip absensi presensi bonus komisi karyawan pegawai')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center text-xl font-bold">
                        👥
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">6. SDM & Penggajian (Payroll)</h2>
                        <p class="text-xs text-gray-500">Manajemen data staf, presensi kehadiran, komisi target penjualan, dan penerbitan slip gaji.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <div class="space-y-2">
                        <h3 class="font-bold text-dark text-xs">Alur Lengkap Siklus Penggajian Karyawan:</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50/60">
                                <span class="font-bold text-primary-700">1. Data Pegawai</span>
                                <p class="text-[10px] text-gray-500 mt-1">Daftarkan nama, NIK, jabatan, serta nominal <em>Gaji Pokok</em> & <em>Tunjangan</em> tetap bulanan.</p>
                            </div>
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50/60">
                                <span class="font-bold text-primary-700">2. Rekap Presensi</span>
                                <p class="text-[10px] text-gray-500 mt-1">Catat jumlah hadir, sakit, izin, atau alfa pada menu <em>Presensi Bulanan</em> (`/employee-attendances`).</p>
                            </div>
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50/60">
                                <span class="font-bold text-primary-700">3. Bonus & Komisi</span>
                                <p class="text-[10px] text-gray-500 mt-1">Input bonus lembur atau komisi penjualan kasir pada menu <em>Bonus & Komisi</em> (`/employee-bonuses`).</p>
                            </div>
                            <div class="p-3 rounded-lg border border-gray-200 bg-gray-50/60">
                                <span class="font-bold text-primary-700">4. Cetak Slip Gaji</span>
                                <p class="text-[10px] text-gray-500 mt-1">Generate payroll bulanan (`/payrolls`). Sistem menghitung total bersih dan mencetak Slip Gaji resmi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 text-[11px]">
                        <strong>Rumus Kalkulasi Slip Gaji:</strong><br>
                        <code>Gaji Diterima (Take Home Pay) = Gaji Pokok + Tunjangan Tetap + Bonus/Komisi - Potongan Absensi/Kasbon</code>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 7: PENGATURAN & MULTI-TENANT ================= --}}
            <section id="pengaturan-bisnis" x-show="matches('pengaturan bisnis unit usaha toko kasir staf switcher galeri tenant')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center text-xl font-bold">
                        ⚙️
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">7. Pengaturan Bisnis, Staf & Multi-Tenant</h2>
                        <p class="text-xs text-gray-500">Pengelolaan toko cabang, akun kasir, kustomisasi nota struk, dan galeri media produk.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">A. Beralih Unit Usaha (*Active Store Switcher*):</h3>
                        <p class="text-gray-600">
                            Jika Anda memiliki lebih dari 1 cabang/toko, Anda dapat beralih toko kapan saja melalui <strong>Dropdown Toko</strong> di header atas sebelah kanan. Begitu toko berganti, seluruh data stok, transaksi kasir, dan laporan langsung tersaring untuk cabang tersebut secara otomatis.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">B. Menambah Akun Kasir / Staf Toko (`/users`):</h3>
                        <p class="text-gray-600">
                            Pemilik usaha dapat membuatkan akun login khusus untuk kasir toko. Pilih role <em>Kasir</em> agar staf hanya dapat mengakses fitur mesin POS dan riwayat penjualan harian tanpa bisa mengedit laporan laba rugi atau mengganti pengaturan bisnis.
                        </p>
                    </div>

                    <div>
                        <h3 class="font-bold text-dark text-xs mb-1">C. Pengaturan Profil Struk Belanja (`/settings`):</h3>
                        <p class="text-gray-600">
                            Atur Nama Perusahaan/Toko, No. Telepon, Alamat Lengkap, dan Pesan Footer Nota (contoh: <em>"Barang yang sudah dibeli tidak dapat ditukar"</em>).
                        </p>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 8: SUPER ADMIN ================= --}}
            <section id="super-admin" x-show="matches('super admin platform tenant paket plan kuota impersonate')" class="glass-card p-6 rounded-2xl border border-purple-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-purple-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl font-bold">
                        👑
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">8. Panduan Super Administrator Platform</h2>
                        <p class="text-xs text-gray-500">Khusus pengelola platform hosting SaaS KarsaERP.</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs leading-relaxed text-gray-700">
                    <p class="text-gray-600">
                        Menu Super Admin (`/admin/dashboard`) hanya muncul jika Anda login menggunakan akun dengan privilege <code>is_super_admin = true</code>.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="p-3.5 rounded-xl border border-purple-100 bg-purple-50/40">
                            <span class="font-bold text-purple-800 text-xs">📊 Dashboard Global</span>
                            <p class="text-[11px] text-gray-600 mt-1">Statistik total tenant aktif, total perputaran transaksi kasir di seluruh server, dan jumlah user terdaftar.</p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-purple-100 bg-purple-50/40">
                            <span class="font-bold text-purple-800 text-xs">🏢 Manajemen Tenant & Impersonasi</span>
                            <p class="text-[11px] text-gray-600 mt-1">Super admin dapat login sementara atas nama tenant (*impersonate*) untuk memberikan bantuan teknis tanpa perlu meminta kata sandi pengguna.</p>
                        </div>
                        <div class="p-3.5 rounded-xl border border-purple-100 bg-purple-50/40">
                            <span class="font-bold text-purple-800 text-xs">💎 Pengaturan Kuota & Paket</span>
                            <p class="text-[11px] text-gray-600 mt-1">Mengatur batas kuota maksimal cabang toko dan kuota staf per tier paket langganan (Starter, Pro, Enterprise).</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ================= SECTION 9: FAQ & TROUBLESHOOTING ================= --}}
            <section id="faq-troubleshooting" x-show="matches('faq kendala troubleshooting tanya jawab printer struk selisih kas kuota')" class="glass-card p-6 rounded-2xl border border-gray-200/80 bg-white space-y-4 scroll-mt-24 shadow-xs">
                <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center text-xl font-bold">
                        ❓
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-dark">9. Tanya Jawab (FAQ) & Solusi Kendala</h2>
                        <p class="text-xs text-gray-500">Jawaban cepat atas pertanyaan operasional yang sering dialami pengguna.</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs leading-relaxed" x-data="{ openFaq: null }">
                    {{-- Q1 --}}
                    <div class="border border-gray-150 rounded-xl overflow-hidden">
                        <button type="button" @click="openFaq = openFaq === 1 ? null : 1" class="w-full text-left p-3.5 font-bold text-dark hover:bg-gray-50 flex items-center justify-between transition">
                            <span>Bagaimana cara mengatasi selisih kas saat tutup shift?</span>
                            <span x-text="openFaq === 1 ? '−' : '+'" class="text-sm font-bold text-primary-600"></span>
                        </button>
                        <div x-show="openFaq === 1" class="p-3.5 pt-0 text-gray-600 text-[11px] border-t border-gray-100 bg-gray-50/40" style="display: none;">
                            Periksa kembali apakah ada pengeluaran kas kecil yang belum dicatat di <em>Kas Masuk/Keluar</em>, atau apakah ada transaksi tunai yang salah diinput sebagai transfer bank. Masukkan jumlah uang fisik yang sebenarnya ada di laci kasir, lalu cantumkan catatan penjelasan pada form tutup shift.
                        </div>
                    </div>

                    {{-- Q2 --}}
                    <div class="border border-gray-150 rounded-xl overflow-hidden">
                        <button type="button" @click="openFaq = openFaq === 2 ? null : 2" class="w-full text-left p-3.5 font-bold text-dark hover:bg-gray-50 flex items-center justify-between transition">
                            <span>Bagaimana jika kuota karyawan atau kuota toko saya penuh?</span>
                            <span x-text="openFaq === 2 ? '−' : '+'" class="text-sm font-bold text-primary-600"></span>
                        </button>
                        <div x-show="openFaq === 2" class="p-3.5 pt-0 text-gray-600 text-[11px] border-t border-gray-100 bg-gray-50/40" style="display: none;">
                            Bila Anda menerima notifikasi peringatan kuota penuh saat menambah karyawan atau membuat unit usaha baru, silakan upgrade paket langganan Anda atau hubungi Super Administrator Platform untuk menambah alokasi kuota akun Anda.
                        </div>
                    </div>

                    {{-- Q3 --}}
                    <div class="border border-gray-150 rounded-xl overflow-hidden">
                        <button type="button" @click="openFaq = openFaq === 3 ? null : 3" class="w-full text-left p-3.5 font-bold text-dark hover:bg-gray-50 flex items-center justify-between transition">
                            <span>Bagaimana cara mencetak struk kasir ke printer Bluetooth/Thermal tanpa header/footer browser?</span>
                            <span x-text="openFaq === 3 ? '−' : '+'" class="text-sm font-bold text-primary-600"></span>
                        </button>
                        <div x-show="openFaq === 3" class="p-3.5 pt-0 text-gray-600 text-[11px] border-t border-gray-100 bg-gray-50/40" style="display: none;">
                            Saat dialog print browser terbuka (Ctrl+P): pada opsi <em>More settings</em>, hilangkan centang (uncheck) opsi <strong>"Headers and footers"</strong> dan atur margin menjadi <strong>"None"</strong> atau <strong>"Minimum"</strong>.
                        </div>
                    </div>

                    {{-- Q4 --}}
                    <div class="border border-gray-150 rounded-xl overflow-hidden">
                        <button type="button" @click="openFaq = openFaq === 4 ? null : 4" class="w-full text-left p-3.5 font-bold text-dark hover:bg-gray-50 flex items-center justify-between transition">
                            <span>Apakah data antar toko cabang bisa saling melihat atau tercampur?</span>
                            <span x-text="openFaq === 4 ? '−' : '+'" class="text-sm font-bold text-primary-600"></span>
                        </button>
                        <div x-show="openFaq === 4" class="p-3.5 pt-0 text-gray-600 text-[11px] border-t border-gray-100 bg-gray-50/40" style="display: none;">
                            Tidak. Setiap transaksi, inventori, dan catatan keuangan di KarsaERP menerapkan <em>Tenant Global Scoping</em> yang ketat. Data antar unit usaha terisolasi 100% secara aman berdasarkan ID bisnis yang aktif.
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
