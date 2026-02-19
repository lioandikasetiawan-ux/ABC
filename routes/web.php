<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HibahWizardController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PaketController;

// --- Route Public ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Route Terproteksi (Must Login) ---
Route::middleware('auth')->group(function () {

 // Dashboard pilih paket
    Route::get('/wizard', [HibahWizardController::class, 'index'])->name('user.wizard');
    
    // Halaman per step
    Route::get('/wizard/paket/{paketId}/step/{step}', [HibahWizardController::class, 'showStep'])->name('user.step');
    
    // Proses simpan
    Route::post('/wizard/store', [HibahWizardController::class, 'store'])->name('user.wizard.store');

    // Sisi Admin (Prefix: admin)
    Route::prefix('admin')->group(function () {
        
        // Dashboard & Monitoring Utama
        Route::get('/dashboard', [MonitoringController::class, 'index'])->name('admin.dashboard');

        // CRUD Paket (Menggunakan PaketController agar 11 step otomatis dibuat)
        Route::post('/paket/store', [PaketController::class, 'store'])->name('admin.paket.store');
        Route::put('/paket/{id}', [PaketController::class, 'update'])->name('admin.paket.update');
        Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('admin.paket.destroy');

        // Monitoring & Detail Progress
        Route::get('/paket/{paketId}/users', [MonitoringController::class, 'showUsers'])->name('admin.paket.users');
        Route::get('/paket/{paketId}/user/{userId}', [MonitoringController::class, 'detailUser'])->name('admin.verify.detail');
        
        // Verifikasi Dokumen
        Route::post('/verify/{id}', [MonitoringController::class, 'verify'])->name('admin.verify.submit');
    });

});