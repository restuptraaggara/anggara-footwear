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
}
