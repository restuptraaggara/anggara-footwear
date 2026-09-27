<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'used_count' => 'integer',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValidForAmount(float $amount): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Kupon ini sudah tidak aktif.'];
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return ['valid' => false, 'message' => 'Kupon ini telah kedaluwarsa.'];
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return ['valid' => false, 'message' => 'Kuota penggunaan kupon ini telah habis.'];
        }

        if ($amount < $this->min_order_amount) {
            $formattedMin = 'Rp ' . number_format($this->min_order_amount, 0, ',', '.');
            return ['valid' => false, 'message' => "Minimal pembelian untuk kupon ini adalah {$formattedMin}."];
        }

        return ['valid' => true, 'message' => 'Kupon berhasil diterapkan!'];
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->type === 'percentage') {
            return round(($amount * $this->value) / 100, 2);
        }

        return min($this->value, $amount);
    }

    public function getFormattedDiscountAttribute(): string
    {
        if ($this->type === 'percentage') {
            return intval($this->value) . '%';
        }

        return 'Rp ' . number_format($this->value, 0, ',', '.');
    }
}
