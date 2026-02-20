<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HibahWizardController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\PaketController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
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

    // ADMIN SIDE
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [MonitoringController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        
        // Menampilkan daftar user per paket (Klik dari Sidebar/Dashboard)
        Route::get('/paket/{paketId}/users', [MonitoringController::class, 'showUsers'])->name('paket.users');
        
        // Detail Verifikasi 11 Step per User
        Route::get('/paket/{paketId}/detail/{userId}', [MonitoringController::class, 'detailUser'])->name('paket.detail');
        
        // Proses Verifikasi
        Route::post('/verify/{id}', [MonitoringController::class, 'verify'])->name('verify.submit');

        // CRUD Paket
        Route::post('/paket/store', [PaketController::class, 'store'])->name('paket.store');
        Route::put('/paket/{id}', [PaketController::class, 'update'])->name('paket.update');
        Route::delete('/paket/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');
    });
});