<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrador;
use App\Models\Medico;
use App\Models\Paciente;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'tipo' => 'required|in:admin,medico,paciente'
        ]);

        $user = null;
        $tipo = $request->tipo;

        // Buscar usuario según el tipo
        switch ($tipo) {
            case 'admin':
                $user = Administrador::where('email', $request->email)->first();
                break;
            case 'medico':
                $user = Medico::where('email', $request->email)->first();
                break;
            case 'paciente':
                $user = Paciente::where('email', $request->email)->first();
                break;
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'user' => $user,
                'token' => $token,
                'tipo' => $tipo
            ]
        ]);
    }

    // Método público para registro de pacientes (sin autenticación)
    public function registerPaciente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:pacientes,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'fecha_nacimiento' => 'required|date',
            'tipo_documento' => 'required|string|max:10',
            'numero_documento' => 'required|string|unique:pacientes,numero_documento',
            'direccion' => 'required|string',
        ]);

        try {
            $paciente = Paciente::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'activo' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente registrado exitosamente',
                'data' => [
                    'user' => $paciente,
                    'tipo' => 'paciente'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar paciente: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para registro general (mantener compatibilidad)
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email|unique:pacientes,email',
            'password' => 'required|string|min:6',
            'telefono' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'tipo_documento' => 'required|string',
            'numero_documento' => 'required|string|unique:pacientes,numero_documento',
            'direccion' => 'required|string',
        ]);

        try {
            $paciente = Paciente::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'activo' => 1
            ]);

            $token = $paciente->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registro exitoso',
                'data' => [
                    'user' => $paciente,
                    'token' => $token,
                    'tipo' => 'paciente'
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para crear médicos (solo administradores)
    public function createMedico(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email|unique:medicos,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'numero_licencia' => 'required|string|unique:medicos,numero_licencia',
            'especialidad_id' => 'required|exists:especialidades,id',
        ]);

        try {
            $medico = Medico::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'telefono' => $request->telefono,
                'numero_licencia' => $request->numero_licencia,
                'especialidad_id' => $request->especialidad_id,
                'activo' => 1,
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

    public function me(Request $request)
    {
        $user = $request->user();
        $tipo = $this->getUserType($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'tipo' => $tipo
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso'
        ]);
    }

    private function getUserType($user)
    {
        if ($user instanceof Administrador) return 'admin';
        if ($user instanceof Medico) return 'medico';
        if ($user instanceof Paciente) return 'paciente';
        return 'unknown';
    }
}
