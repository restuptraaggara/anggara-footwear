@extends('admin.layouts.app')

@section('title', 'Tambah Produk Baru')
@section('header', 'Tambah Produk Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 font-light">
        <a href="{{ route('admin.products.index') }}" class="hover:text-gray-700">Katalog Produk</a>
        <span>/</span>
        <span class="text-gray-700 font-medium">Tambah Produk</span>
    </nav>

    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Basic Info Card --}}
        <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
            <h2 class="text-base font-semibold text-gray-900 border-b border-earth-100 pb-3">Informasi Dasar Produk</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Nama Model Produk *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Wool Lounger"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Kategori *</label>
                    <select name="category_id" required
                            class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Tipe Material Alami *</label>
                    <input type="text" name="material_type" value="{{ old('material_type') }}" required placeholder="Contoh: ZQ Merino Wool, Serat Tebu..."
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                    @error('material_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Harga Dasar (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000" placeholder="Contoh: 1750000"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                    @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                <textarea name="description" rows="4" placeholder="Jelaskan kenyamanan, fitur, dan proses keberlanjutan produk ini..."
                          class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-earth-300 rounded">
                <label for="is_featured" class="ml-2.5 block text-xs font-medium text-gray-700">
                    Jadikan Produk Unggulan (Tampil di Highlight Homepage)
                </label>
            </div>
        </div>

        {{-- Initial Variant (Optional) --}}
        <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
            <div class="border-b border-earth-100 pb-3">
                <h2 class="text-base font-semibold text-gray-900">Varian Pertama (Opsional)</h2>
                <p class="text-xs text-gray-400 font-light mt-0.5">Anda bisa mengisi 1 varian pembuka sekarang, atau melengkapinya nanti di halaman edit.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Nama Warna</label>
                    <input type="text" name="color_name" value="{{ old('color_name', 'Natural White') }}" placeholder="Contoh: Charcoal"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Kode Hex Warna</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color_hex" value="{{ old('color_hex', '#F5F0E8') }}"
                               class="h-11 w-12 rounded-lg border border-earth-200 cursor-pointer p-1 bg-white">
                        <input type="text" name="color_hex_text" value="{{ old('color_hex', '#F5F0E8') }}"
                               class="flex-1 bg-earth-50 border border-earth-200 rounded-xl px-3 py-3 text-sm font-mono focus:bg-white focus:outline-none focus:border-gray-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Ukuran Sepatu</label>
                    <input type="text" name="size" value="{{ old('size', '41') }}" placeholder="Contoh: 42 atau M/L"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Stok Awal</label>
                    <input type="number" name="stock" value="{{ old('stock', 15) }}" min="0"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">URL Gambar (Unsplash/Direct Link)</label>
                    <input type="url" name="image_url" value="{{ old('image_url', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80') }}"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
            </div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.products.index') }}"
               class="px-6 py-3 rounded-xl border border-earth-200 text-xs font-semibold uppercase tracking-wider text-gray-600 hover:bg-earth-100 transition-colors">
                Batal
            </a>
            <button type="submit"
                    class="px-8 py-3 bg-gray-900 hover:bg-earth-800 text-white text-xs font-semibold uppercase tracking-widest rounded-xl transition-all shadow-md">
                Simpan & Lanjut ke Varian →
            </button>
        </div>

    </form>
</div>
@endsection
