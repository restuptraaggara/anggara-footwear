<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    private function getSessionId(): string
    {
        return session()->getId();
    }

    /**
     * Process checkout via AJAX & Generate Snap Token
     */
    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'nullable|string|max:20',
            'shipping_address' => 'required|string',
            'city'             => 'required|string|max:100',
            'postal_code'      => 'required|string|max:10',
            'payment_method'   => 'required|in:qris,va_bca,va_mandiri,va_bri,midtrans_snap',
            'notes'            => 'nullable|string|max:500',
        ]);

        $cartItems = Cart::with(['variant.product'])
            ->where('session_id', $this->getSessionId())
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja Anda kosong.',
            ], 422);
        }

        // 1. INVENTORY VALIDATION: Check if any variant is out of stock
        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->variant;
            if (!$variant || $variant->stock < $cartItem->quantity) {
                $productName = $variant?->product->name ?? 'Produk';
                $varSize     = $variant?->size ?? '';
                $availStock  = $variant?->stock ?? 0;
                return response()->json([
                    'success' => false,
                    'message' => "Maaf, stok '{$productName}' (Size {$varSize}) tidak mencukupi! Hanya sisa {$availStock} pasang. Silakan sesuaikan keranjang Anda.",
                ], 422);
            }
        }

        // 2. Create Order & Items inside DB Transaction
        $order = DB::transaction(function () use ($validated, $cartItems) {
            $subtotal = $cartItems->sum(fn($item) => $item->variant->product->price * $item->quantity);
            $shippingCost = $subtotal >= 500000 ? 0 : 25000;

            // Coupon discount calculation
            $couponSession = session()->get('applied_coupon');
            $discountAmount = 0;
            $couponId = null;
            $couponCode = null;

            if ($couponSession) {
                $coupon = \App\Models\Coupon::find($couponSession['id']);
                if ($coupon && $coupon->isValidForAmount($subtotal)['valid']) {
                    $discountAmount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    $couponCode = $coupon->code;
                    $coupon->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discountAmount + $shippingCost);

            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_name'    => $validated['customer_name'],
                'customer_email'   => $validated['customer_email'],
                'customer_phone'   => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'city'             => $validated['city'],
                'postal_code'      => $validated['postal_code'],
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shippingCost,
                'total'            => $total,
                'coupon_id'        => $couponId,
                'coupon_code'      => $couponCode,
                'discount_amount'  => $discountAmount,
                'payment_method'   => $validated['payment_method'],
                'payment_status'   => 'pending',
                'status'           => 'pending',
                'notes'            => $validated['notes'] ?? null,
            ]);

            // Clear coupon session
            session()->forget('applied_coupon');

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name'       => $item->variant->product->name,
                    'variant_color'      => $item->variant->color_name,
                    'variant_size'       => $item->variant->size,
                    'quantity'           => $item->quantity,
                    'price'              => $item->variant->product->price,
                    'subtotal'           => $item->variant->product->price * $item->quantity,
                ]);
            }

            // Clear session cart
            Cart::where('session_id', $this->getSessionId())->delete();

            return $order;
        });

        // 3. Generate Midtrans Snap Token
        $snapResult = $this->midtransService->createSnapToken($order);

        return response()->json([
            'success'         => true,
            'order_number'    => $order->order_number,
            'snap_token'      => $snapResult['snap_token'],
            'is_mock'         => $snapResult['is_mock'],
            'redirect_url'    => route('checkout.success', $order->order_number),
            'total_formatted' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'client_key'      => config('midtrans.client_key'),
            'snap_js_url'     => config('midtrans.snap_url'),
        ]);
    }

    /**
     * Simulate Payment for Mock Snap Modal
     */
    public function simulatePayment(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
            'payment_type' => 'nullable|string',
        ]);

        $payload = [
            'order_id'           => $request->order_number,
            'transaction_status' => 'settlement',
            'payment_type'       => $request->payment_type ?? 'qris',
            'fraud_status'       => 'accept',
        ];

        $result = $this->midtransService->handleNotification($payload);

        return response()->json($result);
    }

    /**
     * Midtrans Live Webhook Notification Endpoint
     */
    public function handleNotification(Request $request)
    {
        $payload = $request->all();
        $result = $this->midtransService->handleNotification($payload);

        return response()->json($result);
    }

    /**
     * Printable Order Invoice
     */
    public function invoice(string $orderNumber)
    {
        $order = Order::with('items.variant.product')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('invoice', compact('order'));
    }
}
