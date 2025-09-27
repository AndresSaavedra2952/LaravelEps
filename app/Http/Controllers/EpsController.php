<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EpsController extends Controller
{
    public function index()
    {
        try {
            $eps = DB::table('eps')->get();
            return response()->json([
                'success' => true,
                'data' => $eps
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener EPS: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validación mejorada
            $request->validate([
                'nombre' => 'required|string|max:255',
                'nit' => 'required|string|max:20|unique:eps,nit',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|unique:eps,email'
            ]);

            // Crear EPS
            $epsId = DB::table('eps')->insertGetId([
                'nombre' => $request->nombre,
                'nit' => $request->nit,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener la EPS creada para devolverla
            $eps = DB::table('eps')->where('id', $epsId)->first();

            return response()->json([
                'success' => true,
                'message' => 'EPS creada exitosamente',
                'data' => $eps
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
            $eps = DB::table('eps')->where('id', $id)->first();
            
            if (!$eps) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPS no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $eps
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
            // Verificar que la EPS existe
            $eps = DB::table('eps')->where('id', $id)->first();
            
            if (!$eps) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPS no encontrada'
                ], 404);
            }

            // Validación para actualización
            $request->validate([
                'nombre' => 'required|string|max:255',
                'nit' => 'required|string|max:20|unique:eps,nit,' . $id,
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'email' => 'required|email|unique:eps,email,' . $id
            ]);

            // Actualizar EPS
            DB::table('eps')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'nit' => $request->nit,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

            // Obtener la EPS actualizada
            $epsActualizada = DB::table('eps')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'EPS actualizada exitosamente',
                'data' => $epsActualizada
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
            $eps = DB::table('eps')->where('id', $id)->first();
            
            if (!$eps) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPS no encontrada'
                ], 404);
            }

            DB::table('eps')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'EPS eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar EPS: ' . $e->getMessage()
            ], 500);
        }
    }
}