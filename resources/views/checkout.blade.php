@extends('layouts.app')

@section('title', 'Checkout — Allbirds Store')

@push('styles')
{{-- Midtrans Snap JS --}}
<script type="text/javascript"
        src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-6 lg:px-8 py-12" x-data="checkoutApp()">

    <div class="mb-10">
        <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Langkah Terakhir</p>
        <h1 class="text-3xl font-light text-gray-900">Checkout & Pembayaran</h1>
    </div>

    {{-- Alert Error Box --}}
    <div x-show="errorMessage" x-cloak class="mb-8 p-4 rounded-2xl bg-red-50 border-l-4 border-red-500 text-red-700 text-sm flex items-center justify-between">
        <span x-text="errorMessage"></span>
        <button @click="errorMessage = ''" class="text-red-400 hover:text-red-700">✕</button>
    </div>

    <form @submit.prevent="submitCheckout()">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            
            {{-- Left: Shipping & Payment Options (3 Cols) --}}
            <div class="lg:col-span-3 space-y-8">
                
                {{-- Contact Info --}}
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-earth-100 shadow-sm">
                    <h2 class="text-xs font-semibold text-gray-900 tracking-widest uppercase mb-6 pb-2 border-b border-earth-100">
                        1. Informasi Kontak
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Nama Lengkap *</label>
                            <input type="text" x-model="form.customer_name" required placeholder="Budi Santoso"
                                   class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Email *</label>
                                <input type="email" x-model="form.customer_email" required placeholder="budi@email.com"
                                       class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">No. Telepon / WhatsApp</label>
                                <input type="tel" x-model="form.customer_phone" placeholder="081234567890"
                                       class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-earth-100 shadow-sm">
                    <h2 class="text-xs font-semibold text-gray-900 tracking-widest uppercase mb-6 pb-2 border-b border-earth-100">
                        2. Alamat Pengiriman
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Alamat Rumah / Kantor *</label>
                            <textarea x-model="form.shipping_address" rows="3" required placeholder="Jl. Senopati No. 88, RT 02 RW 03, Kebayoran Baru"
                                      class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors resize-none"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Kota / Kabupaten *</label>
                                <input type="text" x-model="form.city" required placeholder="Jakarta Selatan"
                                       class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Kode Pos *</label>
                                <input type="text" x-model="form.postal_code" required placeholder="12190"
                                       class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-earth-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6 pb-2 border-b border-earth-100">
                        <h2 class="text-xs font-semibold text-gray-900 tracking-widest uppercase">
                            3. Metode Pembayaran
                        </h2>
                        <span class="inline-flex items-center gap-1 text-[11px] text-green-700 font-medium bg-green-50 px-2.5 py-0.5 rounded-full border border-green-200">
                            🔒 Midtrans 3D-Secure
                        </span>
                    </div>

                    <div class="space-y-3">
                        
                        {{-- QRIS --}}
                        <label class="flex items-center gap-4 p-4 border rounded-2xl cursor-pointer transition-all"
                               :class="form.payment_method === 'qris' ? 'border-gray-900 bg-earth-50 shadow-sm' : 'border-earth-200 hover:border-gray-400 bg-white'">
                            <input type="radio" value="qris" x-model="form.payment_method" class="sr-only">
                            <div class="w-10 h-10 bg-gray-900 rounded-xl flex items-center justify-center text-white text-base font-bold">⬡</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">QRIS (Instant QR)</p>
                                <p class="text-xs text-gray-400 font-light truncate">GoPay, ShopeePay, OVO, Dana, BCA Mobile & Semua m-Banking</p>
                            </div>
                            <div :class="form.payment_method === 'qris' ? 'block' : 'hidden'" class="text-gray-900">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                        </label>

                        {{-- BCA Virtual Account --}}
                        <label class="flex items-center gap-4 p-4 border rounded-2xl cursor-pointer transition-all"
                               :class="form.payment_method === 'va_bca' ? 'border-gray-900 bg-earth-50 shadow-sm' : 'border-earth-200 hover:border-gray-400 bg-white'">
                            <input type="radio" value="va_bca" x-model="form.payment_method" class="sr-only">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white text-xs font-bold">BCA</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">BCA Virtual Account</p>
                                <p class="text-xs text-gray-400 font-light truncate">Verifikasi instan via myBCA, KlikBCA & ATM</p>
                            </div>
                            <div :class="form.payment_method === 'va_bca' ? 'block' : 'hidden'" class="text-gray-900">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                        </label>

                        {{-- Mandiri Virtual Account --}}
                        <label class="flex items-center gap-4 p-4 border rounded-2xl cursor-pointer transition-all"
                               :class="form.payment_method === 'va_mandiri' ? 'border-gray-900 bg-earth-50 shadow-sm' : 'border-earth-200 hover:border-gray-400 bg-white'">
                            <input type="radio" value="va_mandiri" x-model="form.payment_method" class="sr-only">
                            <div class="w-10 h-10 bg-amber-600 rounded-xl flex items-center justify-center text-white text-xs font-bold">MDR</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">Mandiri Livin' Bill Payment</p>
                                <p class="text-xs text-gray-400 font-light truncate">Pembayaran via Livin' by Mandiri & ATM</p>
                            </div>
                            <div :class="form.payment_method === 'va_mandiri' ? 'block' : 'hidden'" class="text-gray-900">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white rounded-3xl p-6 border border-earth-100 shadow-sm">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1.5">Catatan Pesanan (Opsional)</label>
                    <textarea x-model="form.notes" rows="2" placeholder="Instruksi khusus untuk kurir pengiriman..."
                              class="w-full bg-earth-50 border border-earth-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:outline-none focus:border-gray-900 transition-colors resize-none"></textarea>
                </div>

            </div>

            {{-- Right: Order Summary Sticky Panel (2 Cols) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-earth-100 shadow-sm sticky top-24 space-y-6">
                    <h2 class="text-xs font-semibold text-gray-900 tracking-widest uppercase pb-2 border-b border-earth-100">
                        Ringkasan Pesanan
                    </h2>

                    {{-- Items List --}}
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                        @foreach($cartItems as $item)
                        <div class="flex items-center gap-3 py-1">
                            <div class="w-14 h-14 bg-earth-50 rounded-xl overflow-hidden flex-shrink-0 border border-earth-100">
                                <img src="{{ $item->variant->image_url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ $item->variant->product->name }}</p>
                                <p class="text-[11px] text-gray-400 font-light">{{ $item->variant->color_name }} &bull; Size {{ $item->variant->size }}</p>
                                <p class="text-[11px] text-earth-600 font-medium">Qty: {{ $item->quantity }} pasang</p>
                            </div>
                            <p class="text-xs font-bold text-gray-900 whitespace-nowrap">
                                Rp {{ number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pricing Calculations --}}
                    <div class="border-t border-earth-100 pt-4 space-y-2.5 text-xs">
                        <div class="flex justify-between text-gray-500 font-light">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 font-light">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium {{ $shipping == 0 ? 'text-forest-600 font-semibold' : 'text-gray-900' }}">
                                {{ $shipping == 0 ? 'GRATIS (Promo)' : 'Rp ' . number_format($shipping, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm font-bold text-gray-900 pt-3 border-t border-earth-100">
                            <span>Total Tagihan</span>
                            <span class="text-base text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Submit Pay Button --}}
                    <button type="submit" :disabled="processing"
                            class="w-full bg-gray-900 hover:bg-earth-800 disabled:bg-gray-400 text-white py-4 rounded-xl text-xs tracking-widest uppercase font-semibold transition-all shadow-md flex items-center justify-center gap-2">
                        <span x-show="!processing">💳 Bayar Sekarang</span>
                        <span x-show="processing" x-cloak class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses Snap Token...
                        </span>
                    </button>

                    <div class="flex items-center justify-center gap-4 text-[11px] text-gray-400 font-light pt-2">
                        <span>🔒 256-bit SSL</span>
                        <span>&bull;</span>
                        <span>🌿 Kemasan Ramah Lingkungan</span>
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- =========================================================
         MIDTRANS SNAP SIMULATOR POPUP (Mockup Fallback Modal)
    ========================================================= --}}
    <div x-show="showMockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showMockModal = false"></div>

        <div class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl overflow-hidden border border-earth-100 z-10 animate-scale-up"
             x-show="showMockModal">
            
            {{-- Header (Midtrans Brand Replica) --}}
            <div class="bg-gray-900 text-white p-6 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center text-gray-900 font-bold text-xs">M</div>
                    <div>
                        <p class="text-xs tracking-widest uppercase font-bold text-earth-300">Midtrans Snap</p>
                        <p class="text-[11px] text-gray-400">Payment Gateway Simulator</p>
                    </div>
                </div>
                <button @click="showMockModal = false" class="text-gray-400 hover:text-white p-1">✕</button>
            </div>

            {{-- Amount Banner --}}
            <div class="bg-earth-50 px-6 py-4 border-b border-earth-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">Total Pembayaran</p>
                    <p class="text-xl font-bold text-gray-900" x-text="currentOrderTotal"></p>
                </div>
                <span class="text-xs px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-semibold">
                    Sandbox Mode
                </span>
            </div>

            {{-- Body: QRIS / VA Simulation --}}
            <div class="p-6 space-y-6">
                <div class="text-center">
                    <p class="text-xs font-semibold text-gray-800 uppercase tracking-wider mb-3">
                        <span x-text="selectedMethodTitle()"></span>
                    </p>

                    <template x-if="form.payment_method === 'qris'">
                        <div class="space-y-3">
                            <div class="inline-block p-4 bg-white border-2 border-gray-900 rounded-2xl shadow-md">
                                <div class="w-44 h-44 bg-gray-900 rounded-xl flex flex-col items-center justify-center text-white p-3 mx-auto">
                                    <span class="text-4xl mb-1">▦</span>
                                    <span class="text-[10px] font-mono tracking-widest">QRIS STANDAR ID</span>
                                    <span class="text-[9px] text-earth-300 mt-1" x-text="currentOrderNumber"></span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 font-light">Buka aplikasi e-Wallet atau m-Banking Anda dan scan QR di atas.</p>
                        </div>
                    </template>

                    <template x-if="form.payment_method.startsWith('va_')">
                        <div class="space-y-4">
                            <div class="bg-earth-50 p-5 rounded-2xl border border-earth-200">
                                <p class="text-xs text-gray-500 mb-1">Nomor Virtual Account:</p>
                                <p class="text-2xl font-mono font-bold text-gray-900 tracking-wider">8801 9283 7461</p>
                                <p class="text-[11px] text-gray-400 mt-1 font-light">A/N: Allbirds Indonesia (<span x-text="form.customer_name"></span>)</p>
                            </div>
                            <p class="text-xs text-gray-500 font-light">Salin nomor VA dan selesaikan transfer dari aplikasi m-Banking Anda.</p>
                        </div>
                    </template>
                </div>

                {{-- Action: Instant Pay Button --}}
                <div class="space-y-2.5 pt-2">
                    <button @click="triggerSimulatePayment()" :disabled="paying"
                            class="w-full bg-forest-600 hover:bg-forest-700 text-white py-3.5 rounded-xl text-xs font-semibold uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-2">
                        <span x-show="!paying">⚡ Klik untuk Bayar Sukses (Simulasi)</span>
                        <span x-show="paying" x-cloak>Memverifikasi Pembayaran...</span>
                    </button>
                    <button @click="showMockModal = false"
                            class="w-full py-2.5 text-xs text-gray-400 hover:text-gray-700 font-medium">
                        Batal / Tutup Popup
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

@push('scripts')
<script>
function checkoutApp() {
    return {
        form: {
            customer_name: 'Andi Pratama',
            customer_email: 'andi@allbirds.test',
            customer_phone: '081234567890',
            shipping_address: 'Jl. Senopati No. 88, RT 02 RW 03, Kebayoran Baru',
            city: 'Jakarta Selatan',
            postal_code: '12190',
            payment_method: 'qris',
            notes: '',
        },
        processing: false,
        paying: false,
        errorMessage: '',
        showMockModal: false,
        currentOrderNumber: '',
        currentOrderTotal: '',

        selectedMethodTitle() {
            if (this.form.payment_method === 'qris') return 'Pembayaran via QRIS Nasional';
            if (this.form.payment_method === 'va_bca') return 'BCA Virtual Account';
            if (this.form.payment_method === 'va_mandiri') return 'Mandiri Virtual Account';
            return 'Pembayaran Online';
        },

        async submitCheckout() {
            this.processing = true;
            this.errorMessage = '';

            try {
                const res = await fetch('/checkout/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    this.errorMessage = data.message || 'Terjadi kesalahan saat memproses pesanan.';
                    this.processing = false;
                    return;
                }

                this.currentOrderNumber = data.order_number;
                this.currentOrderTotal = data.total_formatted;

                // Case A: Real Midtrans Snap JS Available
                if (!data.is_mock && window.snap && typeof window.snap.pay === 'function') {
                    window.snap.pay(data.snap_token, {
                        onSuccess: (result) => {
                            window.location.href = data.redirect_url;
                        },
                        onPending: (result) => {
                            window.location.href = data.redirect_url;
                        },
                        onError: (result) => {
                            this.errorMessage = 'Pembayaran gagal atau dibatalkan oleh pengguna.';
                            this.processing = false;
                        },
                        onClose: () => {
                            this.errorMessage = 'Popup pembayaran ditutup sebelum transaksi selesai.';
                            this.processing = false;
                        }
                    });
                } else {
                    // Case B: Mockup Simulator Fallback
                    this.showMockModal = true;
                    this.processing = false;
                }

            } catch (err) {
                console.error(err);
                this.errorMessage = 'Gagal menghubungi server. Periksa koneksi internet Anda.';
                this.processing = false;
            }
        },

        async triggerSimulatePayment() {
            this.paying = true;
            try {
                const res = await fetch('/checkout/simulate-payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        order_number: this.currentOrderNumber,
                        payment_type: this.form.payment_method,
                    })
                });

                const data = await res.json();
                if (data.status === 'success') {
                    window.location.href = `/checkout/success/${this.currentOrderNumber}`;
                } else {
                    alert('Gagal memverifikasi simulasi pembayaran.');
                    this.paying = false;
                }
            } catch (e) {
                console.error(e);
                this.paying = false;
            }
        }
    };
}
</script>
@endpush
@endsection
