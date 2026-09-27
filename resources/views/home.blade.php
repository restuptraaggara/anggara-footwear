@extends('layouts.app')

@section('title', 'Allbirds Store — Naturally Comfortable')

@section('content')

{{-- HERO SECTION --}}
<section class="relative min-h-[85vh] flex items-center overflow-hidden bg-earth-50">
    <div class="absolute inset-0 bg-gradient-to-br from-earth-50 via-earth-100 to-earth-200"></div>
    
    <div class="relative max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-20">
        {{-- Text Content --}}
        <div class="space-y-8">
            <div>
                <p class="text-xs tracking-widest uppercase text-earth-600 font-medium mb-4">Koleksi Terbaru 2026</p>
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-light text-gray-900 leading-[1.1] tracking-tight">
                    Nyaman.<br>
                    <span class="font-semibold">Alami.</span><br>
                    Berkelanjutan.
                </h1>
            </div>
            <p class="text-base text-gray-500 leading-relaxed max-w-md font-light">
                Setiap langkah yang kamu ambil, kami pastikan meninggalkan jejak yang lebih kecil di bumi. 
                Dibuat dari wol merino, serat pohon eucalyptus, dan bahan daur ulang.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-gray-900 text-white px-10 py-4 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-all duration-200 text-center rounded-xl shadow-sm">
                    Jelajahi Koleksi
                </a>
                <a href="#story" 
                   class="inline-block border border-gray-300 text-gray-700 px-10 py-4 text-xs tracking-widest uppercase font-medium hover:border-gray-900 hover:text-gray-900 transition-all duration-200 text-center rounded-xl">
                    Cerita Kami
                </a>
            </div>
            
            {{-- Trust badges --}}
            <div class="flex items-center gap-8 pt-4 border-t border-earth-200">
                <div class="text-center">
                    <p class="text-lg font-semibold text-gray-900">100%</p>
                    <p class="text-xs text-gray-400 font-light">Natural Materials</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-semibold text-gray-900">Carbon</p>
                    <p class="text-xs text-gray-400 font-light">Neutral Certified</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-semibold text-gray-900">B Corp</p>
                    <p class="text-xs text-gray-400 font-light">Certified</p>
                </div>
            </div>
        </div>
        
        {{-- Hero Image --}}
        <div class="relative">
            <div class="aspect-[4/5] rounded-3xl overflow-hidden bg-earth-200 shadow-2xl">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80" 
                     alt="Allbirds Tree Runner"
                     class="w-full h-full object-cover">
            </div>
            {{-- Floating badge --}}
            <div class="absolute -bottom-4 -left-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl p-5 max-w-[200px] border border-earth-100">
                <div class="flex items-center gap-1.5 mb-1 text-amber-500">
                    <span class="text-xs">★★★★★</span>
                    <span class="text-xs font-semibold text-gray-900 ml-1">4.9 / 5</span>
                </div>
                <p class="text-[11px] text-gray-500 font-light leading-tight">Dipercaya lebih dari 12,000+ pelanggan di seluruh dunia</p>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES SECTION --}}
<section class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
    <div class="text-center mb-12">
        <p class="text-xs tracking-widest uppercase text-earth-600 mb-2 font-medium">Koleksi Kami</p>
        <h2 class="text-3xl font-light text-gray-900">Temukan Gaya Kamu</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
           class="group relative aspect-[3/4] overflow-hidden rounded-2xl bg-earth-100 block shadow-sm hover:shadow-lg transition-all duration-300">
            @php
                $catImages = [
                    'men' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=600&q=80',
                    'women' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=600&q=80',
                    'accessories' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&q=80',
                ];
            @endphp
            <img src="{{ $catImages[$cat->slug] ?? '' }}" alt="{{ $cat->name }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8">
                <p class="text-white text-2xl font-semibold mb-1">{{ $cat->name }}</p>
                <p class="text-white/80 text-xs tracking-wider uppercase font-light">{{ $cat->products_count }} pilihan produk</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- FEATURED PRODUCTS WITH WISHLIST & COLOR SWATCHES --}}
<section class="bg-earth-50 py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-xs tracking-widest uppercase text-earth-600 mb-2 font-medium">Pilihan Editor</p>
                <h2 class="text-3xl font-light text-gray-900">Produk Unggulan</h2>
            </div>
            <a href="{{ route('products.index') }}" 
               class="text-xs font-semibold uppercase tracking-wider text-gray-900 hover:text-earth-700 transition-colors underline underline-offset-4">
                Lihat Semua ({{ $featuredProducts->count() }}) →
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            @php
                $productColors = $product->variants->unique('color_name')->values();
                $firstVariant = $product->variants->first();
            @endphp
            <div class="product-card group bg-white rounded-2xl overflow-hidden border border-earth-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between"
                 x-data="{ currentImg: '{{ $firstVariant?->image_url }}' }">
                
                <div>
                    {{-- Product Image with Wishlist Heart --}}
                    <div class="relative aspect-square overflow-hidden bg-earth-50">
                        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
                            <img :src="currentImg" 
                                 src="{{ $firstVariant?->image_url }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </a>

                        {{-- Heart Button --}}
                        <button @click.stop="toggleWishlist({
                                    id: {{ $product->id }},
                                    name: '{{ addslashes($product->name) }}',
                                    slug: '{{ $product->slug }}',
                                    category: '{{ $product->category->name }}',
                                    material_type: '{{ addslashes($product->material_type) }}',
                                    price_formatted: '{{ $product->formatted_price }}',
                                    image_url: currentImg,
                                    first_variant_id: {{ $firstVariant?->id ?? 'null' }}
                                })"
                                class="absolute top-3 right-3 p-2.5 rounded-full bg-white/90 hover:bg-white text-gray-400 hover:text-red-500 hover:scale-110 shadow-sm transition-all duration-200 z-10"
                                :class="isInWishlist({{ $product->id }}) ? 'text-red-500 fill-current' : 'text-gray-400'"
                                title="Tambah ke Wishlist">
                            <svg class="h-4 w-4" :fill="isInWishlist({{ $product->id }}) ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                    </div>
                    
                    {{-- Product Info --}}
                    <div class="p-5">
                        <p class="text-[11px] tracking-wider uppercase text-earth-600 font-medium mb-1">{{ $product->category->name }}</p>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">
                            <a href="{{ route('products.show', $product->slug) }}" class="hover:text-earth-700 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="text-xs text-earth-500 font-light mb-3 line-clamp-1">{{ $product->material_type }}</p>
                        
                        {{-- Color Swatches --}}
                        <div class="flex items-center gap-1.5 mb-2">
                            @foreach($productColors as $variant)
                            <button 
                                type="button"
                                class="color-swatch ring-offset-1 hover:ring-1 hover:ring-gray-900 transition-transform"
                                style="background-color: {{ $variant->color_hex }}"
                                title="{{ $variant->color_name }}"
                                @mouseenter="currentImg = '{{ $variant->image_url }}'"
                                @click="currentImg = '{{ $variant->image_url }}'">
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Price & View link --}}
                <div class="px-5 pb-5 pt-2 flex items-center justify-between border-t border-earth-50">
                    <p class="text-sm font-semibold text-gray-900">{{ $product->formatted_price }}</p>
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="text-xs font-medium text-earth-700 hover:text-gray-900 tracking-wider uppercase underline underline-offset-4 transition-colors">
                        Lihat Detail →
                    </a>
                </div>

            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SUSTAINABLE STORY SECTION --}}
<section id="story" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            {{-- Images Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-earth-100 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" 
                         alt="Sustainable manufacturing" class="w-full h-full object-cover">
                </div>
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-earth-100 mt-8 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=600&q=80" 
                         alt="Natural materials" class="w-full h-full object-cover">
                </div>
            </div>
            
            {{-- Story Content --}}
            <div class="space-y-8">
                <div>
                    <p class="text-xs tracking-widest uppercase text-forest-600 mb-3 font-medium">Misi Kami</p>
                    <h2 class="text-4xl font-light text-gray-900 leading-snug">
                        Mode yang lebih baik <br>
                        <span class="font-semibold">untuk bumi yang lebih baik.</span>
                    </h2>
                </div>
                <p class="text-base text-gray-500 leading-relaxed font-light">
                    Kami percaya bahwa industri fashion bisa beroperasi secara bertanggung jawab. Setiap produk 
                    Allbirds dibuat dari bahan-bahan alami — wol merino ZQ, serat eucalyptus, karet tebu — 
                    yang diproses secara etis dan berkelanjutan.
                </p>
                
                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 py-6 border-y border-earth-100">
                    <div>
                        <p class="text-2xl font-semibold text-gray-900 mb-1">95%</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Material terbarukan</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900 mb-1">7.6 kg</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">CO₂e rata-rata</p>
                    </div>
                    <div>
                        <p class="text-2xl font-semibold text-gray-900 mb-1">100%</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Energi hijau pabrik</p>
                    </div>
                </div>
                
                {{-- Materials --}}
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🌿</span>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-0.5">Serat Eucalyptus</h4>
                            <p class="text-xs text-gray-400 font-light">Lebih lembut dari sutra, diproduksi dengan 95% lebih sedikit air.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🐑</span>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-0.5">ZQ Merino Wool</h4>
                            <p class="text-xs text-gray-400 font-light">Standar etika peternakan wol tertinggi di dunia, naturally breathable.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🎋</span>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 mb-0.5">SweetFoam® Sugar Cane</h4>
                            <p class="text-xs text-gray-400 font-light">Sol pertama di dunia berbahan dasar tebu manis pengganti busa EVA berbasis minyak.</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-forest-700 text-white px-8 py-4 text-xs tracking-widest uppercase font-medium hover:bg-forest-800 transition-colors rounded-xl shadow-sm">
                    Mulai Langkah Berkelanjutan
                </a>
            </div>
        </div>
    </div>
</section>

{{-- NEWSLETTER / CTA --}}
<section class="bg-gray-900 py-20 text-white">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <p class="text-xs tracking-widest uppercase text-earth-400 mb-4 font-medium">Bergabung dengan Komunitas Kami</p>
        <h2 class="text-3xl lg:text-4xl font-light text-white mb-4">Dapatkan Update Eksklusif</h2>
        <p class="text-earth-400 text-sm font-light mb-8 max-w-md mx-auto">
            Dapatkan info produk edisi terbatas, inovasi material baru, dan berita sustainability kami langsung di inbox.
        </p>
        <form class="flex gap-3 max-w-md mx-auto" onsubmit="event.preventDefault(); alert('Terima kasih telah mendaftar!');">
            <input type="email" placeholder="email@kamu.com" required
                   class="flex-1 bg-gray-800 text-white placeholder-gray-500 px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-earth-400 rounded-xl border border-gray-700">
            <button type="submit" class="bg-white text-gray-900 px-6 py-3 text-xs tracking-widest uppercase font-medium hover:bg-earth-100 transition-colors rounded-xl">
                Daftar
            </button>
        </form>
    </div>
</section>

@endsection
