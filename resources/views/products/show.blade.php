@extends('layouts.app')

@section('title', $product->name . ' — Allbirds Store')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-10 font-light">
        <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-gray-700 transition-colors">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">{{ $product->name }}</span>
    </nav>

    {{-- Product Detail Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16"
         x-data="{
            selectedColor: '{{ $product->variants->first()?->color_name }}',
            selectedColorHex: '{{ $product->variants->first()?->color_hex }}',
            selectedSize: null,
            selectedVariantId: null,
            currentStock: null,
            currentImage: '{{ $product->variants->first()?->image_url }}',
            variants: {{ json_encode($product->variants->groupBy('color_name')->map(fn($v) => ['hex' => $v->first()->color_hex, 'image' => $v->first()->image_url, 'sizes' => $v->map(fn($s) => ['id' => $s->id, 'size' => $s->size, 'stock' => $s->stock])->values()])->toArray()) }},
            
            selectColor(colorName, hex, image) {
                this.selectedColor = colorName;
                this.selectedColorHex = hex;
                this.currentImage = image;
                this.selectedSize = null;
                this.selectedVariantId = null;
                this.currentStock = null;
            },
            selectSize(size) {
                this.selectedSize = size;
                const colorData = this.variants[this.selectedColor];
                if (colorData) {
                    const sizeData = colorData.sizes.find(s => s.size === size);
                    this.selectedVariantId = sizeData ? sizeData.id : null;
                    this.currentStock = sizeData ? sizeData.stock : null;
                }
            },
            getSizesForColor() {
                return this.variants[this.selectedColor]?.sizes || [];
            },
            canAddToCart() {
                return this.selectedVariantId !== null && this.currentStock > 0;
            }
         }">
        
        {{-- Left Column: Images --}}
        <div class="space-y-4">
            <div class="aspect-square rounded-3xl overflow-hidden bg-earth-50 border border-earth-100 shadow-sm relative group">
                <img :src="currentImage" alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-all duration-500 group-hover:scale-105">
                
                {{-- Sustainable Badge overlay --}}
                <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full text-[11px] font-medium text-gray-700 flex items-center gap-1.5 shadow-sm">
                    <span>🌿</span>
                    <span>100% Karbon Netral</span>
                </div>
            </div>

            {{-- Thumbnail Selector Strip --}}
            <div class="flex gap-3 overflow-x-auto pb-2">
                @foreach($product->variants->unique('color_name') as $variant)
                <button @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                        class="w-20 h-20 rounded-xl overflow-hidden border-2 transition-all flex-shrink-0 relative group"
                        :class="selectedColor === '{{ $variant->color_name }}' ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-900' : 'border-earth-100 hover:border-gray-400'">
                    <img src="{{ $variant->image_url }}" alt="{{ $variant->color_name }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
        </div>
        
        {{-- Right Column: Product Purchasing Panel --}}
        <div class="space-y-8">
            
            {{-- Title & Price --}}
            <div>
                <p class="text-xs tracking-widest uppercase text-earth-600 mb-2 font-medium">
                    {{ $product->category->name }} &bull; {{ $product->material_type }}
                </p>
                <h1 class="text-3xl lg:text-4xl font-light text-gray-900 mb-3">{{ $product->name }}</h1>
                <p class="text-2xl font-semibold text-gray-900">{{ $product->formatted_price }}</p>
            </div>
            
            {{-- Color Swatches --}}
            <div class="pt-4 border-t border-earth-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-900">Pilihan Warna:</span>
                    <span class="text-xs font-medium text-earth-700" x-text="selectedColor"></span>
                </div>
                <div class="flex gap-3">
                    @foreach($product->variants->unique('color_name') as $variant)
                    <button @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                            class="w-9 h-9 rounded-full border-2 transition-all hover:scale-110 shadow-sm"
                            :class="selectedColor === '{{ $variant->color_name }}' ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-900' : 'border-transparent hover:border-gray-300'"
                            style="background-color: {{ $variant->color_hex }}"
                            title="{{ $variant->color_name }}">
                    </button>
                    @endforeach
                </div>
            </div>
            
            {{-- Size Selector + Dynamic Stock Indicator --}}
            <div class="pt-4 border-t border-earth-100">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-900">Pilih Ukuran:</span>
                    <a href="#" class="text-xs text-gray-400 hover:text-gray-700 font-light underline underline-offset-4">Panduan Ukuran (EU)</a>
                </div>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                    <template x-for="sizeData in getSizesForColor()" :key="sizeData.size">
                        <button 
                            @click="selectSize(sizeData.size)"
                            :disabled="sizeData.stock === 0"
                            :class="{
                                'bg-gray-900 text-white border-gray-900 shadow-md scale-102': selectedSize === sizeData.size,
                                'bg-white border-earth-200 text-gray-700 hover:border-gray-900 hover:text-gray-900': selectedSize !== sizeData.size && sizeData.stock > 0,
                                'bg-earth-50 border-earth-100 text-gray-300 cursor-not-allowed line-through': sizeData.stock === 0
                            }"
                            class="border rounded-xl py-3 text-xs font-medium transition-all text-center">
                            <span x-text="sizeData.size"></span>
                        </button>
                    </template>
                </div>

                {{-- Stock Status Indicator --}}
                <div class="mt-3 min-h-[20px]">
                    <template x-if="selectedSize && currentStock !== null">
                        <div class="flex items-center gap-2 text-xs">
                            <template x-if="currentStock > 5">
                                <span class="flex items-center gap-1.5 text-forest-700 font-medium">
                                    <span class="w-2 h-2 rounded-full bg-forest-500 animate-pulse"></span>
                                    Stok Tersedia (<span x-text="currentStock"></span> pasang)
                                </span>
                            </template>
                            <template x-if="currentStock > 0 && currentStock <= 5">
                                <span class="flex items-center gap-1.5 text-amber-600 font-medium">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                    Stok Menipis! Hanya sisa <span x-text="currentStock"></span> pasang
                                </span>
                            </template>
                            <template x-if="currentStock === 0">
                                <span class="text-red-500 font-medium">Stok Habis untuk ukuran ini</span>
                            </template>
                        </div>
                    </template>
                    <template x-if="!selectedSize">
                        <p class="text-xs text-earth-500 font-light">Pilih ukuran terlebih dahulu untuk melihat ketersediaan stok.</p>
                    </template>
                </div>
            </div>
            
            {{-- Action Buttons: Add to Cart & Wishlist --}}
            <div class="space-y-3 pt-4 border-t border-earth-100">
                <button 
                    @click="if(canAddToCart()) { $dispatch('add-to-cart', { variantId: selectedVariantId }); } else { alert('Pilih ukuran yang tersedia terlebih dahulu!'); }"
                    :class="canAddToCart() ? 'bg-gray-900 hover:bg-earth-800 cursor-pointer shadow-md' : 'bg-gray-300 cursor-not-allowed opacity-75'"
                    class="w-full text-white py-4 rounded-xl text-xs tracking-widest uppercase font-medium transition-all duration-200">
                    Tambah ke Keranjang
                </button>

                {{-- Wishlist Toggle Button --}}
                <button @click="toggleWishlist({
                            id: {{ $product->id }},
                            name: '{{ addslashes($product->name) }}',
                            slug: '{{ $product->slug }}',
                            category: '{{ $product->category->name }}',
                            material_type: '{{ addslashes($product->material_type) }}',
                            price_formatted: '{{ $product->formatted_price }}',
                            image_url: currentImage,
                            first_variant_id: selectedVariantId || {{ $product->variants->first()?->id ?? 'null' }}
                        })"
                        class="w-full border border-earth-200 rounded-xl py-3.5 text-xs tracking-widest uppercase font-medium transition-all flex items-center justify-center gap-2 hover:border-gray-900"
                        :class="isInWishlist({{ $product->id }}) ? 'bg-red-50 border-red-200 text-red-600' : 'text-gray-700 hover:text-gray-900'">
                    <svg class="h-4 w-4" :fill="isInWishlist({{ $product->id }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span x-text="isInWishlist({{ $product->id }}) ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist'"></span>
                </button>
            </div>
            
            {{-- Free Shipping & Guarantee Badges --}}
            <div class="bg-earth-50 rounded-2xl p-5 space-y-2.5 border border-earth-100">
                <div class="flex items-center gap-3 text-xs text-gray-600">
                    <span class="text-base">🚚</span>
                    <span class="font-light"><strong>Gratis Ongkir</strong> untuk seluruh pesanan di atas Rp 500.000</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-600">
                    <span class="text-base">↩️</span>
                    <span class="font-light"><strong>Garansi Retur 30 Hari</strong> tanpa pertanyaan</span>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-600">
                    <span class="text-base">🌱</span>
                    <span class="font-light"><strong>100% Kemasan Daur Ulang</strong> ramah lingkungan</span>
                </div>
            </div>
            
            {{-- Accordion Sections --}}
            <div class="border-t border-earth-100 divide-y divide-earth-100">
                
                {{-- Description --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs font-semibold tracking-wider uppercase text-gray-900">
                        <span>Deskripsi Produk</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pb-4">
                        <p class="text-sm text-gray-500 leading-relaxed font-light">{{ $product->description }}</p>
                    </div>
                </div>
                
                {{-- Material Details --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs font-semibold tracking-wider uppercase text-gray-900">
                        <span>Material & Bahan Alami</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pb-4">
                        <div class="space-y-2 text-sm text-gray-500 font-light">
                            <p>🌿 <strong class="font-medium text-gray-700">Upper:</strong> {{ $product->material_type }}</p>
                            <p>🎋 <strong class="font-medium text-gray-700">Insole:</strong> ZQ Merino Wool + Bio-based foam</p>
                            <p>🌱 <strong class="font-medium text-gray-700">Outsole:</strong> SweetFoam® dari tebu terbarukan</p>
                            <p>♻️ <strong class="font-medium text-gray-700">Laces:</strong> 100% daur ulang botol plastik PET</p>
                        </div>
                    </div>
                </div>
                
                {{-- Care Guide --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs font-semibold tracking-wider uppercase text-gray-900">
                        <span>Petunjuk Perawatan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pb-4">
                        <ul class="text-sm text-gray-500 font-light space-y-1.5">
                            <li>• Cuci dengan tangan menggunakan air dingin</li>
                            <li>• Jangan gunakan pemutih atau pengering mesin putar</li>
                            <li>• Keringkan di tempat teduh (angin-anginkan)</li>
                            <li>• Insole dapat dilepas untuk dibersihkan terpisah</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Related Products Grid --}}
    @if($relatedProducts->isNotEmpty())
    <section class="mt-24 pt-12 border-t border-earth-100">
        <div class="mb-8">
            <p class="text-xs tracking-widest uppercase text-earth-500 mb-1 font-medium">Koleksi Serupa</p>
            <h2 class="text-2xl font-light text-gray-900">Kamu Mungkin Juga Suka</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $related)
            @php $relFirst = $related->variants->first(); @endphp
            <div class="product-card group bg-white rounded-2xl overflow-hidden border border-earth-100 shadow-sm hover:shadow-md transition-all">
                <a href="{{ route('products.show', $related->slug) }}" class="block">
                    <div class="aspect-square overflow-hidden bg-earth-50">
                        <img src="{{ $relFirst?->image_url }}" alt="{{ $related->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                </a>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">
                        <a href="{{ route('products.show', $related->slug) }}" class="hover:text-earth-700 transition-colors">{{ $related->name }}</a>
                    </h3>
                    <p class="text-xs text-earth-500 font-light mb-2">{{ $related->material_type }}</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $related->formatted_price }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
