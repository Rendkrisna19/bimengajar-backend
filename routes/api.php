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
use App\Http\Controllers\Api\MitraController;
use App\Http\Controllers\Api\TentangKamiController;
use App\Http\Controllers\Api\KalenderKegiatanController;
use App\Http\Controllers\Api\UlasanController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});

// Public routes
Route::get('/locations', [EdukasiLocationController::class, 'index']);
Route::get('/articles', [App\Http\Controllers\Api\ArticleController::class, 'index']);
Route::get('/articles/{slug}', [App\Http\Controllers\Api\ArticleController::class, 'show']);
Route::get('/abouts', [AboutController::class, 'index']);

// News (Berita) — public
Route::get('/news', [NewsController::class, 'index']);
Route::get('/news/{slug}', [NewsController::class, 'show']);

// Test route to check Kemdikbud API
Route::get('/test-kemdikbud', function (Request $request) {
    try {
        $url = env('API_SEKOLAH_URL', 'https://dapo.kemdikbud.go.id/api/getSekolah');
        // Add minimal headers to simulate a real browser request, as some government APIs block default HTTP clients
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Accept' => 'application/json',
        ])->timeout(10)->get($url);
        
        return response()->json([
            'status' => $response->status(),
            'successful' => $response->successful(),
            'body' => $response->json() ?? $response->body()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});

// External API Search (Public)
Route::get('/locations/external', [App\Http\Controllers\Api\EdukasiLocationController::class, 'searchExternal']);
Route::get('/locations/external-count', [App\Http\Controllers\Api\EdukasiLocationController::class, 'getExternalCount']);

// Hero Banner — public
Route::get('/hero-banners', [App\Http\Controllers\Api\HeroBannerController::class, 'index']);
Route::get('/hero-banners/{id}', [App\Http\Controllers\Api\HeroBannerController::class, 'show']);

// Dokumentasi — public
Route::get('/dokumentasi', [DokumentasiController::class, 'index']);
Route::get('/dokumentasi/{id}', [DokumentasiController::class, 'show']);

// Mitra Edukasi — public
Route::get('/mitras', [MitraController::class, 'index']);
Route::post('/mitras', [MitraController::class, 'store']); // Public submit kolaborasi
Route::get('/mitras/{id}', [MitraController::class, 'show']);

// Ulasan — public
Route::get('/ulasan', [UlasanController::class, 'index']);
Route::post('/ulasan', [UlasanController::class, 'store']);

// Pojok Koin — public
Route::get('/coin-providers', [CoinProviderController::class, 'index']);
Route::post('/coin-providers', [CoinProviderController::class, 'store']);
Route::get('/coin-providers/{id}', [CoinProviderController::class, 'show']);

// Kalender Kegiatan — public
Route::get('/kalender', [KalenderKegiatanController::class, 'index']);
Route::get('/kalender/{id}', [KalenderKegiatanController::class, 'show']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Kalender Kegiatan — protected
    Route::post('/kalender', [KalenderKegiatanController::class, 'store']);
    Route::put('/kalender/{id}', [KalenderKegiatanController::class, 'update']);
    Route::delete('/kalender/{id}', [KalenderKegiatanController::class, 'destroy']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

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

    // Admin CRUD for mitras
    Route::post('/mitras/{id}', [MitraController::class, 'update']);
    Route::delete('/mitras/{id}', [MitraController::class, 'destroy']);
    Route::patch('/mitras/{id}/status', [MitraController::class, 'toggleStatus']);

    // Materi Edukasi (Admin CRUD)
    Route::post('/kategori-materi', [\App\Http\Controllers\Api\KategoriMateriController::class, 'store'])->middleware('role:admin');
    Route::post('/kategori-materi/{id}', [\App\Http\Controllers\Api\KategoriMateriController::class, 'update'])->middleware('role:admin'); // POST for form-data
    Route::delete('/kategori-materi/{id}', [\App\Http\Controllers\Api\KategoriMateriController::class, 'destroy'])->middleware('role:admin');

    Route::post('/materi-edukasi', [\App\Http\Controllers\Api\MateriEdukasiController::class, 'store'])->middleware('role:admin');
    Route::post('/materi-edukasi/{id}', [\App\Http\Controllers\Api\MateriEdukasiController::class, 'update'])->middleware('role:admin'); // POST karena Form-Data upload
    Route::delete('/materi-edukasi/{id}', [\App\Http\Controllers\Api\MateriEdukasiController::class, 'destroy'])->middleware('role:admin');
    // Hero Banner (Admin CRUD)
    Route::post('/hero-banners', [App\Http\Controllers\Api\HeroBannerController::class, 'store']);
    Route::post('/hero-banners/{id}', [App\Http\Controllers\Api\HeroBannerController::class, 'update']);
    Route::delete('/hero-banners/{id}', [App\Http\Controllers\Api\HeroBannerController::class, 'destroy']);
    Route::patch('/hero-banners/{id}/toggle', [App\Http\Controllers\Api\HeroBannerController::class, 'toggleActive']);

    // Manajemen User
    Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'index'])->middleware('role:admin');
    Route::post('/users', [App\Http\Controllers\Api\UserController::class, 'store'])->middleware('role:admin');
    Route::get('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'show'])->middleware('role:admin');
    Route::put('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'update'])->middleware('role:admin');
    Route::delete('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy'])->middleware('role:admin');
});

// Materi Edukasi (Public Routes)
Route::get('/kategori-materi', [\App\Http\Controllers\Api\KategoriMateriController::class, 'index']);
Route::get('/materi-edukasi', [\App\Http\Controllers\Api\MateriEdukasiController::class, 'index']);
Route::get('/materi-edukasi/{slug}', [\App\Http\Controllers\Api\MateriEdukasiController::class, 'show']);