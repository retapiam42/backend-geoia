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
        'titulo',
        'descripcion',
        'anonima',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'anonima' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', 'usuarios_id');
    }

    public function adjuntos()
    {
        return $this->hasMany(Adjunto::class, 'denuncia_id', 'denuncias_id');
    }
}
