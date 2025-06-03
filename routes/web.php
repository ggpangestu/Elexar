<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminHomeSectionController;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('users.landing');


Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/layout/home', [AdminHomeSectionController::class, 'showForm'])->name('admin.layout.home'); // Form upload
    Route::post('/layout/home', [AdminHomeSectionController::class, 'upload'])->name('admin.layout.home.upload'); // Upload + timpa

    Route::get('/layout/about', function () {
        return view('admin.layout.about');
    })->name('admin.layout.about');

});