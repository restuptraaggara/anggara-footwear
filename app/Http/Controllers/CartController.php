<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
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

        $subtotal = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
        $shipping = $subtotal >= 500000 || $subtotal === 0 ? 0 : 25000;
        $total = $subtotal + $shipping;

        return response()->json([
            'items' => $cartItems->map(fn($item) => [
                'id' => $item->id,
                'product_name' => $item->variant->product->name,
                'color_name' => $item->variant->color_name,
                'size' => $item->variant->size,
                'image_url' => $item->variant->image_url,
                'price' => $item->variant->product->price,
                'price_formatted' => 'Rp ' . number_format($item->variant->product->price, 0, ',', '.'),
                'quantity' => $item->quantity,
                'subtotal' => $item->variant->product->price * $item->quantity,
                'subtotal_formatted' => 'Rp ' . number_format($item->variant->product->price * $item->quantity, 0, ',', '.'),
                'stock' => $item->variant->stock,
            ]),
            'count' => $cartItems->sum('quantity'),
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'shipping' => $shipping,
            'shipping_formatted' => $shipping === 0 ? 'Gratis' : 'Rp ' . number_format($shipping, 0, ',', '.'),
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:10',
        ]);

        $quantity = $request->input('quantity', 1);
        $variant = ProductVariant::findOrFail($request->variant_id);
        
        $existing = Cart::where('session_id', $this->getSessionId())
            ->where('product_variant_id', $request->variant_id)
            ->first();

        $currentQty = $existing ? $existing->quantity : 0;
        $newQty = $currentQty + $quantity;

        if ($variant->stock < $newQty) {
            return response()->json([
                'error' => "Stok tidak mencukupi! Hanya tersisa {$variant->stock} pasang."
            ], 422);
        }

        if ($existing) {
            $existing->update(['quantity' => $newQty]);
        } else {
            Cart::create([
                'session_id' => $this->getSessionId(),
                'product_variant_id' => $request->variant_id,
                'quantity' => $quantity,
            ]);
        }

        return $this->index();
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $cart = Cart::where('session_id', $this->getSessionId())
            ->where('id', $request->cart_id)
            ->firstOrFail();

        if ($request->quantity <= 0) {
            $cart->delete();
        } else {
            if ($cart->variant->stock < $request->quantity) {
                return response()->json([
                    'error' => "Stok tidak mencukupi! Hanya tersisa {$cart->variant->stock} pasang."
                ], 422);
            }
            $cart->update(['quantity' => $request->quantity]);
        }

        return $this->index();
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        Cart::where('session_id', $this->getSessionId())
            ->where('id', $request->cart_id)
            ->delete();

        return $this->index();
    }

    public function count()
    {
        $count = Cart::where('session_id', $this->getSessionId())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
