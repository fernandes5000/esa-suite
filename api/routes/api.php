<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\PetController;
use App\Http\Controllers\Api\V1\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\V1\EsaRequestController;
use App\Http\Controllers\Api\V1\Therapist\RequestController;

/*
|--------------------------------------------------------------------------
| API (V1) Routes
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // --- Public Auth Routes ---
    
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/esa-request/{esaRequest}/download', [RequestController::class, 'downloadPdf']);
    
    // --- Protected Routes (Require Sanctum token) ---
    Route::middleware('auth:sanctum')->group(function () {

     // --- Therapist Routes ---
     Route::prefix('therapist')
               ->name('therapist.')
               ->middleware(['permission:requests.view.assigned,sanctum']) 
               ->group(function () {
          
          Route::get('/requests', [RequestController::class, 'index']);
          
          Route::post('/requests/{esaRequest}/approve', [RequestController::class, 'approve']);
     });
     
        // --- User-Specific Routes ---
        
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        
        Route::post('/pets/{pet}/photo', [PetController::class, 'uploadPhoto']);

        Route::get('/esa-request/active', [EsaRequestController::class, 'getActiveOrCreateRequest'])
             ->name('esa-request.active');

        Route::put('/esa-request/{esaRequest}', [EsaRequestController::class, 'update'])
             ->name('esa-request.update');

        Route::post('/esa-request/{esaRequest}/pets', [EsaRequestController::class, 'syncPets'])
             ->name('esa-request.sync-pets');
        
        Route::apiResource('/pets', PetController::class);

        
        // --- Admin-Only Routes ---

        Route::prefix('admin') 
             ->name('admin.')
             ->middleware(['permission:admin.users.manage,sanctum'])
             ->group(function () {
            
            Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
            
            Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

            Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        });

    });
});