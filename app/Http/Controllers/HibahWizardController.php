<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HibahWizardController extends Controller
{
    // 1. Pilih Paket
    public function index() {
        $pakets = Paket::all();
        return view('user.pilih-paket', compact('pakets'));
    }

    // 2. Tampilkan Step Spesifik
    public function showStep($paketId, $step) {
        $paket = Paket::findOrFail($paketId);
        
        // Ambil data lama jika user pernah upload di step ini
        $submission = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->where('step_number', $step)
            ->first();

        return view('user.wizard', compact('paket', 'step', 'submission'));
    }

    // 3. Simpan (Next)
    public function store(Request $request) {
        $request->validate([
            'file_upload' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ]);

        $step = $request->step_number;
        $paketId = $request->paket_id;

        // Cari data lama untuk mendapatkan path file lama jika tidak upload baru
        $existing = Submission::where('user_id', Auth::id())
                               ->where('paket_id', $paketId)
                               ->where('step_number', $step)
                               ->first();

        $filePath = $existing ? $existing->file_path : null;

        if ($request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('submissions', 'public');
        }

        // Simpan atau Update
        Submission::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'paket_id' => $paketId,
                'step_number' => $step,
            ],
            [
                'file_path' => $filePath,
                'status' => 'pending', // Reset status ke pending jika diupdate
            ]
        );

        // Aksi Tombol
        if ($request->action == 'next' && $step < 11) {
            return redirect()->route('user.step', [$paketId, $step + 1]);
        }

        return redirect()->back()->with('success', 'Progress berhasil disimpan.');
    }
}