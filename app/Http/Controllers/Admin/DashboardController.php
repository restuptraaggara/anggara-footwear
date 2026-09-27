<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Revenue (from paid/processing/shipped/completed orders)
        $totalRevenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'completed'])
            ->sum('total');

        // 2. Total Orders
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // 3. Total Active Products
        $totalProducts = Product::count();

        // 4. Low stock variants (stock < 5)
        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->get();
        $lowStockCount = $lowStockVariants->count();

        // 5. Recent 5 Orders
        $recentOrders = Order::with('items')
            ->latest()
            ->limit(5)
            ->get();

        // 6. Revenue by Payment Method
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'lowStockCount',
            'lowStockVariants',
            'recentOrders',
            'ordersByStatus'
        ));
    }
}
