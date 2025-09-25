<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        User::firstOrCreate(
            ['email' => 'admin@eps.com'],
            [
                'name' => 'Administrador',
                'email' => 'admin@eps.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        $this->command->info('Usuario administrador creado: admin@eps.com / admin123');
    }
}
