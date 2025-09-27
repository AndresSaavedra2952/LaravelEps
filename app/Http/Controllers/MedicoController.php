<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MedicoController extends Controller
{
    public function index()
    {
        try {
            $medicos = DB::table('medicos')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'especialidades.nombre as especialidad_nombre')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $medicos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener médicos: ' . $e->getMessage()
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
                'email' => 'required|email|unique:medicos,email',
                'password' => 'required|string|min:6',
                'telefono' => 'required|string|max:20',
                'numero_licencia' => 'required|string|max:50',
                'especialidad_id' => 'required|integer|exists:especialidades,id'
            ]);

            // Crear médico
            $medicoId = DB::table('medicos')->insertGetId([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'numero_licencia' => $request->numero_licencia,
                'especialidad_id' => $request->especialidad_id,
                'activo' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener el médico creado con su especialidad
            $medico = DB::table('medicos')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'especialidades.nombre as especialidad_nombre')
                ->where('medicos.id', $medicoId)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Médico creado exitosamente',
                'data' => $medico
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
            $medico = DB::table('medicos')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'especialidades.nombre as especialidad_nombre')
                ->where('medicos.id', $id)
                ->first();
            
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $medico
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
            // Verificar que el médico existe
            $medico = DB::table('medicos')->where('id', $id)->first();
            
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            // Validación para actualización
            $request->validate([
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'email' => 'required|email|unique:medicos,email,' . $id,
                'telefono' => 'required|string|max:20',
                'numero_licencia' => 'required|string|max:50',
                'especialidad_id' => 'required|integer|exists:especialidades,id'
            ]);

            // Actualizar médico
            DB::table('medicos')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'numero_licencia' => $request->numero_licencia,
                'especialidad_id' => $request->especialidad_id,
                'updated_at' => now(),
            ]);

            // Obtener el médico actualizado con su especialidad
            $medicoActualizado = DB::table('medicos')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'especialidades.nombre as especialidad_nombre')
                ->where('medicos.id', $id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Médico actualizado exitosamente',
                'data' => $medicoActualizado
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
            $medico = DB::table('medicos')->where('id', $id)->first();
            
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            DB::table('medicos')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Médico eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar médico: ' . $e->getMessage()
            ], 500);
        }
    }
}