<?php

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