<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\AbsensiController as AdminAbsensiController;
use App\Http\Controllers\Admin\IzinController as AdminIzinController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;


// ==================================================
// HALAMAN AWAL
// ==================================================
Route::get('/', function () {
    return redirect()->route('login');
});

// ==================================================
// LOGIN & LOGOUT
// ==================================================
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================================================
// PENDAFTARAN AKUN
// ==================================================
Route::get('/register-account', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register-account', [RegisterController::class, 'register'])->name('register.process');

// ==================================================
// TANTANGAN WAJAH UNTUK PENDAFTARAN
// ==================================================
Route::post('/face-registration-challenge/start', [AbsensiController::class, 'startFaceChallenge'])
    ->middleware('throttle:10,1')
    ->name('face.registration.challenge.start');
Route::post('/face-registration-challenge/verify', [AbsensiController::class, 'verifyFaceChallenge'])
    ->middleware('throttle:10,1')
    ->name('face.registration.challenge.verify');

// ==================================================
// ROUTE PEGAWAI YANG HARUS LOGIN
// ==================================================
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Scan Absensi
    Route::get('/kios-scan', [AbsensiController::class, 'scan'])->name('scan');
    Route::post('/absen/store', [AbsensiController::class, 'storeAbsen'])->name('absen.store');

    // Tantangan Wajah untuk Absensi
    Route::post('/face-challenge/start', [AbsensiController::class, 'startFaceChallenge'])
        ->middleware('throttle:10,1')
        ->name('face.challenge.start');
    Route::post('/face-challenge/verify', [AbsensiController::class, 'verifyFaceChallenge'])
        ->middleware('throttle:10,1')
        ->name('face.challenge.verify');

    // Profil
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/profil/update', [ProfileController::class, 'update'])->name('profil.update');

    // Izin
    Route::get('/izin', [IzinController::class, 'index'])->name('izin');
    Route::post('/izin', [IzinController::class, 'store'])->name('izin.store');

    // Riwayat
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifikasi/{id}/baca', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::patch('/notifikasi/baca-semua', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Kelola Data Wajah
    Route::get('/profil/kelola-wajah', [AbsensiController::class, 'manageFace'])->name('profil.face');
    Route::post('/profil/kelola-wajah/challenge/start', [AbsensiController::class, 'startFaceChallenge'])
        ->middleware('throttle:10,1')
        ->name('face.update.challenge.start');
    Route::post('/profil/kelola-wajah/challenge/verify', [AbsensiController::class, 'verifyFaceChallenge'])
        ->middleware('throttle:10,1')
        ->name('face.update.challenge.verify');
    Route::post('/profil/kelola-wajah/simpan', [AbsensiController::class, 'updateFace'])
        ->middleware('throttle:5,1')
        ->name('face.update.store');
});

// ==================================================
// PENDAFTARAN WAJAH
// ==================================================
Route::get('/register-face', [AbsensiController::class, 'register'])->name('register.face');
Route::post('/pegawai/store', [AbsensiController::class, 'storePegawai'])
    ->middleware('throttle:5,1')
    ->name('pegawai.store');

// ==================================================
// ADMIN LOGIN
// ==================================================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('admin.login.authenticate');
});

// ==================================================
// PASSWORD RESET
// ==================================================
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ==================================================
// ADMIN SI-ABSENSI
// ==================================================

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::get('/pegawai/{pegawai}', [PegawaiController::class, 'show'])->name('pegawai.show');
    Route::get('/absensi', [AdminAbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/izin', [AdminIzinController::class, 'index'])->name('izin.index');

    // Laporan dan Rekap
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/pdf', [AdminLaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::get('/laporan/export/excel', [AdminLaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    Route::patch('/izin/{izin}/status', [AdminIzinController::class, 'updateStatus'])->name('izin.status');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
