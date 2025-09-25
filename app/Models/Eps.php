<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'email',
        'activo',
        'user_id'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con pacientes
    public function pacientes()
    {
        return $this->hasMany(Paciente::class);
    }

    // Scope para EPS activas
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    // Scope para buscar por nombre
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', '%' . $nombre . '%');
    }

    // Scope para buscar por NIT
    public function scopePorNit($query, $nit)
    {
        return $query->where('nit', 'like', '%' . $nit . '%');
    }
}