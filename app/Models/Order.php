<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'postal_code',
        'subtotal',
        'shipping_cost',
        'total',
        'payment_method',
        'snap_token',
        'payment_type',
        'payment_status',
        'paid_at',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'shipping_cost'  => 'decimal:2',
        'total'          => 'decimal:2',
        'paid_at'        => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return 'AB-' . strtoupper(substr(uniqid(), -6)) . '-' . date('Ymd');
    }

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

}
