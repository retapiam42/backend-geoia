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
        'nombre_anonimo',
        'email_anonimo',
        'telefono_anonimo',
        'proyecto_id',
        'monto',
        'anonima',
        'metodo_pago',
        'telefono',
        'correo',
        'documento_path',
        'tipo_archivo',
        'nombre_archivo',
        'referencia_pago',
        'numero_transaccion',
        'estado_pago',
        'fecha_pago',
        'estado',
        'comentarios',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'anonima' => 'boolean',
        'fecha_pago' => 'datetime',
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

    /**
     * Get the full name (usuario or anonymous)
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->anonima) {
            return $this->nombre_anonimo ?? 'Anónimo';
        }
        
        return $this->usuario ? $this->usuario->nombre : 'Usuario no encontrado';
    }

    /**
     * Get the contact email
     */
    public function getEmailContactoAttribute()
    {
        if ($this->anonima) {
            return $this->email_anonimo;
        }
        
        return $this->usuario ? $this->usuario->email : $this->correo;
    }

    /**
     * Get the contact phone
     */
    public function getTelefonoContactoAttribute()
    {
        if ($this->anonima) {
            return $this->telefono_anonimo;
        }
        
        return $this->usuario ? $this->usuario->tel : $this->telefono;
    }

    /**
     * Scope for confirmed payments
     */
    public function scopePagosConfirmados($query)
    {
        return $query->where('estado_pago', 'confirmado');
    }

    /**
     * Scope for pending payments
     */
    public function scopePagosPendientes($query)
    {
        return $query->where('estado_pago', 'pendiente');
    }

    /**
     * Scope for active donations
     */
    public function scopeActivas($query)
    {
        return $query->where('estado', 'activa');
    }

    /**
     * Scope for completed donations
     */
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    /**
     * Scope for anonymous donations
     */
    public function scopeAnonimas($query)
    {
        return $query->where('anonima', true);
    }

    /**
     * Scope for non-anonymous donations
     */
    public function scopeNoAnonimas($query)
    {
        return $query->where('anonima', false);
    }

    /**
     * Check if payment is confirmed
     */
    public function isPagoConfirmado()
    {
        return $this->estado_pago === 'confirmado';
    }

    /**
     * Check if donation is active
     */
    public function isActiva()
    {
        return $this->estado === 'activa';
    }
}
