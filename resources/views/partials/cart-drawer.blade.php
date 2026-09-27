{{-- Slide-Over Cart Drawer --}}
<div x-show="openCart" 
     x-cloak 
     class="fixed inset-0 z-50 overflow-hidden" 
     aria-labelledby="slide-over-title" 
     role="dialog" 
     aria-modal="true">
    
    {{-- Background Backdrop --}}
    <div x-show="openCart" 
         x-transition:enter="ease-in-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="openCart = false"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="openCart" 
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="w-screen max-w-md bg-white text-gray-900 shadow-2xl flex flex-col justify-between">
            
            {{-- Drawer Header --}}
            <div class="p-6 border-b border-earth-100 flex items-center justify-between bg-[#FAF8F5]">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🛍️</span>
                    <div>
                        <h2 class="text-sm font-bold tracking-wider uppercase text-gray-900" id="slide-over-title">
                            Keranjang Belanja
                        </h2>
                        <p class="text-[11px] text-gray-500 font-light" x-text="cartCount + ' pasang sepatu pilihan'"></p>
                    </div>
                </div>
                <button type="button" 
                        @click="openCart = false"
                        class="p-2 text-gray-400 hover:text-gray-900 text-lg font-bold transition-colors">
                    ✕
                </button>
            </div>

            {{-- Free shipping progress bar --}}
            <div class="bg-forest-50 px-6 py-2.5 border-b border-forest-100 text-xs">
                <template x-if="cartCount > 0">
                    <p class="text-forest-800 font-medium flex items-center gap-1.5">
                        <span>🌿</span> <strong>Gratis Ongkir</strong> & Garansi Pengembalian 30 Hari
                    </p>
                </template>
                <template x-if="cartCount === 0">
                    <p class="text-gray-500 font-light">
                        Pilih sepatu favoritmu untuk memulai langkah alami.
                    </p>
                </template>
            </div>

            {{-- Drawer Body: Items List --}}
            <div class="flex-1 overflow-y-auto p-6 divide-y divide-earth-100">
                
                {{-- Empty State --}}
                <div x-show="cartItems.length === 0" class="text-center py-20 space-y-4">
                    <p class="text-5xl">👟</p>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Keranjang Belanja Kosong</h3>
                    <p class="text-xs text-gray-500 font-light max-w-xs mx-auto">
                        Belum ada item di keranjang. Temukan koleksi sepatu wol & serat eucalyptus kami.
                    </p>
                    <a href="{{ route('products.index') }}" 
                       @click="openCart = false"
                       class="inline-block px-6 py-3 bg-gray-900 text-white rounded-xl text-xs uppercase font-semibold tracking-wider hover:bg-forest-800 transition-colors shadow-sm">
                        Jelajahi Katalog →
                    </a>
                </div>

                {{-- Items Loop --}}
                <div x-show="cartItems.length > 0" class="space-y-4">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="pt-4 first:pt-0 flex gap-4 items-center">
                            <div class="w-16 h-16 rounded-xl bg-earth-50 overflow-hidden flex-shrink-0 border border-earth-100">
                                <img :src="item.image_url" :alt="item.product_name" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-gray-900 truncate" x-text="item.product_name"></h4>
                                <p class="text-[11px] text-gray-500" x-text="item.color_name + ' • Size ' + item.size"></p>
                                <p class="text-xs font-bold text-forest-800 mt-0.5" x-text="item.subtotal_formatted"></p>
                                
                                {{-- Qty controls --}}
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="inline-flex items-center border border-earth-200 rounded-lg overflow-hidden text-xs bg-earth-50/50">
                                        <button type="button" 
                                                @click="updateQuantity(item.id, item.quantity - 1)" 
                                                class="px-2.5 py-1 hover:bg-earth-200 font-bold text-gray-700 transition-colors">−</button>
                                        <span class="px-2.5 py-1 font-semibold text-gray-900 font-mono" x-text="item.quantity"></span>
                                        <button type="button" 
                                                @click="updateQuantity(item.id, item.quantity + 1)" 
                                                class="px-2.5 py-1 hover:bg-earth-200 font-bold text-gray-700 transition-colors">+</button>
                                    </div>
                                    <button type="button" 
                                            @click="removeItem(item.id)" 
                                            class="text-[11px] text-red-500 hover:text-red-700 font-semibold underline underline-offset-2">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Drawer Footer --}}
            <div x-show="cartItems.length > 0" class="p-6 border-t border-earth-100 bg-[#FAF8F5] space-y-3">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-mono font-medium text-gray-900" x-text="cartSubtotal"></span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Biaya Pengiriman</span>
                        <span class="font-medium text-forest-700" x-text="cartShipping"></span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-earth-200">
                        <span>Total Tagihan</span>
                        <span class="text-forest-800 font-mono" x-text="cartTotal"></span>
                    </div>
                </div>

                {{-- Action 1: Website Checkout --}}
                <a href="{{ route('checkout') }}" 
                   class="block w-full py-3.5 bg-gray-900 hover:bg-forest-800 text-white text-center text-xs tracking-widest uppercase font-bold rounded-xl transition-all shadow-md hover:shadow-lg">
                    Lanjut ke Pembayaran Web →
                </a>

                {{-- Action 2: WhatsApp Quick Order --}}
                <a :href="getCartWhatsAppUrl()"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="block w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-center text-xs tracking-wider uppercase font-semibold rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5">
                    <span>💬 Pesan via WhatsApp</span>
                </a>
            </div>

        </div>
    </div>
</div>
