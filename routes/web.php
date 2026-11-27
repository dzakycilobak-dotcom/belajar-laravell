<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiAkademikController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;

// Rute Latihan Pertemuan 13 (Katalog Mata Kuliah)
Route::prefix('matakuliah')->group(function () {
    Route::get('/', [MataKuliahController::class, 'index']);
    Route::get('/detail/{kode}', [MataKuliahController::class, 'detail']);
});

// Rute Latihan Pertemuan 14 (Autentikasi & Otorisasi Role)
Route::get('/register', [AuthController::class, 'tampilRegister']);
Route::post('/register', [AuthController::class, 'prosesRegister']);
Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/panel-admin', function () {
        return "<h1>Panel Ruang Server Admin</h1><p>Halo Admin! Kamu memiliki hak akses penuh di halaman ini.</p><a href='/dashboard'>Kembali ke Dashboard</a>";
    })->middleware('role:admin');

    Route::get('/krs-mahasiswa', function () {
        return "<h1>Halaman Pengisian KRS Mahasiswa</h1><p>Silakan pilih mata kuliah sesuai peminatan kamu semester ini.</p><a href='/dashboard'>Kembali ke Dashboard</a>";
    })->middleware('role:mahasiswa');
});
// Rute Latihan Pertemuan 15 (Portal Konsumen RESTful API Mahasiswa)
Route::get('/client-mahasiswa', function () {
    return view('client_mahasiswa');
});

// Rute Latihan Pertemuan 16 (Simulasi Error Handling & Debugging Nilai Akademik)
Route::prefix('uji-nilai')->group(function () {
    Route::get('/', [NilaiAkademikController::class, 'index']);
    Route::get('/hitung/{nim}', [NilaiAkademikController::class, 'hitungIp']);
});

Route::prefix('laporan')->group(function () {
    Route::get('/', [LaporanController::class, 'index']);
    Route::get('/pdf', [LaporanController::class, 'exportPdf']);
    Route::get('/excel', [LaporanController::class, 'exportExcel']);
});