@extends('layouts.app')

@section('title', 'Katalog Produk — Allbirds Store')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12" x-data="catalogFilter()">

    {{-- Top Header & Live Search Bar --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 pb-6 border-b border-earth-100 gap-6">
        <div>
            <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Koleksi Ramah Lingkungan</p>
            <h1 class="text-3xl lg:text-4xl font-light text-gray-900">
                Semua Produk
                <span class="text-sm text-earth-500 font-normal ml-2" x-text="'(' + totalCount + ' model)'"></span>
            </h1>
        </div>

        {{-- Live Search Input Box --}}
        <div class="w-full md:w-80 relative">
            <div class="relative">
                <input type="text"
                       x-model="search"
                       @input.debounce.300ms="applyFilters()"
                       placeholder="Cari sepatu, wool, tree runner..."
                       class="w-full bg-white border border-earth-200 rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-gray-900 transition-all shadow-sm">
                <div class="absolute left-3.5 top-3 text-gray-400">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <button x-show="search.length > 0"
                        @click="search = ''; applyFilters()"
                        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-700 p-0.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Multi-Facet Interactive Filter Bar --}}
    <div class="bg-white rounded-2xl border border-earth-100 p-6 mb-10 shadow-sm space-y-5">
        
        {{-- Row 1: Categories --}}
        <div class="flex flex-wrap items-center gap-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 w-24 flex-shrink-0">Kategori:</span>
            <button @click="setCategory('')"
                    :class="selectedCategory === '' ? 'bg-gray-900 text-white border-gray-900' : 'bg-earth-50 text-gray-600 border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">
                Semua
            </button>
            @foreach($categories as $cat)
            <button @click="setCategory('{{ $cat->slug }}')"
                    :class="selectedCategory === '{{ $cat->slug }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-earth-50 text-gray-600 border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        {{-- Row 2: Material Type --}}
        <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-earth-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 w-24 flex-shrink-0">Material:</span>
            <button @click="setMaterial('')"
                    :class="selectedMaterial === '' ? 'bg-earth-800 text-white' : 'bg-white text-gray-600 border border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="px-3.5 py-1 rounded-lg text-xs font-medium transition-all">
                Semua Material
            </button>
            @foreach($materials as $mat)
            <button @click="setMaterial('{{ $mat }}')"
                    :class="selectedMaterial === '{{ $mat }}' ? 'bg-earth-800 text-white' : 'bg-white text-gray-600 border border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="px-3.5 py-1 rounded-lg text-xs font-medium transition-all">
                🌿 {{ $mat }}
            </button>
            @endforeach
        </div>

        {{-- Row 3: Sizes --}}
        <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-earth-100">
            <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 w-24 flex-shrink-0">Ukuran:</span>
            <button @click="setSize('')"
                    :class="selectedSize === '' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="h-8 px-3 rounded-md text-xs font-medium border transition-all">
                Semua
            </button>
            @foreach($sizes as $sz)
            <button @click="setSize('{{ $sz }}')"
                    :class="selectedSize === '{{ $sz }}' ? 'bg-gray-900 text-white border-gray-900 scale-105' : 'bg-white text-gray-600 border-earth-200 hover:border-gray-900 hover:text-gray-900'"
                    class="h-8 min-w-[36px] px-2.5 rounded-md text-xs font-medium border transition-all flex items-center justify-center">
                {{ $sz }}
            </button>
            @endforeach

            {{-- Reset Filters Button (appears if any filter active) --}}
            <button x-show="hasActiveFilters()" @click="resetFilters()"
                    class="ml-auto text-xs text-red-500 hover:text-red-700 font-medium underline underline-offset-4 transition-colors">
                Reset Semua Filter ✕
            </button>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div x-show="loading" class="flex justify-center items-center py-20">
        <div class="inline-flex items-center gap-3 px-6 py-3 bg-white rounded-full shadow-md border border-earth-100 text-xs tracking-widest uppercase font-medium text-gray-700">
            <svg class="animate-spin h-4 w-4 text-gray-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat Produk...
        </div>
    </div>

    {{-- Empty State --}}
    <div x-show="!loading && productsList.length === 0" x-cloak class="text-center py-24 bg-white rounded-2xl border border-earth-100 p-8 my-6">
        <div class="w-20 h-20 bg-earth-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">🌿</div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada produk yang sesuai</h3>
        <p class="text-xs text-gray-400 font-light max-w-sm mx-auto mb-6">
            Coba sesuaikan kata kunci pencarian atau bersihkan filter kategori, bahan, dan ukuran.
        </p>
        <button @click="resetFilters()" class="bg-gray-900 text-white px-6 py-3 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors rounded-lg">
            Reset Filter
        </button>
    </div>

    {{-- Product Grid --}}
    <div x-show="!loading && productsList.length > 0"
         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <template x-for="product in productsList" :key="product.id">
            <div class="product-card group bg-white rounded-2xl overflow-hidden border border-earth-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                 x-data="{ currentImg: product.image_url }">
                
                <div>
                    {{-- Image & Swatch Hover Container --}}
                    <div class="relative aspect-square overflow-hidden bg-earth-50">
                        <a :href="'/products/' + product.slug" class="block w-full h-full">
                            <img :src="currentImg" :alt="product.name"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy">
                        </a>

                        {{-- Wishlist Heart Button --}}
                        <button @click.stop="toggleWishlist({
                                    id: product.id,
                                    name: product.name,
                                    slug: product.slug,
                                    category: product.category,
                                    material_type: product.material_type,
                                    price_formatted: product.price_formatted,
                                    image_url: currentImg,
                                    first_variant_id: product.colors[0]?.image_url ? product.id : null
                                })"
                                class="absolute top-3 right-3 p-2.5 rounded-full bg-white/90 hover:bg-white text-gray-400 hover:text-red-500 hover:scale-110 shadow-sm transition-all duration-200 z-10"
                                :class="isInWishlist(product.id) ? 'text-red-500 fill-current' : 'text-gray-400'"
                                title="Tambah ke Wishlist">
                            <svg class="h-4 w-4" :fill="isInWishlist(product.id) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>

                        {{-- Featured Badge --}}
                        <span x-show="product.is_featured"
                              class="absolute bottom-3 left-3 bg-earth-900/80 backdrop-blur-sm text-white text-[10px] tracking-widest uppercase font-medium px-2.5 py-1 rounded-md">
                            Best Seller
                        </span>
                    </div>

                    {{-- Product Info --}}
                    <div class="p-5">
                        <p class="text-[11px] tracking-wider uppercase text-earth-600 font-medium mb-1" x-text="product.category"></p>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">
                            <a :href="'/products/' + product.slug" class="hover:text-earth-700 transition-colors" x-text="product.name"></a>
                        </h3>
                        <p class="text-xs text-earth-500 font-light mb-3 line-clamp-1" x-text="product.material_type"></p>

                        {{-- Interactive Color Swatches --}}
                        <div class="flex items-center gap-1.5 mb-2">
                            <template x-for="c in product.colors" :key="c.name">
                                <button type="button"
                                        @mouseenter="currentImg = c.image_url"
                                        @click="currentImg = c.image_url"
                                        :style="'background-color: ' + c.hex"
                                        :title="c.name"
                                        class="color-swatch ring-offset-1 hover:ring-1 hover:ring-gray-900 transition-transform">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Price & Detail Button --}}
                <div class="px-5 pb-5 pt-2 flex items-center justify-between border-t border-earth-50">
                    <p class="text-sm font-semibold text-gray-900" x-text="product.price_formatted"></p>
                    <a :href="'/products/' + product.slug"
                       class="text-xs font-medium text-earth-700 hover:text-gray-900 tracking-wider uppercase underline underline-offset-4 transition-colors">
                        Lihat Detail →
                    </a>
                </div>

            </div>
        </template>
    </div>

</div>

@push('scripts')
<script>
function catalogFilter() {
    return {
        productsList: @json($products->items()),
        totalCount: {{ $products->total() }},
        selectedCategory: '{{ request("category", "") }}',
        selectedMaterial: '{{ request("material", "") }}',
        selectedSize: '{{ request("size", "") }}',
        search: '{{ request("search", "") }}',
        loading: false,

        init() {
            // If URL has search or filter on load, ensure synced
            if (this.selectedCategory || this.selectedMaterial || this.selectedSize || this.search) {
                this.applyFilters();
            }
        },

        setCategory(slug) {
            this.selectedCategory = (this.selectedCategory === slug) ? '' : slug;
            this.applyFilters();
        },

        setMaterial(mat) {
            this.selectedMaterial = (this.selectedMaterial === mat) ? '' : mat;
            this.applyFilters();
        },

        setSize(sz) {
            this.selectedSize = (this.selectedSize === sz) ? '' : sz;
            this.applyFilters();
        },

        hasActiveFilters() {
            return this.selectedCategory !== '' || this.selectedMaterial !== '' || this.selectedSize !== '' || this.search !== '';
        },

        resetFilters() {
            this.selectedCategory = '';
            this.selectedMaterial = '';
            this.selectedSize = '';
            this.search = '';
            this.applyFilters();
        },

        async applyFilters() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.selectedCategory) params.set('category', this.selectedCategory);
                if (this.selectedMaterial) params.set('material', this.selectedMaterial);
                if (this.selectedSize) params.set('size', this.selectedSize);
                if (this.search) params.set('search', this.search);

                const newUrl = `${window.location.pathname}?${params.toString()}`;
                window.history.replaceState({}, '', newUrl);

                const res = await fetch(`/products?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.productsList = data.products;
                this.totalCount = data.count;
            } catch (e) {
                console.error('Filter fetch error:', e);
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
@endpush
@endsection
