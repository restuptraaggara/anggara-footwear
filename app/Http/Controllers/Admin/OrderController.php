<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_name', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        $order->load(['items.variant.product']);
        $statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'cancelled'];

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,completed,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== $newStatus) {
            // STOCK INTEGRATION: If status changed TO 'cancelled' from an active status, return stock
            if ($newStatus === 'cancelled' && in_array($oldStatus, ['pending', 'paid', 'processing', 'shipped'])) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            // If reactivated FROM 'cancelled', deduct stock again
            if ($oldStatus === 'cancelled' && in_array($newStatus, ['pending', 'paid', 'processing', 'shipped'])) {
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::find($item->product_variant_id);
                        if ($variant) {
                            $variant->decrement('stock', min($variant->stock, $item->quantity));
                        }
                    }
                }
            }

            $order->update(['status' => $newStatus]);
        }

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah menjadi: " . strtoupper($newStatus));
    }

    /**
     * Export all orders to CSV / Excel compatible format
     */
    public function exportCsv()
    {
        $orders = Order::with(['items.variant.product', 'coupon'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'rekap-pesanan-anggara-footwear-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Header Columns
            fputcsv($file, [
                'No. Order',
                'Tanggal Transaksi',
                'Nama Pembeli',
                'Email',
                'No. WhatsApp',
                'Kota / Kabupaten',
                'Alamat Pengiriman',
                'Subtotal (IDR)',
                'Ongkos Kirim (IDR)',
                'Kode Kupon',
                'Diskon (IDR)',
                'Total Bayar (IDR)',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Status Pesanan',
                'Rincian Produk (Item - Warna - Size - Qty)',
            ]);

            foreach ($orders as $order) {
                $itemsDetail = $order->items->map(function ($item) {
                    return "{$item->product_name} ({$item->variant_color}, Size {$item->variant_size}) x{$item->quantity}";
                })->implode('; ');

                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone ?? '-',
                    $order->city,
                    $order->shipping_address,
                    $order->subtotal,
                    $order->shipping_cost,
                    $order->coupon_code ?? '-',
                    $order->discount_amount,
                    $order->total,
                    strtoupper($order->payment_method),
                    strtoupper($order->payment_status),
                    strtoupper($order->status),
                    $itemsDetail,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
