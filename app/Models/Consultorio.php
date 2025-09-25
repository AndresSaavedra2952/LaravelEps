<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultorio extends Model
{
    use HasFactory;

    protected $table = 'consultorios';
    
    protected $fillable = [
        'numero',
        'piso',
        'edificio',
        'descripcion'
];

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }
}
