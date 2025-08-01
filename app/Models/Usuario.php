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
        'password',
        'anonimo',
    ];
}
