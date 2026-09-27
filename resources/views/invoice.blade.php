<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $order->order_number }} — Anggara Footwear</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; color: black; font-size: 12pt; }
            .print-border { border: 1px solid #ddd !important; }
            .print-shadow-none { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 antialiased p-4 sm:p-8">

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Top Bar Actions --}}
        <div class="no-print flex items-center justify-between bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <a href="{{ url()->previous() }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 uppercase tracking-wider">
                ← Kembali
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white text-xs uppercase font-bold tracking-widest rounded-xl transition-all shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak / Unduh PDF</span>
                </button>
            </div>
        </div>

        {{-- INVOICE CARD --}}
        <div class="bg-white p-8 sm:p-12 rounded-3xl border border-gray-200 shadow-lg print-shadow-none space-y-8">
            
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 pb-8 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-2xl">🌿</span>
                        <span class="text-xl font-bold tracking-widest uppercase text-gray-900">Anggara Footwear</span>
                    </div>
                    <p class="text-xs text-gray-500 font-light">Sustainable & Carbon-Neutral Footwear</p>
                    <p class="text-xs text-gray-500 font-light">Jakarta, Indonesia • CS: 0812-3456-7890</p>
                </div>
                <div class="sm:text-right space-y-1">
                    <span class="inline-block px-3 py-1 bg-gray-100 rounded-full text-[10px] font-bold uppercase tracking-wider text-gray-700 font-mono">
                        INVOICE RESMI
                    </span>
                    <h2 class="text-lg font-mono font-bold text-gray-900">{{ $order->order_number }}</h2>
                    <p class="text-xs text-gray-500">Tanggal: {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            {{-- Customer & Payment Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs">
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-1">DITAGIHKAN KEPADA:</p>
                    <p class="font-bold text-sm text-gray-900">{{ $order->customer_name }}</p>
                    <p class="text-gray-600 mt-0.5">{{ $order->customer_email }}</p>
                    <p class="text-gray-600">{{ $order->customer_phone ?? '-' }}</p>
                    <p class="text-gray-600 mt-1 leading-relaxed">{{ $order->shipping_address }}, {{ $order->city }} {{ $order->postal_code }}</p>
                </div>
                <div class="sm:text-right space-y-1">
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-1">STATUS PEMBAYARAN:</p>
                    <div>
                        @if($order->payment_status === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider font-mono">
                                ✓ LUNAS / PAID
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider font-mono">
                                ● PENDING / MENUNGGU BAYAR
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-[11px] pt-1">Metode: <strong class="text-gray-800 uppercase font-mono">{{ $order->payment_method }}</strong></p>
                    @if($order->paid_at)
                        <p class="text-gray-400 text-[10px]">Waktu Bayar: {{ $order->paid_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>

            {{-- Items Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-y border-gray-200 bg-gray-50/50 text-gray-500 uppercase tracking-wider text-[10px]">
                            <th class="py-3 px-2">No</th>
                            <th class="py-3 px-2">Item Produk</th>
                            <th class="py-3 px-2">Varian & Ukuran</th>
                            <th class="py-3 px-2 text-center">Qty</th>
                            <th class="py-3 px-2 text-right">Harga Satuan</th>
                            <th class="py-3 px-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($order->items as $idx => $item)
                        <tr>
                            <td class="py-3.5 px-2 text-gray-400 font-mono">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-2 font-semibold text-gray-900">{{ $item->product_name }}</td>
                            <td class="py-3.5 px-2 text-gray-600">{{ $item->variant_color }} • Size {{ $item->variant_size }}</td>
                            <td class="py-3.5 px-2 text-center font-mono font-medium">{{ $item->quantity }}</td>
                            <td class="py-3.5 px-2 text-right font-mono">{{ 'Rp ' . number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-2 text-right font-mono font-bold text-gray-900">{{ 'Rp ' . number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Summary & Totals --}}
            <div class="border-t border-gray-200 pt-4 flex flex-col sm:flex-row justify-between items-start gap-6 text-xs">
                <div class="max-w-xs text-gray-500 space-y-1">
                    <p class="font-semibold text-gray-700">Catatan:</p>
                    <p class="text-[11px] leading-relaxed">
                        {{ $order->notes ?? 'Terima kasih atas pesanan Anda. Produk ramah lingkungan Anggara Footwear dirancang untuk kenyamanan tahan lama.' }}
                    </p>
                </div>
                
                <div class="w-full sm:w-64 space-y-2 font-mono">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>{{ 'Rp ' . number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Ongkir:</span>
                        <span>{{ 'Rp ' . number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-emerald-700 font-bold">
                        <span>Diskon ({{ $order->coupon_code }}):</span>
                        <span>- {{ 'Rp ' . number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Total Tagihan:</span>
                        <span class="text-emerald-800">{{ 'Rp ' . number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Footer Guarantee --}}
            <div class="pt-6 border-t border-gray-100 text-center text-[11px] text-gray-400 space-y-1">
                <p>Dokumen invoice ini diterbitkan secara elektronik oleh sistem Anggara Footwear.</p>
                <p>Garansi Pengembalian 30 Hari Tanpa Syarat • 100% Carbon Neutral Shipping</p>
            </div>

        </div>

    </div>

</body>
</html>
