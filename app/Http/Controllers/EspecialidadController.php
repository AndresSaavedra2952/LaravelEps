<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EspecialidadController extends Controller
{
    public function index()
    {
        try {
            $especialidades = DB::table('especialidades')->get();
            return response()->json([
                'success' => true,
                'data' => $especialidades
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener especialidades: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación mejorada
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500'
            ]);

            // Crear especialidad
            $especialidadId = DB::table('especialidades')->insertGetId([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener la especialidad creada para devolverla
            $especialidad = DB::table('especialidades')->where('id', $especialidadId)->first();

            return response()->json([
                'success' => true,
                'message' => 'Especialidad creada exitosamente',
                'data' => $especialidad
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
            $especialidad = DB::table('especialidades')->where('id', $id)->first();
            
            if (!$especialidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidad no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $especialidad
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
            // Verificar que la especialidad existe
            $especialidad = DB::table('especialidades')->where('id', $id)->first();
            
            if (!$especialidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidad no encontrada'
                ], 404);
            }

            // Validación para actualización
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string|max:500'
            ]);

            // Actualizar especialidad
            DB::table('especialidades')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'updated_at' => now(),
            ]);

            // Obtener la especialidad actualizada
            $especialidadActualizada = DB::table('especialidades')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Especialidad actualizada exitosamente',
                'data' => $especialidadActualizada
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
            $especialidad = DB::table('especialidades')->where('id', $id)->first();
            
            if (!$especialidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidad no encontrada'
                ], 404);
            }

            DB::table('especialidades')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Especialidad eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar especialidad: ' . $e->getMessage()
            ], 500);
        }
    }
}