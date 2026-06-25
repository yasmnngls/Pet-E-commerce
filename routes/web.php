<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashBoardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtsSellerProductController;
use App\Http\Controllers\OtsSellerProfileController;
use App\Http\Controllers\OtsSellerOrderController;
use App\Http\Controllers\OtsSellerEarningsController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/Home', [PageController::class, 'landing'])->name('landing');

    Route::get('/profile/edit', [OtsSellerProfileController::class, 'edit'])->name('seller.profile.edit');
    Route::post('/profile/update', [OtsSellerProfileController::class, 'update'])->name('seller.profile.update');
// Product Detail
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

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

Route::prefix('seller')->middleware(['auth'])->group(function () {
    
    // Product Management Suite
    Route::get('/products', [OtsSellerProductController::class, 'index'])->name('seller.products');
    Route::post('/products', [OtsSellerProductController::class, 'store'])->name('seller.products.store');
    Route::put('/products/{id}', [OtsSellerProductController::class, 'update'])->name('seller.products.update');
    Route::delete('/products/{id}', [OtsSellerProductController::class, 'destroy'])->name('seller.products.destroy');
    
    // Order Fulfillment Processing System
    Route::get('/orders', [OtsSellerOrderController::class, 'index'])->name('seller.orders');
    Route::patch('/orders/{id}/status', [OtsSellerOrderController::class, 'updateStatus'])->name('seller.orders.update');
    Route::delete('/orders/{id}', [OtsSellerOrderController::class, 'cancel'])->name('seller.orders.cancel');

    // Balance Sheet & Payout Mechanics
    Route::get('/earnings', [OtsSellerEarningsController::class, 'index'])->name('seller.earnings');
    Route::post('/earnings/withdraw', [OtsSellerEarningsController::class, 'storeWithdrawal'])->name('seller.earnings.withdraw');
});

//Order of Users
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');

// Cart
Route::middleware(['auth'])->group(function () {
    Route::get('/account-settings', [AccountSettingsController::class, 'edit'])->name('account.settings');
    Route::post('/account-settings', [AccountSettingsController::class, 'update'])->name('account.settings.update');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{id}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
});

Route::prefix('apply/vendor')->group(function () {
        Route::get('/cancel', [VendorController::class, 'cancelApplication'])->name('vendor.cancel');
        
        Route::get('/step-1', [VendorController::class, 'step1'])->name('vendor.step1');
        Route::post('/step-1', [VendorController::class, 'postStep1'])->name('vendor.step1.post');
        
        Route::get('/step-2', [VendorController::class, 'step2'])->name('vendor.step2');
        Route::post('/step-2', [VendorController::class, 'postStep2'])->name('vendor.step2.post');
        
        Route::get('/step-3', [VendorController::class, 'step3'])->name('vendor.step3');
        Route::post('/step-3', [VendorController::class, 'postStep3'])->name('vendor.step3.post');
        
        Route::get('/step-4', [VendorController::class, 'step4'])->name('vendor.step4');
        Route::post('/step-4', [VendorController::class, 'postStep4'])->name('vendor.step4.post');
    });

// Clean explicit catalog filtering route asset link
Route::get('/shop', [ProductController::class, 'catalog'])->name('products.catalog');

// ADMIN ADMIN ADMIN //
//Admin Hidden Routes
Route::get('/backrooms/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/backrooms/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

//Admin Backrooms
Route::middleware(['auth'])->prefix('backrooms')->group(function(){

    //Dashboard
    Route::get('/dashboard', [AdminDashBoardController::class, 'index'])->name('admin.dashboard');

    //CRUD for User and Product
    Route::post('/dashboard', [AdminDashboardController::class, 'updateUserRole'])->name('admin.update.user.role');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.delete.user');
    Route::delete('/products/{id}', [AdminDashBoardController::class, 'deleteProduct'])->name('admin.products.delete');

    //Admin Approval
    Route::post('/seller-applications/{id}/approve', [AdminDashBoardController::class, 'approveSeller'])->name('admin.approve.seller');
    Route::post('/seller-applications/{id}/reject', [AdminDashBoardController::class, 'rejectSeller'])->name('admin.reject.seller');

    Route::post('/product-applications/{id}/approve', [AdminDashBoardController::class, 'approveProduct'])->name('admin.approve.product');
    Route::post('/product-applications/{id}/reject', [AdminDashBoardController::class, 'rejectProduct'])->name('admin.reject.product');
});