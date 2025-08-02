<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $primaryKey = 'usuarios_id';

    protected $fillable = [
        'nombre',
        'email',
        'tel',
        'password',
        'anonimo',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'anonimo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function denuncias()
    {
        return $this->hasMany(Denuncia::class, 'usuario_id', 'usuarios_id');
    }

    public function donaciones()
    {
        return $this->hasMany(Donacion::class, 'usuario_id', 'usuarios_id');
    }
}
