<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductFilterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::middleware('admin.auth')->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'dashboard']);
});
// Route::post('/logout', [AuthController::class, 'logout']);
// Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

Route::prefix('admin')->group(function () {
    // Admin Login API
    Route::post('/login', [AuthController::class, 'login']);

    // Routes Protected with JWT Authentication
    Route::middleware(['auth:api'])->group(function () { // Ensure token is validated
        Route::post('/logout', [AuthController::class, 'logout']); // Admin Logout API
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard'); // Admin Dashboard
        Route::get('/profile', [AuthController::class, 'profile']); // Fetch Admin Profile
    });
});

Route::middleware('auth:api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/orders', [OrderController::class, 'placeOrder']);
    Route::post('/products', [ProductController::class, 'create']);
    Route::post('/payments', [PaymentController::class, 'processPayment']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/filters', [ProductFilterController::class, 'index']);
    Route::post('/filters', [ProductFilterController::class, 'store']);
    Route::get('/filters/{id}', [ProductFilterController::class, 'show']);
    Route::put('/filters/{id}', [ProductFilterController::class, 'update']);
    Route::delete('/filters/{id}', [ProductFilterController::class, 'destroy']);
});

// User update and delete routes
Route::middleware('auth:api')->group(function () {
    Route::put('/users/{id}', [UserController::class, 'update']); // Update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete user
});

Route::middleware('auth:api')->group(function () {
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});