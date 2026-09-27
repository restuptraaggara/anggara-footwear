@extends('layouts.app')

@section('title', 'Checkout — Anggara Footwear')

@push('styles')
{{-- Midtrans Snap JS --}}
<script type="text/javascript"
        src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-6 lg:px-8 py-12" x-data="checkoutApp()" x-init="initCheckout()">

    <div class="mb-10">
        <p class="text-xs tracking-widest uppercase text-earth-500 mb-2 font-medium">Langkah Terakhir</p>
        <h1 class="text-3xl font-light text-gray-900">Checkout & Pembayaran</h1>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div x-show="errorMessage" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm" x-cloak>
        <span x-text="errorMessage"></span>
    </div>

    <form id="checkout-form" @submit.prevent="submitCheckout()">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            {{-- Left: Customer & Shipping Details --}}
            <div class="lg:col-span-7 space-y-8">
                
                {{-- Contact Info --}}
                <div class="bg-white p-8 rounded-3xl border border-earth-100 shadow-sm space-y-5">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-900 pb-3 border-b border-earth-100">
                        1. Informasi Kontak
                    </h2>
                    
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Nama Lengkap *
                        </label>
                        <input type="text" name="customer_name" x-model="form.customer_name" required
                               placeholder="Budi Santoso"
                               class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                Email *
                            </label>
                            <input type="email" name="customer_email" x-model="form.customer_email" required
                                   placeholder="budi@email.com"
                                   class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                No. Telepon / WhatsApp
                            </label>
                            <input type="tel" name="customer_phone" x-model="form.customer_phone"
                                   placeholder="081234567890"
                                   class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                        </div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                <div class="bg-white p-8 rounded-3xl border border-earth-100 shadow-sm space-y-5">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-900 pb-3 border-b border-earth-100">
                        2. Alamat Pengiriman
                    </h2>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                            Alamat Rumah / Kantor *
                        </label>
                        <textarea name="shipping_address" x-model="form.shipping_address" required rows="3"
                                  placeholder="Jl. Senopati No. 88, RT 02 RW 03, Kebayoran Baru"
                                  class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                Kota / Kabupaten *
                            </label>
                            <input type="text" name="city" x-model="form.city" required
                                   placeholder="Jakarta Selatan"
                                   class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                                Kode Pos *
                            </label>
                            <input type="text" name="postal_code" x-model="form.postal_code" required
                                   placeholder="12190"
                                   class="w-full text-sm border border-earth-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                        </div>
                    </div>
                </div>

                {{-- Payment Method Selection --}}
                <div class="bg-white p-8 rounded-3xl border border-earth-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-earth-100">
                        <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-900">
                            3. Metode Pembayaran
                        </h2>
                        <span class="text-[11px] text-earth-600 font-semibold">🔒 Midtrans 3D-Secure</span>
                    </div>

                    <div class="space-y-2.5">
                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-earth-200 hover:border-gray-900 cursor-pointer transition-all has-[:checked]:border-gray-900 has-[:checked]:bg-earth-50">
                            <input type="radio" name="payment_method" value="qris" x-model="form.payment_method" class="text-gray-900 focus:ring-gray-900">
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">QRIS (Instant QR)</p>
                                    <p class="text-[11px] text-gray-500">GoPay, ShopeePay, OVO, Dana, BCA Mobile & Semua m-Banking</p>
                                </div>
                                <span class="text-xs font-mono font-bold text-earth-700">⬡ QRIS</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-earth-200 hover:border-gray-900 cursor-pointer transition-all has-[:checked]:border-gray-900 has-[:checked]:bg-earth-50">
                            <input type="radio" name="payment_method" value="bank_transfer_bca" x-model="form.payment_method" class="text-gray-900 focus:ring-gray-900">
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">BCA Virtual Account</p>
                                    <p class="text-[11px] text-gray-500">Verifikasi instan via myBCA, KlikBCA & ATM</p>
                                </div>
                                <span class="text-xs font-mono font-bold text-blue-700">BCA</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 rounded-xl border border-earth-200 hover:border-gray-900 cursor-pointer transition-all has-[:checked]:border-gray-900 has-[:checked]:bg-earth-50">
                            <input type="radio" name="payment_method" value="bank_transfer_mandiri" x-model="form.payment_method" class="text-gray-900 focus:ring-gray-900">
                            <div class="flex-1 flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">Mandiri Livin' Bill Payment</p>
                                    <p class="text-[11px] text-gray-500">Pembayaran via Livin' by Mandiri & ATM</p>
                                </div>
                                <span class="text-xs font-mono font-bold text-amber-700">MDR</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="bg-white p-6 rounded-3xl border border-earth-100 shadow-sm">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-700 mb-1">
                        Catatan Pesanan (Opsional)
                    </label>
                    <input type="text" name="notes" x-model="form.notes"
                           placeholder="Instruksi khusus untuk kurir pengiriman..."
                           class="w-full text-sm border border-earth-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-1 focus:ring-earth-800 bg-earth-50/50">
                </div>

            </div>

            {{-- Right: Order Summary & Interactive Payment Trigger --}}
            <div class="lg:col-span-5">
                <div class="bg-white p-8 rounded-3xl border border-earth-100 shadow-sm sticky top-28 space-y-6">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-gray-900 pb-4 border-b border-earth-100">
                        Ringkasan Pesanan
                    </h2>

                    {{-- Items list --}}
                    <div class="space-y-4 max-h-72 overflow-y-auto pr-1 divide-y divide-earth-50">
                        @foreach($cartItems as $item)
                        <div class="pt-3 first:pt-0 flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-earth-50 overflow-hidden flex-shrink-0 border border-earth-100">
                                <img src="{{ $item->variant->image_url }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-900 truncate">{{ $item->variant->product->name }}</p>
                                <p class="text-[11px] text-gray-500">{{ $item->variant->color_name }} • Size {{ $item->variant->size }}</p>
                                <p class="text-[11px] text-gray-400">Qty: {{ $item->quantity }} pasang</p>
                            </div>
                            <p class="text-xs font-semibold text-gray-900">{{ 'Rp ' . number_format($item->variant->product->price * $item->quantity, 0, ',', '.') }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Subtotal, Shipping, Total --}}
                    <div class="space-y-2.5 pt-4 border-t border-earth-100 text-xs">
                        <div class="flex justify-between text-gray-500">
                            <span>Subtotal Produk</span>
                            <span>{{ 'Rp ' . number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Pengiriman (Carbon Neutral)</span>
                            <span>{{ 'Rp ' . number_format($shipping, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-earth-100">
                            <span>Total Tagihan</span>
                            <span class="text-forest-800">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- REACT ISLAND: HoldButton Interactive Checkout --}}
                    <div class="pt-2">
                        <div class="react-hold-button mb-3"
                             data-label="Tahan untuk Memesan (Bayar)"
                             data-completed-label="✓ Menyiapkan Pembayaran..."
                             data-hold-time="1200"
                             data-action="trigger-checkout">
                        </div>

                        {{-- Quick Click Action --}}
                        <button 
                            type="button"
                            @click="submitCheckout()"
                            :disabled="processing"
                            class="w-full py-2.5 bg-earth-100 hover:bg-earth-200 text-gray-700 text-[11px] tracking-wider uppercase font-semibold rounded-xl transition-all flex items-center justify-center gap-1.5 border border-earth-200">
                            <span x-show="!processing">Atau Klik Bayar Langsung</span>
                            <span x-show="processing" class="flex items-center gap-2" x-cloak>
                                <span>Memproses Pembayaran...</span>
                            </span>
                        </button>
                    </div>

                    <div class="flex items-center justify-center gap-2 text-[11px] text-gray-400">
                        <span>🛡️</span>
                        <span>Garansi Pengembalian 30 Hari Tanpa Syarat</span>
                    </div>

                </div>
            </div>

        </div>
    </form>

    {{-- MOCKUP SNAP SIMULATOR MODAL --}}
    <div x-show="showMockModal" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm flex items-center justify-center p-4"
         x-cloak>
        <div class="bg-white rounded-3xl max-w-md w-full p-8 text-center space-y-6 shadow-2xl border border-earth-200 animate-in fade-in zoom-in-95 duration-200">
            <div class="w-16 h-16 bg-forest-50 text-forest-700 rounded-full flex items-center justify-center mx-auto text-2xl font-bold border border-forest-200">
                🔒
            </div>

            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold uppercase tracking-wider mb-2">
                    Midtrans Snap Simulator
                </span>
                <h3 class="text-xl font-bold text-gray-900">Simulasi Pembayaran Midtrans</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Nomor Pesanan: <strong class="text-gray-900 font-mono" x-text="currentOrderNumber"></strong>
                </p>
                <p class="text-base font-bold text-forest-800 mt-1" x-text="currentOrderTotal"></p>
            </div>

            {{-- QRIS Simulation Box --}}
            <div class="bg-earth-50 p-4 rounded-2xl border border-earth-200 text-left space-y-2">
                <div class="flex items-center justify-between text-xs font-semibold text-gray-700">
                    <span>Metode: <strong x-text="form.payment_method.toUpperCase()"></strong></span>
                    <span class="text-green-600 font-mono font-bold">ACTIVE</span>
                </div>
                <div class="text-[11px] text-gray-500 font-light leading-relaxed">
                    Mode simulasi pembayaran interaktif. Klik tombol hijau di bawah untuk memicu webhook status <code class="bg-earth-200 px-1 rounded">settlement</code> dan otomatis memotong stok produk di database.
                </div>
            </div>

            <div class="space-y-3">
                <button @click="triggerSimulatePayment()" :disabled="paying"
                        class="w-full bg-forest-600 hover:bg-forest-700 text-white py-3.5 rounded-xl text-xs font-semibold uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-2">
                    <span x-show="!paying">⚡ Klik untuk Bayar Sukses (Simulasi)</span>
                    <span x-show="paying" x-cloak>Memverifikasi Pembayaran...</span>
                </button>

                <button @click="showMockModal = false" type="button"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-colors">
                    Batal / Tutup Popup
                </button>
            </div>
        </div>
    </div>

</div>

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

        initCheckout() {
            window.addEventListener('hold-button-completed', (e) => {
                if (e.detail && e.detail.action === 'trigger-checkout') {
                    this.submitCheckout();
                }
            });
        },

        async submitCheckout() {
            if (this.processing) return;
            this.processing = true;
            this.errorMessage = '';

            try {
                const res = await fetch('{{ route("checkout.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await res.json();

                if (!res.ok) {
                    this.errorMessage = data.message || 'Terjadi kesalahan saat memproses pesanan.';
                    this.processing = false;
                    return;
                }

                if (data.is_mock) {
                    // Open interactive mockup Snap modal
                    this.currentOrderNumber = data.order_number;
                    this.currentOrderTotal = data.total_formatted;
                    this.showMockModal = true;
                    this.processing = false;
                } else if (data.snap_token && window.snap) {
                    // Real Midtrans Snap Popup
                    window.snap.pay(data.snap_token, {
                        onSuccess: (result) => {
                            window.location.href = data.redirect_url;
                        },
                        onPending: (result) => {
                            window.location.href = data.redirect_url;
                        },
                        onError: (result) => {
                            this.errorMessage = 'Pembayaran gagal atau dibatalkan oleh bank.';
                            this.processing = false;
                        },
                        onClose: () => {
                            this.processing = false;
                        }
                    });
                } else {
                    window.location.href = data.redirect_url;
                }

            } catch (err) {
                console.error(err);
                this.errorMessage = 'Gagal menghubungi server. Silakan coba kembali.';
                this.processing = false;
            }
        },

        async triggerSimulatePayment() {
            if (this.paying) return;
            this.paying = true;

            try {
                const res = await fetch('{{ route("checkout.simulate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        order_number: this.currentOrderNumber,
                        payment_type: this.form.payment_method
                    })
                });

                const data = await res.json();
                if (data.success) {
                    window.location.href = data.redirect_url;
                } else {
                    alert(data.message || 'Gagal simulasi pembayaran.');
                    this.paying = false;
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi kesalahan koneksi.');
                this.paying = false;
            }
        }
    }
}
</script>
@endsection
