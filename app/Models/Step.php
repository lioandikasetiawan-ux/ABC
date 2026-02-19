<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model {
    protected $fillable = ['paket_id', 'nama_step', 'urutan'];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }
}

