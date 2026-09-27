@extends('layouts.app')

@section('title', 'Checkout — Allbirds Store')

@section('content')
<div class="max-w-6xl mx-auto px-6 lg:px-8 py-12">

    <div class="mb-10">
        <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Langkah Terakhir</p>
        <h1 class="text-3xl font-light text-gray-900">Checkout</h1>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            
            {{-- Left: Form --}}
            <div class="lg:col-span-3 space-y-8">
                
                {{-- Contact Info --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-900 tracking-wide uppercase mb-6">Informasi Kontak</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Nama Lengkap *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                                   class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded"
                                   placeholder="Budi Santoso" required>
                            @error('customer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Email *</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                                       class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded"
                                       placeholder="budi@email.com" required>
                                @error('customer_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">No. Telepon</label>
                                <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}"
                                       class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded"
                                       placeholder="08123456789">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-900 tracking-wide uppercase mb-6">Alamat Pengiriman</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Alamat Lengkap *</label>
                            <textarea name="shipping_address" rows="3" required
                                      class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded resize-none"
                                      placeholder="Jl. Sudirman No. 123, RT 05 RW 03">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Kota *</label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                       class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded"
                                       placeholder="Jakarta" required>
                                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Kode Pos *</label>
                                <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                       class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded"
                                       placeholder="12345" required>
                                @error('postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm" x-data="{ payment: 'qris' }">
                    <h2 class="text-sm font-semibold text-gray-900 tracking-wide uppercase mb-6">Metode Pembayaran</h2>
                    
                    <div class="space-y-3">
                        {{-- QRIS --}}
                        <label class="flex items-center gap-4 p-4 border rounded-xl cursor-pointer transition-all"
                               :class="payment === 'qris' ? 'border-gray-900 bg-earth-50' : 'border-earth-200 hover:border-gray-400'">
                            <input type="radio" name="payment_method" value="qris" x-model="payment" class="sr-only">
                            <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white text-lg">⬡</div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">QRIS</p>
                                <p class="text-xs text-gray-400 font-light">Bayar via GoPay, OVO, Dana, ShopeePay, m-Banking</p>
                            </div>
                            <div :class="payment === 'qris' ? 'block' : 'hidden'" class="text-gray-900">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                        </label>
                        
                        {{-- Transfer --}}
                        <label class="flex items-center gap-4 p-4 border rounded-xl cursor-pointer transition-all"
                               :class="payment === 'transfer' ? 'border-gray-900 bg-earth-50' : 'border-earth-200 hover:border-gray-400'">
                            <input type="radio" name="payment_method" value="transfer" x-model="payment" class="sr-only">
                            <div class="w-10 h-10 bg-earth-600 rounded-lg flex items-center justify-center text-white text-lg">🏦</div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Transfer Bank</p>
                                <p class="text-xs text-gray-400 font-light">BCA, Mandiri, BNI, BRI — instruksi dikirim via email</p>
                            </div>
                            <div :class="payment === 'transfer' ? 'block' : 'hidden'" class="text-gray-900">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                        </label>
                    </div>
                    
                    {{-- QRIS Display --}}
                    <div x-show="payment === 'qris'" class="mt-4 p-4 bg-earth-50 rounded-xl text-center">
                        <p class="text-xs text-gray-400 mb-3 font-light">Setelah klik "Konfirmasi Pesanan", scan QR berikut:</p>
                        <div class="inline-block border-4 border-white shadow-md">
                            {{-- QR Code placeholder --}}
                            <div class="w-32 h-32 bg-gray-900 flex items-center justify-center text-white text-3xl">
                                ▦
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-3 font-light">QR ini adalah simulasi untuk portofolio.</p>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <label class="block text-xs font-medium text-gray-600 mb-1.5 tracking-wide">Catatan Pesanan (opsional)</label>
                    <textarea name="notes" rows="2"
                              class="w-full border border-earth-200 px-4 py-3 text-sm focus:outline-none focus:border-gray-900 transition-colors rounded resize-none"
                              placeholder="Instruksi khusus untuk pengiriman...">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            {{-- Right: Order Summary --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl p-6 shadow-sm sticky top-24">
                    <h2 class="text-sm font-semibold text-gray-900 tracking-wide uppercase mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex gap-3">
                            <div class="w-14 h-14 bg-earth-50 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $item->variant->image_url }}" alt="{{ $item->variant->product->name }}"
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ $item->variant->product->name }}</p>
                                <p class="text-xs text-gray-400 font-light">{{ $item->variant->color_name }} · {{ $item->variant->size }}</p>
                                <p class="text-xs text-gray-400 font-light">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-xs font-semibold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t border-earth-100 pt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-light">Subtotal</span>
                            <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-light">Ongkos Kirim</span>
                            <span class="text-gray-900">Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm pt-3 border-t border-earth-100">
                            <span class="font-semibold text-gray-900">Total</span>
                            <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <button type="submit"
                            class="w-full bg-gray-900 text-white mt-6 py-4 text-xs tracking-widest uppercase font-medium hover:bg-earth-800 transition-colors">
                        Konfirmasi Pesanan
                    </button>
                    
                    <div class="mt-4 flex items-center justify-center gap-4 text-xs text-gray-400">
                        <span>🔒 SSL Secured</span>
                        <span>·</span>
                        <span>🌿 Eco Packaging</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection