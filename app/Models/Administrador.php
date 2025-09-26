<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Administrador extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'administradores';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}