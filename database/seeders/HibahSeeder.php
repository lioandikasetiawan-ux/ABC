<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Paket;
use Illuminate\Support\Facades\Hash;

class HibahSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        // Menggunakan 'username' dan nama_satker default
        User::create([
            'name' => 'Administrator Utama',
            'username' => 'admin', 
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nama_satker' => 'Internal BBWS', 
        ]);

        // 2. Buat Akun User (Satker)
        // Menggunakan 'username' unik untuk satker
        User::create([
            'name' => 'Satker Wilayah I',
            'username' => 'satker01',
            'password' => Hash::make('password'),
            'role' => 'user',
            'nama_satker' => 'Satker Wilayah Cirebon',
        ]);

        // 3. Buat Data Paket Hibah Contoh
        $pakets = [
            ['nama_paket' => 'Pembangunan Jaringan Irigasi DI. Cipancuh'],
            ['nama_paket' => 'Normalisasi Sungai Cimanuk Hilir'],
            ['nama_paket' => 'Rehabilitasi Bendung Rentang'],
        ];

        foreach ($pakets as $p) {
            Paket::create($p);
        }
    }
}