@extends('layouts.app')

@section('title', 'Katalog Sepatu & Aksesoris Ramah Lingkungan — Anggara Footwear')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-10" 
     x-data="catalogApp()"
     x-init="initCatalog()">
    
    {{-- Header Page --}}
    <div class="mb-8">
        <p class="text-xs tracking-widest uppercase text-earth-600 mb-1 font-semibold">Koleksi Berkelanjutan</p>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-4xl font-light text-gray-900 tracking-tight">Semua Produk</h1>
            
            <div class="flex items-center gap-3">
                {{-- Quick Live Search Input --}}
                <div class="relative w-full sm:w-64">
                    <input 
                        type="text" 
                        x-model="search"
                        @input.debounce.300ms="applyFilters()"
                        placeholder="Cari model / material..."
                        class="w-full bg-white border border-earth-200 rounded-full pl-9 pr-4 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-earth-800 transition-all shadow-sm"
                    >
                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <button x-show="search" @click="search = ''; applyFilters()" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-700 text-xs">✕</button>
                </div>

                {{-- Sort Dropdown --}}
                <select x-model="sort" @change="applyFilters()" 
                        class="text-xs border border-earth-200 rounded-full px-4 py-2 bg-white text-gray-700 focus:outline-none focus:ring-1 focus:ring-earth-800 shadow-sm">
                    <option value="featured">Paling Populer</option>
                    <option value="price_asc">Harga: Rendah ke Tinggi</option>
                    <option value="price_desc">Harga: Tinggi ke Rendah</option>
                    <option value="newest">Koleksi Terbaru</option>
                </select>
            </div>
        </div>
    </div>

    {{-- REACT ISLAND: BRANCHED MENU CATEGORY FILTER --}}
    @php
        $categoryMenuData = [
            ['id' => '', 'name' => 'Semua Koleksi', 'slug' => '', 'count' => $products->count(), 'icon' => '🌟'],
            ['id' => 'men', 'name' => 'Sepatu Pria', 'slug' => 'men', 'count' => $categories->firstWhere('slug', 'men')?->products_count ?? 3, 'icon' => '👞'],
            ['id' => 'women', 'name' => 'Sepatu Wanita', 'slug' => 'women', 'count' => $categories->firstWhere('slug', 'women')?->products_count ?? 3, 'icon' => '👠'],
            ['id' => 'accessories', 'name' => 'Aksesoris Alami', 'slug' => 'accessories', 'count' => $categories->firstWhere('slug', 'accessories')?->products_count ?? 2, 'icon' => '🧦'],
        ];
    @endphp
    
    <div class="mb-8">
        <div id="category-branched-menu"
             data-categories='@json($categoryMenuData)'
             data-active-slug="{{ request('category', '') }}">
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        {{-- FILTERS SIDEBAR --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-earth-100 shadow-sm space-y-6">
                
                {{-- Reset Button --}}
                <div class="flex items-center justify-between pb-4 border-b border-earth-100">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-900">Filter Multi-Facet</span>
                    <button 
                        type="button" 
                        @click="resetAllFilters()"
                        class="text-[11px] text-earth-600 hover:text-earth-900 font-semibold underline underline-offset-2">
                        Reset Semua
                    </button>
                </div>

                {{-- Filter Material --}}
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-900 mb-3">Material Alami</h3>
                    <div class="space-y-2">
                        @foreach($materials as $material)
                        <label class="flex items-center gap-2.5 text-xs text-gray-600 hover:text-gray-900 cursor-pointer">
                            <input 
                                type="checkbox" 
                                value="{{ $material }}" 
                                x-model="selectedMaterials"
                                @change="applyFilters()"
                                class="rounded border-earth-300 text-earth-800 focus:ring-earth-800 h-3.5 w-3.5"
                            >
                            <span>{{ $material }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Filter Ukuran Sepatu --}}
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-900 mb-3">Pilih Ukuran</h3>
                    <div class="grid grid-cols-4 gap-1.5">
                        @foreach($sizes as $sz)
                        <button 
                            type="button"
                            @click="toggleSize('{{ $sz }}')"
                            :class="selectedSizes.includes('{{ $sz }}') ? 'bg-gray-900 text-white border-gray-900 shadow-sm' : 'bg-earth-50 text-gray-700 border-earth-200 hover:bg-earth-100'"
                            class="py-2 text-[11px] font-semibold rounded-lg border transition-all text-center">
                            {{ $sz }}
                        </button>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        {{-- PRODUCT GRID WITH REACT FLIP CARDS --}}
        <div class="lg:col-span-3">
            
            {{-- Loading State Indicator --}}
            <div x-show="loading" class="py-20 text-center" x-cloak>
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mb-3"></div>
                <p class="text-xs text-gray-500 font-light tracking-wider">Memuat koleksi ramah lingkungan...</p>
            </div>

            {{-- Products Container --}}
            <div x-show="!loading">
                @if($products->isEmpty())
                    <div class="text-center py-24 bg-earth-50 rounded-3xl border border-dashed border-earth-200">
                        <p class="text-4xl mb-3">👟</p>
                        <h3 class="text-base font-semibold text-gray-900 mb-1">Produk Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-500 max-w-sm mx-auto mb-4 font-light">
                            Tidak ada produk yang cocok dengan kriteria filter saat ini.
                        </p>
                        <button @click="resetAllFilters()" class="px-5 py-2.5 bg-gray-900 text-white rounded-xl text-xs uppercase tracking-wider font-semibold">
                            Reset Filter
                        </button>
                    </div>
                @else
                    {{-- Grid of React FlipCards --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            @php
                                $colors = $product->variants->groupBy('color_name')->map(function($vars, $colorName) {
                                    $first = $vars->first();
                                    return [
                                        'name' => $colorName,
                                        'hex' => $first->color_hex ?? '#cccccc',
                                        'image_url' => $first->image_url,
                                    ];
                                })->values()->toArray();

                                $productPayload = [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'slug' => $product->slug,
                                    'category' => $product->category->name ?? 'Footwear',
                                    'material_type' => $product->material_type,
                                    'description' => $product->description,
                                    'price_formatted' => $product->formatted_price,
                                    'price' => $product->price,
                                    'is_featured' => (bool)$product->is_featured,
                                    'image_url' => $product->variants->first()?->image_url ?? '',
                                    'colors' => $colors,
                                ];
                            @endphp

                            {{-- React Island Mount Node: FlipCard --}}
                            <div class="react-flip-card"
                                 data-product='@json($productPayload)'>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

<script>
function catalogApp() {
    return {
        search: '{{ request('search', '') }}',
        category: '{{ request('category', '') }}',
        selectedMaterials: @json(request('materials', [])),
        selectedSizes: @json(request('sizes', [])),
        sort: '{{ request('sort', 'featured') }}',
        loading: false,

        initCatalog() {
            // Listen to BranchedMenu category selections
            window.addEventListener('category-filter-selected', (e) => {
                this.category = e.detail.slug;
                this.applyFilters();
            });
        },

        toggleSize(size) {
            if (this.selectedSizes.includes(size)) {
                this.selectedSizes = this.selectedSizes.filter(s => s !== size);
            } else {
                this.selectedSizes.push(size);
            }
            this.applyFilters();
        },

        resetAllFilters() {
            this.search = '';
            this.category = '';
            this.selectedMaterials = [];
            this.selectedSizes = [];
            this.sort = 'featured';
            this.applyFilters();
        },

        applyFilters() {
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.category) params.append('category', this.category);
            if (this.sort && this.sort !== 'featured') params.append('sort', this.sort);
            
            this.selectedMaterials.forEach(m => params.append('materials[]', m));
            this.selectedSizes.forEach(s => params.append('sizes[]', s));

            const newUrl = `${window.location.pathname}?${params.toString()}`;
            window.location.href = newUrl;
        }
    }
}
</script>
@endsection
