<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('paket_id')->constrained()->onDelete('cascade');
        $table->integer('step_number');
        $table->json('file_path'); // Menggunakan JSON untuk mendukung multiple file (Step 8)
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
        $table->text('catatan_admin')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
