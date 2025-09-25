<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $citas = Cita::with(['paciente', 'medico.especialidad'])->get();
        return response()->json([
            'success' => true,
            'data' => $citas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha_hora' => 'required|date|after:now',
            'estado' => 'in:programada,confirmada,cancelada,completada',
            'motivo_consulta' => 'required|string',
            'observaciones' => 'nullable|string'
        ]);

        $cita = Cita::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cita creada exitosamente',
            'data' => $cita->load(['paciente', 'medico.especialidad'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cita $cita): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $cita->load(['paciente', 'medico.especialidad'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cita $cita): JsonResponse
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'medico_id' => 'required|exists:medicos,id',
            'fecha_hora' => 'required|date',
            'estado' => 'in:programada,confirmada,cancelada,completada',
            'motivo_consulta' => 'required|string',
            'observaciones' => 'nullable|string'
        ]);

        $cita->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cita actualizada exitosamente',
            'data' => $cita->load(['paciente', 'medico.especialidad'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cita $cita): JsonResponse
    {
        $cita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cita eliminada exitosamente'
        ]);
    }
}
