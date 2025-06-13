<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PerangkatMateriController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::post('/auth/sign-in', [AuthController::class, 'login']);
Route::post('/auth/sign-out', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('user', UserController::class)->except(['show']);
    Route::apiResource('kelas', KelasController::class)->except(['show']);
    Route::apiResource('sekolah', SekolahController::class)->except(['show']);
    Route::apiResource('materi', MateriController::class)->except(['show']);
    Route::apiResource('presensi', PresensiController::class)->except(['show']);
    Route::apiResource('artikel', ArtikelController::class)->except(['show']);
    Route::apiResource('soal', SoalController::class)->except(['show']);
    Route::apiResource('nilai', NilaiController::class)->except(['show']);
    Route::apiResource('perangkat-materi', PerangkatMateriController::class)->except(['show']);
});


Route::fallback(function (Request $request) {
    return response()->json([
        'message' => 'Endpoint not found',
        'status' => false,
    ], 404);
});

