<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', fn() => redirect('/login'));

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware('auth.web')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/pets', \App\Livewire\Pets\Index::class)
        ->name('pets.index');
});
