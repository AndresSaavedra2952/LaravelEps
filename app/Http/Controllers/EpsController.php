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
            $request->validate([
                'nombre' => 'required|string',
                'nit' => 'required|string',
                'direccion' => 'required|string',
                'telefono' => 'required|string',
                'email' => 'required|email',
            ]);

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

            return response()->json([
                'success' => true,
                'message' => 'EPS creada exitosamente',
                'data' => ['id' => $epsId]
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
            DB::table('eps')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'EPS actualizada exitosamente'
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