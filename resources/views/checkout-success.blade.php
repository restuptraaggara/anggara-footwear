@extends('layouts.app')

@section('title', 'Status Pesanan ' . $order->order_number . ' — Allbirds Store')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-16">

    {{-- Success / Paid Header Banner --}}
    <div class="text-center mb-10">
        @if($order->payment_status === 'paid' || $order->status === 'paid')
            <div class="w-20 h-20 bg-forest-100 text-forest-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-green-50 border border-green-200 text-green-800 text-xs font-semibold uppercase tracking-wider rounded-full mb-3">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Pembayaran Terverifikasi (Lunas)
            </span>
            <h1 class="text-3xl font-light text-gray-900 mb-2">Terima kasih atas pesananmu, {{ $order->customer_name }}! 🌿</h1>
            <p class="text-sm text-gray-500 font-light">Pembayaran Anda telah kami terima dan pesanan sedang disiapkan di gudang kami.</p>
        @else
            <div class="w-20 h-20 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold uppercase tracking-wider rounded-full mb-3">
                Menunggu Pembayaran
            </span>
            <h1 class="text-3xl font-light text-gray-900 mb-2">Pesanan Dibuat, {{ $order->customer_name }}</h1>
            <p class="text-sm text-gray-500 font-light">Selesaikan pembayaran sebelum batas waktu berakhir.</p>
        @endif
    </div>

    {{-- Order Summary Receipt Card --}}
    <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
        
        {{-- Order ID & Meta --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-earth-100 gap-4">
            <div>
                <p class="text-[10px] uppercase font-bold tracking-widest text-earth-600">Nomor Pesanan</p>
                <p class="text-2xl font-mono font-bold text-gray-900 tracking-wider">{{ $order->order_number }}</p>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-[10px] uppercase font-bold tracking-widest text-gray-400">Tanggal & Waktu</p>
                <p class="text-xs font-medium text-gray-700">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        {{-- Ordered Items --}}
        <div>
            <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-900 mb-4">Item yang Dipesan</h2>
            <div class="divide-y divide-earth-100">
                @foreach($order->items as $item)
                @php $variant = $item->variant; @endphp
                <div class="py-3.5 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-earth-50 rounded-xl overflow-hidden border border-earth-100 flex-shrink-0">
                            <img src="{{ $variant?->image_url ?? 'https://via.placeholder.com/80' }}"
                                 alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-400 font-light">
                                {{ $item->variant_color }} &bull; Size {{ $item->variant_size }} &bull; Qty: {{ $item->quantity }}
                            </p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-gray-900">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Price Breakdown --}}
        <div class="pt-4 border-t border-earth-100 space-y-2 text-xs">
            <div class="flex justify-between text-gray-500 font-light">
                <span>Subtotal Produk</span>
                <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-gray-500 font-light">
                <span>Ongkos Kirim</span>
                <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-earth-100">
                <span>Total Tagihan</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Shipping Destination --}}
        <div class="pt-6 border-t border-earth-100 bg-earth-50 rounded-2xl p-5 text-xs text-gray-700 space-y-1.5">
            <p class="font-bold text-gray-900 uppercase tracking-wider text-[10px]">Tujuan Pengiriman:</p>
            <p class="font-medium">{{ $order->customer_name }} ({{ $order->customer_phone ?? $order->customer_email }})</p>
            <p class="font-light text-gray-600">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</p>
            <p class="text-[11px] text-earth-700 font-medium pt-1">Metode Bayar: {{ strtoupper($order->payment_method) }}</p>
        </div>

        {{-- Action Buttons --}}
        <div class="pt-4 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('orders.invoice', $order->order_number) }}" target="_blank"
               class="px-6 py-3.5 bg-earth-100 hover:bg-earth-200 text-earth-900 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors flex items-center justify-center gap-2">
                <span>🖨️ Cetak / Unduh Invoice</span>
            </a>
            <a href="{{ route('products.index') }}"
               class="px-6 py-3.5 bg-gray-900 hover:bg-earth-800 text-white rounded-xl text-xs font-semibold uppercase tracking-widest transition-colors flex items-center justify-center">
                Lanjut Belanja Produk Lain →
            </a>
        </div>

    </div>

</div>
@endsection
