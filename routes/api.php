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
// 🔹 RUTAS SIN AUTENTICACIÓN (TEMPORAL)
// ==========================
// Médicos (CRUD completo sin autenticación)
Route::get('/medicos', [MedicoController::class, 'index']);
Route::get('/medicos/{id}', [MedicoController::class, 'show']);
Route::post('/medicos', [MedicoController::class, 'store']);
Route::put('/medicos/{id}', [MedicoController::class, 'update']);
Route::delete('/medicos/{id}', [MedicoController::class, 'destroy']);

// Pacientes (CRUD completo sin autenticación)
Route::get('/pacientes', [PacienteController::class, 'index']);
Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
Route::post('/pacientes', [PacienteController::class, 'store']);
Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);

// Especialidades (CRUD completo sin autenticación)
Route::post('/especialidades', [EspecialidadController::class, 'store']);
Route::put('/especialidades/{id}', [EspecialidadController::class, 'update']);
Route::delete('/especialidades/{id}', [EspecialidadController::class, 'destroy']);

// EPS (CRUD completo sin autenticación)
Route::post('/eps', [EpsController::class, 'store']);
Route::put('/eps/{id}', [EpsController::class, 'update']);
Route::delete('/eps/{id}', [EpsController::class, 'destroy']);

// Consultorios (CRUD completo sin autenticación)
Route::get('/consultorios', [ConsultorioController::class, 'index']);
Route::get('/consultorios/{id}', [ConsultorioController::class, 'show']);
Route::post('/consultorios', [ConsultorioController::class, 'store']);
Route::put('/consultorios/{id}', [ConsultorioController::class, 'update']);
Route::delete('/consultorios/{id}', [ConsultorioController::class, 'destroy']);

// Citas (CRUD completo sin autenticación)
Route::get('/citas', [CitaController::class, 'index']);
Route::get('/citas/{id}', [CitaController::class, 'show']);
Route::post('/citas', [CitaController::class, 'store']);
Route::put('/citas/{id}', [CitaController::class, 'update']);
Route::delete('/citas/{id}', [CitaController::class, 'destroy']);

// Rutas específicas para admin (sin autenticación temporal)
Route::prefix('admin')->group(function () {
    Route::get('/citas', [CitaController::class, 'index']);
    Route::get('/medicos', [MedicoController::class, 'index']);
    Route::get('/pacientes', [PacienteController::class, 'index']);
});