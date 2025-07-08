<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GangguanController;
use App\Http\Controllers\PenyebabController;
use App\Http\Controllers\SolusiController;
use App\Http\Controllers\KategoriSolusiController;
use App\Http\Controllers\AturanController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\DiagnosaHasilController;


Route::get('login', [LoginController::class, 'login'])->name('login');
Route::post('login', [LoginController::class, 'proses_login'])->name('proses_login');

Route::middleware(['auth', 'level:admin,superadmin'])->group(function () {
    Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// DIAGNOSA ROUTES - Perbaikan yang benar
Route::get('diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index'); // Ubah nama route ke diagnosa.index
Route::post('diagnosa/proses', [DiagnosaController::class, 'proses'])->name('diagnosa.proses');
Route::get('diagnosahasil', [DiagnosaHasilController::class, 'hasil'])->name('diagnosa.hasil');
});

Route::middleware(['auth', 'level:superadmin'])->group(function () {
Route::get('datauser', [UserController::class, 'index'])->name('user.index');
Route::post('datauser', [UserController::class, 'store'])->name('user.store');
Route::post('datauser/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('datauser/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('datagangguan', [GangguanController::class, 'index'])->name('gangguan.index');
Route::post('datagangguan', [GangguanController::class, 'store'])->name('gangguan.store');
Route::put('datagangguan/{kode_gangguan}', [GangguanController::class, 'update'])->name('gangguan.update');
Route::delete('datagangguan/{kode_gangguan}', [GangguanController::class, 'destroy'])->name('gangguan.destroy');

Route::get('datapenyebab', [PenyebabController::class, 'index'])->name('penyebab.index');
Route::post('datapenyebab', [PenyebabController::class, 'store'])->name('penyebab.store');
Route::put('datapenyebab/{kode_penyebab}', [PenyebabController::class, 'update'])->name('penyebab.update');
Route::delete('datapenyebab/{kode_penyebab}', [PenyebabController::class, 'destroy'])->name('penyebab.destroy');

Route::get('datasolusi', [SolusiController::class, 'index'])->name('solusi.index');
Route::post('datasolusi', [SolusiController::class, 'store'])->name('solusi.store');
Route::put('datasolusi/{kode_solusi}', [SolusiController::class, 'update'])->name('solusi.update');
Route::delete('datasolusi/{kode_solusi}', [SolusiController::class, 'destroy'])->name('solusi.destroy');

Route::get('datakategorisolusi', [KategoriSolusiController::class, 'index'])->name('kategorisolusi.index');
Route::post('datakategorisolusi', [KategoriSolusiController::class, 'store'])->name('kategorisolusi.store');
Route::put('datakategorisolusi/{kode_kategori}', [KategoriSolusiController::class, 'update'])->name('kategorisolusi.update');
Route::delete('datakategorisolusi/{kode_kategori}', [KategoriSolusiController::class, 'destroy'])->name('kategorisolusi.destroy');

Route::get('dataaturan', [AturanController::class, 'index'])->name('aturan.index');
Route::post('dataaturan', [AturanController::class, 'store'])->name('aturan.store');
Route::put('dataaturan/{kode_aturan}', [AturanController::class, 'update'])->name('aturan.update');
Route::delete('dataaturan/{kode_aturan}', [AturanController::class, 'destroy'])->name('aturan.destroy');
});


// Test route
Route::get('/test-solusi/{kode?}', [DiagnosaHasilController::class, 'testSolusi']);

Route::get('/', function () {
    return redirect()->route('login');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

