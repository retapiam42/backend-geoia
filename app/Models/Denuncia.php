<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denuncia extends Model
{
    /** @use HasFactory<\Database\Factories\DenunciaFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'denuncias';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'denuncias_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'usuario_id',
        'nombre_anonimo',
        'email_anonimo',
        'telefono_anonimo',
        'titulo',
        'descripcion',
        'anonima',
        'archivo_path',
        'tipo_archivo',
        'nombre_archivo',
        'estado',
        'ubicacion',
        'observaciones',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'anonima' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the usuario that owns the denuncia.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuarios_id');
    }

    /**
     * Get the adjuntos for this denuncia.
     */
    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class, 'denuncia_id', 'denuncias_id');
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
        
        return $this->usuario ? $this->usuario->email : null;
    }

    /**
     * Get the contact phone
     */
    public function getTelefonoContactoAttribute()
    {
        if ($this->anonima) {
            return $this->telefono_anonimo;
        }
        
        return $this->usuario ? $this->usuario->tel : null;
    }

    /**
     * Scope for pending denuncias
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope for resolved denuncias
     */
    public function scopeResueltas($query)
    {
        return $query->where('estado', 'resuelta');
    }

    /**
     * Scope for anonymous denuncias
     */
    public function scopeAnonimas($query)
    {
        return $query->where('anonima', true);
    }

    /**
     * Scope for non-anonymous denuncias
     */
    public function scopeNoAnonimas($query)
    {
        return $query->where('anonima', false);
    }
}
