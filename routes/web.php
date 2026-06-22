<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtsSellerProductController;
use App\Http\Controllers\OtsSellerOrderController;
use App\Http\Controllers\OtsSellerEarningsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/Home', [PageController::class, 'landing'])->name('landing');

// Login Page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Register Post
Route::get('/login/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login/register', [AuthController::class, 'register'])->name('register.submit');

// Logout Post
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Reusable Multi-Vendor Seller Routes (Ots Namespace)
|--------------------------------------------------------------------------
*/
Route::redirect('/seller', '/seller/products');

Route::prefix('seller')->group(function () {
    
    // Product Management Suite
    Route::get('/products', [OtsSellerProductController::class, 'index'])->name('seller.products');
    Route::post('/products', [OtsSellerProductController::class, 'store'])->name('seller.products.store');
    Route::put('/products/{id}', [OtsSellerProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{id}', [OtsSellerProductController::class, 'destroy'])->name('seller.products.destroy');

    // Order Fulfillment Processing System
    Route::get('/orders', [OtsSellerOrderController::class, 'index'])->name('seller.orders');
    Route::patch('/orders/{id}/status', [OtsSellerOrderController::class, 'updateStatus'])->name('seller.orders.update');

    // Balance Sheet & Payout Mechanics
    Route::get('/earnings', [OtsSellerEarningsController::class, 'index'])->name('seller.earnings');
    Route::post('/earnings/withdraw', [OtsSellerEarningsController::class, 'storeWithdrawal'])->name('seller.earnings.withdraw');
});