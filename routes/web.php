<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashBoardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

//Landing Page
Route::get('/Home', [PageController::class, 'landing'])->name('landing');

//Login Page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

//Register Post
Route::get('/login/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/login/register', [AuthController::class, 'register'])->name('register.submit');

//Logout Post
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ADMIN ADMIN ADMIN //
//Admin Hidden Routes
Route::get('/backrooms/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/backrooms/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

//Admin Dashboard (Only Accessible if user is admin)
Route::get('/backrooms/dashboard', function(){
    return "Welcome to the backrooms";
})->name('admin.dashboard');

//Admin Backrooms
Route::middleware(['auth'])->prefix('backrooms')->group(function(){

    //Dashboard
    Route::get('/dashboard', [AdminDashBoardController::class, 'index'])->name('admin.dashboard');

    //CRUD for User and Product
    Route::post('/dashboard', [AdminDashboardController::class, 'updateUserRole'])->name('admin.users.updateRole');
    Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser'])->name('admin.users.delete');
    Route::delete('/products/{id}', [AdminDashBoardController::class, 'deleteProduct'])->name('admin.products.delete');

    //Admin Approval
    Route::post('/seller-applications/{id}/approve', [AdminDashBoardController::class, 'approveSeller'])->name('admin.approve.seller');
    Route::post('/seller-applications/{id}/reject', [AdminDashBoardController::class, 'rejectSeller'])->name('admin.reject.seller');

    Route::post('/product-applications/{id}/approve', [AdminDashBoardController::class, 'approveProduct'])->name('admin.approve.product');
    Route::post('/product-applications/{id}/reject', [AdminDashBoardController::class, 'rejectProduct'])->name('admin.reject.product');
});