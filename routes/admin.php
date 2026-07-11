<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Registered in bootstrap/app.php with the "/admin" prefix, the "admin."
| name prefix, and the web + auth + admin middleware — nothing here is
| reachable by non-admins.
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
    ->withTrashed()
    ->name('products.restore');
Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])
    ->name('products.toggle-featured');
Route::resource('products', ProductController::class)->except('show');

Route::resource('categories', CategoryController::class)->except('show');

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
Route::patch('orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');

Route::get('logs', [ActivityLogController::class, 'index'])->name('logs.index');
