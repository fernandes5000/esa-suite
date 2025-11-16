<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PetController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| API (V1) Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // --- Public Auth Routes ---
    
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    Route::post('/auth/login', [AuthController::class, 'login']);

    
    // --- Protected Routes (Require Sanctum token) ---
    Route::middleware('auth:sanctum')->group(function () {

        // --- User-Specific Routes ---
        
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        
        Route::post('/pets/{pet}/photo', [PetController::class, 'uploadPhoto']);
        
        Route::apiResource('/pets', PetController::class);

        
        // --- Admin-Only Routes ---

        // This group creates the /api/v1/admin/... prefix
        Route::prefix('admin') 
             ->name('admin.')
             // We use a general permission for the whole admin user management
             ->middleware(['permission:admin.users.manage,sanctum'])
             ->group(function () {
            
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            
            Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        });

    });
});