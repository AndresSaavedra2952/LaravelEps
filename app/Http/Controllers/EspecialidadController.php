<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function index()
    {
        try {
            $especialidades = Especialidad::all();
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
            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
            ]);

            $especialidad = Especialidad::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Especialidad creada exitosamente',
                'data' => $especialidad
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear especialidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $especialidad = Especialidad::find($id);
            
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
                'message' => 'Error al obtener especialidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $especialidad = Especialidad::find($id);
            
            if (!$especialidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidad no encontrada'
                ], 404);
            }

            $request->validate([
                'nombre' => 'sometimes|required|string|max:255',
                'descripcion' => 'nullable|string',
            ]);

            $especialidad->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Especialidad actualizada exitosamente',
                'data' => $especialidad
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar especialidad: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $especialidad = Especialidad::find($id);
            
            if (!$especialidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Especialidad no encontrada'
                ], 404);
            }

            $especialidad->delete();

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