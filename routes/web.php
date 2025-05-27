<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/', function () {
    return view('dashboard');
});



Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });
});