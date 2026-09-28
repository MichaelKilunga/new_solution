<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\WebPortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Information & Services Portal
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicPortalController::class, 'index'])->name('public.portal');

/*
|--------------------------------------------------------------------------
| Guest Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Protected Admin Dashboard & Operations (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [WebPortalController::class, 'index'])->name('dashboard');
    Route::get('/admin', [WebPortalController::class, 'index'])->name('admin');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
