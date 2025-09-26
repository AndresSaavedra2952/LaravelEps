<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\CitaController;

// ==========================
// 🔹 RUTA DE PRUEBA
// ==========================
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'Conexión exitosa con la API',
        'timestamp' => now(),
        'server' => 'Laravel API funcionando correctamente'
    ]);
});

// ==========================
// 🔹 AUTENTICACIÓN
// ==========================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ==========================
// 🔹 RUTAS PÚBLICAS (SIN AUTENTICACIÓN)
// ==========================
// Registro público de pacientes
Route::post('/register/paciente', [AuthController::class, 'registerPaciente']);

// Consultas públicas
Route::get('/especialidades', [EspecialidadController::class, 'index']);
Route::get('/eps', [EpsController::class, 'index']);

// ==========================
// 🔹 RUTAS AUTENTICADAS
// ==========================
Route::middleware('auth:sanctum')->group(function () {
    // Perfil
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Médicos (solo lectura para usuarios autenticados)
    Route::get('/medicos', [MedicoController::class, 'index']);
    Route::get('/medicos/{id}', [MedicoController::class, 'show']);
    
    // Pacientes (solo lectura para usuarios autenticados)
    Route::get('/pacientes', [PacienteController::class, 'index']);
    Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
    
    // Especialidades (CRUD para usuarios autenticados)
    Route::post('/especialidades', [EspecialidadController::class, 'store']);
    Route::put('/especialidades/{id}', [EspecialidadController::class, 'update']);
    Route::delete('/especialidades/{id}', [EspecialidadController::class, 'destroy']);
    
    // EPS (CRUD para usuarios autenticados)
    Route::post('/eps', [EpsController::class, 'store']);
    Route::put('/eps/{id}', [EpsController::class, 'update']);
    Route::delete('/eps/{id}', [EpsController::class, 'destroy']);
});

// ==========================
// 🔹 RUTAS DE ADMINISTRADOR
// ==========================
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Crear médicos (solo admin)
    Route::post('/medicos', [MedicoController::class, 'store']);
    Route::put('/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/medicos/{id}', [MedicoController::class, 'destroy']);
    
    // Gestión completa de pacientes (solo admin)
    Route::post('/pacientes', [PacienteController::class, 'store']);
    Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);
});