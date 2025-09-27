<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Paciente extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'pacientes';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'telefono',
        'fecha_nacimiento',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'eps_id',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
    ];

    // Relación con EPS
    public function eps()
    {
        return $this->belongsTo(Eps::class);
    }

    // Mutator para hashear la contraseña automáticamente
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Accessor para obtener el nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    // Scope para pacientes activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para buscar por nombre o apellido
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('apellido', 'like', "%{$termino}%")
                    ->orWhere('email', 'like', "%{$termino}%");
    }
}