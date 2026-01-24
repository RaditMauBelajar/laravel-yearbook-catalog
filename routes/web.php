<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// =======================
// FRONTEND CONTROLLERS
// =======================
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookAccessController;
use App\Http\Controllers\BookViewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\CustomerChatController;

// =======================
// ADMIN CONTROLLERS
// =======================
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\InquiryAdminController;

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (BREEZE)
|--------------------------------------------------------------------------
| Laravel Breeze default ke /dashboard
| Bedakan admin vs customer (biar customer ga nyasar ke admin)
| Pakai Request injection supaya Intelephense paham (hilang warning).
*/
Route::middleware(['auth', 'verified'])->get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| FRONTEND / PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');


// =======================
// BOOK ACCESS (LOGIN VIEWER)
// =======================
Route::get('/book/{book}/login', [BookAccessController::class, 'showLogin'])
    ->name('book.login');

Route::post('/book/{book}/login', [BookAccessController::class, 'login'])
    ->name('book.login.submit');


// =======================
// BOOK VIEWER (PROTECTED)
// =======================
Route::middleware(['book.access'])->group(function () {

    Route::get('/book/{book}/view', [BookViewController::class, 'show'])
        ->name('book.view');

    Route::get('/book/{book}/pages', [BookViewController::class, 'getPages'])
        ->name('book.pages');

    Route::get('/book/{book}/download', [BookViewController::class, 'download'])
        ->name('book.download');

    Route::get('/book/{book}/video', [BookViewController::class, 'streamVideo'])
        ->name('book.video');

    Route::post('/book/{book}/logout', [BookAccessController::class, 'logout'])
        ->name('book.logout');
});


// =======================
// INQUIRY (PUBLIC - FORM LAMA)
// =======================
Route::post('/inquiry', [InquiryController::class, 'store'])
    ->name('inquiry.store');


/*
|--------------------------------------------------------------------------
| CHAT CUSTOMER (LOGIN REQUIRED - POPUP)
|--------------------------------------------------------------------------
| Guest klik icon → masuk /chat → Laravel lempar ke login
| Setelah login → controller open() redirect ke /?openChat=1
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/chat', [CustomerChatController::class, 'open'])
        ->name('chat.open');

    Route::get('/chat/thread', [CustomerChatController::class, 'thread'])
        ->name('chat.thread');

    Route::post('/chat/send', [CustomerChatController::class, 'send'])
        ->name('chat.send');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (AUTH + VERIFIED + ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Books CRUD
        Route::resource('books', AdminBookController::class);

        // Book extra actions
        Route::patch('/books/{book}/toggle-status', [AdminBookController::class, 'toggleStatus'])
            ->name('books.toggle');

        Route::delete('/books/pages/{page}', [AdminBookController::class, 'deletePage'])
            ->name('books.pages.delete');

        // Inquiry admin (chat)
        Route::get('/inquiries', [InquiryAdminController::class, 'index'])
            ->name('inquiries.index');

        Route::get('/inquiries/{inquiry}', [InquiryAdminController::class, 'show'])
            ->name('inquiries.show');

        Route::post('/inquiries/{inquiry}/reply', [InquiryAdminController::class, 'reply'])
            ->name('inquiries.reply');
    });


/*
|--------------------------------------------------------------------------
| PROFILE (BREEZE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


// =======================
// AUTH ROUTES (BREEZE)
// =======================
require __DIR__ . '/auth.php';
