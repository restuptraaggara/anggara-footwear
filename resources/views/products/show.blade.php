@extends('layouts.app')

@section('title', $product->name . ' — Anggara Footwear')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-10"
     x-data="{
        selectedColor: '{{ $product->variants->first()?->color_name }}',
        selectedColorHex: '{{ $product->variants->first()?->color_hex }}',
        selectedSize: '{{ $product->variants->first()?->size }}',
        selectedVariantId: {{ $product->variants->first()?->id ?? 'null' }},
        currentStock: {{ $product->variants->first()?->stock ?? 0 }},
        currentImage: '{{ $product->variants->first()?->image_url }}',
        variants: {{ json_encode($product->variants->groupBy('color_name')->map(fn($v) => ['hex' => $v->first()->color_hex, 'image' => $v->first()->image_url, 'sizes' => $v->map(fn($s) => ['id' => $s->id, 'size' => $s->size, 'stock' => $s->stock])->values()])->toArray()) }},
        
        fitFinderOpen: false,
        footLengthCm: 25.5,
        waNumber: '{{ config('services.whatsapp.number', '6281234567890') }}',
        productName: '{{ addslashes($product->name) }}',
        productPrice: '{{ $product->formatted_price }}',

        get calculatedSize() {
            const cm = parseFloat(this.footLengthCm);
            if (cm <= 22.5) return '36';
            if (cm <= 23.0) return '37';
            if (cm <= 23.5) return '38';
            if (cm <= 24.0) return '39';
            if (cm <= 25.0) return '40';
            if (cm <= 25.5) return '41';
            if (cm <= 26.5) return '42';
            if (cm <= 27.0) return '43';
            if (cm <= 28.0) return '44';
            return '45';
        },

        get calculatedDesc() {
            return `Sesuai untuk panjang kaki ${this.footLengthCm} cm dengan ruang gerak jari kaki alami.`;
        },

        applyRecommendedSize() {
            this.selectSize(this.calculatedSize);
            this.fitFinderOpen = false;
        },

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
        },

        getWhatsAppUrl() {
            const color = this.selectedColor || '-';
            const size = this.selectedSize || '-';
            const msg = `Halo Anggara Footwear, saya tertarik untuk memesan:\n• Produk: ${this.productName}\n• Warna: ${color}\n• Ukuran: EU ${size}\n• Harga: ${this.productPrice}\n\nMohon info ketersediaan stok & panduan pembayarannya. Terima kasih!`;
            return `https://wa.me/${this.waNumber}?text=${encodeURIComponent(msg)}`;
        }
     }">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-earth-500 mb-8 font-light">
        <a href="{{ route('home') }}" class="hover:text-gray-900 transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-gray-900 transition-colors">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
    </nav>

    {{-- Product Detail Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">

        {{-- Left Column: Images --}}
        <div class="space-y-4">
            <div class="aspect-square rounded-3xl overflow-hidden bg-earth-50 border border-earth-100 shadow-sm relative group">
                <img :src="currentImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                
                @if($product->is_featured)
                <span class="absolute top-4 left-4 bg-[#4A3D2C]/90 backdrop-blur-sm text-white text-[10px] tracking-widest uppercase font-semibold px-3 py-1 rounded-md">
                    Featured
                </span>
                @endif
            </div>

            {{-- Thumbnail Grid --}}
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->variants->unique('color_name') as $variant)
                <button 
                    @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                    :class="selectedColor === '{{ $variant->color_name }}' ? 'border-gray-900 ring-1 ring-gray-900' : 'border-earth-100 opacity-60 hover:opacity-100'"
                    class="aspect-square rounded-xl overflow-hidden border-2 transition-all bg-earth-50">
                    <img src="{{ $variant->image_url }}" alt="{{ $variant->color_name }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
        </div>

        {{-- Right Column: Product Information & Controls --}}
        <div class="space-y-8">
            
            {{-- Header Info --}}
            <div class="space-y-2">
                <p class="text-xs tracking-widest uppercase text-earth-600 font-semibold">{{ $product->category->name }} • {{ $product->material_type }}</p>
                <h1 class="text-3xl sm:text-4xl font-light text-gray-900 tracking-tight">{{ $product->name }}</h1>
                <p class="text-2xl font-bold text-gray-900 font-mono pt-1">{{ $product->formatted_price }}</p>
            </div>

            {{-- Color Selector --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-900">Pilihan Warna:</span>
                    <span class="text-xs text-earth-600 font-medium" x-text="selectedColor"></span>
                </div>
                <div class="flex items-center gap-3">
                    @foreach($product->variants->unique('color_name') as $variant)
                    <button 
                        @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                        :class="selectedColor === '{{ $variant->color_name }}' ? 'ring-2 ring-offset-2 ring-gray-900 scale-110' : 'hover:scale-105'"
                        class="w-8 h-8 rounded-full transition-all border border-black/10 cursor-pointer shadow-sm"
                        style="background-color: {{ $variant->color_hex }}"
                        title="{{ $variant->color_name }}">
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Size Selector + Dynamic Stock Indicator + Fit Finder --}}
            <div class="pt-4 border-t border-earth-100 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-900">Pilih Ukuran:</span>
                    <button type="button" 
                            @click="fitFinderOpen = true" 
                            class="text-xs text-forest-700 hover:text-forest-900 font-semibold underline underline-offset-2 flex items-center gap-1 cursor-pointer transition-colors">
                        <span>📏 Cek Ukuran Kaki (Fit Finder)</span>
                    </button>
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
                <div class="min-h-[20px] pt-1">
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

            {{-- Action Buttons: Add to Cart, Wishlist & WhatsApp --}}
            <div class="space-y-3 pt-4 border-t border-earth-100">
                <button 
                    type="button"
                    @click="if(canAddToCart()) { $dispatch('add-to-cart', { variantId: selectedVariantId }); } else { alert('Pilih ukuran yang tersedia terlebih dahulu!'); }"
                    :class="canAddToCart() ? 'bg-gray-900 hover:bg-forest-800 cursor-pointer shadow-md' : 'bg-gray-300 cursor-not-allowed opacity-75'"
                    class="w-full text-white py-4 rounded-xl text-xs tracking-widest uppercase font-bold transition-all duration-200">
                    Tambah ke Keranjang
                </button>

                <button type="button"
                        @click="toggleWishlist({
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

                {{-- Direct WhatsApp Button --}}
                <a :href="getWhatsAppUrl()"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs tracking-wider uppercase font-semibold transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
                    <span>💬 Pesan Cepat via WhatsApp</span>
                </a>
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

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs font-semibold tracking-wider uppercase text-gray-900">
                        <span>Material & Bahan Alami</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pb-4 space-y-2 text-xs text-gray-500 font-light">
                        <p>• <strong>Bahan Utama:</strong> {{ $product->material_type }}</p>
                        <p>• <strong>Insole:</strong> ZQ Certified Merino Wool dengan busa minyak jarak alami</p>
                        <p>• <strong>Outsole:</strong> SweetFoam® berbahan dasar tebu ramah lingkungan</p>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-xs font-semibold tracking-wider uppercase text-gray-900">
                        <span>Petunjuk Perawatan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pb-4 text-xs text-gray-500 font-light space-y-1">
                        <p>1. Lepaskan insole dan tali sepatu.</p>
                        <p>2. Cuci menggunakan mesin cuci dengan air dingin dan putaran lembut.</p>
                        <p>3. Keringkan secara alami (jangan gunakan mesin pengering).</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- MODAL INTERAKTIF: FIT FINDER / FOOT SIZE CALCULATOR --}}
    <div x-show="fitFinderOpen" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak>
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-earth-200 animate-in fade-in zoom-in-95 duration-200 text-gray-900"
             @click.away="fitFinderOpen = false">
            
            <div class="flex items-center justify-between pb-4 border-b border-earth-100">
                <div class="flex items-center gap-2.5">
                    <span class="text-2xl">📏</span>
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Kalkulator Ukuran Kaki (Fit Finder)</h3>
                        <p class="text-[11px] text-gray-500 font-light">Temukan ukuran pas untuk kenyamanan maksimal</p>
                    </div>
                </div>
                <button type="button" @click="fitFinderOpen = false" class="text-gray-400 hover:text-gray-700 text-xl font-bold">✕</button>
            </div>

            <div class="py-6 space-y-6">
                <div class="bg-[#FAF8F5] p-4 rounded-2xl border border-[#EDE4D3] text-xs space-y-2">
                    <p class="font-semibold text-gray-900">💡 Cara Mengukur Telapak Kaki:</p>
                    <ol class="list-decimal list-inside space-y-1 text-gray-600 font-light">
                        <li>Letakkan selembar kertas di lantai menempel pada dinding.</li>
                        <li>Berdiri tegak dengan tumit menempel ke dinding.</li>
                        <li>Tandai ujung jari kaki terpanjang Anda dengan pensil.</li>
                        <li>Ukur jarak dari dinding ke tanda tersebut dalam centimeter (cm).</li>
                    </ol>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="text-xs font-semibold uppercase tracking-wider text-gray-700">
                            Panjang Telapak Kaki Anda:
                        </label>
                        <span class="text-lg font-bold text-forest-800 font-mono" x-text="footLengthCm + ' cm'"></span>
                    </div>

                    <input type="range" 
                           min="22.0" 
                           max="29.0" 
                           step="0.5" 
                           x-model="footLengthCm" 
                           class="w-full accent-forest-700 h-2 bg-earth-200 rounded-lg cursor-pointer">
                    
                    <div class="flex justify-between text-[10px] text-gray-400 font-mono">
                        <span>22.0 cm (EU 36)</span>
                        <span>25.5 cm (EU 41)</span>
                        <span>29.0 cm (EU 45)</span>
                    </div>
                </div>

                <div class="bg-forest-50 p-5 rounded-2xl border border-forest-200 text-center space-y-2">
                    <span class="text-[10px] font-bold tracking-widest uppercase text-forest-700 bg-white px-3 py-1 rounded-full border border-forest-200">
                        Rekomendasi Ukuran Terbaik
                    </span>
                    <div class="text-3xl font-extrabold text-forest-900 tracking-tight" x-text="'EU ' + calculatedSize"></div>
                    <p class="text-xs text-forest-800 font-light" x-text="calculatedDesc"></p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" 
                        @click="applyRecommendedSize()"
                        class="flex-1 py-3.5 bg-gray-900 hover:bg-forest-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-md">
                    ✓ Pilih Ukuran Ini (EU <span x-text="calculatedSize"></span>)
                </button>
                <button type="button" 
                        @click="fitFinderOpen = false"
                        class="px-5 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
    <div class="mt-24 pt-16 border-t border-earth-100">
        <div class="text-center mb-12">
            <p class="text-xs tracking-widest uppercase text-earth-600 mb-2 font-medium">Rekomendasi Pilihan</p>
            <h2 class="text-3xl font-light text-gray-900">Kamu Mungkin Juga Suka</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($relatedProducts as $related)
            <div class="group">
                <div class="aspect-square rounded-2xl overflow-hidden bg-earth-50 border border-earth-100 mb-4 shadow-sm group-hover:shadow-md transition-all">
                    <a href="{{ route('products.show', $related->slug) }}">
                        <img src="{{ $related->variants->first()?->image_url }}" alt="{{ $related->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </a>
                </div>
                <p class="text-[11px] tracking-wider uppercase text-earth-600 font-medium mb-0.5">{{ $related->category->name }}</p>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">
                    <a href="{{ route('products.show', $related->slug) }}" class="hover:text-earth-700 transition-colors">{{ $related->name }}</a>
                </h3>
                <p class="text-xs font-medium text-gray-900 font-mono">{{ $related->formatted_price }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
