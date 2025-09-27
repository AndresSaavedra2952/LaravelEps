<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    use HasFactory;

    protected $table = 'eps';
    
    protected $fillable = [
        'nombre',
        'nit',
        'direccion',
        'telefono',
        'email',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Scope para EPS activas
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    // Scope para buscar por nombre
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('nit', 'like', "%{$termino}%");
    }
}