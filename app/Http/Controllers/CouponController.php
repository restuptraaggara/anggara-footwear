<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private function getSessionId(): string
    {
        return session()->getId();
    }

    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($request->code));
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon tidak ditemukan.',
            ], 404);
        }

        // Calculate current cart subtotal
        $cartItems = Cart::with(['variant.product'])
            ->where('session_id', $this->getSessionId())
            ->get();

        $subtotal = $cartItems->sum(fn($i) => $i->variant->product->price * $i->quantity);

        $check = $coupon->isValidForAmount($subtotal);
        if (!$check['valid']) {
            return response()->json([
                'success' => false,
                'message' => $check['message'],
            ], 422);
        }

        $discount = $coupon->calculateDiscount($subtotal);
        $shipping = 25000;
        $finalTotal = max(0, $subtotal - $discount + $shipping);

        // Store coupon in session
        session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'discount' => $discount,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Kupon {$coupon->code} berhasil diterapkan! Hemat Rp " . number_format($discount, 0, ',', '.'),
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'discount_formatted' => '- Rp ' . number_format($discount, 0, ',', '.'),
            'new_total' => $finalTotal,
            'new_total_formatted' => 'Rp ' . number_format($finalTotal, 0, ',', '.'),
        ]);
    }

    public function remove(): JsonResponse
    {
        session()->forget('applied_coupon');

        // Recalculate original total
        $cartItems = Cart::with(['variant.product'])
            ->where('session_id', $this->getSessionId())
            ->get();

        $subtotal = $cartItems->sum(fn($i) => $i->variant->product->price * $i->quantity);
        $shipping = 25000;
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'message' => 'Kupon telah dihapus.',
            'new_total' => $total,
            'new_total_formatted' => 'Rp ' . number_format($total, 0, ',', '.'),
        ]);
    }
}
