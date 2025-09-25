<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultorio;
use Illuminate\Http\JsonResponse;

class ConsultorioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $consultorios = Consultorio::all();
        return response()->json([
            'success' => true,
            'data' => $consultorios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'numero' => 'required|string|max:10',
            'piso' => 'nullable|string|max:10',
            'edificio' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $consultorio = Consultorio::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Consultorio creado exitosamente',
            'data' => $consultorio
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $consultorio = Consultorio::find($id);
        
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $consultorio = Consultorio::find($id);
        
        if (!$consultorio) {
            return response()->json([
                'success' => false,
                'message' => 'Consultorio no encontrado'
            ], 404);
        }

        $request->validate([
            'numero' => 'sometimes|required|string|max:10',
            'piso' => 'nullable|string|max:10',
            'edificio' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string',
        ]);

        $consultorio->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Consultorio actualizado exitosamente',
            'data' => $consultorio
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $consultorio = Consultorio::find($id);
        
        if (!$consultorio) {
            return response()->json([
                'success' => false,
                'message' => 'Consultorio no encontrado'
            ], 404);
        }

        $consultorio->delete(); 

        return response()->json([
            'success' => true,
            'message' => 'Consultorio eliminado exitosamente'
        ]);
    }
}
