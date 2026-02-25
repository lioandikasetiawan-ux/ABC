<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up(): void
{
    Schema::create('submission_histories', function (Blueprint $table) {
        $table->id();
        $table->string('nama_paket');
        $table->string('nama_pengunggah');
        $table->integer('total_dokumen');
        $table->string('aksi');
        // PASTIKAN BARIS INI ADA:
        $table->string('di_eksekusi_oleh'); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_histories');
    }
};
