<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\AdminActionNotification;

class MonitoringController extends Controller
{
    public function index() {
        // Ambil data submission, group berdasarkan Paket dan User
        $monitoringData = Submission::with(['user', 'paket'])
            ->get()
            ->groupBy(function($item) {
                return $item->paket_id . '-' . $item->user_id;
            })
            ->map(function($group) {
                $first = $group->first();
                $paket = $first->paket;
                
                $uploaded_count = $group->whereNotNull('file_path')->unique('step_number')->count();
                $verified_count = $group->whereIn('status', ['verified', 'disetujui'])->unique('step_number')->count();

                return (object)[
                    'id' => $paket->id,
                    'nama_paket' => $paket->nama_paket,
                    'user_id' => $first->user_id,
                    'user_name' => $first->user->name ?? 'Unknown',
                    'uploaded_count' => $uploaded_count,
                    'verified_count' => $verified_count,
                    'status_label' => $this->getLabel($uploaded_count, $verified_count)
                ];
            });

        $existingPaketIds = $monitoringData->pluck('id')->toArray();
        $emptyPakets = Paket::whereNotIn('id', $existingPaketIds)->get()->map(function($paket) {
            return (object)[
                'id' => $paket->id,
                'nama_paket' => $paket->nama_paket,
                'user_id' => null,
                'user_name' => '-',
                'uploaded_count' => 0,
                'verified_count' => 0,
                'status_label' => 'MENUNGGU USER'
            ];
        });

        $pakets = $monitoringData->concat($emptyPakets)->sortBy('id');

        return view('admin.dashboard', compact('pakets'));
    }

    private function getLabel($uploaded, $verified) {
        if ($uploaded == 0) return 'MENUNGGU USER';
        if ($verified == 11) return 'SELESAI';
        if ($uploaded > $verified) return 'PERLU VERIF';
        return 'ON PROGRESS';
    }

    // Method Baru untuk Halaman Riwayat Aktivitas
    public function riwayatIndex() 
    {
        $histories = DB::table('submission_histories')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.riwayat.index', compact('histories'));
    }

    public function showUsers(Request $request, $paketId) 
    {
        $userId = $request->user_id;

        if (!$userId) {
            $firstSubmission = Submission::where('paket_id', $paketId)->first();
            $userId = $firstSubmission ? $firstSubmission->user_id : null;
        }

        if (!$userId) {
            return redirect()->route('admin.dashboard')->with('error', 'Belum ada data submission untuk paket ini.');
        }

        return redirect()->route('admin.paket.detail', ['paketId' => $paketId, 'userId' => $userId]);
    }

    public function detailUser($paketId, $userId) {
        $user = User::findOrFail($userId);
        $paket = Paket::findOrFail($paketId);
        $submissions = Submission::where('user_id', $userId)
                                 ->where('paket_id', $paketId)
                                 ->get()
                                 ->keyBy('step_number');

        return view('admin.verifikasi-detail', compact('user', 'paket', 'submissions'));
    }

    public function verify(Request $request, $id) {
        $request->validate([
            'status' => 'required',
            'catatan_admin' => 'required_if:status,ditolak,rejected',
        ]);

        $submission = Submission::findOrFail($id);
        $status = $request->status;

        // Normalisasi Status
        if ($status === 'verified') $status = 'disetujui';
        if ($status === 'rejected') $status = 'ditolak';

        $submission->update([
            'status' => $status,
            'catatan_admin' => $request->catatan_admin
        ]);

        // Kirim Notifikasi ke User
        $user = User::find($submission->user_id);
        if ($user) {
            $message = $status === 'disetujui' 
                ? "telah memverifikasi dokumen Step {$submission->step_number}" 
                : "menolak dokumen Step {$submission->step_number}";

            $user->notify(new AdminActionNotification([
                'status' => $status,
                'message' => $message,
                'paket_id' => $submission->paket_id 
            ]));
        }

        return redirect()->route('admin.paket.detail', [$submission->paket_id, $submission->user_id])
                         ->with('success', 'Status verifikasi berhasil diperbarui!');
    }

    public function resetUserSubmission($paketId, $userId)
    {
        $paket = Paket::findOrFail($paketId);
        $user = User::findOrFail($userId);
        $submissions = Submission::where('paket_id', $paketId)->where('user_id', $userId);
        $count = $submissions->count();

        // 1. Catat ke Riwayat
        DB::table('submission_histories')->insert([
            'nama_paket' => $paket->nama_paket,
            'nama_pengunggah' => $user->name,
            'total_dokumen' => $count,
            'aksi' => 'RESET DATA',
            'di_eksekusi_oleh' => auth()->user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Hapus Data
        $submissions->delete();

        return back()->with('success', "Data milik {$user->name} berhasil direset dan dipindahkan ke riwayat.");
    }
}