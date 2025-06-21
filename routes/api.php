<?php

use App\Models\User;
use App\Models\SoalModel;
use App\Models\KelasModel;
use App\Models\NilaiModel;
use App\Models\MateriModel;
use App\Models\ArtikelModel;
use App\Models\SekolahModel;
use Illuminate\Http\Request;
use App\Models\PresensiModel;
use App\Models\PerangkatMateriModel;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\BankBacaanController;
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



Route::post('/auth/sign-in', [AuthController::class, 'login'])->name('auth.sign-in')->middleware('throttle:5,1');
Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/auth/sign-out', [AuthController::class, 'logout']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::patch('/auth/update-profile', [AuthController::class, 'updateProfile']);
    Route::patch('/auth/change-password', [AuthController::class, 'changePassword']);

    Route::apiResource('artikel', ArtikelController::class)->only(['index']);
    Route::apiResource('soal', SoalController::class)->only(['index']);
    Route::apiResource('nilai', NilaiController::class)->only(['index', "store"]);

    // materi & perangkat materi & presensi
    Route::apiResource('materi', MateriController::class)->except(['show']);
    Route::apiResource('perangkat-materi', PerangkatMateriController::class)->except(['show']);
    
    Route::apiResource('presensi', PresensiController::class)->only(['index']);
    Route::apiResource('bank-bacaan', BankBacaanController::class)->only(['index']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return response()->json([
            'status' => true,
            'message' => [
                'User' => User::count(),
                'Kelas' => KelasModel::count(),
                'Sekolah' => SekolahModel::count(),
                'Materi' => MateriModel::count(),
                'Presensi' => PresensiModel::count(),
                'Perangkat Materi' => PerangkatMateriModel::count(),
                'Artikel' => ArtikelModel::count(),
                'Soal' => SoalModel::count(),
                'Nilai' => NilaiModel::count(),
            ]
        ]);
    });
    Route::apiResource('user', UserController::class)->except(['show']);
    Route::apiResource('kelas', KelasController::class)->except(['show']);
    Route::apiResource('sekolah', SekolahController::class)->except(['show']);
    Route::apiResource('presensi', PresensiController::class)->except(['show', 'index']);
    Route::apiResource('artikel', ArtikelController::class)->except(['index']);
    Route::apiResource('soal', SoalController::class)->except(['index']);
    Route::apiResource('nilai', NilaiController::class)->except(['index', 'store']);
    Route::apiResource('bank-bacaan', BankBacaanController::class)->except(['index']);
});


Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint not found',
        'status' => false,
    ], 404);
});

