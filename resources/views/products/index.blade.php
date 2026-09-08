<x-app-layout>
    <x-slot name="header">
        <x-page-header 
            title="Katalog Produk"
            subtitle="Daftar inventori fisik barang dagangan dan harga jual"
            :searchAction="route('products.index')"
            searchPlaceholder="Cari nama produk..."
            :createRoute="route('products.create')"
            createLabel="Tambah Produk"
        />
    </x-slot>

    <div class="pb-28 space-y-3" x-data="productList()" @scroll.window="checkScroll()">

        <x-inventory-subnav />

        {{-- Sticky Category Filter Chips (fixed under header when scrolled) --}}
        @if(isset($categories) && $categories->count() > 0)
        <div class="sticky top-[60px] z-20 -mx-3 px-3 py-2 transition-all duration-200 overflow-x-auto scrollbar-hide"
             :class="isStickyCategory ? 'bg-white/90 backdrop-blur-md border-b border-gray-150 shadow-sm' : 'bg-transparent border-transparent'">
            <div class="flex gap-1.5 whitespace-nowrap">
                <a href="{{ route('products.index', ['search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg text-[11px] font-semibold transition-all inline-block flex-shrink-0"
                    :class="!activeCategory ? 'bg-primary-600 text-white shadow-xs' : (isStickyCategory ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs')">
                    Semua
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->id, 'search' => request('search')]) }}"
                    class="px-3 py-1.5 rounded-lg text-[11px] font-semibold transition-all inline-block flex-shrink-0"
                    :class="activeCategory == '{{ $cat->id }}' ? 'bg-primary-600 text-white shadow-xs' : (isStickyCategory ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-white/90 border border-gray-200/70 text-gray-600 shadow-xs')">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Active Search Indicator --}}
        <div x-show="activeSearch" x-transition class="glass-card px-4 py-2.5 flex items-center justify-between text-xs font-semibold" style="display: none;">
            <div class="flex items-center gap-1 text-gray-500">
                <span>Hasil pencarian:</span>
                <span class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded text-[10px]" x-text="'&quot;' + activeSearch + '&quot;'"></span>
            </div>
            <a :href="clearSearchUrl" class="text-[11px] font-bold text-red-500 hover:text-red-700 transition-colors flex-shrink-0">Reset</a>
        </div>

        {{-- Product Grid (Infinite Scroll) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
            <template x-for="product in items" :key="product.id">
                <a :href="product.show_url" class="card-solid overflow-hidden block active:scale-[0.98] transition-transform bg-white rounded-lg">
                    <div class="aspect-square bg-gray-100 relative">
                        <template x-if="product.image">
                            <img :src="'/storage/' + product.image" class="w-full h-full object-cover" :alt="product.name" loading="lazy">
                        </template>
                        <template x-if="!product.image">
                            <div class="w-full h-full flex items-center justify-center">
                                <!-- Duotone Icon: Package -->
                                <svg class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3" d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor"/>
                                    <path d="M2 17L12 22L22 17M2 12L17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </template>

                        {{-- Category Overlay Badge --}}
                        <span class="absolute top-2 right-2 bg-black/60 backdrop-blur-sm text-white text-[9px] px-1.5 py-0.5 rounded font-semibold tracking-wide"
                              x-text="product.category_name"></span>

                        {{-- Stock Badge --}}
                        <span :class="product.stock <= product.min_stock ? 'bg-red-500 text-white' : 'bg-primary-100 text-primary-700'"
                              class="absolute bottom-2 left-2 text-[10px] px-1.5 py-0.5 rounded font-semibold"
                              x-text="'Stok: ' + formatNumber(product.stock) + ' ' + product.sell_unit_symbol"></span>

                        <template x-if="!product.is_active">
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center">
                                <span class="text-xs text-white font-medium bg-black/60 px-2 py-1 rounded">Nonaktif</span>
                            </div>
                        </template>
                    </div>
                    <div class="p-3 space-y-1">
                        <h4 class="text-xs font-bold text-dark line-clamp-2 leading-tight h-8" x-text="product.name"></h4>
                        <p class="text-sm font-extrabold text-primary-600" x-text="formatRupiah(product.selling_price)"></p>
                    </div>
                </a>
            </template>

            {{-- Empty State (only when no items after initial load) --}}
            <template x-if="items.length === 0 && !loading">
                <div class="col-span-full py-12 text-center">
                    <!-- Duotone Icon: Package empty -->
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.3" d="M12 2L2 7L12 12L22 7L12 2Z" fill="currentColor"/>
                        <path d="M2 17L12 22L22 17M2 12L12 17L22 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <p class="text-sm text-gray-400">Belum ada produk</p>
                    <a href="{{ route('products.create') }}" class="inline-block mt-3 px-4 py-2 bg-primary-600 text-white text-sm rounded-lg">+ Tambah Produk</a>
                </div>
            </template>
        </div>

        {{-- Loading spinner --}}
        <div x-show="loading" class="flex justify-center py-6" style="display: none;">
            <svg class="w-6 h-6 animate-spin text-primary-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>

    </div>

    @push('scripts')
    <script>
        function productList() {
            const initialItems = {!! json_encode($products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'selling_price' => (float) $p->selling_price,
                'stock' => (float) $p->stock,
                'min_stock' => (float) ($p->min_stock ?? 0),
                'is_active' => (bool) $p->is_active,
                'image' => $p->image,
                'category_name' => $p->category->name ?? 'Tanpa Kategori',
                'sell_unit_symbol' => $p->sellUnit->symbol ?? '',
                'show_url' => route('products.show', $p),
            ])) !!};
            const nextPageUrl = {!! json_encode($products->nextPageUrl()) !!};

            return {
                items: initialItems,
                nextPageUrl: nextPageUrl,
                loading: false,
                openFloatingSearch: false,
                searchQuery: '{{ request('search', '') }}',
                activeSearch: '{{ request('search', '') }}',
                activeCategory: '{{ request('category', '') }}',
                isStickyCategory: false,

                get clearSearchUrl() {
                    let params = new URLSearchParams(window.location.search);
                    params.delete('search');
                    const qs = params.toString();
                    return '{{ route('products.index') }}' + (qs ? '?' + qs : '');
                },

                checkScroll() {
                    this.isStickyCategory = window.scrollY > 20;

                    if (this.loading || !this.nextPageUrl) return;

                    const threshold = 300;
                    const scrollPosition = window.innerHeight + window.scrollY;
                    const documentHeight = document.documentElement.scrollHeight;

                    if (documentHeight - scrollPosition < threshold) {
                        this.loadMore();
                    }
                },

                loadMore() {
                    if (!this.nextPageUrl || this.loading) return;
                    this.loading = true;

                    fetch(this.nextPageUrl, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.items.push(...data.data);
                        this.nextPageUrl = data.next_page_url;
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error('Load more error:', err);
                        this.loading = false;
                    });
                },

                submitSearch() {
                    let params = new URLSearchParams();
                    if (this.searchQuery) params.set('search', this.searchQuery);
                    if (this.activeCategory) params.set('category', this.activeCategory);
                    const qs = params.toString();
                    window.location.href = '{{ route('products.index') }}' + (qs ? '?' + qs : '');
                },

                cancelSearch() {
                    this.openFloatingSearch = false;
                    this.searchQuery = this.activeSearch;
                },

                formatRupiah(n) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
                },

                formatNumber(n) {
                    return new Intl.NumberFormat('id-ID').format(n);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
