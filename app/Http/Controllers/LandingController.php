<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Mengambil kombinasi unik paket_id dan user_id yang sudah memiliki submission
        // Ini akan memisahkan "Satker Wilayah I" dan "Lioandika" meskipun paketnya sama
        $submissionsGroups = Submission::select('paket_id', 'user_id')
            ->groupBy('paket_id', 'user_id')
            ->with(['paket', 'user'])
            ->get();

        $monitoringData = $submissionsGroups->map(function ($group) {
            $paket = $group->paket;
            $user = $group->user;

            // Mengambil semua submission khusus untuk user ini di paket ini
            $userSubmissions = Submission::where('paket_id', $group->paket_id)
                ->where('user_id', $group->user_id)
                ->get();

            $stepLabels = [
                1 => 'Pernyataan Hibah', 2 => 'Permohonan Tim', 3 => 'SK Tim Balai',
                4 => 'BA Penelitian', 5 => 'Saran Teknis', 6 => 'Rekomtek & Izin',
                7 => 'Persetujuan Hibah', 8 => 'BAST & Naskah', 9 => 'SK Penghapusan',
                10 => 'Bukti SAKTI', 11 => 'Laporan KPKNL'
            ];

            $visualSteps = [];
            foreach ($stepLabels as $num => $label) {
                $sub = $userSubmissions->where('step_number', $num)->first();
                
                $status = 'lock'; 
                if ($sub) {
                    if (in_array($sub->status, ['disetujui', 'verified'])) {
                        $status = 'verified'; // HIJAU
                    } elseif ($sub->status === 'ditolak' || $sub->status === 'rejected') {
                        $status = 'rejected'; // MERAH
                    } elseif (!empty($sub->file_path)) {
                        $status = 'completed'; // KUNING
                    }
                }

                $visualSteps[] = [
                    'nomor' => $num,
                    'label' => $label,
                    'status' => $status
                ];
            }

            return (object)[
                'nama_paket' => $paket->nama_paket ?? 'Tanpa Nama',
                'user_name' => $user->name ?? 'Anonim',
                'visual_steps' => $visualSteps,
                'total_verified' => $userSubmissions->whereIn('status', ['disetujui', 'verified'])->count()
            ];
        });

        return view('landing', compact('monitoringData'));
    }
}