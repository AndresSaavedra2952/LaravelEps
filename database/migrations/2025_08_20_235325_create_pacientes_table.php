<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'cedula',
        'fecha_nacimiento',
        'telefono',
        'email',
        'direccion',
        'eps_id',
        'activo',
        'user_id'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con EPS
    public function eps()
    {
        return $this->belongsTo(Eps::class);
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    // Scope para pacientes activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    // Scope para buscar por nombre
    public function scopePorNombre($query, $nombre)
    {
        return $query->where('nombre', 'like', '%' . $nombre . '%')
                    ->orWhere('apellido', 'like', '%' . $nombre . '%');
    }

    // Scope para buscar por cédula
    public function scopePorCedula($query, $cedula)
    {
        return $query->where('cedula', 'like', '%' . $cedula . '%');
    }
}