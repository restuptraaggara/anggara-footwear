@extends('admin.layouts.app')

@section('title', 'Katalog Produk')
@section('header', 'Katalog Produk & Stok')

@section('content')
<div class="space-y-6">

    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari produk / material…"
                       class="bg-white border border-earth-200 rounded-xl pl-9 pr-4 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-gray-900 shadow-sm w-56">
                <div class="absolute left-3 top-3 text-gray-400">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <select name="category" onchange="this.form.submit()"
                    class="bg-white border border-earth-200 rounded-xl px-3 py-2.5 text-xs text-gray-700 focus:outline-none focus:border-gray-900 shadow-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            @if(request('search') || request('category'))
                <a href="{{ route('admin.products.index') }}" class="text-xs text-red-500 hover:underline">Reset</a>
            @endif
        </form>

        {{-- Add Product Button --}}
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-900 hover:bg-earth-800 text-white text-xs font-semibold uppercase tracking-wider rounded-xl transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk Baru
        </a>
    </div>

    {{-- Product Table --}}
    <div class="bg-white rounded-3xl border border-earth-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-earth-50/50 text-gray-400 uppercase tracking-wider border-b border-earth-100">
                    <tr>
                        <th class="py-4 px-6 font-semibold">Produk</th>
                        <th class="py-4 px-4 font-semibold">Kategori & Material</th>
                        <th class="py-4 px-4 font-semibold">Harga</th>
                        <th class="py-4 px-4 font-semibold">Total Varian</th>
                        <th class="py-4 px-4 font-semibold">Total Stok</th>
                        <th class="py-4 px-4 font-semibold">Status</th>
                        <th class="py-4 px-6 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-earth-100">
                    @forelse($products as $product)
                    @php
                        $totalStock = $product->variants->sum('stock');
                        $firstVariant = $product->variants->first();
                    @endphp
                    <tr class="hover:bg-earth-50/40 transition-colors">
                        
                        {{-- Image + Name --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-earth-50 overflow-hidden border border-earth-100 flex-shrink-0">
                                    <img src="{{ $firstVariant?->image_url ?? 'https://via.placeholder.com/80' }}"
                                         alt="{{ $product->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="truncate max-w-xs">
                                    <p class="font-semibold text-gray-900 text-sm">{{ $product->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-light truncate">/products/{{ $product->slug }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Category & Material --}}
                        <td class="py-4 px-4">
                            <span class="inline-block px-2.5 py-0.5 bg-earth-100 text-earth-800 rounded-md font-medium text-[11px] mb-1">
                                {{ $product->category->name }}
                            </span>
                            <p class="text-[11px] text-gray-500 font-light line-clamp-1">{{ $product->material_type }}</p>
                        </td>

                        {{-- Price --}}
                        <td class="py-4 px-4 font-semibold text-gray-900 text-sm">
                            {{ $product->formatted_price }}
                        </td>

                        {{-- Variant Count --}}
                        <td class="py-4 px-4 text-gray-600 font-medium">
                            {{ $product->variants_count }} varian
                        </td>

                        {{-- Total Stock --}}
                        <td class="py-4 px-4">
                            @if($totalStock == 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-700 rounded-full font-semibold text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Habis
                                </span>
                            @elseif($totalStock < 10)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full font-semibold text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ $totalStock }} pasang (Menipis)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 rounded-full font-semibold text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> {{ $totalStock }} pasang
                                </span>
                            @endif
                        </td>

                        {{-- Featured --}}
                        <td class="py-4 px-4">
                            @if($product->is_featured)
                                <span class="px-2 py-0.5 bg-earth-900 text-white rounded text-[10px] font-semibold uppercase">Featured</span>
                            @else
                                <span class="text-gray-300 text-[11px]">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="inline-block px-3 py-1.5 bg-earth-100 hover:bg-earth-200 text-earth-800 rounded-lg text-xs font-semibold transition-colors">
                                Kelola & Varian
                            </a>

                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $product->name }} beserta seluruh variannya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors" title="Hapus Produk">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400 text-xs">
                            Tidak ada produk yang sesuai dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-6 border-t border-earth-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
