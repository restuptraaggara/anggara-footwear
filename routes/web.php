<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;

/*
|--------------------------------------------------------------------------
| Web Routes — Storefront
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Wishlist Page
Route::get('/wishlist', function () {
    return view('wishlist');
})->name('wishlist');

// Checkout & Payment Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/process', [PaymentController::class, 'processCheckout'])->name('checkout.process');
Route::post('/checkout/simulate-payment', [PaymentController::class, 'simulatePayment'])->name('checkout.simulate');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/orders/invoice/{orderNumber}', [PaymentController::class, 'invoice'])->name('orders.invoice');

// Coupon AJAX Routes
Route::post('/checkout/apply-coupon', [CouponController::class, 'apply'])->name('checkout.coupon.apply');
Route::post('/checkout/remove-coupon', [CouponController::class, 'remove'])->name('checkout.coupon.remove');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Back-Office Panel
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class);

    // Variants Sub-Manager
    Route::post('/products/{product}/variants', [AdminProductVariantController::class, 'store'])->name('products.variants.store');
    Route::put('/variants/{variant}', [AdminProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('/variants/{variant}', [AdminProductVariantController::class, 'destroy'])->name('variants.destroy');

    // Orders Management
    Route::get('/orders/export', [AdminOrderController::class, 'exportCsv'])->name('orders.export');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');

    // Coupons Management
    Route::resource('coupons', AdminCouponController::class)->except(['show']);
    Route::patch('/coupons/{coupon}/toggle', [AdminCouponController::class, 'toggle'])->name('coupons.toggle');
});
