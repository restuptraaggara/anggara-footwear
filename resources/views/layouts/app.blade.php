<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Anggara Footwear — Sustainable Comfort')</title>

    {{-- Vite compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="relative min-h-screen bg-[#0d1712] text-gray-100 antialiased selection:bg-emerald-500 selection:text-white" x-data="appState()"
      @cart-updated.window="refreshCart()"
      @add-to-cart.window="addToCart($event.detail.variantId)"
      @wishlist-updated.window="loadWishlist()">
    {{-- Global Aurora WebGL Background --}}
    <div id="aurora-background" class="fixed inset-0 pointer-events-none -z-10 overflow-hidden"></div>


    {{-- Announcement Bar --}}
    <div class="bg-gray-900 text-earth-200 text-center py-2.5 px-4 text-xs tracking-widest font-light">
        🌿 &nbsp; GRATIS ONGKIR untuk pembelian di atas Rp 500.000 &nbsp;|&nbsp; Dibuat dari bahan alami terbarukan &nbsp; 🌿
    </div>

    {{-- Sticky Navbar --}}
    <nav class="sticky top-0 z-40 bg-gray-950/80 backdrop-blur-md border-b border-emerald-950/50 text-white" x-data="{ searchOpen: false }">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="text-xl font-semibold tracking-widest uppercase text-white flex-shrink-0">
                    Allbirds
                </a>

                {{-- Nav Links (hidden when search open) --}}
                <div class="hidden md:flex items-center gap-8" x-show="!searchOpen" x-transition>
                    <a href="{{ route('products.index', ['category' => 'men']) }}"
                       class="text-sm font-medium text-gray-300 hover:text-white tracking-wide transition-colors">Men</a>
                    <a href="{{ route('products.index', ['category' => 'women']) }}"
                       class="text-sm font-medium text-gray-300 hover:text-white tracking-wide transition-colors">Women</a>
                    <a href="{{ route('products.index', ['category' => 'accessories']) }}"
                       class="text-sm font-medium text-gray-300 hover:text-white tracking-wide transition-colors">Accessories</a>
                    <a href="{{ route('products.index') }}"
                       class="text-sm font-medium text-gray-300 hover:text-white tracking-wide transition-colors">All Products</a>
                </div>

                {{-- Search bar (expands inline) --}}
                <div class="flex-1 mx-4" x-show="searchOpen" x-cloak x-transition
                     x-data="navSearch()" @click.outside="searchOpen = false; query = ''; results = []">
                    <div class="relative">
                        <input x-ref="searchInput" x-model="query" @input.debounce.300ms="fetchResults()"
                               @keydown.escape="searchOpen = false; query = ''; results = []"
                               type="text" placeholder="Cari produk atau material…"
                               class="w-full border-b-2 border-gray-900 bg-transparent py-2 pr-8 text-sm text-gray-900 placeholder-gray-400 focus:outline-none">
                        <button @click="searchOpen = false; query = ''; results = []"
                                class="absolute right-0 top-2 text-gray-400 hover:text-gray-900">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>

                        {{-- Dropdown results --}}
                        <div x-show="results.length > 0" x-cloak
                             class="absolute top-full left-0 right-0 mt-1 bg-white shadow-xl border border-earth-100 rounded-b-xl z-50 overflow-hidden">
                            <template x-for="item in results" :key="item.slug">
                                <a :href="item.url"
                                   class="flex items-center gap-3 px-4 py-3 hover:bg-earth-50 transition-colors border-b border-earth-50 last:border-0">
                                    <img :src="item.image_url" :alt="item.name"
                                         class="w-10 h-10 object-cover rounded bg-earth-100 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate" x-text="item.name"></p>
                                        <p class="text-xs text-earth-500 font-light" x-text="item.material_type"></p>
                                    </div>
                                    <span class="text-sm font-semibold text-white flex-shrink-0" x-text="item.price_formatted"></span>
                                </a>
                            </template>
                            <a href="/products" @click.prevent="goSearch()"
                               class="block px-4 py-3 text-xs text-center text-earth-600 hover:text-gray-900 font-medium tracking-wide">
                                Lihat semua hasil untuk "<span x-text="query"></span>" →
                            </a>
                        </div>
                        {{-- No results --}}
                        <div x-show="query.length >= 2 && results.length === 0 && !loading" x-cloak
                             class="absolute top-full left-0 right-0 mt-1 bg-white shadow-xl border border-earth-100 rounded-b-xl z-50 px-4 py-6 text-center">
                            <p class="text-sm text-gray-400 font-light">Tidak ada produk ditemukan untuk "<span x-text="query"></span>"</p>
                        </div>
                    </div>
                </div>

                {{-- Right Icons --}}
                <div class="flex items-center gap-1">
                    {{-- Search toggle --}}
                    <button @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.searchInput?.focus())"
                            class="p-2 text-gray-600 hover:text-gray-900 transition-colors rounded-lg hover:bg-earth-50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    {{-- Wishlist icon --}}
                    <a href="{{ route('wishlist') }}"
                       class="relative p-2 text-gray-600 hover:text-red-500 transition-colors rounded-lg hover:bg-earth-50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span x-show="wishlistCount > 0" x-cloak x-text="wishlistCount"
                              class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-medium leading-none">
                        </span>
                    </a>

                    {{-- Cart --}}
                    <button @click="openCart = true"
                            class="relative p-2 text-gray-600 hover:text-gray-900 transition-colors rounded-lg hover:bg-earth-50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-show="cartCount > 0" x-cloak x-text="cartCount"
                              class="absolute -top-0.5 -right-0.5 bg-gray-900 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-medium leading-none">
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </nav>

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')

    {{-- Wishlist Drawer --}}
    @include('partials.wishlist-drawer')

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-3 text-sm">{{ session('error') }}</div>
    @endif

    <main>@yield('content')</main>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-earth-300 mt-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div>
                    <p class="text-xl font-semibold tracking-widest uppercase text-white mb-4">Allbirds</p>
                    <p class="text-sm text-earth-400 leading-relaxed font-light">Dibuat dari bahan alami, dirancang untuk kenyamanan maksimal, berkomitmen pada masa depan yang berkelanjutan.</p>
                </div>
                <div>
                    <h3 class="text-xs tracking-widest uppercase text-earth-500 mb-4 font-medium">Produk</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index', ['category' => 'men']) }}" class="hover:text-white transition-colors">Men</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'women']) }}" class="hover:text-white transition-colors">Women</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'accessories']) }}" class="hover:text-white transition-colors">Accessories</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs tracking-widest uppercase text-earth-500 mb-4 font-medium">Perusahaan</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sustainability</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs tracking-widest uppercase text-earth-500 mb-4 font-medium">Bantuan</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Pengiriman & Retur</a></li>
                        <li><a href="{{ route('wishlist') }}" class="hover:text-white transition-colors">Wishlist Saya</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-earth-500 font-light">© {{ date('Y') }} Anggara Footwear — Sustainable Comfort</p>
                <span class="text-xs text-earth-500">🌿 Carbon Neutral Certified</span>
            </div>
        </div>
    </footer>

    {{-- =========================================================
         GLOBAL APP STATE (Alpine.js)
         Manages: Cart + Wishlist
    ========================================================= --}}
    <script>
    function appState() {
        return {
        getCartWhatsAppUrl() {
            const waNumber = '{{ config('services.whatsapp.number', '6281234567890') }}';
            if (!this.cart || !this.cart.items || this.cart.items.length === 0) {
                return `https://wa.me/${waNumber}?text=${encodeURIComponent('Halo Anggara Footwear, saya mau tanya tentang koleksi sepatu ramah lingkungan.')}`;
            }

            let msg = 'Halo Anggara Footwear, saya mau pesan:\n';
            this.cart.items.forEach(item => {
                msg += `• ${item.product_name} - Warna: ${item.color_name} - Size: ${item.size} (Qty: ${item.quantity}) - ${item.formatted_subtotal}\n`;
            });
            msg += `\nTotal: ${this.cart.formatted_total || ''}\nMohon info ketersediaan stok dan cara pembayaran. Terima kasih!`;
            return `https://wa.me/${waNumber}?text=${encodeURIComponent(msg)}`;
        },

            // Cart state
            openCart: false,
            cartCount: 0,
            cartItems: [],
            cartTotal: 'Rp 0',
            cartLoading: false,

            // Wishlist state
            openWishlist: false,
            wishlistItems: [],
            wishlistCount: 0,

            init() {
                this.refreshCart();
                this.loadWishlist();
            },

            // ── CART ──────────────────────────────────────────
            async refreshCart() {
                try {
                    const res = await fetch('/cart', {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const data = await res.json();
                    this.cartCount = data.count;
                    this.cartItems = data.items;
                    this.cartTotal = data.total_formatted;
                } catch(e) {}
            },

            async addToCart(variantId, quantity = 1) {
                this.cartLoading = true;
                try {
                    const res = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ variant_id: variantId, quantity })
                    });
                    const data = await res.json();
                    if (res.ok) {
                        this.cartCount = data.cart_count;
                        await this.refreshCart();
                        this.openCart = true;
                    } else {
                        alert(data.error || 'Gagal menambahkan ke keranjang');
                    }
                } finally {
                    this.cartLoading = false;
                }
            },

            async updateQuantity(cartId, quantity) {
                const res = await fetch(`/cart/${cartId}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ quantity })
                });
                const data = await res.json();
                this.cartItems = data.items; this.cartTotal = data.total_formatted; this.cartCount = data.count;
            },

            async removeItem(cartId) {
                const res = await fetch(`/cart/${cartId}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                const data = await res.json();
                this.cartItems = data.items; this.cartTotal = data.total_formatted; this.cartCount = data.count;
            },

            // ── WISHLIST (LocalStorage) ───────────────────────
            loadWishlist() {
                try {
                    this.wishlistItems = JSON.parse(localStorage.getItem('allbirds_wishlist') || '[]');
                    this.wishlistCount = this.wishlistItems.length;
                } catch(e) {
                    this.wishlistItems = [];
                    this.wishlistCount = 0;
                }
            },

            saveWishlist() {
                localStorage.setItem('allbirds_wishlist', JSON.stringify(this.wishlistItems));
                this.wishlistCount = this.wishlistItems.length;
                window.dispatchEvent(new CustomEvent('wishlist-storage-updated'));
            },

            isInWishlist(productId) {
                return this.wishlistItems.some(item => item.id === productId);
            },

            toggleWishlist(product) {
                const idx = this.wishlistItems.findIndex(item => item.id === product.id);
                if (idx >= 0) {
                    this.wishlistItems.splice(idx, 1);
                } else {
                    this.wishlistItems.push(product);
                }
                this.saveWishlist();
            },

            removeFromWishlist(productId) {
                this.wishlistItems = this.wishlistItems.filter(item => item.id !== productId);
                this.saveWishlist();
            },

            addWishlistItemToCart(item) {
                // Add first variant of wishlist product to cart
                if (item.first_variant_id) {
                    this.addToCart(item.first_variant_id, 1);
                    this.openWishlist = false;
                    this.openCart = true;
                }
            },

            formatPrice(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            }
        };
    }

    // ── NAVBAR SEARCH ─────────────────────────────────────────
    function navSearch() {
        return {
            query: '',
            results: [],
            loading: false,

            async fetchResults() {
                if (this.query.length < 2) { this.results = []; return; }
                this.loading = true;
                try {
                    const res = await fetch(`/products/search?q=${encodeURIComponent(this.query)}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.results = data.results || [];
                } finally {
                    this.loading = false;
                }
            },

            goSearch() {
                window.location.href = `/products?search=${encodeURIComponent(this.query)}`;
            }
        };
    }
    </script>

    @stack('scripts')
</body>
</html>