<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private function getSessionId(): string
    {
        return session()->getId();
    }

    public function index()
    {
        $cartItems = Cart::with(['variant.product'])
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang belanja kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
        $shipping = 25000;
        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:qris,transfer',
        ]);

        $cartItems = Cart::with(['variant.product'])
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout')->with('error', 'Keranjang belanja kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
        $shipping = 25000;
        $total = $subtotal + $shipping;

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'shipping_address' => $request->shipping_address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $total,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'product_name' => $item->variant->product->name,
                'variant_color' => $item->variant->color_name,
                'variant_size' => $item->variant->size,
                'quantity' => $item->quantity,
                'price' => $item->variant->product->price,
                'subtotal' => $item->variant->product->price * $item->quantity,
            ]);
        }

        // Clear cart
        Cart::where('session_id', $this->getSessionId())->delete();

        return redirect()->route('checkout.success', $order->order_number);
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items.variant.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('checkout-success', compact('order'));
    }
}
