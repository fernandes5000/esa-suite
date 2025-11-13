<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PetController;

Route::prefix('v1')->group(function () {

    // --- Public Auth Routes ---
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // --- Protected Routes ---
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Pets CRUD
        Route::apiResource('pets', PetController::class);

        // Upload Photo
        Route::post('/pets/{pet}/photo', [PetController::class, 'uploadPhoto']);
    });
});
