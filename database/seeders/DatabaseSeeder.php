<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // HAPUS atau KOMENTAR baris di bawah ini karena masih pakai 'email'
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Panggil seeder yang sudah kita buat tadi
        $this->call([
            HibahSeeder::class,
        ]);
    }
}