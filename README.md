# 🌿 Allbirds Store — Full-Stack Laravel E-Commerce Portfolio

> A clean, minimalist sustainable fashion e-commerce inspired by Allbirds.  
> Built with Laravel 12, Blade, Tailwind CSS v4, and Alpine.js.

![Laravel](https://img.shields.io/badge/Laravel-12-red?logo=laravel) 
![PHP](https://img.shields.io/badge/PHP-8.3-blue?logo=php)
![Tailwind](https://img.shields.io/badge/Tailwind-v4-teal?logo=tailwindcss)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-green?logo=alpine.js)
![SQLite](https://img.shields.io/badge/Database-SQLite-lightblue?logo=sqlite)

---

## ✨ Fitur Utama



### 💳 Update Stage 3: Integrasi Midtrans Snap & Transaksi Realistis
- 🌐 **Konfigurasi Midtrans Snap**: Konfigurasi lengkap di `config/midtrans.php` dan `.env` (Server Key, Client Key, Production/Sandbox flag).
- ⚡ **Smart Mockup Snap Simulator**: Fallback cerdas interaktif popup (QRIS & VA Bank) jika akun Sandbox belum diisi, memungkinkan pengujian alur pembayaran dan webhook tanpa hambatan.
- 🔄 **AJAX Snap Token & Callback**: Generate Snap Token via backend AJAX tanpa reload halaman, menangani status `onSuccess`, `onPending`, `onError`, `onClose`.
- 📦 **Automated Inventory & Stock Deduction**:
  - Validasi stok sebelum checkout: mencegah pembelian jika stok varian yang dipilih sudah 0.
  - Saat pembayaran terverifikasi settlement/lunas via webhook/simulator, kuantitas stok varian otomatis berkurang di database secara atomik (`DB::transaction`).
- 🔔 **Webhook Notification Handler**: Rute `POST /api/midtrans/notification` untuk menangani status transaksi settlement, capture, pending, expire, dan cancel.
- 🖨️ **Halaman Order Status & Cetak Invoice**: Halaman konfirmasi pesanan interaktif dengan status pembayaran real-time dan halaman cetak/unduh invoice print-ready (`/orders/invoice/{orderNumber}`).

### 🔐 Update Stage 2: Admin Panel & Manajemen Stok
- 🛡️ **Autentikasi Admin Terproteksi**: Rute `/admin` dengan middleware `AdminMiddleware`, akun default `admin@allbirds.test` / `password123`, login page minimalis, dan tombol switch ke public store.
- 📊 **Dashboard Overview**: Kartu metrik real-time (Total Pendapatan dari order lunas, Total Pesanan, Total Produk Aktif, Peringatan Varian Stok Menipis <5) dan tabel 5 pesanan terbaru.
- 👟 **Manajemen Katalog & Varian (CRUD)**:
  - List produk dengan total stok kalkulatif, status featured, dan filter kategori/pencarian.
  - Form tambah produk baru + varian pembuka.
  - Manajemen varian sepatu lengkap di dalam detail produk: admin dapat menambah varian baru, mengubah kuantitas stok per ukuran, hex warna, dan URL gambar.
  - Fitur hapus produk secara aman.
- 📦 **Manajemen Pesanan Masuk (Order Management)**:
  - List pesanan masuk dengan badge status dinamis (`pending`, `paid`, `processing`, `shipped`, `completed`, `cancelled`).
  - Halaman detail pesanan lengkap: data pembeli, alamat pengiriman, rincian varian produk yang dipesan, dan total pembayaran.
  - **Integrasi Stok Otomatis**: Jika status pesanan diubah ke `cancelled`, stok varian produk yang dipesan otomatis dikembalikan ke gudang penyimpanan.

### 🚀 Update Stage 1: UX Enhancements & Interactive Features
- 🔍 **Live Instant Search**: Search bar di navbar dan input pencarian langsung di halaman katalog dengan debounce.
- 🎛️ **Multi-Facet Filter**: Filter multi-dimensi berdasarkan Kategori, Tipe Material Alami, dan Ukuran Sepatu secara AJAX tanpa reload halaman.
- ❤️ **Wishlist System**: Simpan produk favorit via LocalStorage, sinkronisasi counter navbar real-time, halaman `/wishlist` mandiri dan modal slide-over drawer.
- 📊 **Dynamic Stock Indicator**: Indikator ketersediaan stok real-time saat memilih ukuran (Stok Tersedia, Stok Menipis <5, Stok Habis).
- 🎨 **Enhanced Swatch Hover**: Preview gambar varian warna secara langsung saat cursor diarahkan ke swatch.


| Fitur | Deskripsi |
|-------|-----------|
| 🏠 **Homepage** | Announcement bar, sticky navbar, hero banner, featured products, sustainable story section |
| 🎨 **Color Swatches** | Swatch interaktif yang menukar gambar produk secara real-time |
| 👟 **Product Detail** | Pilihan warna + ukuran dinamis, accordion Material/Deskripsi/Perawatan |
| 🛒 **Cart Drawer** | Slide-over cart tanpa reload halaman (Alpine.js + Fetch API) |
| 💳 **Checkout** | Form pengiriman + simulasi QRIS/Transfer Bank |
| ✅ **Order Success** | Halaman konfirmasi pesanan dengan ringkasan dan instruksi bayar |
| 🗃️ **SQLite** | Zero-config database, langsung jalan tanpa setup MySQL |

---

## 🗂️ Struktur Project

```
allbirds-store/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php        # Homepage + featured products
│   │   ├── ProductController.php     # Product listing & detail
│   │   ├── CartController.php        # Cart AJAX API
│   │   └── CheckoutController.php    # Checkout + order creation
│   └── Models/
│       ├── Category.php              # Men, Women, Accessories
│       ├── Product.php               # Produk dengan slug & material
│       ├── ProductVariant.php        # Warna × Ukuran × Stok
│       ├── Cart.php                  # Session-based cart
│       ├── Order.php                 # Order header
│       └── OrderItem.php             # Line items
├── database/
│   ├── migrations/                   # 5 migration tables
│   └── seeders/
│       └── DatabaseSeeder.php        # 8 produk + 87 varian dummy
├── resources/views/
│   ├── layouts/app.blade.php         # Layout utama + cart drawer JS
│   ├── partials/cart-drawer.blade.php
│   ├── home.blade.php                # Homepage
│   ├── products/
│   │   ├── index.blade.php           # Product listing + filter kategori
│   │   └── show.blade.php            # Product detail page
│   ├── checkout.blade.php
│   └── checkout-success.blade.php
└── routes/web.php                    # Semua route
```

---

## 🚀 Setup & Instalasi

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- Git

### Langkah Instalasi

```bash
# 1. Clone (jika dari Git)
git clone <repo-url> allbirds-store
cd allbirds-store

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database (SQLite — sudah terkonfigurasi otomatis)
php artisan migrate:fresh --seed

# 5. Install & build frontend assets
npm install
npm run build

# 6. Jalankan server
php artisan serve --port=8888
```

Buka: **http://127.0.0.1:8888**

### Development Mode (hot reload)
```bash
# Terminal 1
php artisan serve --port=8888

# Terminal 2
npm run dev
```

---

## 🎨 Design System

### Palette Warna
| Token | Hex | Penggunaan |
|-------|-----|-----------|
| `earth-50` | `#FAF8F5` | Background utama |
| `earth-100` | `#F5F0E8` | Card background |
| `earth-400` | `#C9B99A` | Borders halus |
| `earth-700` | `#806A49` | Accent text |
| `gray-900` | `#111827` | Primary buttons, navbar |
| `forest-600` | `#3A723A` | CTA sustainability |

### Typography
- Font: **Inter** (Google Fonts) — weight 300, 400, 500, 600
- Tracking: `tracking-widest` untuk label uppercase
- Scale: Tailwind default (sm=14px, base=16px, lg=18px)

---

## 📡 API Cart (AJAX)

| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/cart` | Get cart items + total |
| POST | `/cart/add` | Add item `{variant_id, quantity}` |
| PATCH | `/cart/{id}` | Update quantity `{quantity}` |
| DELETE | `/cart/{id}` | Remove item |
| GET | `/cart/count` | Badge count only |

---

## 🗃️ Database Schema

```
categories          products             product_variants
├─ id               ├─ id                ├─ id
├─ name             ├─ category_id (FK)  ├─ product_id (FK)
├─ slug             ├─ name              ├─ color_name
└─ description      ├─ slug              ├─ color_hex
                    ├─ description       ├─ size
                    ├─ material_type     ├─ stock
                    ├─ price             └─ image_url
                    └─ is_featured

carts               orders               order_items
├─ id               ├─ id                ├─ id
├─ session_id       ├─ order_number      ├─ order_id (FK)
├─ product_variant_id (FK)  ├─ customer_name  ├─ product_variant_id (FK)
└─ quantity         ├─ shipping_address  ├─ product_name
                    ├─ total             ├─ quantity
                    ├─ payment_method    ├─ price
                    └─ status            └─ subtotal
```

---

## 🛍️ Data Seeder

8 produk dummy dengan 87 varian total:

| Produk | Kategori | Harga | Warna |
|--------|----------|-------|-------|
| Tree Runner | Men | Rp 1.650.000 | 3 warna |
| Wool Runner | Men | Rp 1.850.000 | 2 warna |
| Tree Dasher | Men | Rp 1.950.000 | 2 warna |
| Tree Breezers | Women | Rp 1.450.000 | 3 warna |
| Wool Runner Go | Women | Rp 2.050.000 | 2 warna |
| Mizzle Runner | Women | Rp 1.750.000 | 2 warna |
| Merino Wool Socks | Accessories | Rp 225.000 | 2 warna |
| Shoelace Pack | Accessories | Rp 95.000 | 1 warna |

Gambar menggunakan **Unsplash** (sneakers/fashion bertema minimalis).

---

## 🌿 Tech Stack

- **Backend**: Laravel 12 (PHP 8.3)
- **Frontend**: Blade Templates + Tailwind CSS v4 + Alpine.js 3
- **Build Tool**: Vite 8 + @tailwindcss/vite plugin
- **Database**: SQLite (zero-config)
- **Images**: Unsplash (free CDN)
- **Fonts**: Inter via Google Fonts

---

## 📄 License

MIT — Free to use for portfolio, learning, and personal projects.

---

*Dibuat sebagai portfolio project full-stack Laravel. Terinspirasi dari [Allbirds.com](https://allbirds.com).*
