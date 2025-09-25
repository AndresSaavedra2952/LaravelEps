<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class MedicoController extends Controller
{
    public function index()
    {
        try {
            $medicos = DB::table('medicos')
                ->join('users', 'medicos.user_id', '=', 'users.id')
                ->join('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'users.email as user_email', 'users.name as user_name', 'especialidades.nombre as especialidad_nombre')
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
            $request->validate([
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'telefono' => 'required|string',
                'numero_licencia' => 'required|string|unique:medicos,numero_licencia',
                'especialidad_id' => 'required|integer|exists:especialidades,id',
            ]);

            DB::beginTransaction();

            // 1. Crear usuario en la tabla users
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'medico',
            ]);

            // 2. Crear médico en la tabla medicos
            $medicoId = DB::table('medicos')->insertGetId([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'numero_licencia' => $request->numero_licencia,
                'especialidad_id' => $request->especialidad_id,
                'activo' => 1,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Médico creado exitosamente',
                'data' => [
                    'user_id' => $user->id,
                    'medico_id' => $medicoId
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
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
                ->join('users', 'medicos.user_id', '=', 'users.id')
                ->join('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select('medicos.*', 'users.email as user_email', 'users.name as user_name', 'especialidades.nombre as especialidad_nombre')
                ->where('medicos.id', $id)
                ->first();
            
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
            DB::beginTransaction();

            $medico = DB::table('medicos')->where('id', $id)->first();
            
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            // Actualizar datos del médico
            DB::table('medicos')->where('id', $id)->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'telefono' => $request->telefono,
                'updated_at' => now(),
            ]);

            // Actualizar datos del usuario
            DB::table('users')->where('id', $medico->user_id)->update([
                'name' => $request->nombre . ' ' . $request->apellido,
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Médico actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $medico = DB::table('medicos')->where('id', $id)->first();
            
            if (!$medico) {
                return response()->json([
                    'success' => false,
                    'message' => 'Médico no encontrado'
                ], 404);
            }

            // Eliminar médico
            DB::table('medicos')->where('id', $id)->delete();
            
            // Eliminar usuario
            DB::table('users')->where('id', $medico->user_id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Médico eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar médico: ' . $e->getMessage()
            ], 500);
        }
    }
}