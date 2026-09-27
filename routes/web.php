<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// ==========================================
// STOREFRONT ROUTES (Customer Facing)
// ==========================================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products + Live Search API
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart (AJAX)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{cart}', [CartController::class, 'update'])->name('update');
    Route::delete('/{cart}', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Wishlist
Route::view('/wishlist', 'wishlist')->name('wishlist');


// ==========================================
// ADMIN PANEL ROUTES
// ==========================================

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest Admin (Login)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Area
    Route::middleware('admin')->group(function () {
        
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Product Management (CRUD)
        Route::resource('products', AdminProductController::class);
        
        // Variant Management (Nested inside product or direct update)
        Route::post('/products/{product}/variants', [AdminProductVariantController::class, 'store'])->name('products.variants.store');
        Route::put('/variants/{variant}', [AdminProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('/variants/{variant}', [AdminProductVariantController::class, 'destroy'])->name('variants.destroy');

        // Order Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
    });

});
