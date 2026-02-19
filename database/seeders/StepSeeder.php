<?php

namespace Database\Seeders;

use App\Models\Paket;
use App\Models\Step;
use Illuminate\Database\Seeder;

class StepSeeder extends Seeder
{
    public function run(): void
    {
        $pakets = Paket::all();

        // 11 Tahapan berdasarkan gambar Mekanisme Hibah Persediaan
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

        foreach ($pakets as $paket) {
            foreach ($namaSteps as $index => $nama) {
                Step::updateOrCreate(
                    [
                        'paket_id' => $paket->id, 
                        'urutan' => $index + 1
                    ],
                    ['nama_step' => $nama]
                );
            }
        }
    }
}