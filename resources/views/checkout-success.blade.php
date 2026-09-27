@extends('layouts.app')

@section('title', 'Pesanan Berhasil — Allbirds Store')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-20 text-center">

    <div class="mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <p class="text-xs tracking-widest uppercase text-earth-500 mb-3 font-medium">Pesanan Dikonfirmasi</p>
        <h1 class="text-3xl font-light text-gray-900 mb-2">Terima kasih, {{ $order->customer_name }}! 🌿</h1>
        <p class="text-gray-500 font-light">Pesanan kamu telah kami terima dan sedang diproses.</p>
    </div>

    {{-- Order Number --}}
    <div class="bg-earth-50 rounded-2xl p-6 mb-8">
        <p class="text-xs text-earth-500 font-light mb-2">Nomor Pesanan</p>
        <p class="text-2xl font-semibold text-gray-900 tracking-wider">{{ $order->order_number }}</p>
        <p class="text-xs text-gray-400 font-light mt-2">Konfirmasi dikirim ke <strong>{{ $order->customer_email }}</strong></p>
    </div>

    {{-- Order Items --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm mb-8 text-left">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Item Pesanan</h2>
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-2 border-b border-earth-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                    <p class="text-xs text-gray-400 font-light">{{ $item->variant_color }} · {{ $item->variant_size }} · Qty: {{ $item->quantity }}</p>
                </div>
                <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
            </div>
            @endforeach
        </div>
        <div class="border-t border-earth-100 pt-4 mt-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Subtotal</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm mt-1">
                <span class="text-gray-500">Ongkos Kirim</span>
                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-semibold mt-3 pt-3 border-t border-earth-100">
                <span>Total</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Payment Instructions --}}
    <div class="bg-earth-50 rounded-2xl p-6 mb-8 text-left">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">
            @if($order->payment_method === 'qris') Instruksi Pembayaran QRIS
            @else Instruksi Transfer Bank
            @endif
        </h2>
        @if($order->payment_method === 'qris')
        <div class="text-center">
            <div class="inline-block border-4 border-white shadow-md mx-auto mb-3">
                <div class="w-40 h-40 bg-gray-900 flex items-center justify-center text-white text-5xl">▦</div>
            </div>
            <p class="text-xs text-gray-400 font-light">Scan QR di atas untuk membayar Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <p class="text-xs text-earth-400 mt-2 font-light">(Ini adalah simulasi QR untuk tujuan portofolio)</p>
        </div>
        @else
        <div class="space-y-2 text-sm text-gray-600">
            <p class="font-light">Transfer ke rekening berikut:</p>
            <div class="bg-white rounded-lg p-3 border border-earth-200">
                <p><span class="text-gray-400 font-light">Bank:</span> BCA</p>
                <p><span class="text-gray-400 font-light">No. Rekening:</span> <strong>1234567890</strong></p>
                <p><span class="text-gray-400 font-light">A/N:</span> PT Allbirds Indonesia</p>
                <p class="mt-2"><span class="text-gray-400 font-light">Jumlah:</span> <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>
            </div>
        </div>
        @endif
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('home') }}"
           class="bg-gray-900 text-white px-10 py-4 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors">
            Kembali Belanja
        </a>
        <a href="{{ route('products.index') }}"
           class="border border-gray-200 text-gray-600 px-10 py-4 text-xs tracking-widest uppercase font-medium hover:border-gray-900 hover:text-gray-900 transition-colors">
            Lihat Produk Lain
        </a>
    </div>
</div>
@endsection