<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ]);

            $email = $request->email;
            $password = $request->password;

            // Buscar en administradores
            $admin = DB::table('administradores')->where('email', $email)->first();
            if ($admin && Hash::check($password, $admin->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'user' => [
                        'id' => $admin->id,
                        'nombre' => $admin->nombre,
                        'apellido' => $admin->apellido,
                        'email' => $admin->email,
                        'tipo' => 'admin',
                        'role' => 'admin' // ✅ AGREGAR ESTE CAMPO
                    ],
                    'token' => 'admin_token_' . $admin->id
                ]);
            }

            // Buscar en médicos
            $medico = DB::table('medicos')->where('email', $email)->first();
            if ($medico && Hash::check($password, $medico->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'user' => [
                        'id' => $medico->id,
                        'nombre' => $medico->nombre,
                        'apellido' => $medico->apellido,
                        'email' => $medico->email,
                        'tipo' => 'medico',
                        'role' => 'medico' // ✅ AGREGAR ESTE CAMPO
                    ],
                    'token' => 'medico_token_' . $medico->id
                ]);
            }

            // Buscar en pacientes
            $paciente = DB::table('pacientes')->where('email', $email)->first();
            if ($paciente && Hash::check($password, $paciente->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login exitoso',
                    'user' => [
                        'id' => $paciente->id,
                        'nombre' => $paciente->nombre,
                        'apellido' => $paciente->apellido,
                        'email' => $paciente->email,
                        'tipo' => 'paciente',
                        'role' => 'paciente' // ✅ AGREGAR ESTE CAMPO
                    ],
                    'token' => 'paciente_token_' . $paciente->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el login: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerPaciente(Request $request)
    {
        try {
            // Validación mejorada
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:pacientes,email',
                'password' => 'required|string|min:6',
                'telefono' => 'required|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'tipo_documento' => 'nullable|string|max:10',
                'numero_documento' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'eps_id' => 'nullable|integer|exists:eps,id'
            ]);

            // Crear paciente CON eps_id
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento ?? '1990-01-01',
                'tipo_documento' => $request->tipo_documento ?? 'CC',
                'numero_documento' => $request->numero_documento ?? '12345678',
                'direccion' => $request->direccion ?? 'Dirección por defecto',
                'eps_id' => $request->eps_id,
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener el paciente creado para devolverlo
            $paciente = DB::table('pacientes')->where('id', $pacienteId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Paciente registrado exitosamente',
                'data' => $paciente
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}