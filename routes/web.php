<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\HibahWizardController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\UserController;
// Pastikan buat controller ini atau arahkan ke method di MonitoringController
use App\Http\Controllers\Admin\RiwayatController; 

// Landing & Auth
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    
    // USER SIDE
    Route::get('/wizard', [HibahWizardController::class, 'index'])->name('user.wizard');
    Route::post('/wizard/delete-file', [HibahWizardController::class, 'deleteFile'])->name('user.wizard.delete-file');
    Route::get('/wizard/paket/{paketId}/step/{step}', [HibahWizardController::class, 'showStep'])->name('user.step');
    Route::post('/wizard/store', [HibahWizardController::class, 'store'])->name('user.wizard.store');
    Route::get('/dashboard/progres', [HibahWizardController::class, 'progresIndex'])->name('user.progres.index');
    Route::get('/dashboard/progres/{paketId}', [HibahWizardController::class, 'progresDetail'])->name('user.progres.detail');
    Route::get('/notifications/{id}/read', [HibahWizardController::class, 'markAsRead'])->name('notifications.read');

    // ADMIN SIDE
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [MonitoringController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        
        // Monitoring & Detail
        Route::get('/paket/{paketId}/users', [MonitoringController::class, 'showUsers'])->name('paket.users');
        Route::get('/paket/{paketId}/detail/{userId}', [MonitoringController::class, 'detailUser'])->name('paket.detail');
        Route::post('/verify/{id}', [MonitoringController::class, 'verify'])->name('verify.submit');

        // CRUD Paket
        Route::post('/paket/store', [PaketController::class, 'store'])->name('paket.store');
        Route::put('/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');

        // Reset Data (Sudah disesuaikan prefixnya)
        Route::delete('/monitoring/reset/{paketId}/{userId}', [MonitoringController::class, 'resetUserSubmission'])->name('monitoring.reset');

        // Riwayat (Menu baru yang Anda minta)
        Route::get('/riwayat', [MonitoringController::class, 'riwayatIndex'])->name('riwayat.index');
    });
});