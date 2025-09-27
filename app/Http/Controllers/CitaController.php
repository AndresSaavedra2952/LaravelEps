<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index()
    {
        try {
            $citas = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('medicos', 'citas.medico_id', '=', 'medicos.id')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select(
                    'citas.*',
                    'pacientes.nombre as paciente_nombre',
                    'pacientes.apellido as paciente_apellido',
                    'medicos.nombre as medico_nombre',
                    'medicos.apellido as medico_apellido',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $citas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener citas: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'paciente_id' => 'required|exists:pacientes,id',
                'medico_id' => 'required|exists:medicos,id',
                'fecha' => 'required|date',
                'hora' => 'required|string',
                'motivo' => 'required|string',
                'observaciones' => 'nullable|string', // ← AGREGAR ESTA LÍNEA
                'estado' => 'nullable|string|in:programada,confirmada,completada,cancelada'
            ]);

            // Crear fecha_hora combinando fecha y hora
            $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

            $citaId = DB::table('citas')->insertGetId([
                'paciente_id' => $request->paciente_id,
                'medico_id' => $request->medico_id,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'fecha_hora' => $fechaHora,
                'motivo_consulta' => $request->motivo,
                'observaciones' => $request->observaciones, // ← AGREGAR ESTA LÍNEA
                'estado' => $request->estado ?? 'programada',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $cita = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('medicos', 'citas.medico_id', '=', 'medicos.id')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select(
                    'citas.*',
                    'pacientes.nombre as paciente_nombre',
                    'pacientes.apellido as paciente_apellido',
                    'medicos.nombre as medico_nombre',
                    'medicos.apellido as medico_apellido',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->where('citas.id', $citaId)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Cita creada exitosamente',
                'data' => $cita
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear cita: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $cita = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('medicos', 'citas.medico_id', '=', 'medicos.id')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select(
                    'citas.*',
                    'pacientes.nombre as paciente_nombre',
                    'pacientes.apellido as paciente_apellido',
                    'medicos.nombre as medico_nombre',
                    'medicos.apellido as medico_apellido',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->where('citas.id', $id)
                ->first();

            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $cita
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
            $cita = DB::table('citas')->find($id);
            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            $request->validate([
                'paciente_id' => 'required|exists:pacientes,id',
                'medico_id' => 'required|exists:medicos,id',
                'fecha' => 'required|date',
                'hora' => 'required|string',
                'motivo' => 'required|string',
                'observaciones' => 'nullable|string', // ← AGREGAR ESTA LÍNEA
                'estado' => 'nullable|string|in:programada,confirmada,completada,cancelada'
            ]);

            // Crear fecha_hora combinando fecha y hora
            $fechaHora = $request->fecha . ' ' . $request->hora . ':00';

            DB::table('citas')->where('id', $id)->update([
                'paciente_id' => $request->paciente_id,
                'medico_id' => $request->medico_id,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'fecha_hora' => $fechaHora,
                'motivo_consulta' => $request->motivo,
                'observaciones' => $request->observaciones, // ← AGREGAR ESTA LÍNEA
                'estado' => $request->estado ?? 'programada',
                'updated_at' => now()
            ]);

            $citaActualizada = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('medicos', 'citas.medico_id', '=', 'medicos.id')
                ->leftJoin('especialidades', 'medicos.especialidad_id', '=', 'especialidades.id')
                ->select(
                    'citas.*',
                    'pacientes.nombre as paciente_nombre',
                    'pacientes.apellido as paciente_apellido',
                    'medicos.nombre as medico_nombre',
                    'medicos.apellido as medico_apellido',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->where('citas.id', $id)
                ->first();

            return response()->json([
                'success' => true,
                'message' => 'Cita actualizada exitosamente',
                'data' => $citaActualizada
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
            $cita = DB::table('citas')->find($id);
            if (!$cita) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ], 404);
            }

            DB::table('citas')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cita eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cita: ' . $e->getMessage()
            ], 500);
        }
    }
}