<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Medico extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'medicos';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'numero_licencia',
        'especialidad_id',
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

    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }
}