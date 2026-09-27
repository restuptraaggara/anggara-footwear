@extends('admin.layouts.app')

@section('title', 'Edit Produk — ' . $product->name)
@section('header', 'Edit Produk: ' . $product->name)

@section('content')
<div class="space-y-10 max-w-6xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs text-gray-400 font-light">
            <a href="{{ route('admin.products.index') }}" class="hover:text-gray-700">Katalog Produk</a>
            <span>/</span>
            <span class="text-gray-700 font-medium">Edit: {{ $product->name }}</span>
        </nav>

        <a href="{{ route('products.show', $product->slug) }}" target="_blank"
           class="inline-flex items-center gap-1.5 text-xs text-earth-700 hover:text-gray-900 font-semibold">
            <span>Lihat di Toko Publik</span>
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
        </a>
    </div>

    {{-- SECTION 1: EDIT BASIC INFO --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-earth-100 pb-3">
                <h2 class="text-base font-semibold text-gray-900">Informasi Produk</h2>
                <button type="submit"
                        class="px-6 py-2 bg-gray-900 hover:bg-earth-800 text-white text-xs font-semibold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                    Simpan Perubahan
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Nama Model Produk *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Kategori *</label>
                    <select name="category_id" required
                            class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Tipe Material Alami *</label>
                    <input type="text" name="material_type" value="{{ old('material_type', $product->material_type) }}" required
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Harga Dasar (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price', (int)$product->price) }}" required min="0" step="1000"
                           class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Deskripsi Lengkap</label>
                <textarea name="description" rows="3"
                          class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                       class="h-4 w-4 text-gray-900 focus:ring-gray-900 border-earth-300 rounded">
                <label for="is_featured" class="ml-2.5 block text-xs font-medium text-gray-700">
                    Jadikan Produk Unggulan (Tampil di Homepage)
                </label>
            </div>
        </div>
    </form>

    {{-- SECTION 2: VARIANT & INVENTORY MANAGEMENT --}}
    <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-earth-100 pb-3">
            <div>
                <h2 class="text-base font-semibold text-gray-900">Manajemen Varian & Stok ({{ $product->variants->count() }} Varian)</h2>
                <p class="text-xs text-gray-400 font-light mt-0.5">Kelola kombinasi warna, ukuran, kuantitas stok, dan URL gambar.</p>
            </div>
            <span class="text-xs px-3 py-1 bg-earth-100 text-earth-800 rounded-full font-bold">
                Total Stok: {{ $product->variants->sum('stock') }} pasang
            </span>
        </div>

        {{-- Variants List --}}
        <div class="space-y-4">
            @forelse($product->variants as $variant)
            <div class="p-5 rounded-2xl bg-earth-50/50 border border-earth-100 hover:border-earth-200 transition-colors">
                
                {{-- Update Form for Existing Variant --}}
                <form action="{{ route('admin.variants.update', $variant->id) }}" method="POST" class="flex flex-col lg:flex-row lg:items-center gap-4">
                    @csrf
                    @method('PUT')

                    {{-- Image Thumbnail Preview --}}
                    <div class="w-14 h-14 rounded-xl bg-white overflow-hidden border border-earth-200 flex-shrink-0">
                        <img src="{{ $variant->image_url }}" alt="{{ $variant->color_name }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Color Name & Hex --}}
                    <div class="w-full lg:w-48">
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Warna</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color_hex" value="{{ $variant->color_hex }}"
                                   class="h-9 w-10 rounded border border-earth-200 cursor-pointer bg-white p-0.5">
                            <input type="text" name="color_name" value="{{ $variant->color_name }}" required
                                   class="w-full bg-white border border-earth-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900">
                        </div>
                    </div>

                    {{-- Size --}}
                    <div class="w-full lg:w-28">
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Ukuran</label>
                        <input type="text" name="size" value="{{ $variant->size }}" required
                               class="w-full bg-white border border-earth-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-900 font-semibold text-center">
                    </div>

                    {{-- Stock Quantity --}}
                    <div class="w-full lg:w-32">
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Kuantitas Stok</label>
                        <div class="relative">
                            <input type="number" name="stock" value="{{ $variant->stock }}" min="0" required
                                   class="w-full bg-white border {{ $variant->stock < 5 ? 'border-amber-400 bg-amber-50/40 text-amber-900' : 'border-earth-200 text-gray-900' }} rounded-lg px-2.5 py-1.5 text-xs font-bold">
                            @if($variant->stock < 5)
                            <span class="absolute right-2 top-2 text-[9px] font-bold text-amber-600 uppercase">Menipis</span>
                            @endif
                        </div>
                    </div>

                    {{-- Image URL --}}
                    <div class="flex-1 min-w-0">
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">URL Gambar</label>
                        <input type="url" name="image_url" value="{{ $variant->image_url }}" required
                               class="w-full bg-white border border-earth-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-600 truncate">
                    </div>

                    {{-- Save & Delete Buttons --}}
                    <div class="flex items-center gap-2 pt-4 lg:pt-0">
                        <button type="submit"
                                class="px-4 py-2 bg-gray-900 hover:bg-earth-800 text-white rounded-lg text-xs font-semibold transition-colors shadow-sm" title="Simpan Perubahan Varian">
                            Update
                        </button>
                </form>

                        <form action="{{ route('admin.variants.destroy', $variant->id) }}" method="POST"
                              onsubmit="return confirm('Hapus varian warna {{ $variant->color_name }} (Size {{ $variant->size }})?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-2 text-gray-400 hover:text-red-500 rounded-lg hover:bg-white transition-colors" title="Hapus Varian">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>

            </div>
            @empty
            <div class="py-8 text-center text-gray-400 text-xs bg-earth-50 rounded-2xl">
                Belum ada varian warna atau ukuran. Tambahkan varian pertama di bawah ini.
            </div>
            @endforelse
        </div>

        {{-- FORM TAMBAH VARIAN BARU --}}
        <div class="mt-8 pt-6 border-t border-earth-100 bg-earth-50 rounded-2xl p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <span>➕</span>
                <span>Tambah Varian Baru untuk Produk Ini</span>
            </h3>

            <form action="{{ route('admin.products.variants.store', $product->id) }}" method="POST"
                  class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
                @csrf

                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Nama Warna *</label>
                    <input type="text" name="color_name" required placeholder="Contoh: Sage Green"
                           class="w-full bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-900">
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Hex Warna *</label>
                    <div class="flex items-center gap-2">
                        <input type="color" name="color_hex" value="#8FAF8A"
                               class="h-10 w-12 rounded border border-earth-200 cursor-pointer bg-white p-0.5">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Ukuran *</label>
                    <input type="text" name="size" required placeholder="Contoh: 42"
                           class="w-full bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-900 text-center font-bold">
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">Stok Awal *</label>
                    <input type="number" name="stock" value="10" min="0" required
                           class="w-full bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-900 font-bold">
                </div>

                <div class="lg:col-span-1">
                    <label class="block text-[10px] uppercase font-bold text-gray-600 mb-1">URL Gambar *</label>
                    <input type="url" name="image_url" value="https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80" required
                           class="w-full bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-900">
                </div>

                <div>
                    <button type="submit"
                            class="w-full py-2.5 bg-gray-900 hover:bg-earth-800 text-white rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors shadow-sm">
                        + Tambah
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
