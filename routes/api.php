<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EdukasiLocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1'); // Throttle 10 req/min
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Public read-only route for landing page map
Route::get('/locations', [EdukasiLocationController::class, 'index']);
Route::get('/articles', [App\Http\Controllers\Api\ArticleController::class, 'index']);
Route::get('/articles/{slug}', [App\Http\Controllers\Api\ArticleController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // CRUD for locations
    Route::post('/locations', [EdukasiLocationController::class, 'store']);
    Route::post('/locations/{id}', [EdukasiLocationController::class, 'update']);
    Route::delete('/locations/{id}', [EdukasiLocationController::class, 'destroy']);
    
    // CRUD for articles
    Route::post('/articles', [App\Http\Controllers\Api\ArticleController::class, 'store']);
    Route::post('/articles/{id}', [App\Http\Controllers\Api\ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [App\Http\Controllers\Api\ArticleController::class, 'destroy']);
});
