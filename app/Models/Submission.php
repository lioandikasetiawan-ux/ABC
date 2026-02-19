<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model 
{
    protected $fillable = [
        'user_id', 
        'paket_id', 
        'step_number', 
        'file_path', 
        'status', 
        'catatan_admin'
    ];

    // Cukup satu kali pendefinisian casts
    protected $casts = [
        'file_path' => 'array',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Paket
     */
    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function step(): BelongsTo
{
    // Jika step_number merujuk pada urutan, gunakan where urutan
    return $this->belongsTo(Step::class, 'step_number', 'urutan');
}
}