<?php

use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BookAccessController;
use App\Http\Controllers\BookViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ========================================
// BREEZE COMPATIBILITY
// ========================================
// Breeze default redirect ke route 'dashboard'
// Kita redirect ke 'admin.dashboard' karena struktur kita berbeda

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});

// ========================================
// FRONTEND ROUTES (Public)
// ========================================

// Homepage - Katalog Buku
Route::get('/', [HomeController::class, 'index'])->name('home');

// Book Access - Login untuk Akses Buku
Route::get('/book/{book}/login', [BookAccessController::class, 'showLogin'])->name('book.login');
Route::post('/book/{book}/login', [BookAccessController::class, 'login'])->name('book.login.submit');

// Book Viewer - Harus Login Dulu (Protected by Middleware)
Route::middleware(['book.access'])->group(function () {
    Route::get('/book/{book}/view', [BookViewController::class, 'show'])->name('book.view');
    Route::get('/book/{book}/pages', [BookViewController::class, 'getPages'])->name('book.pages');
    Route::get('/book/{book}/download', [BookViewController::class, 'download'])->name('book.download');
    Route::get('/book/{book}/video', [BookViewController::class, 'streamVideo'])->name('book.video');
    Route::post('/book/{book}/logout', [BookAccessController::class, 'logout'])->name('book.logout');
});

// ========================================
// ADMIN ROUTES (Protected by Auth)
// ========================================

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books CRUD
    Route::resource('books', AdminBookController::class);

    // Additional Book Actions
    Route::patch('/books/{book}/toggle-status', [AdminBookController::class, 'toggleStatus'])->name('books.toggle');
    Route::delete('/books/pages/{page}', [AdminBookController::class, 'deletePage'])->name('books.pages.delete');
});

// ========================================
// AUTHENTICATION ROUTES (dari Breeze)
// ========================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
