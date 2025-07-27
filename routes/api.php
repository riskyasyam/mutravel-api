<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaketController;
use App\Http\Controllers\Api\JamaahController;
use App\Http\Controllers\Api\DokumentasiController;
use App\Http\Controllers\Api\TestimoniController;
use App\Http\Controllers\Api\PemesananController;
use App\Http\Controllers\Api\TabunganController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\ExportPemesananController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute API untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| diberi prefix /api.
|
*/

// --- ROUTES UNTUK OTENTIKASI ---
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// --- ROUTES BARU UNTUK DASHBOARD ---
Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
Route::get('/pemesanans/recent', [PemesananController::class, 'recent']);

// --- ROUTES UNTUK LANDING PAGE (PUBLIK) ---
Route::get('/pakets/landing', [PaketController::class, 'index']);
Route::get('/testimonis/landing', [TestimoniController::class, 'index']);
Route::get('/dokumentasi/landing', [DokumentasiController::class, 'index']);


// --- ROUTES UNTUK DASHBOARD ADMIN (CRUD) ---
// Di masa depan, semua route ini akan dilindungi oleh middleware 'auth:sanctum'

// Route standar CRUD untuk Paket
Route::apiResource('pakets', PaketController::class);
Route::get('/paket', [PaketController::class, 'index']);
Route::get('/dashboard/pakets', [PaketController::class, 'getAllForDashboard']);


// Route standar CRUD untuk Jamaah
Route::apiResource('jamaahs', JamaahController::class);

// Route standar CRUD untuk Dokumentasi
Route::apiResource('dokumentasi', DokumentasiController::class);


// Route standar CRUD untuk Testimoni
Route::apiResource('testimonis', TestimoniController::class);


// Route standar CRUD untuk Pemesanan
Route::apiResource('pemesanans', PemesananController::class);


// Route standar CRUD untuk Tabungan (Setoran)
Route::apiResource('tabungans', TabunganController::class);
// Route khusus untuk melihat riwayat tabungan per jamaah
Route::get('/jamaahs/{jamaah}/tabungans', [TabunganController::class, 'index']);
Route::get('/tabungan/summary', [TabunganController::class, 'summary']);

// Route khusus untuk melihat laporan
Route::get('/laporan/pemesanan', [LaporanController::class, 'pemesanan']);
Route::get('/export/pemesanan', [ExportPemesananController::class, 'export']);
