<?php


use App\Http\Controllers\Admin\DashboardController;

// Admin authentication routes

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// // Admin authentication routes
// Route::prefix('admin')->group(function () {
//     Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
//     Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
// });


// // Admin Login Page
// Route::get('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login.page');

// // Admin Login Submit
// Route::post('admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login');

// // Admin Dashboard (protected)
// Route::middleware('admin.auth')->group(function () {
//     Route::get('admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
//     Route::post('admin/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

//     // Product Management Routes
//     Route::get('admin/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
//     Route::get('admin/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
//     Route::post('admin/products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
// });

Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::get('dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
});
Route::get('/admin/products/add', function () {
    return view('product.add_product');
})->name('admin.products.add');