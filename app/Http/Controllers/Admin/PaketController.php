<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paket;
use App\Models\Step;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_paket' => 'required|string|max:255',
        ]);

        // 1. Buat Paket
        $paket = Paket::create([
            'nama_paket' => $request->nama_paket
        ]);

        // 2. Daftar 11 Tahapan
        $namaSteps = [
            'Pernyataan Kesediaan Menerima Hibah',
            'Permohonan Anggota Tim Internal (Sesditjen SDA)',
            'Pembentukan Tim Internal (Kepala Balai)',
            'Penyusunan Berita Acara Penelitian Tim Internal',
            'Saran Teknis Tim Internal (Kepala Balai)',
            'Rekomendasi Teknis & Permohonan Persetujuan Hibah',
            'Persetujuan Permohonan Hibah (Sekjen An. Menteri)',
            'Penyusunan BAST dan Naskah Hibah',
            'Penetapan SK Penghapusan (Kasatker)',
            'Transaksi Hibah Keluar di SAKTI Modul Persediaan',
            'Laporan Pelaksanaan Hibah kepada KPKNL'
        ];

        // 3. Simpan Steps ke Database
        foreach ($namaSteps as $index => $nama) {
            Step::create([
                'paket_id' => $paket->id,
                'nama_step' => $nama,
                'urutan'   => $index + 1,
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Paket dan 11 tahapan berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_paket' => 'required|string|max:255']);
        $paket = Paket::findOrFail($id);
        $paket->update(['nama_paket' => $request->nama_paket]);
        return redirect()->back()->with('success', 'Nama paket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $paket = Paket::findOrFail($id);
        $paket->steps()->delete();
        $paket->submissions()->delete();
        $paket->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Paket berhasil dihapus.');
    }
}