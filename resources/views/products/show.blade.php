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
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    {{-- Product Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16"
         x-data="{
            selectedColor: '{{ $product->variants->first()?->color_name }}',
            selectedColorHex: '{{ $product->variants->first()?->color_hex }}',
            selectedSize: null,
            selectedVariantId: null,
            currentImage: '{{ $product->variants->first()?->image_url }}',
            variants: {{ json_encode($product->variants->groupBy('color_name')->map(fn($v) => ['hex' => $v->first()->color_hex, 'image' => $v->first()->image_url, 'sizes' => $v->map(fn($s) => ['id' => $s->id, 'size' => $s->size, 'stock' => $s->stock])->values()])->toArray()) }},
            
            selectColor(colorName, hex, image) {
                this.selectedColor = colorName;
                this.selectedColorHex = hex;
                this.currentImage = image;
                this.selectedSize = null;
                this.selectedVariantId = null;
            },
            selectSize(size) {
                this.selectedSize = size;
                // Find variant ID
                const colorData = this.variants[this.selectedColor];
                if (colorData) {
                    const sizeData = colorData.sizes.find(s => s.size === size);
                    this.selectedVariantId = sizeData ? sizeData.id : null;
                }
            },
            getSizesForColor() {
                return this.variants[this.selectedColor]?.sizes || [];
            },
            canAddToCart() {
                return this.selectedVariantId !== null;
            }
         }">
        
        {{-- Left: Image --}}
        <div class="space-y-4">
            <div class="aspect-square rounded-2xl overflow-hidden bg-earth-50">
                <img :src="currentImage" alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-all duration-500">
            </div>
            {{-- Thumbnail strip --}}
            <div class="flex gap-3">
                @foreach($product->variants->unique('color_name') as $variant)
                <button @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                        class="w-16 h-16 rounded-lg overflow-hidden border-2 transition-all"
                        :class="selectedColor === '{{ $variant->color_name }}' ? 'border-gray-900 ring-1 ring-gray-900' : 'border-transparent'">
                    <img src="{{ $variant->image_url }}" alt="{{ $variant->color_name }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
        </div>
        
        {{-- Right: Product Info --}}
        <div class="space-y-8">
            
            <div>
                <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">{{ $product->category->name }} &bull; {{ $product->material_type }}</p>
                <h1 class="text-3xl font-light text-gray-900 mb-3">{{ $product->name }}</h1>
                <p class="text-2xl font-semibold text-gray-900">{{ $product->formatted_price }}</p>
            </div>
            
            {{-- Color Selection --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-900">Warna</span>
                    <span class="text-sm text-earth-500 font-light" x-text="selectedColor"></span>
                </div>
                <div class="flex gap-3">
                    @foreach($product->variants->unique('color_name') as $variant)
                    <button @click="selectColor('{{ $variant->color_name }}', '{{ $variant->color_hex }}', '{{ $variant->image_url }}')"
                            class="w-8 h-8 rounded-full border-2 transition-all hover:scale-110"
                            :class="selectedColor === '{{ $variant->color_name }}' ? 'border-gray-900 ring-2 ring-offset-2 ring-gray-900' : 'border-transparent'"
                            style="background-color: {{ $variant->color_hex }}"
                            title="{{ $variant->color_name }}">
                    </button>
                    @endforeach
                </div>
            </div>
            
            {{-- Size Selection --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-900">Ukuran</span>
                    <a href="#" class="text-xs text-gray-400 hover:text-gray-600 font-light underline">Panduan Ukuran</a>
                </div>
                <div class="grid grid-cols-4 gap-2">
                    <template x-for="sizeData in getSizesForColor()" :key="sizeData.size">
                        <button 
                            @click="selectSize(sizeData.size)"
                            :disabled="sizeData.stock === 0"
                            :class="{
                                'bg-gray-900 text-white border-gray-900': selectedSize === sizeData.size,
                                'border-gray-200 text-gray-600 hover:border-gray-900': selectedSize !== sizeData.size && sizeData.stock > 0,
                                'border-gray-100 text-gray-300 cursor-not-allowed line-through': sizeData.stock === 0
                            }"
                            class="border py-2.5 text-sm font-medium transition-all text-center">
                            <span x-text="sizeData.size"></span>
                        </button>
                    </template>
                </div>
                <p x-show="!selectedSize" class="text-xs text-earth-500 mt-2 font-light">Pilih ukuran untuk melanjutkan.</p>
            </div>
            
            {{-- Add to Cart --}}
            <div class="space-y-3">
                <button 
                    @click="if(canAddToCart()) { $dispatch('add-to-cart', { variantId: selectedVariantId }); } else { alert('Pilih ukuran terlebih dahulu!'); }"
                    :class="canAddToCart() ? 'bg-gray-900 hover:bg-earth-800 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                    class="w-full text-white py-4 text-xs tracking-widest uppercase font-medium transition-all">
                    Tambah ke Keranjang
                </button>
                <button class="w-full border border-gray-200 text-gray-600 py-4 text-xs tracking-widest uppercase font-medium hover:border-gray-900 hover:text-gray-900 transition-all">
                    ♡ &nbsp; Simpan ke Wishlist
                </button>
            </div>
            
            {{-- Delivery Info --}}
            <div class="bg-earth-50 rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>🚚</span>
                    <span class="font-light">Gratis pengiriman untuk pesanan di atas Rp 500.000</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>↩️</span>
                    <span class="font-light">Retur gratis dalam 30 hari</span>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span>🌿</span>
                    <span class="font-light">Kemasan 100% dapat didaur ulang</span>
                </div>
            </div>
            
            {{-- Accordion: Material Info --}}
            <div class="border-t border-earth-100 divide-y divide-earth-100">
                
                {{-- Description --}}
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-sm font-medium text-gray-900">
                        <span>Deskripsi Produk</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pb-4">
                        <p class="text-sm text-gray-500 leading-relaxed font-light">{{ $product->description }}</p>
                    </div>
                </div>
                
                {{-- Material --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-sm font-medium text-gray-900">
                        <span>Material & Bahan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pb-4">
                        <div class="space-y-2 text-sm text-gray-500 font-light">
                            <p>🌿 <strong class="font-medium text-gray-700">Upper:</strong> {{ $product->material_type }}</p>
                            <p>🎋 <strong class="font-medium text-gray-700">Insole:</strong> ZQ Merino Wool + Bio-based foam</p>
                            <p>🌱 <strong class="font-medium text-gray-700">Outsole:</strong> SweetFoam® dari tebu</p>
                            <p>♻️ <strong class="font-medium text-gray-700">Laces:</strong> Daur ulang botol plastik</p>
                        </div>
                    </div>
                </div>
                
                {{-- Care --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full py-4 text-sm font-medium text-gray-900">
                        <span>Cara Perawatan</span>
                        <svg :class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="pb-4">
                        <ul class="text-sm text-gray-500 font-light space-y-1">
                            <li>• Cuci tangan dengan air dingin</li>
                            <li>• Jangan gunakan mesin cuci dengan suhu tinggi</li>
                            <li>• Keringkan secara alami, jauh dari sinar matahari langsung</li>
                            <li>• Lepas insole sebelum mencuci</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
    <section class="mt-24">
        <div class="mb-10">
            <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Koleksi Serupa</p>
            <h2 class="text-2xl font-light text-gray-900">Kamu Mungkin Juga Suka</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach($relatedProducts as $related)
            @php $relFirst = $related->variants->first(); @endphp
            <div class="product-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg">
                <a href="{{ route('products.show', $related->slug) }}" class="block">
                    <div class="aspect-square overflow-hidden bg-earth-50">
                        <img src="{{ $relFirst?->image_url }}" alt="{{ $related->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                </a>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">
                        <a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a>
                    </h3>
                    <p class="text-sm font-semibold text-gray-700">{{ $related->formatted_price }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Alpine collapse plugin (simple implementation)
    document.addEventListener('alpine:init', () => {
        Alpine.directive('collapse', (el, {}, { effect }) => {
            // Simple collapse: toggle max-height
        });
    });
</script>
@endpush