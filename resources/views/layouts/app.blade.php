<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Allbirds Store — Naturally Comfortable')</title>
    {{-- Vite compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Additional styles not in Tailwind */
        [x-cloak] { display: none !important; }
        .cart-drawer-enter { transform: translateX(100%); }
        .cart-drawer-enter-end { transform: translateX(0); }
    </style>
    @stack('styles')
</head>
<body x-data="cartApp()" @cart-updated.window="refreshCart()" @add-to-cart.window="addToCart($event.detail.variantId)">

    {{-- Announcement Bar --}}
    <div class="bg-gray-900 text-earth-200 text-center py-2.5 px-4 text-xs tracking-widest font-light">
        🌿 &nbsp; GRATIS ONGKIR untuk pembelian di atas Rp 500.000 &nbsp;|&nbsp; Dibuat dari bahan alami terbarukan &nbsp; 🌿
    </div>

    {{-- Sticky Navbar --}}
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-earth-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" class="text-xl font-semibold tracking-widest uppercase text-gray-900">
                    Allbirds
                </a>
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('products.index', ['category' => 'men']) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 tracking-wide transition-colors">Men</a>
                    <a href="{{ route('products.index', ['category' => 'women']) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 tracking-wide transition-colors">Women</a>
                    <a href="{{ route('products.index', ['category' => 'accessories']) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 tracking-wide transition-colors">Accessories</a>
                    <a href="{{ route('products.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 tracking-wide transition-colors">All Products</a>
                </div>
                <div class="flex items-center gap-4">
                    <button @click="openCart = true" class="relative text-gray-600 hover:text-gray-900 transition-colors p-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span x-show="cartCount > 0" x-cloak x-text="cartCount"
                              class="absolute -top-1 -right-1 bg-gray-900 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center font-medium"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Cart Drawer --}}
    @include('partials.cart-drawer')

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
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-earth-500 font-light">© {{ date('Y') }} Allbirds Store — Portfolio Project</p>
                <span class="text-xs text-earth-500">🌿 Carbon Neutral Certified</span>
            </div>
        </div>
    </footer>

    <script>
    function cartApp() {
        return {
            openCart: false,
            cartCount: 0,
            cartItems: [],
            cartTotal: 'Rp 0',
            loading: false,
            init() { this.refreshCart(); },
            async refreshCart() {
                try {
                    const res = await fetch('/cart', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
                    const data = await res.json();
                    this.cartCount = data.count;
                    this.cartItems = data.items;
                    this.cartTotal = data.total_formatted;
                } catch(e) {}
            },
            async addToCart(variantId, quantity = 1) {
                this.loading = true;
                try {
                    const res = await fetch('/cart/add', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ variant_id: variantId, quantity })
                    });
                    const data = await res.json();
                    if (res.ok) { this.cartCount = data.cart_count; await this.refreshCart(); this.openCart = true; }
                    else { alert(data.error || 'Gagal menambahkan ke keranjang'); }
                } finally { this.loading = false; }
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
            }
        }
    }
    </script>
    @stack('scripts')
</body>
</html>