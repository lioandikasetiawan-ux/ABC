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
    Schema::create('steps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paket_id')->constrained('pakets')->onDelete('cascade');
        $table->string('nama_step'); // Contoh: "Dokumen Kontrak"
        $table->integer('urutan');   // 1, 2, 3... sampai 11
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steps');
    }
};
