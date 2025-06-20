<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminHomeSectionController;
use App\Http\Controllers\AdminAboutSectionController;
use App\Http\Controllers\AdminWorkSectionController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminFolderController;
use App\Http\Controllers\AdminPhotoController;
use App\Http\Controllers\GalleryController;

Route::get('/', [LandingController::class, 'index'])->name('users.landing');

Route::middleware(['auth'])->group(function () {
    Route::get('/gallery/{id}', [GalleryController::class, 'show']);
});

Route::middleware(['web'])->group(function () {
    Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('web')->prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/chart-data/users', [AdminDashboardController::class, 'getUserChartData'])->name('admin.chart.users');
    Route::get('/chart-data', [AdminDashboardController::class, 'chartData'])->name('admin.chart-data');



    //home
    Route::get('/layout/home', [AdminHomeSectionController::class, 'showForm'])->name('admin.layout.home'); // Form upload
    Route::post('/layout/home', [AdminHomeSectionController::class, 'upload'])->name('admin.layout.home.upload'); // Upload + timpa

    //about
    Route::get('/layout/about', [AdminAboutSectionController::class, 'showForm'])->name('admin.layout.about'); // Form upload
    Route::post('/layout/about', [AdminAboutSectionController::class, 'upload'])->name('admin.layout.about.upload');

    //work
    Route::get('/layout/work', [AdminWorkSectionController::class, 'showForm'])->name('admin.layout.work');
    Route::post('/layout/work/carousel', [AdminWorkSectionController::class, 'storeCarousel'])->name('admin.layout.work.storeCarousel');
    Route::post('/layout/work/video', [AdminWorkSectionController::class, 'storeVideo'])->name('admin.layout.work.storeVideo');
    Route::delete('/layout/work/carousel/{id}', [AdminWorkSectionController::class, 'deleteCarousel'])->name('admin.carousel.delete');
    Route::post('/layout/work/carousel/reorder', [AdminWorkSectionController::class, 'reorderCarousel'])->name('admin.carousel.reorder');

    //portofolio folder

    // Route manajemen folder
    Route::get('/folders', [AdminFolderController::class, 'index'])->name('admin.folders.index');
    Route::post('/folders', [AdminFolderController::class, 'store'])->name('admin.folders.store');
    Route::get('/folders/{folder}/edit', [AdminFolderController::class, 'edit'])->name('admin.folders.edit');
    Route::put('/folders/{folder}', [AdminFolderController::class, 'update'])->name('admin.folders.update');
    Route::delete('/folders/{folder}', [AdminFolderController::class, 'delete'])->name('admin.folders.delete');

    // Route manajemen foto (berhubungan dengan folder)
    Route::get('/folders/{folder}/photos', [AdminPhotoController::class, 'index'])->name('admin.photos.index');
    Route::post('/folders/{folder}/photos', [AdminPhotoController::class, 'store'])->name('admin.photos.store');

    Route::put('/photos/{photo}', [AdminPhotoController::class, 'update'])->name('admin.photos.update');
    Route::delete('/photos/{photo}', [AdminPhotoController::class, 'delete'])->name('admin.photos.delete');
    Route::put('/photos/{photo}/toggle-display', [AdminPhotoController::class, 'toggleDisplay'])->name('admin.photos.toggleDisplay');


    // Rute tambahan jika butuh reorder via AJAX (drag & drop)
    Route::post('/folders/{folder}/photos/reorder', [AdminPhotoController::class, 'reorder'])->name('admin.photos.reorder');
});

use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    Mail::raw('Ini adalah email test.', function ($message) {
        $message->to('gilang.guna@mhs.itenas.ac.id')
                ->subject('Email Test dari Laravel');
    });

    return 'Email berhasil dikirim (jika konfigurasi benar).';
});

require __DIR__.'/auth.php';
