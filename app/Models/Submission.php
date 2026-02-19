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


    protected $casts = [
        'file_path' => 'array',
    ];

   
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(Paket::class);
    }

    public function step(): BelongsTo
{
   
    return $this->belongsTo(Step::class, 'step_number', 'urutan');
}
}