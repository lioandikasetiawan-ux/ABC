<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HibahWizardController extends Controller
{
    public function index() {
        $pakets = Paket::all();
        return view('user.pilih-paket', compact('pakets'));
    }

    public function showStep($paketId, $step) {
        $paket = Paket::findOrFail($paketId);
        $submission = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->where('step_number', $step)
            ->first();

        return view('user.wizard', compact('paket', 'step', 'submission'));
    }

    public function store(Request $request) {
        $step = $request->step_number;
        $paketId = $request->paket_id;

        // Validasi: Step 8 mengizinkan banyak file
        if ($step == 8) {
            $request->validate(['file_upload.*' => 'nullable|mimes:pdf,jpg,png|max:2048']);
        } else {
            $request->validate(['file_upload' => 'nullable|mimes:pdf,jpg,png|max:2048']);
        }

        $existing = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->where('step_number', $step)
            ->first();

        $filePath = $existing ? $existing->file_path : null;

        if ($request->hasFile('file_upload')) {
            if ($step == 8) {
                // Logika Multiple Upload
                $paths = [];
                foreach ($request->file('file_upload') as $file) {
                    $paths[] = $file->store('submissions/step8', 'public');
                }
                $filePath = $paths; // Disimpan sebagai array
            } else {
                // Logika Single Upload
                $filePath = $request->file('file_upload')->store('submissions', 'public');
            }
        }

        Submission::updateOrCreate(
            ['user_id' => Auth::id(), 'paket_id' => $paketId, 'step_number' => $step],
            ['file_path' => $filePath, 'status' => 'pending']
        );

        if ($request->action == 'next' && $step < 11) {
            return redirect()->route('user.step', [$paketId, $step + 1]);
        }

        return redirect()->back()->with('success', 'Progress berhasil disimpan.');
    }
}