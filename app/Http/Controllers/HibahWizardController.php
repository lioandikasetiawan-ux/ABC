<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HibahWizardController extends Controller
{
    /**
     * Helper untuk menghitung progres paket.
     */
    private function getPaketsWithProgress()
    {
        return Paket::all()->map(function ($paket) {
            $submissions = Submission::where('user_id', Auth::id())
                ->where('paket_id', $paket->id)
                ->get();

            // Menggunakan unique() pada Collection setelah get()
            $completedSteps = $submissions->whereNotNull('file_path')->unique('step_number')->count();
            $verifiedSteps = $submissions->whereIn('status', ['disetujui', 'verified'])->unique('step_number')->count();

            $paket->progres_step = $submissions->max('step_number') ?? 0;
            $paket->progres_persen = ($completedSteps / 11) * 100;
            $paket->step_selesai = $completedSteps;
            $paket->step_verifikasi = $verifiedSteps;
            
            return $paket;
        });
    }

    public function index()
    {
        $pakets = $this->getPaketsWithProgress();
        return view('user.pilih-paket', compact('pakets'));
    }

    public function progresIndex()
    {
        $pakets = $this->getPaketsWithProgress();
        return view('user.progres', compact('pakets'));
    }

    public function progresDetail($paketId)
    {
        $paket = Paket::findOrFail($paketId);
        $submissions = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->get()
            ->keyBy('step_number');

        return view('user.progres-detail', compact('paket', 'submissions'));
    }

    public function showStep($paketId, $step)
    {
        $paket = Paket::findOrFail($paketId);
        
        // PROTEKSI: Perbaikan query unique -> distinct untuk database
        $verifiedCount = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->whereIn('status', ['disetujui', 'verified'])
            ->distinct()
            ->count('step_number');

        if ($verifiedCount >= 11) {
            return redirect()->route('user.progres.index')->with('error', 'Paket ini sudah selesai diverifikasi dan dikunci.');
        }

        $userSubmissions = Submission::where('user_id', Auth::id())
            ->where('paket_id', $paketId)
            ->get();
            
        $submission = $userSubmissions->where('step_number', $step)->first();
        $completedStepsList = $userSubmissions->whereNotNull('file_path')->pluck('step_number')->toArray();

        return view('user.wizard', compact('paket', 'step', 'submission', 'completedStepsList'));
    }

    public function store(Request $request) 
    {
        $step = $request->step_number;
        $paketId = $request->paket_id;
        $userId = Auth::id();

        // PROTEKSI: Perbaikan query unique -> distinct
        $verifiedCount = Submission::where('user_id', $userId)
            ->where('paket_id', $paketId)
            ->whereIn('status', ['disetujui', 'verified'])
            ->distinct()
            ->count('step_number');

        if ($verifiedCount >= 11) {
            return redirect()->back()->with('error', 'Data sudah dikunci karena telah selesai diverifikasi.');
        }

        $existing = Submission::where('user_id', $userId)
            ->where('paket_id', $paketId)
            ->where('step_number', $step)
            ->first();

        if (!$request->hasFile('file_upload') && (!$existing || empty($existing->file_path))) {
            return redirect()->back()->withErrors(['file_upload' => 'Berkas harus terisi.']);
        }

        $request->validate([
            'file_upload' => $step == 8 ? 'nullable' : 'nullable|mimes:pdf,jpg,png|max:2048',
            'file_upload.*' => 'nullable|mimes:pdf,jpg,png|max:2048'
        ]);

        $filePath = $existing ? $existing->file_path : null;

        if ($request->hasFile('file_upload')) {
            $uploadedFiles = is_array($request->file('file_upload')) ? $request->file('file_upload') : [$request->file('file_upload')];
            $storedPaths = [];

            foreach ($uploadedFiles as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $nameWithoutExt = pathinfo($originalName, PATHINFO_FILENAME);
                
                $fileName = $originalName;
                $counter = 1;
                $folder = ($step == 8) ? 'submissions/step8' : 'submissions';

                while (Storage::disk('public')->exists($folder . '/' . $fileName)) {
                    $fileName = $nameWithoutExt . '-' . $counter . '.' . $extension;
                    $counter++;
                }

                $path = $file->storeAs($folder, $fileName, 'public');
                $storedPaths[] = $path;
            }

            if ($step == 8) {
                $currentPaths = is_array($existing->file_path ?? []) ? ($existing->file_path ?? []) : (empty($existing->file_path) ? [] : [$existing->file_path]);
                $filePath = array_merge($currentPaths, $storedPaths);
            } else {
                if ($existing && is_string($existing->file_path) && Storage::disk('public')->exists($existing->file_path)) {
                    Storage::disk('public')->delete($existing->file_path);
                }
                $filePath = $storedPaths[0];
            }
        }

        Submission::updateOrCreate(
            ['user_id' => $userId, 'paket_id' => $paketId, 'step_number' => $step],
            ['file_path' => $filePath, 'status' => 'pending']
        );

        if ($request->action == 'next' && $step < 11) {
            return redirect()->route('user.step', [$paketId, $step + 1]);
        }

        return redirect()->back()->with('success', 'File berhasil disimpan.');
    }

    public function deleteFile(Request $request) 
    {
        $submission = Submission::where('user_id', Auth::id())->findOrFail($request->id);
        
        if (in_array($submission->status, ['disetujui', 'verified'])) {
            return response()->json(['success' => false, 'message' => 'File tidak bisa dihapus karena sudah diverifikasi.'], 403);
        }

        $files = is_array($submission->file_path) ? $submission->file_path : [$submission->file_path];

        if (Storage::disk('public')->exists($request->file_path)) {
            Storage::disk('public')->delete($request->file_path);
        }

        $updatedFiles = array_filter($files, function($f) use ($request) {
            return $f !== $request->file_path;
        });

        $finalPath = count($updatedFiles) > 0 ? array_values($updatedFiles) : null;
        
        if ($submission->step_number != 8 && is_array($finalPath)) {
            $finalPath = $finalPath[0] ?? null;
        }

        $submission->update(['file_path' => $finalPath]);
        return response()->json(['success' => true]);
    }
}