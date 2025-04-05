<?php


use App\Http\Controllers\Admin\DashboardController;

// Admin authentication routes

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// Admin authentication routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});


