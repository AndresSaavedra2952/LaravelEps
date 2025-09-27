<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
                'eps_id' => 'nullable|integer|exists:eps,id' // ✅ AGREGAR VALIDACIÓN DE EPS_ID
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
                'eps_id' => $request->eps_id, // ✅ INCLUIR EPS_ID
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener el paciente creado para devolverlo
            $paciente = DB::table('pacientes')->where('id', $pacienteId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Paciente creado exitosamente',
                'data' => $paciente
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
            
            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado'
                ], 404);
            }

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
            // Verificar que el paciente existe
            $paciente = DB::table('pacientes')->where('id', $id)->first();
            
            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paciente no encontrado'
                ], 404);
            }

            // Validación para actualización
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:pacientes,email,' . $id,
                'telefono' => 'required|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'tipo_documento' => 'nullable|string|max:10',
                'numero_documento' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'eps_id' => 'nullable|integer|exists:eps,id' // ✅ AGREGAR VALIDACIÓN DE EPS_ID
            ]);

            // Actualizar paciente CON eps_id
            DB::table('pacientes')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento ?? $paciente->fecha_nacimiento,
                'tipo_documento' => $request->tipo_documento ?? $paciente->tipo_documento,
                'numero_documento' => $request->numero_documento ?? $paciente->numero_documento,
                'direccion' => $request->direccion ?? $paciente->direccion,
                'eps_id' => $request->eps_id, // ✅ INCLUIR EPS_ID
                'updated_at' => now(),
            ]);

            // Obtener el paciente actualizado
            $pacienteActualizado = DB::table('pacientes')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Paciente actualizado exitosamente',
                'data' => $pacienteActualizado
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