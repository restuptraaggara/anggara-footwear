@extends('layouts.app')

@section('title', 'Anggara Footwear — Naturally Comfortable & Sustainable')

@section('content')

{{-- HERO SECTION WITH REACT AURORA BACKGROUND --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden bg-transparent text-white">
    


    {{-- Dark vignette gradient overlay for contrast --}}
    <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-black/40 z-10 pointer-events-none"></div>
    
    <div class="relative z-20 max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-24">
        {{-- Text Content --}}
        <div class="space-y-8">
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-xs tracking-widest uppercase text-emerald-300 font-semibold mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    Koleksi Anggara Footwear 2026
                </span>
                <h1 class="text-5xl lg:text-6xl xl:text-7xl font-light text-white leading-[1.1] tracking-tight">
                    Langkah Alami.<br>
                    <span class="font-semibold text-emerald-300">Kenyamanan</span><br>
                    Berkelanjutan.
                </h1>
            </div>
            <p class="text-base text-gray-300 leading-relaxed max-w-md font-light">
                Setiap pasang sepatu dirancang dari wol merino ZQ, serat eucalyptus alami, dan sol tebu SweetFoam® 
                untuk memberikan kenyamanan maksimal dengan jejak karbon terendah di bumi.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-white hover:bg-emerald-50 text-white px-10 py-4 text-xs tracking-widest uppercase font-semibold transition-all duration-200 text-center rounded-xl shadow-lg hover:scale-105">
                    Jelajahi Katalog
                </a>
                <a href="#signature" 
                   class="inline-block border border-white/30 text-white px-10 py-4 text-xs tracking-widest uppercase font-semibold hover:border-white hover:bg-white/10 transition-all duration-200 text-center rounded-xl backdrop-blur-sm">
                    Signature Series ↓
                </a>
            </div>
            
            {{-- Trust badges --}}
            <div class="flex items-center gap-8 pt-6 border-t border-white/10 text-gray-300">
                <div>
                    <p class="text-xl font-bold text-white">100%</p>
                    <p class="text-[11px] text-gray-400 font-light">Natural Materials</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-white">0%</p>
                    <p class="text-[11px] text-gray-400 font-light">Net Carbon Emitted</p>
                </div>
                <div>
                    <p class="text-xl font-bold text-white">B Corp</p>
                    <p class="text-[11px] text-gray-400 font-light">Certified Sustainable</p>
                </div>
            </div>
        </div>
        
        {{-- Hero Image with Floating Badge --}}
        <div class="relative z-20">
            <div class="aspect-[4/5] rounded-3xl overflow-hidden bg-white/5 border border-white/10 shadow-2xl backdrop-blur-sm">
                <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80" 
                     alt="Allbirds Tree Runner"
                     class="w-full h-full object-cover">
            </div>
            {{-- Floating badge --}}
            <div class="absolute -bottom-4 -left-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl p-5 max-w-[220px] border border-white/10 text-white">
                <div class="flex items-center gap-1.5 mb-1 text-amber-500">
                    <span class="text-xs">★★★★★</span>
                    <span class="text-xs font-bold text-white ml-1">4.9 / 5</span>
                </div>
                <p class="text-[11px] text-gray-300 font-light leading-tight">Dipercaya lebih dari 12,000+ pelanggan di seluruh dunia</p>
            </div>
        </div>
    </div>
</section>

{{-- REACT ISLAND: SCROLL EXPAND SHOWCASE --}}
<div id="signature">
    <div id="hero-scroll-expand"
         data-title="Signature Anggara Tree Runner"
         data-subtitle="Bahan eucalyptus airy dengan insole merino wool yang mampu meregulasi suhu kaki Anda secara alami di segala cuaca."
         data-image="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1600&q=85"
         data-cta-text="Beli Signature Series"
         data-cta-link="{{ route('products.show', 'tree-runner') }}">
    </div>
</div>

{{-- REACT ISLAND: ACCORDION GALLERY --}}
<section class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
    @php
        $galleryData = $featuredProducts->map(function($p) {
            $first = $p->variants->first();
            return [
                'id' => $p->id,
                'title' => $p->name,
                'subtitle' => $p->material_type . ' — ' . $p->description,
                'price' => $p->formatted_price,
                'image' => $first?->image_url ?? 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1000&q=85',
                'link' => route('products.show', $p->slug),
            ];
        })->values()->toArray();
    @endphp

    <div id="product-accordion-gallery"
         data-items='@json($galleryData)'>
    </div>
</section>

{{-- CATEGORIES SECTION --}}
<section class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
    <div class="text-center mb-12">
        <p class="text-xs tracking-widest uppercase text-earth-600 mb-2 font-medium">Kategori Pilihan</p>
        <h2 class="text-3xl font-light text-white">Temukan Gaya Kamu</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" 
           class="group relative aspect-[3/4] overflow-hidden rounded-3xl bg-white/10 block shadow-sm hover:shadow-xl transition-all duration-500">
            @php
                $catImages = [
                    'men' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=600&q=80',
                    'women' => 'https://images.unsplash.com/photo-1604671801908-6f0c6a092c05?w=600&q=80',
                    'accessories' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600&q=80',
                ];
            @endphp
            <img src="{{ $catImages[$cat->slug] ?? '' }}" alt="{{ $cat->name }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-8">
                <p class="text-white text-2xl font-semibold mb-1">{{ $cat->name }}</p>
                <p class="text-white/80 text-xs tracking-wider uppercase font-light">{{ $cat->products_count }} pilihan model</p>
            </div>
        </a>
        @endforeach
    </div>
</section>

{{-- SUSTAINABLE STORY SECTION --}}
<section id="story" class="py-24 bg-[#090b10]/60 backdrop-blur-md border-t border-white/10 text-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            {{-- Images Grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-white/10 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80" 
                         alt="Sustainable manufacturing" class="w-full h-full object-cover">
                </div>
                <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-white/10 mt-8 shadow-sm">
                    <img src="https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?w=600&q=80" 
                         alt="Natural materials" class="w-full h-full object-cover">
                </div>
            </div>
            
            {{-- Story Content --}}
            <div class="space-y-8">
                <div>
                    <p class="text-xs tracking-widest uppercase text-forest-600 mb-3 font-medium">Misi Berkelanjutan</p>
                    <h2 class="text-4xl font-light text-white leading-snug">
                        Fashion yang bertanggung jawab <br>
                        <span class="font-semibold text-forest-700">untuk bumi yang lebih hijau.</span>
                    </h2>
                </div>
                <p class="text-base text-gray-300 leading-relaxed font-light">
                    Kami percaya bahwa industri alas kaki tidak harus mengorbankan planet. Setiap produk 
                    Allbirds dibuat dari bahan-bahan alami — wol merino ZQ, serat eucalyptus, karet tebu — 
                    yang diproses secara etis dan bersertifikasi ramah lingkungan.
                </p>
                
                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 py-6 border-y border-white/10">
                    <div>
                        <p class="text-2xl font-bold text-white mb-1">95%</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Material terbarukan</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white mb-1">7.6 kg</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">CO₂e rata-rata</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-white mb-1">100%</p>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">Energi terbarukan</p>
                    </div>
                </div>
                
                {{-- Materials --}}
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🌿</span>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-0.5">Serat Eucalyptus</h4>
                            <p class="text-xs text-gray-400 font-light">Lebih lembut dari sutra, diproduksi dengan 95% lebih sedikit air.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🐑</span>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-0.5">ZQ Merino Wool</h4>
                            <p class="text-xs text-gray-400 font-light">Standar etika peternakan wol tertinggi di dunia, naturally breathable.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="text-2xl">🎋</span>
                        <div>
                            <h4 class="text-sm font-semibold text-white mb-0.5">SweetFoam® Sugar Cane</h4>
                            <p class="text-xs text-gray-400 font-light">Sol pertama di dunia berbahan dasar tebu manis pengganti busa EVA sintetis.</p>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('products.index') }}" 
                   class="inline-block bg-forest-700 text-white px-8 py-4 text-xs tracking-widest uppercase font-semibold hover:bg-forest-800 transition-colors rounded-xl shadow-md">
                    Lihat Seluruh Katalog
                </a>
            </div>
        </div>
    </div>
</section>

{{-- NEWSLETTER --}}
<section class="bg-gray-900 py-20 text-white">
    <div class="max-w-2xl mx-auto px-6 text-center space-y-4">
        <p class="text-xs tracking-widest uppercase text-emerald-400 font-semibold">Komunitas Anggara Footwear</p>
        <h2 class="text-3xl font-light text-white">Dapatkan Info Rilis Eksklusif</h2>
        <p class="text-gray-400 text-sm font-light max-w-md mx-auto">
            Daftarkan email Anda untuk mendapatkan pemberitahuan koleksi edisi terbatas dan diskon khusus anggota.
        </p>
        <form class="flex gap-3 max-w-md mx-auto pt-4" onsubmit="event.preventDefault(); alert('Terima kasih telah bergabung!');">
            <input type="email" placeholder="email@kamu.com" required
                   class="flex-1 bg-gray-800 text-white placeholder-gray-500 px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-emerald-400 rounded-xl border border-gray-700">
            <button type="submit" class="bg-white text-white px-6 py-3 text-xs tracking-widest uppercase font-semibold hover:bg-emerald-50 transition-colors rounded-xl shadow-sm">
                Daftar
            </button>
        </form>
    </div>
</section>

@endsection
