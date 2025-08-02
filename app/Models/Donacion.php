<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donacion extends Model
{
    use HasFactory;

    protected $primaryKey = 'donacions_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'proyecto_id',
        'monto',
        'anonima',
        'metodo_pago',
        'telefono',
        'correo',
        'documento_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'anonima' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the usuario that owns the donacion.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuarios_id');
    }

    /**
     * Get the proyecto that owns the donacion.
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id', 'proyectos_id');
    }

    /**
     * Get the movimientos for this donacion.
     */
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'donacion_id', 'donacions_id');
    }
}
