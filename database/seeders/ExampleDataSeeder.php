<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Especialidad;
use App\Models\Eps;
use App\Models\Consultorio;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear especialidades
        $especialidades = [
            ['nombre' => 'Medicina General', 'descripcion' => 'Atención médica general'],
            ['nombre' => 'Cardiología', 'descripcion' => 'Especialidad en enfermedades del corazón'],
            ['nombre' => 'Pediatría', 'descripcion' => 'Medicina para niños'],
            ['nombre' => 'Ginecología', 'descripcion' => 'Salud reproductiva femenina'],
            ['nombre' => 'Ortopedia', 'descripcion' => 'Especialidad en huesos y articulaciones'],
            ['nombre' => 'Dermatología', 'descripcion' => 'Especialidad en piel'],
            ['nombre' => 'Oftalmología', 'descripcion' => 'Especialidad en ojos'],
            ['nombre' => 'Psicología', 'descripcion' => 'Salud mental'],
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::firstOrCreate(
                ['nombre' => $especialidad['nombre']],
                $especialidad
            );
        }

        // Crear EPS
        $eps = [
            [
                'nombre' => 'EPS Sura',
                'nit' => '890123456-1',
                'direccion' => 'Carrera 50 #26-20, Bogotá',
                'telefono' => '6012345678',
                'email' => 'contacto@epssura.com',
                'descripcion' => 'Entidad Promotora de Salud Sura'
            ],
            [
                'nombre' => 'Nueva EPS',
                'nit' => '890123456-2',
                'direccion' => 'Calle 26 #68-35, Bogotá',
                'telefono' => '6012345679',
                'email' => 'contacto@nuevaeps.com',
                'descripcion' => 'Entidad Promotora de Salud Nueva EPS'
            ],
            [
                'nombre' => 'Sanitas',
                'nit' => '890123456-3',
                'direccion' => 'Carrera 7 #32-16, Bogotá',
                'telefono' => '6012345680',
                'email' => 'contacto@sanitas.com',
                'descripcion' => 'Entidad Promotora de Salud Sanitas'
            ],
        ];

        foreach ($eps as $ep) {
            Eps::firstOrCreate(
                ['nit' => $ep['nit']],
                $ep
            );
        }

        // Crear consultorios
        $consultorios = [
            ['numero' => '101', 'piso' => '1', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Medicina General'],
            ['numero' => '102', 'piso' => '1', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Cardiología'],
            ['numero' => '201', 'piso' => '2', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Pediatría'],
            ['numero' => '202', 'piso' => '2', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Ginecología'],
            ['numero' => '301', 'piso' => '3', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Ortopedia'],
            ['numero' => '302', 'piso' => '3', 'edificio' => 'Principal', 'descripcion' => 'Consultorio de Dermatología'],
        ];

        foreach ($consultorios as $consultorio) {
            Consultorio::firstOrCreate(
                ['numero' => $consultorio['numero']],
                $consultorio
            );
        }

        $this->command->info('Datos de ejemplo creados exitosamente');
    }
}
