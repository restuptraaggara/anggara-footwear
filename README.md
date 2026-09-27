# 🌿 Allbirds Store — Full-Stack Sustainable E-Commerce Platform

<p align="center">
  <img src="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=1200&q=80" alt="Allbirds Store Banner" width="100%" style="border-radius: 16px; max-height: 400px; object-fit: cover;" />
</p>

<p align="center">
  <strong>A modern, minimalist, carbon-neutral inspired fashion e-commerce application.</strong><br>
  Crafted with Laravel 12, Blade, Tailwind CSS v4, Alpine.js 3, Midtrans Snap Payment Gateway, and SQLite.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3" />
  <img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS v4" />
  <img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white" alt="Alpine.js 3" />
  <img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 8" />
  <img src="https://img.shields.io/badge/Midtrans-Snap_3DS-002B49?style=for-the-badge&logo=googlepay&logoColor=white" alt="Midtrans Snap" />
  <img src="https://img.shields.io/badge/Database-SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white" alt="SQLite" />
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License" />
</p>

---

## 📖 Ringkasan Proyek (Overview)

**Allbirds Store** adalah aplikasi web e-commerce *full-stack* yang terinspirasi dari filosofi desain **Allbirds** — mengusung estetika *clean minimalist*, *earthy warm palette*, *spacious whitespace*, dan fokus pada produk fashion ramah lingkungan (*sustainable fashion*).

Aplikasi ini dibangun dari nol (*scratch*) dengan arsitektur modern yang mencakup:
1. **Storefront Interaktif (Frontend):** Katalog multi-facet, instant live search, swatch warna dinamis, preview hover gambar, dan slide-over cart/wishlist drawers tanpa reload halaman.
2. **Payment Gateway Integration:** Terintegrasi dengan **Midtrans Snap 3DS** (QRIS & Virtual Account) lengkap dengan **Smart Mockup Simulator** untuk pengujian lokal yang mulus.
3. **Automated Inventory Engine:** Pengurangan stok otomatis secara atomik saat pembayaran sukses (*settlement/paid*) dan pengembalian stok saat order dibatalkan (*cancelled*).
4. **Admin Back-Office Panel (`/admin`):** Dashboard analitik ringkasan metrik pendapatan, pemantauan varian stok menipis, manajemen CRUD produk & varian warna/ukuran, serta pemrosesan status pesanan.

---

## ✨ Fitur-Fitur Utama (Key Features)

### 🛍️ 1. Storefront & Pengalaman Pengguna (UX)
- **Minimalist Sticky Navigation & Announcement Bar:** Menampilkan promo gratis ongkir, navigasi kategori, live search trigger, counter wishlist, dan drawer keranjang.
- **Hero Banner & Storytelling Section:** Bagian cerita keberlanjutan (*ZQ Merino Wool, Serat Eucalyptus, SweetFoam® Tebu*) dengan metrik jejak karbon terverifikasi.
- **Interactive Color Swatches & Hover Preview:** Mengarahkan kursor ke swatch warna langsung mengganti gambar produk secara real-time.
- **Live Search & Multi-Facet AJAX Filter:**
  - Pencarian instan dengan debounce 300ms.
  - Filter kategori (*Men, Women, Accessories*), tipe material alami, dan ukuran sepatu (*36–45, S/M, M/L*) via AJAX tanpa reload halaman (`window.history.replaceState`).
  - Tombol reset filter instan dan badge counter hasil pencarian.
- **Product Detail Page (PDP):**
  - Pilihan warna interaktif + pemilih ukuran sepatu.
  - **Dynamic Stock Indicator:** Menampilkan status stok real-time (*Stok Tersedia, Stok Menipis < 5 pasang, atau Stok Habis*).
  - Accordion informasi bahan material, spesifikasi, dan petunjuk perawatan.
  - Rekomendasi produk terkait (*You Might Also Like*).

### 🛒 2. Keranjang & Wishlist (Slide-Over Drawers)
- **Slide-Over Cart Drawer:** Menggunakan Alpine.js & Fetch API untuk mengelola keranjang secara *seamless*:
  - Tambah varian ukuran & warna ke keranjang secara asinkron.
  - Pengaturan kuantitas (+ / −) dan hapus item secara instan.
  - Badge counter keranjang yang tersinkronisasi di seluruh tab.
- **Wishlist System (Client-side Persistence):**
  - Toggle ikon hati di kartu produk dan halaman detail.
  - Data tersimpan persisten di `localStorage`.
  - Halaman khusus `/wishlist` dengan aksi cepat *"Pilih Ukuran & Beli"*.

### 💳 3. Pembayaran Midtrans Snap & Transaksi Realistis
- **Integrasi Midtrans Snap JS (Sandbox & Production Ready):**
  - Generate Snap Token via backend AJAX saat checkout.
  - Popup Snap modal muncul tanpa meninggalkan halaman checkout.
  - Mendukung handler callback: `onSuccess`, `onPending`, `onError`, `onClose`.
- **⚡ Smart Mockup Snap Simulator (Zero-Config Fallback):**
  - Jika kredensial Sandbox belum diisi atau offline, sistem otomatis mengaktifkan popup simulator Snap khas Midtrans (QRIS & Virtual Account BCA/Mandiri).
  - Tombol *"⚡ Simulasi Bayar Sukses"* untuk memicu webhook internal dan menguji alur pembayaran secara instan.
- **Webhook & Inventaris Atomik:**
  - Endpoint `POST /api/midtrans/notification` untuk menerima status `settlement`, `capture`, `pending`, `expire`, `cancel`.
  - **Atomic Stock Deduction:** Kuantitas stok varian sepatu otomatis dipotong di database saat pembayaran terverifikasi lunas.
  - **Pre-Checkout Stock Guard:** Mencegah pembuatan order jika varian yang dipilih sudah habis (*out of stock*).
- **Konfirmasi Pesanan & Cetak Invoice:**
  - Halaman status pesanan `/checkout/success/{orderNumber}` dengan badge status real-time.
  - Halaman cetak invoice resmi `/orders/invoice/{orderNumber}` print-friendly siap unduh ke PDF (`window.print()`).

### 🔐 4. Back-Office Admin Panel (`/admin`)
- **Autentikasi Terproteksi:** Rute grup `/admin` dilindungi oleh `AdminMiddleware`.
- **Dashboard Overview Analitik:**
  - 💰 *Total Pendapatan:* Akumulasi transaksi berstatus lunas (*paid/completed*).
  - 📦 *Total Pesanan:* Total order masuk beserta badge pesanan pending.
  - 👟 *Katalog Aktif:* Jumlah model sepatu yang aktif.
  - ⚠️ *Peringatan Stok Menipis:* Menghitung varian dengan sisa stok < 5 pasang.
  - Tabel 5 pesanan masuk terbaru.
- **Katalog & Manajemen Varian (CRUD):**
  - Tabel produk dengan kalkulasi total stok seluruh varian dan filter kategori.
  - Form tambah produk baru + varian pembuka.
  - **Variant Manager Terintegrasi:** Admin dapat menambah kombinasi warna, color hex picker, ukuran sepatu, stok kuantitas, dan URL gambar per varian.
- **Manajemen Pesanan (Order Processing):**
  - Filter pesanan berdasarkan status (`pending`, `paid`, `processing`, `shipped`, `completed`, `cancelled`).
  - Halaman detail order invoice lengkap dengan data kontak & alamat pembeli.
  - **Automated Restock:** Jika admin mengubah status pesanan ke `CANCELLED`, stok varian produk yang dipesan otomatis dikembalikan ke gudang.

---

## 🗃️ Arsitektur Database (Database Schema)

Aplikasi menggunakan **SQLite** (*zero-configuration*) dengan relasi tabel sebagai berikut:

```
┌─────────────────┐       ┌─────────────────┐       ┌──────────────────────┐
│   categories    │       │    products     │       │   product_variants   │
├─────────────────┤       ├─────────────────┤       ├──────────────────────┤
│ id (PK)         │◄──┐   │ id (PK)         │◄──┐   │ id (PK)              │
│ name            │   └───│ category_id(FK) │   └───│ product_id (FK)      │
│ slug (unique)   │       │ name            │       │ color_name           │
│ description     │       │ slug (unique)   │       │ color_hex            │
└─────────────────┘       │ description     │       │ size                 │
                          │ material_type   │       │ stock                │
                          │ price           │       │ image_url            │
                          │ is_featured     │       └──────────────────────┘
                          └─────────────────┘                  ▲
                                                               │
┌─────────────────┐       ┌─────────────────┐                  │
│      users      │       │      carts      │                  │
├─────────────────┤       ├─────────────────┤                  │
│ id (PK)         │       │ id (PK)         │                  │
│ name            │       │ session_id      │                  │
│ email (unique)  │       │ product_variant_id (FK) ───────────┤
│ password        │       │ quantity        │                  │
│ is_admin (bool) │       └─────────────────┘                  │
└─────────────────┘                                            │
                                                               │
┌─────────────────────────────────┐       ┌────────────────────┴─────────┐
│             orders              │       │         order_items          │
├─────────────────────────────────┤       ├──────────────────────────────┤
│ id (PK)                         │◄──┐   │ id (PK)                      │
│ order_number (unique)           │   └───│ order_id (FK)                │
│ customer_name                   │       │ product_variant_id (FK)      │
│ customer_email, customer_phone  │       │ product_name                 │
│ shipping_address, city, postal  │       │ variant_color, variant_size  │
│ subtotal, shipping_cost, total  │       │ quantity                     │
│ payment_method, snap_token      │       │ price                        │
│ payment_type, payment_status    │       │ subtotal                     │
│ status, paid_at, notes          │       └──────────────────────────────┘
└─────────────────────────────────┘
```

---

## 🚀 Panduan Instalasi Lokal (Local Setup)

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### 1. Prasyarat Sistem
- **PHP:** Versi 8.2 atau lebih tinggi (dengan ekstensi `pdo_sqlite`, `mbstring`, `openssl`, `curl`)
- **Composer:** Versi 2.x
- **Node.js & npm:** Versi 18+ (Node 20+ direkomendasikan)
- **Git**

### 2. Kloning Repositori & Instal Dependensi
```bash
# Clone repositori
git clone https://github.com/username/allbirds-store.git
cd allbirds-store

# Instal dependensi PHP
composer install

# Instal dependensi Frontend (Tailwind v4, Vite, Alpine.js)
npm install
```

### 3. Konfigurasi Environment (`.env`)
```bash
# Salin file environment
cp .env.example .env

# Generate Application Encryption Key
php artisan key:generate
```

Pastikan konfigurasi database di file `.env` menggunakan SQLite:
```ini
DB_CONNECTION=sqlite
# Konfigurasi Midtrans (Opsional, fallback otomatis aktif jika kosong)
MIDTRANS_SERVER_KEY=SB-Mid-server-demo-key
MIDTRANS_CLIENT_KEY=SB-Mid-client-demo-key
MIDTRANS_IS_PRODUCTION=false
```

### 4. Migrasi Database & Seeding Data Dummy
Jalankan migrasi untuk membuat tabel dan mengisi 8 produk sepatu, 87 varian ukuran/warna, dan akun administrator:
```bash
php artisan migrate:fresh --seed
```

### 5. Build Aset Frontend
```bash
# Build untuk mode produksi
npm run build

# ATAU jalankan dev server jika ingin mengedit tampilan secara live
npm run dev
```

### 6. Jalankan Server Lokal
```bash
php artisan serve --port=8888
```

Akses aplikasi di browser:
- 🌐 **Storefront Publik:** [http://127.0.0.1:8888](http://127.0.0.1:8888)
- 🔐 **Admin Dashboard:** [http://127.0.0.1:8888/admin](http://127.0.0.1:8888/admin)

---

## 🔑 Kredensial Akun Administrator

| Parameter | Kredensial Default |
|-----------|--------------------|
| **URL Login** | `/admin/login` |
| **Email** | `admin@allbirds.test` |
| **Password** | `password123` |
| **Role** | Super Administrator (`is_admin = true`) |

---

## 📦 Data Seeder Awal (Katalog Dummy)

Tersedia **8 model produk** dengan **87 kombinasi varian warna & ukuran** siap uji:

| No | Nama Produk | Kategori | Harga Satuan | Material Utama | Varian Warna |
|:--:|-------------|:--------:|:------------:|----------------|:------------:|
| 1 | **Tree Runner** | Men | Rp 1.650.000 | Eucalyptus Tree Fiber | Natural White, Charcoal, Sage Green |
| 2 | **Wool Runner** | Men | Rp 1.850.000 | ZQ Merino Wool | Moonrise Grey, Navy Blue |
| 3 | **Tree Dasher** | Men | Rp 1.950.000 | Eucalyptus + SweetFoam® | Blizzard, Tropic Teal |
| 4 | **Tree Breezers** | Women | Rp 1.450.000 | Eucalyptus Tree Fiber | Rose Blush, Cream, Dusty Lavender |
| 5 | **Wool Runner Go** | Women | Rp 2.050.000 | ZQ Merino Wool (Water Repellent) | Pebble, Forest |
| 6 | **Mizzle Runner** | Women | Rp 1.750.000 | Eucalyptus + Bio-DWR | Storm Grey, Clay |
| 7 | **Merino Wool Socks** | Accessories | Rp 225.000 | 100% ZQ Merino Wool | Natural, Charcoal |
| 8 | **Shoelace Pack** | Accessories | Rp 95.000 | 100% Recycled PET Bottles | Earth Pack |

*Seluruh gambar produk menggunakan foto beresolusi tinggi bertema minimalis sneakers dari Unsplash CDN.*

---

## 🛠️ Tech Stack & Dependencies

- **Backend Framework:** [Laravel 12](https://laravel.com/)
- **Programming Language:** [PHP 8.3](https://www.php.net/)
- **Frontend Template Engine:** [Blade](https://laravel.com/docs/blade)
- **Styling & Design System:** [Tailwind CSS v4](https://tailwindcss.com/) dengan custom `@theme` Earthy Palette
- **Client-side Interactivity:** [Alpine.js v3](https://alpinejs.dev/)
- **Bundler / Build Tool:** [Vite v8](https://vitejs.dev/) via `@tailwindcss/vite`
- **Database Engine:** [SQLite](https://www.sqlite.org/)
- **Payment Gateway:** [Midtrans Snap 3DS REST API](https://midtrans.com/)
- **Typography:** Inter by Google Fonts

---

## 📄 Lisensi (License)

Proyek ini dirilis di bawah lisensi open-source [MIT License](LICENSE). Bebas digunakan untuk keperluan portofolio, pembelajaran, maupun referensi komersial.

---

<p align="center">
  Didesain & Dikembangkan dengan 🌿 untuk Portofolio Full-Stack Web Development.<br>
  <em>Inspirasi desain & estetika: <a href="https://allbirds.com" target="_blank">Allbirds.com</a></em>
</p>
