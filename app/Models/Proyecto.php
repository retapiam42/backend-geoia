<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    use HasFactory;

    protected $primaryKey = 'proyectos_id';

    protected $fillable = [
        'nombre',
        'descripcion',
        'meta_fondos',
        'fondos_actuales',
        'activo',
    ];

    protected $casts = [
        'meta_fondos' => 'decimal:2',
        'fondos_actuales' => 'decimal:2',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'proyecto_id', 'proyectos_id');
    }

    public function movimientos()
    {
        return $this->hasManyThrough(Movimiento::class, Donacion::class, 'proyecto_id', 'donacion_id', 'proyectos_id', 'donacions_id');
    }
}
