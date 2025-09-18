<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\CategoryController;

// Public (always accessible)
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/qrcode/{qrcode_key}', [QRController::class, 'show'])->name('qr.show');

// Guest-only routes (not available after login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:15,1'); // max 15 per min

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:15,1'); // max 15 per min
});

Route::get('/qr/create', [QRController::class, 'create'])->name('qr.create');
Route::post('/qr/preview', [QRController::class, 'preview'])->name('qr.preview');
Route::post('/qr/store', [QRController::class, 'store'])->name('qr.store');

// Auth-only routes (must be logged in)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/qrcodes', [QRController::class, 'index'])->name('qr.index');
    Route::get('/qr/{qrcode_id}/edit', [QRController::class, 'edit'])->name('qr.edit');
    Route::put('/qrcodes/{qrcode_id}', [QRController::class, 'update'])->name('qr.update');
    Route::put('/qrcodes/{qrcode_id}/rename', [QRController::class, 'rename'])->name('qr.rename');
    Route::delete('/qrcodes/{qrcode_id}', [QRController::class, 'destroy'])->name('qr.destroy');
});
