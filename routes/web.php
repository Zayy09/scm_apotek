<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|---------------- AUTH ----------------|
*/
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'loginProses']);
Route::post('/register', [AuthController::class, 'registerProses']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'resetPasswordProses']);

/*
|-------------- PROTECTED -------------|
*/
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->middleware('auth');

    // PROFILE
    Route::get('/profile', fn() => view('profile.index'));
    Route::post('/profile/update', [AuthController::class, 'updateProfil']);
    Route::post('/profile/password', [AuthController::class, 'updatePassword']);

    // MASTER
    Route::get('/obat', [App\Http\Controllers\ObatController::class, 'index']);
    Route::post('/obat', [App\Http\Controllers\ObatController::class, 'store']);
    Route::put('/obat/{id}', [App\Http\Controllers\ObatController::class, 'update']);
    Route::delete('/obat/{id}', [App\Http\Controllers\ObatController::class, 'destroy']);
    // KATEGORI
    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index']);
    Route::post('/kategori', [App\Http\Controllers\KategoriController::class, 'store']);
    Route::put('/kategori/{id}', [App\Http\Controllers\KategoriController::class, 'update']);
    Route::delete('/kategori/{id}', [App\Http\Controllers\KategoriController::class, 'destroy']);

    // JENIS
    Route::get('/jenis', [App\Http\Controllers\JenisController::class, 'index']);
    Route::post('/jenis', [App\Http\Controllers\JenisController::class, 'store']);
    Route::put('/jenis/{id}', [App\Http\Controllers\JenisController::class, 'update']);
    Route::delete('/jenis/{id}', [App\Http\Controllers\JenisController::class, 'destroy']);

    // SATUAN
    Route::get('/satuan', [App\Http\Controllers\SatuanController::class, 'index']);
    Route::post('/satuan', [App\Http\Controllers\SatuanController::class, 'store']);
    Route::put('/satuan/{id}', [App\Http\Controllers\SatuanController::class, 'update']);
    Route::delete('/satuan/{id}', [App\Http\Controllers\SatuanController::class, 'destroy']);
    // SUPPLIER
    Route::get('/supplier', [App\Http\Controllers\SupplierController::class, 'index']);
    Route::post('/supplier', [App\Http\Controllers\SupplierController::class, 'store']);
    Route::put('/supplier/{id}', [App\Http\Controllers\SupplierController::class, 'update']);
    Route::delete('/supplier/{id}', [App\Http\Controllers\SupplierController::class, 'destroy']);

    // TRANSAKSI
    Route::get('/transaksi/{jenis}', [App\Http\Controllers\TransaksiController::class, 'index'])->where('jenis', 'masuk|keluar|kadaluarsa');
    Route::post('/transaksi', [App\Http\Controllers\TransaksiController::class, 'store']);
    Route::put('/transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'update']);
    Route::delete('/transaksi/{id}', [App\Http\Controllers\TransaksiController::class, 'destroy']);

    // LAPORAN
    Route::get('/laporan/masuk', [App\Http\Controllers\LaporanController::class, 'masuk']);
    Route::get('/laporan/masuk/export', [App\Http\Controllers\LaporanController::class, 'exportMasuk']);
    Route::get('/laporan/keluar', [App\Http\Controllers\LaporanController::class, 'keluar']);
    Route::get('/laporan/keluar/export', [App\Http\Controllers\LaporanController::class, 'exportKeluar']);
});