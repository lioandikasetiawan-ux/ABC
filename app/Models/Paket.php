<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    protected $fillable = ['nama_paket'];

    /**
     * Relasi: Satu paket memiliki banyak submission
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Relasi: Satu paket memiliki banyak tahapan (Steps)
     */
    public function steps(): HasMany
    {
        // Pastikan Anda sudah memiliki model Step dan tabel steps di database
        return $this->hasMany(Step::class)->orderBy('urutan', 'asc');
    }
}