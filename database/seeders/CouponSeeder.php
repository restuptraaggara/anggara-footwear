<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::firstOrCreate(
            ['code' => 'ANGGARA10'],
            [
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 0,
                'max_uses' => 500,
                'used_count' => 12,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'HEMAT50K'],
            [
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 500000,
                'max_uses' => 200,
                'used_count' => 45,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'WELCOME20'],
            [
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 1000000,
                'max_uses' => 100,
                'used_count' => 8,
                'expires_at' => now()->addMonths(1),
                'is_active' => true,
            ]
        );
    }
}
