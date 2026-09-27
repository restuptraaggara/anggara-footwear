<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransService
{
    protected string $serverKey;
    protected string $clientKey;
    protected bool $isProduction;
    protected string $apiUrl;

    public function __construct()
    {
        $this->serverKey    = config('midtrans.server_key', '');
        $this->clientKey    = config('midtrans.client_key', '');
        $this->isProduction = config('midtrans.is_production', false);
        $this->apiUrl       = config('midtrans.api_url');
    }

    /**
     * Create Snap Token for an Order
     */
    public function createSnapToken(Order $order): array
    {
        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id'       => (string) $item->product_variant_id,
                'price'    => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name'     => substr($item->product_name . ' (' . $item->variant_color . ' ' . $item->variant_size . ')', 0, 50),
            ];
        }

        // Add Shipping cost as item
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING-FEE',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim Standar',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email'      => $order->customer_email,
                'phone'      => $order->customer_phone ?? '',
                'shipping_address' => [
                    'first_name'   => $order->customer_name,
                    'address'      => $order->shipping_address,
                    'city'         => $order->city,
                    'postal_code'  => $order->postal_code,
                    'country_code' => 'IDN',
                ],
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('checkout.success', $order->order_number),
            ],
        ];

        // Try calling real Midtrans API
        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->timeout(5)
                ->post($this->apiUrl, $params);

            if ($response->successful()) {
                $data = $response->json();
                $snapToken = $data['token'] ?? null;
                $redirectUrl = $data['redirect_url'] ?? null;

                if ($snapToken) {
                    $order->update(['snap_token' => $snapToken]);
                    return [
                        'success'      => true,
                        'is_mock'      => false,
                        'snap_token'   => $snapToken,
                        'redirect_url' => $redirectUrl,
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Midtrans API unavailable, fallback to Mock Simulator: ' . $e->getMessage());
        }

        // Fallback: Smart Mockup Snap Simulator Mode
        $mockToken = 'mock_snap_' . md5($order->order_number . microtime());
        $order->update(['snap_token' => $mockToken]);

        return [
            'success'      => true,
            'is_mock'      => true,
            'snap_token'   => $mockToken,
            'redirect_url' => route('checkout.success', $order->order_number),
        ];
    }

    /**
     * Process Webhook / Notification from Midtrans or Mock Simulator
     */
    public function handleNotification(array $payload): array
    {
        $orderNumber       = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? 'qris';

        if (!$orderNumber) {
            return ['status' => 'error', 'message' => 'Invalid order_id in payload'];
        }

        $order = Order::with('items')->where('order_number', $orderNumber)->first();
        if (!$order) {
            return ['status' => 'error', 'message' => 'Order not found: ' . $orderNumber];
        }

        DB::transaction(function () use ($order, $transactionStatus, $fraudStatus, $paymentType) {
            $isPaid = false;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $isPaid = true;
                }
            } elseif ($transactionStatus == 'settlement') {
                $isPaid = true;
            } elseif ($transactionStatus == 'pending') {
                $order->update([
                    'payment_type'   => $paymentType,
                    'payment_status' => 'pending',
                    'status'         => 'pending',
                ]);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update([
                    'payment_type'   => $paymentType,
                    'payment_status' => 'failed',
                    'status'         => 'cancelled',
                ]);
            }

            if ($isPaid && $order->payment_status !== 'paid') {
                // 1. Update Order Status
                $order->update([
                    'payment_type'   => $paymentType,
                    'payment_status' => 'paid',
                    'status'         => 'paid',
                    'paid_at'        => now(),
                ]);

                // 2. STOCK DEDUCTION (Atomic)
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = ProductVariant::lockForUpdate()->find($item->product_variant_id);
                        if ($variant) {
                            $newStock = max(0, $variant->stock - $item->quantity);
                            $variant->update(['stock' => $newStock]);
                        }
                    }
                }
            }
        });

        return [
            'status'         => 'success',
            'order_number'   => $order->order_number,
            'payment_status' => $order->fresh()->payment_status,
        ];
    }
}
