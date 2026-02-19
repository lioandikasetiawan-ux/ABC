<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paket extends Model
{
    protected $fillable = ['nama_paket'];

   
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

  
    public function steps(): HasMany
    {
   
        return $this->hasMany(Step::class)->orderBy('urutan', 'asc');
    }
}