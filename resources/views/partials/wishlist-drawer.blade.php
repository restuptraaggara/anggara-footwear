{{-- Wishlist Slide-over Drawer --}}
<div x-show="openWishlist" x-cloak class="fixed inset-0 z-50 flex">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="openWishlist = false"></div>

    {{-- Drawer --}}
    <div class="relative ml-auto h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
         x-show="openWishlist"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-earth-100">
            <div class="flex items-center gap-2">
                <svg class="h-4 w-4 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                </svg>
                <h2 class="text-sm font-semibold tracking-widest uppercase">Wishlist (<span x-text="wishlistCount">0</span>)</h2>
            </div>
            <button @click="openWishlist = false" class="text-gray-400 hover:text-gray-900 transition-colors p-1">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">

            {{-- Empty --}}
            <div x-show="wishlistItems.length === 0" class="flex flex-col items-center justify-center h-full text-center py-16">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="h-8 w-8 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-800 mb-2">Wishlist kamu masih kosong</p>
                <p class="text-xs text-gray-400 mb-8 font-light">Klik ikon ♡ pada produk untuk menyimpan favorit kamu.</p>
                <button @click="openWishlist = false"
                        class="bg-gray-900 text-white px-8 py-3 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors">
                    Jelajahi Produk
                </button>
            </div>

            {{-- Wishlist Items --}}
            <div x-show="wishlistItems.length > 0" class="space-y-4">
                <template x-for="item in wishlistItems" :key="item.id">
                    <div class="flex gap-4 p-3 rounded-xl border border-earth-100 hover:border-earth-200 transition-colors group">
                        {{-- Image --}}
                        <a :href="'/products/' + item.slug" class="block flex-shrink-0">
                            <div class="w-20 h-20 bg-earth-50 rounded-lg overflow-hidden">
                                <img :src="item.image_url" :alt="item.name"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <a :href="'/products/' + item.slug">
                                <p class="text-sm font-semibold text-gray-900 hover:text-earth-700 transition-colors truncate" x-text="item.name"></p>
                            </a>
                            <p class="text-xs text-earth-500 font-light mt-0.5" x-text="item.material_type"></p>
                            <p class="text-sm font-semibold text-gray-800 mt-1" x-text="item.price_formatted"></p>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 mt-2">
                                <button @click="addWishlistItemToCart(item)"
                                        class="flex-1 bg-gray-900 text-white py-1.5 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors rounded">
                                    + Keranjang
                                </button>
                                <button @click="removeFromWishlist(item.id)"
                                        class="p-1.5 text-gray-300 hover:text-red-400 transition-colors rounded border border-transparent hover:border-red-200"
                                        title="Hapus dari wishlist">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div x-show="wishlistItems.length > 0" class="border-t border-earth-100 px-6 py-4">
            <a href="{{ route('wishlist') }}" @click="openWishlist = false"
               class="block w-full border border-gray-200 text-gray-600 text-center py-3 text-xs tracking-widest uppercase font-medium hover:border-gray-900 hover:text-gray-900 transition-colors">
                Lihat Semua Wishlist
            </a>
        </div>
    </div>
</div>