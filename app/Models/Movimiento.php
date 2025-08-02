<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $primaryKey = 'movimientos_id';

    protected $fillable = [
        'donacion_id',
        'tipo',
        'monto',
        'descripcion',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function donacion()
    {
        return $this->belongsTo(Donacion::class, 'donacion_id', 'donacions_id');
    }
}
