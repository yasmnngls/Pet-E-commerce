<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Landing Page
Route::get('/home', [PageController::class, 'landing'])->name('landing');

// Login Page
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Register Action
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Vendor Application Form
Route::prefix('apply/vendor')->group(function(){

    //Cancel Form
    Route::get('/cancel', [VendorController::class, 'cancelApplication'])->name('vendor.cancel');

    //Form Page 1
    Route::get('/step-1', [VendorController::class, 'step1'])->name('vendor.step1');
    Route::post('/step-1', [VendorController::class, 'postStep1'])->name('vendor.step1.post');

    //Form Page 2
    Route::get('/step-2', [VendorController::class, 'step2'])->name('vendor.step2');
    Route::post('/step-2', [VendorController::class, 'postStep2'])->name('vendor.step2.post');

    //Form Page 3
    Route::get('/step-3', [VendorController::class, 'step3'])->name('vendor.step3');
    Route::post('/step-3', [VendorController::class, 'postStep3'])->name('vendor.step3.post');

    //Form Page 4
    Route::get('/step-4', [VendorController::class, 'step4'])->name('vendor.step4');
    Route::post('/step-4', [VendorController::class, 'postStep4'])->name('vendor.step4.post');
});