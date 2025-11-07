<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/check-auth', [AuthController::class, 'checkAuth'])->name('check.auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



// Protected routes
Route::middleware(['manual.auth'])->group(function () {
    Route::get('/manual', function () {
        return view('manual');
    })->name('manual');
});
