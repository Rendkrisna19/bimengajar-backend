<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EdukasiLocationController;
use App\Http\Controllers\Api\CoinProviderController;
use App\Http\Controllers\Api\PengajuanEdukasiController;
use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\DokumentasiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Public routes
Route::get('/locations', [EdukasiLocationController::class, 'index']);
Route::get('/articles', [App\Http\Controllers\Api\ArticleController::class, 'index']);
Route::get('/articles/{slug}', [App\Http\Controllers\Api\ArticleController::class, 'show']);
Route::get('/abouts', [AboutController::class, 'index']);

// News (Berita) — public
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);

// Dokumentasi — public
Route::get('/dokumentasi', [DokumentasiController::class, 'index']);
Route::get('/dokumentasi/{id}', [DokumentasiController::class, 'show']);

// Pojok Koin — public
Route::get('/coin-providers', [CoinProviderController::class, 'index']);
Route::post('/coin-providers', [CoinProviderController::class, 'store']);
Route::get('/coin-providers/{id}', [CoinProviderController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Pengajuan Edukasi
    Route::post('/pengajuan-edukasi', [PengajuanEdukasiController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/pengajuan-edukasi', [PengajuanEdukasiController::class, 'index']);
    Route::get('/pengajuan-edukasi/{id}', [PengajuanEdukasiController::class, 'show']);
    Route::patch('/pengajuan-edukasi/{id}/status', [PengajuanEdukasiController::class, 'updateStatus'])->middleware('role:admin');

    
    // CRUD for locations
    Route::post('/locations', [EdukasiLocationController::class, 'store']);
    Route::post('/locations/{id}', [EdukasiLocationController::class, 'update']);
    Route::delete('/locations/{id}', [EdukasiLocationController::class, 'destroy']);
    
    // CRUD for articles
    Route::post('/articles', [App\Http\Controllers\Api\ArticleController::class, 'store']);
    Route::post('/articles/{id}', [App\Http\Controllers\Api\ArticleController::class, 'update']);
    Route::delete('/articles/{id}', [App\Http\Controllers\Api\ArticleController::class, 'destroy']);

    // Pojok Koin — admin only
    Route::put('/coin-providers/{id}', [CoinProviderController::class, 'update']);
    Route::delete('/coin-providers/{id}', [CoinProviderController::class, 'destroy']);
    Route::patch('/coin-providers/{id}/toggle', [CoinProviderController::class, 'toggle']);

    // About routes
    Route::post('/abouts/{type}', [AboutController::class, 'update']);

    // CRUD for news (berita)
    Route::post('/news', [NewsController::class, 'store']);
    Route::post('/news/{id}', [NewsController::class, 'update']);
    Route::delete('/news/{id}', [NewsController::class, 'destroy']);

    // CRUD for dokumentasi
    Route::post('/dokumentasi', [DokumentasiController::class, 'store']);
    Route::post('/dokumentasi/{id}', [DokumentasiController::class, 'update']);
    Route::delete('/dokumentasi/{id}', [DokumentasiController::class, 'destroy']);
});