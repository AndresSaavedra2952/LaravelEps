<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function index()
    {
        try {
            $pacientes = DB::table('pacientes')->get();
            return response()->json([
                'success' => true,
                'data' => $pacientes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener pacientes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación mínima
            $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'telefono' => 'required|string',
            ]);

            // Crear paciente directamente en la tabla pacientes
            $pacienteId = DB::table('pacientes')->insertGetId([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => '1990-01-01',
                'tipo_documento' => 'CC',
                'numero_documento' => '12345678',
                'direccion' => 'Dirección por defecto',
                'activo' => 1,
                'user_id' => null, // Por ahora sin usuario
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente creado exitosamente',
                'data' => ['paciente_id' => $pacienteId]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $paciente = DB::table('pacientes')->where('id', $id)->first();
            return response()->json([
                'success' => true,
                'data' => $paciente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('pacientes')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $paciente = DB::table('pacientes')->where('id', $id)->first();
            
            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado'
                ], 404);
            }

            DB::table('pacientes')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Paciente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar paciente: ' . $e->getMessage()
            ], 500);
        }
    }
}