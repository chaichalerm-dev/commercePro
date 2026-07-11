<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\DashboardController;
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
