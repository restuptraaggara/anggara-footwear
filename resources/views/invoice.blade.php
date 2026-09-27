<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} — Allbirds Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #1F2937; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-6 md:p-12">

    {{-- Print Bar Button (Hidden when printing) --}}
    <div class="max-w-3xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('home') }}" class="text-xs text-gray-500 hover:text-gray-900 font-medium">
            ← Kembali ke Toko
        </a>
        <div class="flex items-center gap-3">
            <button onclick="window.print()"
                    class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-xs font-semibold uppercase tracking-wider shadow-sm flex items-center gap-2">
                <span>🖨️ Cetak Invoice (PDF)</span>
            </button>
        </div>
    </div>

    {{-- Official Invoice Paper --}}
    <div class="max-w-3xl mx-auto bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
        
        {{-- Invoice Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start pb-8 border-b border-gray-100 gap-6">
            <div>
                <h1 class="text-2xl font-bold tracking-widest uppercase text-gray-900">ALLBIRDS</h1>
                <p class="text-xs text-gray-400 font-light mt-1">Naturally Comfortable &bull; Carbon Neutral</p>
                <p class="text-xs text-gray-500 font-light mt-2">
                    PT Allbirds Retail Indonesia<br>
                    Jl. Senopati Raya No. 88, Jakarta Selatan 12190<br>
                    support@allbirds.test
                </p>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-block px-3 py-1 bg-green-50 text-green-800 border border-green-200 text-xs font-bold uppercase tracking-wider rounded-full mb-2">
                    {{ $order->payment_status === 'paid' ? 'LUNAS / PAID' : 'PENDING' }}
                </span>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">INVOICE RESMI</p>
                <p class="text-xl font-mono font-bold text-gray-900 mt-1">{{ $order->order_number }}</p>
                <p class="text-xs text-gray-500 font-light mt-1">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
        </div>

        {{-- Buyer & Shipping Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 py-8 border-b border-gray-100 text-xs">
            <div>
                <p class="font-bold uppercase tracking-wider text-gray-400 mb-2">Ditujukan Kepada:</p>
                <p class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</p>
                <p class="text-gray-600 mt-0.5">{{ $order->customer_email }}</p>
                <p class="text-gray-600">{{ $order->customer_phone ?? '—' }}</p>
            </div>

            <div>
                <p class="font-bold uppercase tracking-wider text-gray-400 mb-2">Alamat Pengiriman:</p>
                <p class="text-gray-900 font-medium leading-relaxed">{{ $order->shipping_address }}</p>
                <p class="text-gray-600 mt-0.5">{{ $order->city }}, {{ $order->postal_code }}</p>
                <p class="text-gray-500 mt-2 font-light">Metode Bayar: <span class="font-semibold text-gray-900">{{ strtoupper($order->payment_method) }}</span></p>
            </div>
        </div>

        {{-- Line Items Table --}}
        <div class="py-8">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-3">
                        <th class="pb-3 font-semibold">Deskripsi Produk</th>
                        <th class="pb-3 font-semibold">Varian (Warna / Ukuran)</th>
                        <th class="pb-3 font-semibold text-center">Qty</th>
                        <th class="pb-3 font-semibold text-right">Harga Satuan</th>
                        <th class="pb-3 font-semibold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-4 font-semibold text-gray-900">
                            {{ $item->product_name }}
                        </td>
                        <td class="py-4 text-gray-600">
                            {{ $item->variant_color }} &bull; Size {{ $item->variant_size }}
                        </td>
                        <td class="py-4 text-center text-gray-700 font-medium">
                            {{ $item->quantity }}
                        </td>
                        <td class="py-4 text-right text-gray-700">
                            Rp {{ number_format($item->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 text-right font-semibold text-gray-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals Summary --}}
        <div class="pt-4 border-t border-gray-100 flex justify-end">
            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-gray-500">
                    <span>Subtotal Produk:</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Ongkos Kirim:</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-base font-bold text-gray-900 pt-3 border-t border-gray-200">
                    <span>Total Pembayaran:</span>
                    <span class="text-gray-900">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Invoice Footer --}}
        <div class="mt-12 pt-8 border-t border-gray-100 text-center text-[11px] text-gray-400 font-light">
            <p>Terima kasih telah berbelanja dan mendukung kelestarian alam bersama Allbirds.</p>
            <p class="mt-1">Invoice ini sah dan diterbitkan secara elektronik oleh sistem komputer Allbirds Store.</p>
        </div>

    </div>

</body>
</html>
