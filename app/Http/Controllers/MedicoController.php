<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Medico;
use App\Models\Especialidad;

class MedicoController extends Controller
{
    public function index()
    {
        try {
            $medicos = Medico::with('especialidad')->get();
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
            $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'email' => 'required|email|unique:medicos,email',
                'password' => 'required|string|min:6',
                'telefono' => 'required|string',
                'numero_licencia' => 'required|string|unique:medicos,numero_licencia',
                'especialidad_id' => 'required|exists:especialidades,id'
            ]);

            $medico = Medico::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'numero_licencia' => $request->numero_licencia,
                'especialidad_id' => $request->especialidad_id,
                'activo' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Médico creado exitosamente',
                'data' => $medico
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear médico: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $medico = Medico::with('especialidad')->find($id);
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
            $medico = Medico::find($id);
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'telefono' => 'required|string',
                'especialidad_id' => 'required|exists:especialidades,id'
            ]);

            $medico->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'especialidad_id' => $request->especialidad_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Médico actualizado exitosamente',
                'data' => $medico
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
            $medico = Medico::find($id);
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            $medico->delete();

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