<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LanguageController;

Route::get('/language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');
Route::get('/', fn() => redirect('/login'));

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::middleware('auth.web')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/pets', \App\Livewire\Pets\Index::class)->name('pets.index');

    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::middleware(['auth.web', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', \App\Livewire\Admin\Users\Index::class)->name('users.index');
});