@extends('layouts.app')

@section('title', 'Wishlist Saya — Allbirds Store')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12" x-data="wishlistPage()">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 pb-6 border-b border-earth-100 gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-400 mb-3 font-light">
                <a href="{{ route('home') }}" class="hover:text-gray-700 transition-colors">Home</a>
                <span>/</span>
                <span class="text-gray-700">Wishlist Saya</span>
            </nav>
            <h1 class="text-3xl lg:text-4xl font-light text-gray-900">
                Favorit Tersimpan
                <span class="text-base text-earth-500 font-normal ml-2" x-text="'(' + wishlistItems.length + ' produk)'"></span>
            </h1>
        </div>

        <div class="flex items-center gap-3" x-show="wishlistItems.length > 0">
            <button @click="clearAll()"
                    class="text-xs text-gray-400 hover:text-red-500 tracking-wider uppercase transition-colors font-medium">
                Kosongkan Wishlist
            </button>
        </div>
    </div>

    {{-- Empty State --}}
    <div x-show="wishlistItems.length === 0" x-cloak class="text-center py-24 bg-white rounded-2xl border border-earth-100 p-8 my-6">
        <div class="w-24 h-24 bg-earth-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="h-10 w-10 text-earth-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <h2 class="text-xl font-light text-gray-900 mb-2">Belum ada produk favorit</h2>
        <p class="text-sm text-gray-400 font-light max-w-md mx-auto mb-8">
            Jelajahi koleksi ramah lingkungan kami dan klik ikon hati untuk menyimpan sepatu dan aksesori impianmu.
        </p>
        <a href="{{ route('products.index') }}"
           class="inline-block bg-gray-900 text-white px-8 py-4 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors">
            Jelajahi Produk
        </a>
    </div>

    {{-- Wishlist Grid --}}
    <div x-show="wishlistItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <template x-for="item in wishlistItems" :key="item.id">
            <div class="product-card group bg-white rounded-2xl overflow-hidden border border-earth-100 shadow-sm hover:shadow-md flex flex-col justify-between">
                <div>
                    {{-- Image & Heart overlay --}}
                    <div class="relative aspect-square overflow-hidden bg-earth-50">
                        <a :href="'/products/' + item.slug" class="block w-full h-full">
                            <img :src="item.image_url" :alt="item.name"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </a>
                        <button @click="removeFromWishlist(item.id); loadLocal();"
                                class="absolute top-3 right-3 p-2 rounded-full bg-white/90 text-red-500 hover:scale-110 shadow-sm transition-all"
                                title="Hapus dari favorit">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Product Info --}}
                    <div class="p-5">
                        <p class="text-xs text-earth-500 font-light mb-1" x-text="item.category || 'Footwear'"></p>
                        <h3 class="text-sm font-semibold text-gray-900 mb-1">
                            <a :href="'/products/' + item.slug" class="hover:text-earth-700 transition-colors" x-text="item.name"></a>
                        </h3>
                        <p class="text-xs text-earth-400 font-light mb-3" x-text="item.material_type"></p>
                        <p class="text-sm font-semibold text-gray-900" x-text="item.price_formatted"></p>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="p-5 pt-0">
                    <a :href="'/products/' + item.slug"
                       class="block w-full text-center bg-gray-900 text-white py-3 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors rounded-lg">
                        Pilih Ukuran & Beli
                    </a>
                </div>
            </div>
        </template>
    </div>

</div>

@push('scripts')
<script>
function wishlistPage() {
    return {
        wishlistItems: [],
        init() {
            this.loadLocal();
            window.addEventListener('wishlist-storage-updated', () => this.loadLocal());
        },
        loadLocal() {
            try {
                this.wishlistItems = JSON.parse(localStorage.getItem('allbirds_wishlist') || '[]');
            } catch(e) {
                this.wishlistItems = [];
            }
        },
        clearAll() {
            if (confirm('Kosongkan semua daftar wishlist?')) {
                localStorage.setItem('allbirds_wishlist', JSON.stringify([]));
                this.wishlistItems = [];
                window.dispatchEvent(new CustomEvent('wishlist-updated'));
            }
        }
    }
}
</script>
@endpush
@endsection
