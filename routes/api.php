<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PacienteController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\ConsultorioController;
use App\Http\Controllers\AuthController;

// ==========================
// 🔹 RUTA DE PRUEBA (para verificar conexión)
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
// 🔹 Autenticación (público)
// ==========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ==========================
// 🔹 RUTAS PÚBLICAS (sin autenticación)
// ==========================
Route::get('/especialidades', [EspecialidadController::class, 'index']);
Route::get('/especialidades/{id}', [EspecialidadController::class, 'show']);
Route::get('/medicos', [MedicoController::class, 'index']);
Route::get('/medicos/{id}', [MedicoController::class, 'show']);
Route::get('/eps', [EpsController::class, 'index']);
Route::get('/eps/{id}', [EpsController::class, 'show']);
Route::get('/pacientes', [PacienteController::class, 'index']);
Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
Route::get('/consultorios', [ConsultorioController::class, 'index']);
Route::get('/consultorios/{id}', [ConsultorioController::class, 'show']);

// ==========================
// 🔹 RUTAS AUTENTICADAS (sin middleware de rol)
// ==========================
Route::middleware('auth:sanctum')->group(function () {
    // Perfil del usuario autenticado
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // NUEVA RUTA PARA ACTUALIZAR PERFIL
    Route::put('/profile/update', [AuthController::class, 'updateProfile']);
    
    // ESPECIALIDADES - CRUD completo para usuarios autenticados (sin middleware de rol)
    Route::post('/especialidades', [EspecialidadController::class, 'store']);
    Route::put('/especialidades/{id}', [EspecialidadController::class, 'update']);
    Route::delete('/especialidades/{id}', [EspecialidadController::class, 'destroy']);
    
    // MÉDICOS - CRUD completo para usuarios autenticados (sin middleware de rol)
    Route::post('/medicos', [MedicoController::class, 'store']);
    Route::put('/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/medicos/{id}', [MedicoController::class, 'destroy']);
    
    // EPS - CRUD completo para usuarios autenticados (sin middleware de rol)
    Route::post('/eps', [EpsController::class, 'store']);
    Route::put('/eps/{id}', [EpsController::class, 'update']);
    Route::delete('/eps/{id}', [EpsController::class, 'destroy']);
    
    // PACIENTES - CRUD completo para usuarios autenticados (sin middleware de rol)
    Route::post('/pacientes', [PacienteController::class, 'store']);
    Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
    Route::delete('/pacientes/{id}', [PacienteController::class, 'destroy']);
});

// ==========================
// 🔹 PACIENTE
// ==========================
Route::middleware('role:paciente')->group(function () {
    // Gestión de perfil del paciente
    Route::get('/paciente/profile', [PacienteController::class, 'show']);
    Route::put('/paciente/profile', [PacienteController::class, 'update']);

    // Citas del paciente
    Route::get('/citas', [CitaController::class, 'index']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas/{id}', [CitaController::class, 'show']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
});

// ==========================
// 🔹 MÉDICO
// ==========================
Route::middleware('role:medico')->group(function () {
    // Gestión de perfil del médico
    Route::get('/medico/profile', [MedicoController::class, 'show']);
    Route::put('/medico/profile', [MedicoController::class, 'update']);

    // Citas del médico
    Route::get('/citas', [CitaController::class, 'index']);
    Route::get('/citas/{id}', [CitaController::class, 'show']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
});

// ==========================
// 🔹 ADMINISTRADOR (CRUD completo)
// ==========================
Route::middleware('role:admin')->group(function () {
    // Gestión de usuarios
    Route::get('/users', [AuthController::class, 'index']);
    Route::get('/users/{id}', [AuthController::class, 'show']);
    Route::put('/users/{id}', [AuthController::class, 'update']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);

    // Gestión de pacientes (CRUD completo)
    Route::get('/admin/pacientes', [PacienteController::class, 'index']);
    Route::post('/admin/pacientes', [PacienteController::class, 'store']);
    Route::get('/admin/pacientes/{id}', [PacienteController::class, 'show']);
    Route::put('/admin/pacientes/{id}', [PacienteController::class, 'update']);
    Route::delete('/admin/pacientes/{id}', [PacienteController::class, 'destroy']);

    // Gestión de médicos (CRUD completo)
    Route::get('/admin/medicos', [MedicoController::class, 'index']);
    Route::post('/admin/medicos', [MedicoController::class, 'store']);
    Route::get('/admin/medicos/{id}', [MedicoController::class, 'show']);
    Route::put('/admin/medicos/{id}', [MedicoController::class, 'update']);
    Route::delete('/admin/medicos/{id}', [MedicoController::class, 'destroy']);

    // Gestión de especialidades (CRUD completo) - RUTAS ADMIN
    Route::get('/admin/especialidades', [EspecialidadController::class, 'index']);
    Route::post('/admin/especialidades', [EspecialidadController::class, 'store']);
    Route::get('/admin/especialidades/{id}', [EspecialidadController::class, 'show']);
    Route::put('/admin/especialidades/{id}', [EspecialidadController::class, 'update']);
    Route::delete('/admin/especialidades/{id}', [EspecialidadController::class, 'destroy']);

    // Gestión de EPS (CRUD completo)
    Route::get('/admin/eps', [EpsController::class, 'index']);
    Route::post('/admin/eps', [EpsController::class, 'store']);
    Route::get('/admin/eps/{id}', [EpsController::class, 'show']);
    Route::put('/admin/eps/{id}', [EpsController::class, 'update']);
    Route::delete('/admin/eps/{id}', [EpsController::class, 'destroy']);

    // Gestión de consultorios (CRUD completo)
    Route::get('/admin/consultorios', [ConsultorioController::class, 'index']);
    Route::post('/admin/consultorios', [ConsultorioController::class, 'store']);
    Route::get('/admin/consultorios/{id}', [ConsultorioController::class, 'show']);
    Route::put('/admin/consultorios/{id}', [ConsultorioController::class, 'update']);
    Route::delete('/admin/consultorios/{id}', [ConsultorioController::class, 'destroy']);

    // Gestión de citas (CRUD completo)
    Route::get('/admin/citas', [CitaController::class, 'index']);
    Route::post('/admin/citas', [CitaController::class, 'store']);
    Route::get('/admin/citas/{id}', [CitaController::class, 'show']);
    Route::put('/admin/citas/{id}', [CitaController::class, 'update']);
    Route::delete('/admin/citas/{id}', [CitaController::class, 'destroy']);
});