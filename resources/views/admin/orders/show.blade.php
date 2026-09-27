@extends('admin.layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)
@section('header', 'Detail Pesanan: ' . $order->order_number)

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    {{-- Breadcrumb & Top Action --}}
    <div class="flex items-center justify-between">
        <nav class="flex items-center gap-2 text-xs text-gray-400 font-light">
            <a href="{{ route('admin.orders.index') }}" class="hover:text-gray-700">Daftar Pesanan</a>
            <span>/</span>
            <span class="text-gray-700 font-medium">{{ $order->order_number }}</span>
        </nav>

        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-1 text-xs text-gray-500 hover:text-gray-900 font-semibold">
            ← Kembali ke Daftar Pesanan
        </a>
    </div>

    {{-- Main Grid (Order Info + Status Update Panel) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: Order Items & Pricing (2 Cols) --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Items Card --}}
            <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
                <div class="flex items-center justify-between border-b border-earth-100 pb-3">
                    <h2 class="text-base font-semibold text-gray-900">Rincian Produk yang Dipesan</h2>
                    <span class="text-xs text-gray-400 font-light">{{ $order->items->count() }} jenis produk</span>
                </div>

                <div class="divide-y divide-earth-100">
                    @foreach($order->items as $item)
                    @php $variant = $item->variant; @endphp
                    <div class="py-4 flex items-center justify-between gap-4 first:pt-0 last:pb-0">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-earth-50 overflow-hidden border border-earth-100 flex-shrink-0">
                                <img src="{{ $variant?->image_url ?? 'https://via.placeholder.com/100' }}"
                                     alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</h3>
                                <p class="text-xs text-gray-500 font-light mt-0.5">
                                    Warna: <span class="font-medium text-gray-700">{{ $item->variant_color }}</span> &bull; 
                                    Ukuran: <span class="font-medium text-gray-700">{{ $item->variant_size }}</span>
                                </p>
                                <p class="text-xs text-gray-400 font-light mt-0.5">
                                    Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }} pasang
                                </p>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-sm font-bold text-gray-900">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Price Breakdown Summary --}}
                <div class="pt-6 border-t border-earth-100 space-y-2.5 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-3 border-t border-earth-100">
                        <span>Total Pembayaran</span>
                        <span class="text-base text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping & Customer Info Card --}}
            <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
                <h2 class="text-base font-semibold text-gray-900 border-b border-earth-100 pb-3">Informasi Pembeli & Pengiriman</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div>
                        <p class="text-gray-400 font-semibold uppercase tracking-wider mb-1">Nama Penerima</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-400 font-semibold uppercase tracking-wider mb-1">Kontak</p>
                        <p class="font-medium text-gray-900">{{ $order->customer_email }}</p>
                        <p class="text-gray-500 font-light mt-0.5">{{ $order->customer_phone ?? '—' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-gray-400 font-semibold uppercase tracking-wider mb-1">Alamat Tujuan Pengiriman</p>
                        <p class="font-medium text-gray-900 leading-relaxed">{{ $order->shipping_address }}</p>
                        <p class="text-gray-500 font-light mt-0.5">{{ $order->city }}, {{ $order->postal_code }}</p>
                    </div>

                    @if($order->notes)
                    <div class="sm:col-span-2 p-3 bg-earth-50 rounded-xl border border-earth-100">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-earth-700 mb-1">Catatan Tambahan dari Pembeli:</p>
                        <p class="text-xs text-gray-700 italic font-light">"{{ $order->notes }}"</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

        {{-- Right: Status Management Card (1 Col) --}}
        <div class="space-y-6">
            
            {{-- Update Status Box --}}
            <div class="bg-white rounded-3xl p-8 border border-earth-100 shadow-sm space-y-6">
                <h2 class="text-base font-semibold text-gray-900 border-b border-earth-100 pb-3">Status Pesanan</h2>

                @php
                    $badgeColors = [
                        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200',
                        'paid'       => 'bg-blue-50 text-blue-700 border-blue-200',
                        'processing' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'shipped'    => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'completed'  => 'bg-green-50 text-green-700 border-green-200',
                        'cancelled'  => 'bg-red-50 text-red-700 border-red-200',
                    ];
                @endphp

                <div class="p-4 rounded-2xl border text-center {{ $badgeColors[$order->status] ?? 'bg-gray-50' }}">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-gray-500 mb-1">Status Saat Ini</p>
                    <p class="text-lg font-bold uppercase tracking-wider">{{ $order->status }}</p>
                </div>

                {{-- Status Change Form --}}
                <form action="{{ route('admin.orders.status.update', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Ubah Status Menjadi:</label>
                        <select name="status" required
                                class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-900 focus:bg-white focus:outline-none focus:border-gray-900">
                            @foreach($statuses as $st)
                                <option value="{{ $st }}" {{ $order->status === $st ? 'selected' : '' }}>
                                    {{ strtoupper($st) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Stock Return Warning --}}
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800 space-y-1">
                        <p class="font-bold flex items-center gap-1">
                            <span>ℹ️</span> <span>Integrasi Stok Otomatis</span>
                        </p>
                        <p class="font-light leading-relaxed">
                            Jika diubah ke <strong class="font-semibold">CANCELLED</strong>, kuantitas stok varian produk yang dipesan akan otomatis dikembalikan ke gudang.
                        </p>
                    </div>

                    <button type="submit"
                            class="w-full py-3 bg-gray-900 hover:bg-earth-800 text-white rounded-xl text-xs font-semibold uppercase tracking-widest transition-colors shadow-md">
                        Simpan Status Baru
                    </button>
                </form>
            </div>

            {{-- Payment Summary Box --}}
            <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm space-y-4 text-xs">
                <h3 class="font-semibold uppercase tracking-wider text-gray-900 text-xs pb-2 border-b border-earth-100">Info Pembayaran</h3>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-bold uppercase text-gray-900">{{ strtoupper($order->payment_method) }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Waktu Order</span>
                    <span class="text-gray-700 font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Update Terakhir</span>
                    <span class="text-gray-700 font-medium">{{ $order->updated_at->format('d M Y, H:i') }}</span>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
