<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Paket;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan 'layouts.user' karena kita sudah memisahkan layout user dan admin
        view()->composer('layouts.user', function ($view) {
            if (Auth::check()) {
                $pakets = Paket::all();

                foreach ($pakets as $paket) {
                    // 1. Hitung Progres (Berdasarkan file yang diupload)
                    $completed = Submission::where('user_id', Auth::id())
                        ->where('paket_id', $paket->id)
                        ->whereNotNull('file_path')
                        ->count();
                    
                    $paket->progres_persen = ($completed / 11) * 100;
                    $paket->step_selesai = $completed;

                    // 2. Logika Status Admin (Approved/Rejected/Pending)
                    // Cek jika ada satu saja dokumen yang ditolak
                    $hasRejected = Submission::where('user_id', Auth::id())
                        ->where('paket_id', $paket->id)
                        ->where('status', 'rejected')
                        ->exists();

                    // Cek jika semua dokumen (11 step) sudah disetujui
                    $approvedCount = Submission::where('user_id', Auth::id())
                        ->where('paket_id', $paket->id)
                        ->where('status', 'approved')
                        ->count();

                    if ($hasRejected) {
                        $paket->status_admin = 'rejected';
                    } elseif ($approvedCount >= 11) {
                        $paket->status_admin = 'approved';
                    } else {
                        $paket->status_admin = 'pending';
                    }
                }

                $view->with('pakets', $pakets);
            }
        });
    }
}