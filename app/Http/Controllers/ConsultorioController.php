<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultorioController extends Controller
{
    public function index()
    {
        try {
            $consultorios = DB::table('consultorios')->get();
            return response()->json([
                'success' => true,
                'data' => $consultorios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener consultorios: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'ubicacion' => 'required|string',
                'telefono' => 'required|string',
                'numero' => 'required|string', // ← AGREGAR ESTA LÍNEA
                'piso' => 'nullable|string',
                'edificio' => 'nullable|string',
                'descripcion' => 'nullable|string'
            ]);

            $consultorioId = DB::table('consultorios')->insertGetId([
                'nombre' => $request->nombre,
                'ubicacion' => $request->ubicacion,
                'telefono' => $request->telefono,
                'numero' => $request->numero, // ← AGREGAR ESTA LÍNEA
                'piso' => $request->piso,
                'edificio' => $request->edificio,
                'descripcion' => $request->descripcion,
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $consultorio = DB::table('consultorios')->find($consultorioId);

            return response()->json([
                'success' => true,
                'message' => 'Consultorio creado exitosamente',
                'data' => $consultorio
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear consultorio: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $consultorio = DB::table('consultorios')->find($id);
            if (!$consultorio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consultorio no encontrado'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => $consultorio
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
            $consultorio = DB::table('consultorios')->find($id);
            if (!$consultorio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consultorio no encontrado'
                ], 404);
            }

            $request->validate([
                'nombre' => 'required|string',
                'ubicacion' => 'required|string',
                'telefono' => 'required|string',
                'numero' => 'required|string', // ← AGREGAR ESTA LÍNEA
                'piso' => 'nullable|string',
                'edificio' => 'nullable|string',
                'descripcion' => 'nullable|string'
            ]);

            DB::table('consultorios')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'ubicacion' => $request->ubicacion,
                'telefono' => $request->telefono,
                'numero' => $request->numero, // ← AGREGAR ESTA LÍNEA
                'piso' => $request->piso,
                'edificio' => $request->edificio,
                'descripcion' => $request->descripcion,
                'updated_at' => now()
            ]);

            $consultorioActualizado = DB::table('consultorios')->find($id);

            return response()->json([
                'success' => true,
                'message' => 'Consultorio actualizado exitosamente',
                'data' => $consultorioActualizado
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
            $consultorio = DB::table('consultorios')->find($id);
            if (!$consultorio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Consultorio no encontrado'
                ], 404);
            }

            DB::table('consultorios')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Consultorio eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar consultorio: ' . $e->getMessage()
            ], 500);
        }
    }
}