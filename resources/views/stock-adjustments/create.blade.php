<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('stock-adjustments.index') }}" class="p-2 rounded-xl bg-white/60 hover:bg-white text-gray-600 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-lg font-bold text-dark">Form Penyesuaian & Opname Stok</h2>
                    <p class="text-xs text-gray-500">Koreksi selisih stok fisik, barang rusak/hilang, atau stok awal</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-5 pb-24" x-data="stockAdjustmentForm()">
        <form action="{{ route('stock-adjustments.store') }}" method="POST" id="adjustmentForm">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Form Header & Info --}}
                <div class="lg:col-span-1 space-y-4">
                    <div class="glass-card p-5 space-y-4">
                        <h3 class="font-bold text-dark text-sm border-b border-gray-100 pb-2">📋 Informasi Penyesuaian</h3>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Penyesuaian <span class="text-red-500">*</span></label>
                            <input type="date" name="adjustment_date" value="{{ old('adjustment_date', date('Y-m-d')) }}" required class="form-input-glass">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe / Alasan Penyesuaian <span class="text-red-500">*</span></label>
                            <select name="type" x-model="selectedType" required class="form-input-glass">
                                <option value="opname">Stok Opname Fisik Berkala</option>
                                <option value="in_manual">Input Stok Masuk Manual (+)</option>
                                <option value="out_manual">Input Stok Keluar Manual (-)</option>
                                <option value="damaged">Barang Rusak / Cacat (-)</option>
                                <option value="expired">Barang Kadaluarsa / Basi (-)</option>
                                <option value="internal_use">Pemakaian Internal / Sampel (-)</option>
                                <option value="initial_stock">Stok Awal Migrasi (+)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan / Memo</label>
                            <textarea name="reason" rows="3" class="form-input-glass text-xs" placeholder="Contoh: Hasil audit opname gudang akhir bulan..."></textarea>
                        </div>
                    </div>

                    {{-- Summary Card --}}
                    <div class="glass-card-solid p-5 space-y-3 bg-gradient-to-br from-primary-900 to-slate-900 text-white shadow-xl">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-emerald-300">Ringkasan Dampak Finansial</h4>
                        
                        <div class="flex justify-between items-center text-xs text-gray-300">
                            <span>Total Item Produk:</span>
                            <span class="font-bold text-white text-sm" x-text="items.length + ' Item'"></span>
                        </div>

                        <div class="flex justify-between items-center text-xs text-gray-300">
                            <span>Total Selisih Unit:</span>
                            <span class="font-bold text-sm" :class="totalQuantityDiff >= 0 ? 'text-emerald-400' : 'text-rose-400'" x-text="(totalQuantityDiff > 0 ? '+' : '') + totalQuantityDiff"></span>
                        </div>

                        <div class="border-t border-white/10 pt-2 flex justify-between items-center">
                            <span class="text-xs text-gray-200 font-semibold">Estimasi Dampak Nilai (HPP):</span>
                            <span class="text-base font-extrabold" :class="totalCostImpact >= 0 ? 'text-emerald-400' : 'text-rose-400'" x-text="formatRupiah(totalCostImpact)"></span>
                        </div>

                        <p class="text-[11px] text-gray-400 leading-relaxed pt-1">
                            * Stok sistem akan otomatis diperbarui dan dicatat dalam buku pergerakan stok.
                        </p>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3.5 font-bold shadow-float flex items-center justify-center gap-2" :disabled="items.length === 0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Penyesuaian Stok
                    </button>
                </div>

                {{-- Product Selection & Table Items --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Search & Quick Add --}}
                    <div class="glass-card p-4 space-y-3">
                        <div class="flex flex-col sm:flex-row items-center gap-3">
                            <div class="relative flex-1 w-full">
                                <input type="text" x-model="searchQuery" placeholder="Cari nama produk atau SKU..." class="form-input-glass text-xs pl-9">
                                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <select x-model="selectedCategory" class="form-input-glass text-xs w-full sm:w-48">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>

                            <button type="button" @click="addAllFilteredProducts()" class="btn-secondary text-xs py-2 px-3 whitespace-nowrap">
                                + Tambah Semua
                            </button>
                        </div>

                        {{-- Quick product drop list when searching --}}
                        <div x-show="filteredProducts.length > 0 && searchQuery.length > 0" class="max-h-48 overflow-y-auto border border-gray-100 rounded-xl divide-y divide-gray-100 bg-white/95">
                            <template x-for="prod in filteredProducts" :key="prod.id">
                                <div class="p-2.5 flex items-center justify-between hover:bg-primary-50/50 cursor-pointer transition" @click="addProduct(prod)">
                                    <div>
                                        <p class="text-xs font-bold text-dark" x-text="prod.name"></p>
                                        <p class="text-[10px] text-gray-500">
                                            SKU: <span x-text="prod.sku || '-'"></span> | Stok Sistem: <span class="font-bold text-dark" x-text="prod.stock + ' ' + (prod.sell_unit?.symbol || '')"></span>
                                        </p>
                                    </div>
                                    <button type="button" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-primary-100 text-primary-700 hover:bg-primary-200">
                                        + Pilih
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Selected Items Table --}}
                    <div class="glass-card overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-dark text-sm">Daftar Produk yang Disesuaikan</h3>
                            <span class="text-xs text-gray-500" x-text="items.length + ' produk dipilih'"></span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-gray-50/70 border-b border-gray-100 text-gray-500 uppercase font-semibold text-[10px]">
                                    <tr>
                                        <th class="p-3">Produk</th>
                                        <th class="p-3 text-center">Stok Sistem</th>
                                        <th class="p-3 text-center w-28">Stok Fisik / Baru</th>
                                        <th class="p-3 text-center">Selisih</th>
                                        <th class="p-3 text-right">Dampak HPP</th>
                                        <th class="p-3">Catatan Item</th>
                                        <th class="p-3 text-center w-10">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="(item, index) in items" :key="item.product_id">
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="p-3">
                                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                                <p class="font-bold text-dark" x-text="item.name"></p>
                                                <p class="text-[10px] text-gray-400" x-text="'HPP: ' + formatRupiah(item.unit_cost) + ' / ' + item.unit"></p>
                                            </td>
                                            <td class="p-3 text-center font-semibold text-gray-600" x-text="item.system_stock + ' ' + item.unit"></td>
                                            <td class="p-3">
                                                <input type="number" step="any" min="0" :name="'items[' + index + '][actual_stock]'" x-model.number="item.actual_stock" class="form-input-glass text-center font-bold text-xs py-1" required>
                                            </td>
                                            <td class="p-3 text-center font-bold">
                                                <span :class="(item.actual_stock - item.system_stock) >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="((item.actual_stock - item.system_stock) > 0 ? '+' : '') + (item.actual_stock - item.system_stock)"></span>
                                            </td>
                                            <td class="p-3 text-right font-semibold" :class="((item.actual_stock - item.system_stock) * item.unit_cost) >= 0 ? 'text-emerald-600' : 'text-rose-600'" x-text="formatRupiah((item.actual_stock - item.system_stock) * item.unit_cost)"></td>
                                            <td class="p-3">
                                                <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes" placeholder="Ket..." class="form-input-glass text-[11px] py-1">
                                            </td>
                                            <td class="p-3 text-center">
                                                <button type="button" @click="removeItem(index)" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="items.length === 0">
                                        <td colspan="7" class="p-8 text-center text-gray-400">
                                            <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            Belum ada produk yang dipilih. Cari dan tambahkan produk di atas untuk memulai penyesuaian.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function stockAdjustmentForm() {
            return {
                allProducts: @json($products),
                selectedType: '{{ $defaultType }}',
                searchQuery: '',
                selectedCategory: '',
                items: [],

                get filteredProducts() {
                    return this.allProducts.filter(p => {
                        const matchCat = !this.selectedCategory || p.category_id == this.selectedCategory;
                        const matchSearch = !this.searchQuery || 
                            p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                            (p.sku && p.sku.toLowerCase().includes(this.searchQuery.toLowerCase()));
                        const notAdded = !this.items.some(item => item.product_id === p.id);
                        return matchCat && matchSearch && notAdded;
                    });
                },

                get totalQuantityDiff() {
                    return this.items.reduce((sum, item) => sum + (Number(item.actual_stock) - Number(item.system_stock)), 0);
                },

                get totalCostImpact() {
                    return this.items.reduce((sum, item) => {
                        const diff = Number(item.actual_stock) - Number(item.system_stock);
                        return sum + (diff * Number(item.unit_cost));
                    }, 0);
                },

                addProduct(product) {
                    const cost = Number(product.avg_purchase_price) > 0 ? Number(product.avg_purchase_price) : Number(product.last_purchase_price || 0);
                    this.items.push({
                        product_id: product.id,
                        name: product.name,
                        system_stock: Number(product.stock),
                        actual_stock: Number(product.stock), // default same
                        unit_cost: cost,
                        unit: product.sell_unit?.symbol || 'Pcs',
                        notes: ''
                    });
                    this.searchQuery = '';
                },

                addAllFilteredProducts() {
                    this.filteredProducts.forEach(p => {
                        const cost = Number(p.avg_purchase_price) > 0 ? Number(p.avg_purchase_price) : Number(p.last_purchase_price || 0);
                        this.items.push({
                            product_id: p.id,
                            name: p.name,
                            system_stock: Number(p.stock),
                            actual_stock: Number(p.stock),
                            unit_cost: cost,
                            unit: p.sell_unit?.symbol || 'Pcs',
                            notes: ''
                        });
                    });
                    this.searchQuery = '';
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                },

                formatRupiah(amount) {
                    return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
