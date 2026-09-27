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

        $total = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);

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
            ]),
            'count' => $cartItems->sum('quantity'),
            'total' => $total,
            'total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        
        if ($variant->stock < ($request->quantity ?? 1)) {
            return response()->json(['error' => 'Stok tidak mencukupi'], 422);
        }

        $existing = Cart::where('session_id', $this->getSessionId())
            ->where('product_variant_id', $request->variant_id)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $request->quantity ?? 1);
        } else {
            Cart::create([
                'session_id' => $this->getSessionId(),
                'product_variant_id' => $request->variant_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        $count = Cart::where('session_id', $this->getSessionId())->sum('quantity');
        
        return response()->json([
            'message' => 'Produk ditambahkan ke keranjang!',
            'cart_count' => $count,
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:0|max:10']);

        if ($request->quantity === 0) {
            $cart->delete();
        } else {
            $cart->update(['quantity' => $request->quantity]);
        }

        return $this->index();
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        return $this->index();
    }

    public function count()
    {
        $count = Cart::where('session_id', $this->getSessionId())->sum('quantity');
        return response()->json(['count' => $count]);
    }
}
