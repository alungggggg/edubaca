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

Route::get('/kelas', [KelasController::class, 'index']);
Route::post('/kelas', [KelasController::class, 'create']);
Route::patch('/kelas/{id}', [KelasController::class, 'update']);
Route::delete('/kelas/{id}', [KelasController::class, 'destroy']);

Route::get('/sekolah', [SekolahController::class, 'index']);
Route::post('/sekolah', [SekolahController::class, 'store']);
Route::patch('/sekolah/{id}', [SekolahController::class, 'update']);
Route::delete('/sekolah/{id}', [SekolahController::class, 'destroy']);

Route::get('/user', [UserController::class, 'index']);
Route::post('/user', [UserController::class, 'add']);
Route::patch('/user/{id}', [UserController::class, 'update']);
Route::delete('/user/{id}', [UserController::class, 'destroy']);

// materi
Route::get('/materi', [MateriController::class, 'get']);
Route::post('/materi', [MateriController::class, 'create']);
Route::patch('/materi/{id}', [MateriController::class, 'update']);
Route::delete('/materi/{id}', [MateriController::class, 'delete']);

Route::post('/auth/sign-in', [AuthController::class, 'login']);
// 'adminOnly'
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/sign-out', [AuthController::class, 'logout']);

    

    // artikel
    Route::get('/artikel', [ArtikelController::class, 'getArtikel']);
    Route::post('/artikel', [ArtikelController::class, 'createArtikel']);
    Route::patch('/artikel/{id}', [ArtikelController::class, 'updateArtikel']);
    Route::delete('/artikel/{id}', [ArtikelController::class, 'deleteArtikel']);

    // soal
    Route::get('/soal', [SoalController::class, 'getSoal']);
    Route::post('/soal', [SoalController::class, 'createSoal']);
    Route::patch('/soal/{id}', [SoalController::class, 'updateSoal']);
    Route::delete('/soal/{id}', [SoalController::class, 'deleteSoal']);

    // nilai
    Route::get('/nilai', [NilaiController::class, 'getNilai']);
    Route::post('/nilai', [NilaiController::class, 'createNilai']);
    Route::patch('/nilai/{id}', [NilaiController::class, 'updateNilai']);
    Route::delete('/nilai/{id}', [NilaiController::class, 'deleteNilai']);

    

    // perangkat materi
    Route::get('/materi/perangkat', [MateriController::class, 'get']);
    Route::post('/materi/perangkat', [MateriController::class, 'create']);
    Route::patch('/materi/perangkat/{id}', [MateriController::class, 'update']);
    Route::delete('/materi/perangkat/{id}', [MateriController::class, 'delete']);
});

