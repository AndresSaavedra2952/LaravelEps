# 📋 API Endpoints - Sistema de Citas EPS

## 🔐 Autenticación (Público)

### Registro de Usuario
- **POST** `/api/register`
- **Body:**
```json
{
    "name": "Juan Pérez",
    "email": "juan@test.com",
    "password": "123456",
    "password_confirmation": "123456",
    "role": "paciente" // o "medico" o "admin"
}
```

### Login
- **POST** `/api/login`
- **Body:**
```json
{
    "email": "juan@test.com",
    "password": "123456"
}
```

### Credenciales por Defecto
- **Administrador:** `admin@eps.com` / `admin123`
- **Paciente de Prueba:** `test@example.com` / `password`

## 🌐 Rutas Públicas (Sin autenticación)

### Especialidades
- **GET** `/api/especialidades` - Listar especialidades
- **GET** `/api/especialidades/{id}` - Ver especialidad específica

### Médicos
- **GET** `/api/medicos` - Listar médicos
- **GET** `/api/medicos/{id}` - Ver médico específico

### EPS
- **GET** `/api/eps` - Listar EPS
- **GET** `/api/eps/{id}` - Ver EPS específica

### Consultorios
- **GET** `/api/consultorios` - Listar consultorios
- **GET** `/api/consultorios/{id}` - Ver consultorio específico

## 👤 Paciente (Requiere autenticación + rol: paciente)

### Perfil
- **GET** `/api/me` - Ver perfil del usuario autenticado
- **POST** `/api/logout` - Cerrar sesión
- **GET** `/api/paciente/profile` - Ver perfil del paciente
- **PUT** `/api/paciente/profile` - Actualizar perfil del paciente

### Citas
- **GET** `/api/citas` - Listar citas del paciente
- **POST** `/api/citas` - Crear nueva cita
- **GET** `/api/citas/{id}` - Ver cita específica
- **PUT** `/api/citas/{id}` - Actualizar cita
- **DELETE** `/api/citas/{id}` - Cancelar cita

## 👨‍⚕️ Médico (Requiere autenticación + rol: medico)

### Perfil
- **GET** `/api/me` - Ver perfil del usuario autenticado
- **POST** `/api/logout` - Cerrar sesión
- **GET** `/api/medico/profile` - Ver perfil del médico
- **PUT** `/api/medico/profile` - Actualizar perfil del médico

### Citas
- **GET** `/api/citas` - Listar citas del médico
- **GET** `/api/citas/{id}` - Ver cita específica
- **PUT** `/api/citas/{id}` - Actualizar cita (marcar como atendida, etc.)

## 🔧 Administrador (Requiere autenticación + rol: admin)

### Gestión de Usuarios
- **GET** `/api/users` - Listar todos los usuarios
- **GET** `/api/users/{id}` - Ver usuario específico
- **PUT** `/api/users/{id}` - Actualizar usuario
- **DELETE** `/api/users/{id}` - Eliminar usuario

### Gestión de Pacientes
- **GET** `/api/admin/pacientes` - Listar todos los pacientes
- **POST** `/api/admin/pacientes` - Crear paciente
- **GET** `/api/admin/pacientes/{id}` - Ver paciente específico
- **PUT** `/api/admin/pacientes/{id}` - Actualizar paciente
- **DELETE** `/api/admin/pacientes/{id}` - Eliminar paciente

### Gestión de Médicos
- **GET** `/api/admin/medicos` - Listar todos los médicos
- **POST** `/api/admin/medicos` - Crear médico
- **GET** `/api/admin/medicos/{id}` - Ver médico específico
- **PUT** `/api/admin/medicos/{id}` - Actualizar médico
- **DELETE** `/api/admin/medicos/{id}` - Eliminar médico

### Gestión de Especialidades
- **POST** `/api/admin/especialidades` - Crear especialidad
- **PUT** `/api/admin/especialidades/{id}` - Actualizar especialidad
- **DELETE** `/api/admin/especialidades/{id}` - Eliminar especialidad

### Gestión de EPS
- **POST** `/api/admin/eps` - Crear EPS
- **PUT** `/api/admin/eps/{id}` - Actualizar EPS
- **DELETE** `/api/admin/eps/{id}` - Eliminar EPS

### Gestión de Consultorios
- **POST** `/api/admin/consultorios` - Crear consultorio
- **PUT** `/api/admin/consultorios/{id}` - Actualizar consultorio
- **DELETE** `/api/admin/consultorios/{id}` - Eliminar consultorio

### Gestión de Citas
- **GET** `/api/admin/citas` - Listar todas las citas
- **POST** `/api/admin/citas` - Crear cita
- **GET** `/api/admin/citas/{id}` - Ver cita específica
- **PUT** `/api/admin/citas/{id}` - Actualizar cita
- **DELETE** `/api/admin/citas/{id}` - Eliminar cita

## 📝 Ejemplos de Uso

### Crear un Paciente (Admin)
```json
POST /api/admin/pacientes
{
    "nombre": "María García",
    "apellido": "López",
    "email": "maria@test.com",
    "telefono": "3001234567",
    "fecha_nacimiento": "1990-05-15",
    "tipo_documento": "CC",
    "numero_documento": "12345678",
    "direccion": "Calle 123 #45-67"
}
```

### Crear un Médico (Admin)
```json
POST /api/admin/medicos
{
    "nombre": "Dr. Carlos",
    "apellido": "Mendoza",
    "email": "carlos@test.com",
    "telefono": "3007654321",
    "numero_licencia": "MED123456",
    "especialidad_id": 1
}
```

### Crear una EPS (Admin)
```json
POST /api/admin/eps
{
    "nombre": "EPS Sura",
    "nit": "890123456-1",
    "direccion": "Carrera 50 #26-20",
    "telefono": "6012345678",
    "email": "contacto@epssura.com",
    "descripcion": "Entidad Promotora de Salud"
}
```

## 🔑 Headers Requeridos

Para todas las rutas protegidas, incluir:
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## 📊 Códigos de Respuesta

- **200** - OK
- **201** - Creado exitosamente
- **400** - Error en la petición
- **401** - No autenticado
- **403** - Sin permisos
- **404** - Recurso no encontrado
- **422** - Error de validación
- **500** - Error del servidor
