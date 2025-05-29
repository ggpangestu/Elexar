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
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/layout/home', function () {
        return view('admin.layout.home');
    })->name('admin.layout.home');


    Route::get('/layout/about', function () {
        return view('admin.layout.about');
    })->name('admin.layout.about');
});