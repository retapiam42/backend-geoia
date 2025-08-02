<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjunto extends Model
{
    use HasFactory;

    protected $primaryKey = 'adjutos_id';

    protected $fillable = [
        'denuncia_id',
        'archivo',
        'tipo',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function denuncia()
    {
        return $this->belongsTo(Denuncia::class, 'denuncia_id', 'denuncias_id');
    }
}
