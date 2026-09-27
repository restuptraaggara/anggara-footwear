{{-- Cart Drawer --}}
<div x-show="openCart" x-cloak class="fixed inset-0 z-50 flex">
    {{-- Overlay --}}
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="openCart = false"></div>

    {{-- Drawer --}}
    <div class="relative ml-auto h-full w-full max-w-md bg-white shadow-2xl flex flex-col"
         x-show="openCart"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-earth-100">
            <h2 class="text-sm font-semibold tracking-widest uppercase">Keranjang (<span x-text="cartCount">0</span>)</h2>
            <button @click="openCart = false" class="text-gray-400 hover:text-gray-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Items --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">

            {{-- Empty State --}}
            <div x-show="cartItems.length === 0" class="flex flex-col items-center justify-center h-full text-center py-16">
                <div class="text-6xl mb-6">🛒</div>
                <p class="text-sm font-medium text-gray-800 mb-2">Keranjang kamu kosong</p>
                <p class="text-xs text-gray-400 mb-8 font-light">Mulai jelajahi koleksi kami yang dibuat dari bahan alami.</p>
                <button @click="openCart = false"
                        class="bg-gray-900 text-white px-8 py-3 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors">
                    Mulai Belanja
                </button>
            </div>

            {{-- Cart Items --}}
            <div x-show="cartItems.length > 0" class="space-y-5">
                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex gap-4">
                        {{-- Image --}}
                        <div class="w-20 h-20 bg-earth-50 rounded-sm overflow-hidden flex-shrink-0">
                            <img :src="item.image_url" :alt="item.product_name"
                                 class="w-full h-full object-cover" loading="lazy">
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate" x-text="item.product_name"></p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                <span x-text="item.color_name"></span> &bull; <span x-text="'Size ' + item.size"></span>
                            </p>
                            <p class="text-sm font-medium text-gray-800 mt-1" x-text="item.price_formatted"></p>
                            {{-- Qty Controls --}}
                            <div class="flex items-center gap-2 mt-2">
                                <button @click="updateQuantity(item.id, item.quantity - 1)"
                                        class="w-6 h-6 border border-gray-200 rounded flex items-center justify-center text-gray-500 hover:border-gray-900 hover:text-gray-900 transition-colors text-sm">−</button>
                                <span class="text-sm w-6 text-center" x-text="item.quantity"></span>
                                <button @click="updateQuantity(item.id, item.quantity + 1)"
                                        class="w-6 h-6 border border-gray-200 rounded flex items-center justify-center text-gray-500 hover:border-gray-900 hover:text-gray-900 transition-colors text-sm">+</button>
                                <button @click="removeItem(item.id)"
                                        class="ml-auto text-gray-300 hover:text-red-400 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Footer --}}
        <div x-show="cartItems.length > 0" class="border-t border-earth-100 px-6 py-5 space-y-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 font-light">Subtotal</span>
                <span class="font-semibold text-gray-900" x-text="cartTotal"></span>
            </div>
            <p class="text-xs text-gray-400 font-light">Ongkos kirim & pajak dihitung saat checkout.</p>
            <a href="/checkout"
               class="block w-full bg-gray-900 text-white text-center py-4 text-xs tracking-widest uppercase font-medium hover:bg-earth-900 transition-colors">
                Lanjut Checkout
            </a>
            <button @click="openCart = false"
                    class="block w-full border border-gray-200 text-gray-600 text-center py-3 text-xs tracking-widest uppercase font-medium hover:border-gray-900 hover:text-gray-900 transition-colors">
                Lanjut Belanja
            </button>
        </div>
    </div>
</div>