<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_create_hibah_tables.php
public function up(): void
{
    Schema::create('pakets', function (Blueprint $table) {
        $table->id();
        $table->string('nama_paket');
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('hibah_tables');
    }
};
