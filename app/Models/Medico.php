<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'telefono',
        'numero_licencia',
        'especialidad_id',
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

    // Relación con especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class);
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    // Scope para médicos activos
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

    // Scope para buscar por licencia
    public function scopePorLicencia($query, $licencia)
    {
        return $query->where('numero_licencia', 'like', '%' . $licencia . '%');
    }
}