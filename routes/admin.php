<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Registered in bootstrap/app.php with the "/admin" prefix, the "admin."
| name prefix, and the web + auth + admin middleware — nothing here is
| reachable by non-admins.
|
| Within the panel, each section is additionally gated by a `can:<ability>`
| Gate (defined in AppServiceProvider::configureAdminGates(), resolved
| against UserRole::can()) so Owner/Admin/Staff tiers see different slices
| of the panel. Dashboard and Products/Orders/Reviews are open to every
| admin tier; everything else is restricted per the role matrix.
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('can:products')->group(function (): void {
    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
        ->withTrashed()
        ->name('products.restore');
    Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])
        ->name('products.toggle-featured');
    Route::resource('products', ProductController::class)->except('show');
});

Route::middleware('can:categories')->group(function (): void {
    Route::resource('categories', CategoryController::class)->except('show');
});

Route::middleware('can:orders')->group(function (): void {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');
});

Route::middleware('can:customers')->group(function (): void {
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
});

Route::middleware('can:users')->group(function (): void {
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::patch('users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');
    Route::patch('users/{user}/toggle-ban', [UserController::class, 'toggleBan'])->name('users.toggle-ban');
});

Route::middleware('can:reviews')->group(function (): void {
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::middleware('can:coupons')->group(function (): void {
    Route::resource('coupons', CouponController::class)->except('show');
});

Route::middleware('can:banners')->group(function (): void {
    Route::resource('banners', BannerController::class)->except('show');
});

Route::middleware('can:settings')->group(function (): void {
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('can:logs')->group(function (): void {
    Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
});
