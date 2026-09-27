@extends('layouts.app')

@section('title', 'Semua Produk — Allbirds Store')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">

    {{-- Page Header --}}
    <div class="mb-10">
        <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Koleksi</p>
        <h1 class="text-4xl font-light text-gray-900">
            @if(request('category'))
                {{ ucfirst(request('category')) }}
            @else
                Semua Produk
            @endif
        </h1>
    </div>

    {{-- Category Filter --}}
    <div class="flex items-center gap-3 mb-10 overflow-x-auto pb-2">
        <a href="{{ route('products.index') }}"
           class="flex-shrink-0 px-5 py-2 text-xs tracking-widest uppercase font-medium border transition-colors
                  {{ !request('category') ? 'bg-gray-900 text-white border-gray-900' : 'border-gray-200 text-gray-600 hover:border-gray-900 hover:text-gray-900' }}">
            Semua
        </a>
        @foreach($categories as $cat)
        <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
           class="flex-shrink-0 px-5 py-2 text-xs tracking-widest uppercase font-medium border transition-colors
                  {{ request('category') === $cat->slug ? 'bg-gray-900 text-white border-gray-900' : 'border-gray-200 text-gray-600 hover:border-gray-900 hover:text-gray-900' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    {{-- Product Grid --}}
    @if($products->isEmpty())
        <div class="text-center py-24">
            <p class="text-4xl mb-4">🔍</p>
            <p class="text-gray-400 font-light">Tidak ada produk yang ditemukan.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @foreach($products as $product)
            @php
                $productColors = $product->variants->unique('color_name')->values();
                $firstVariant = $product->variants->first();
            @endphp
            <div class="product-card group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg">
                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    <div class="aspect-square overflow-hidden bg-earth-50">
                        <img src="{{ $firstVariant?->image_url }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                </a>
                <div class="p-4">
                    <p class="text-xs text-earth-500 font-light mb-1">{{ $product->category->name }}</p>
                    <h3 class="text-sm font-semibold text-gray-900 mb-1">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-earth-700 transition-colors">
                            {{ $product->name }}
                        </a>
                    </h3>
                    <p class="text-xs text-earth-400 font-light mb-3">{{ $product->material_type }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            @foreach($productColors->take(4) as $variant)
                            <span class="color-swatch" style="background-color: {{ $variant->color_hex }}" 
                                  title="{{ $variant->color_name }}"></span>
                            @endforeach
                            @if($productColors->count() > 4)
                            <span class="text-xs text-gray-400 font-light">+{{ $productColors->count() - 4 }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $product->formatted_price }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection