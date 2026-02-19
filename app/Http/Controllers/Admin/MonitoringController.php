<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index() {
        $pakets = Paket::withCount('submissions')->get();
        return view('admin.dashboard', compact('pakets'));
    }

    public function showUsers(Request $request, $paketId) 
    {
        $paket = Paket::with('steps')->findOrFail($paketId);
        $stepNumber = $request->query('step');

        $users = User::whereHas('submissions', function($q) use ($paketId, $stepNumber) {
            $q->where('paket_id', $paketId);
            if ($stepNumber) {
                $q->where('step_number', $stepNumber);
            }
        })->with(['submissions' => function($q) use ($paketId, $stepNumber) {
            $q->where('paket_id', $paketId);
            if ($stepNumber) {
                $q->where('step_number', $stepNumber);
            }
        }])->get();

        return view('admin.list-users', compact('paket', 'users', 'stepNumber'));
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
        $submission = Submission::findOrFail($id);
        $submission->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }
    

}