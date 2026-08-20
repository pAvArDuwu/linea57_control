<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API Linea 61 Control',
    description: 'Documentacion de endpoints para el control de unidades'
)]
#[OA\Server(url: '/', description: 'Servidor actual de la aplicacion')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    description: 'Token Bearer emitido por Laravel Sanctum',
    bearerFormat: 'Sanctum',
    scheme: 'bearer'
)]
#[OA\Tag(name: 'Autenticacion', description: 'Inicio de sesion y usuario autenticado')]
#[OA\Tag(name: 'Conductores', description: 'Gestion de conductores')]
#[OA\Tag(name: 'Propietarios', description: 'Gestion de propietarios')]
#[OA\Tag(name: 'Internos', description: 'Gestion de internos')]
#[OA\Tag(name: 'Micros', description: 'Gestion de micros')]
#[OA\Tag(name: 'Rutas', description: 'Gestion de rutas')]
#[OA\Tag(name: 'Paradas', description: 'Gestion de paradas')]
#[OA\Tag(name: 'Turnos', description: 'Gestion de turnos')]
#[OA\Schema(
    schema: 'LoginRequest',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
        new OA\Property(property: 'device_name', type: 'string', nullable: true, example: 'swagger-ui'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'AuthToken',
    properties: [
        new OA\Property(property: 'access_token', type: 'string', example: '1|sanctum-token'),
        new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'MessageResponse',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Operacion realizada correctamente'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    properties: [
        new OA\Property(property: 'error', type: 'string', example: 'Credenciales invalidas'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Administrador'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
        new OA\Property(property: 'email_verified_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Conductor',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'apellido', type: 'string', example: 'Perez'),
        new OA\Property(property: 'telefono', type: 'string', example: '70000000'),
        new OA\Property(property: 'correo', type: 'string', format: 'email', example: 'juan@example.com'),
        new OA\Property(property: 'ci', type: 'string', example: '1234567'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ConductorRequest',
    required: ['nombre', 'apellido', 'telefono', 'correo', 'ci'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Juan'),
        new OA\Property(property: 'apellido', type: 'string', example: 'Perez'),
        new OA\Property(property: 'telefono', type: 'string', example: '70000000'),
        new OA\Property(property: 'correo', type: 'string', format: 'email', example: 'juan@example.com'),
        new OA\Property(property: 'ci', type: 'string', example: '1234567'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Propietario',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nombre', type: 'string', example: 'Maria'),
        new OA\Property(property: 'apellido', type: 'string', example: 'Lopez'),
        new OA\Property(property: 'telefono', type: 'string', nullable: true, example: '71000000'),
        new OA\Property(property: 'correo', type: 'string', format: 'email', example: 'maria@example.com'),
        new OA\Property(property: 'ci', type: 'string', example: '7654321'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'fecha_registro', type: 'string', format: 'date', nullable: true, example: '2026-08-17'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'PropietarioRequest',
    required: ['nombre', 'apellido', 'correo', 'ci'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Maria'),
        new OA\Property(property: 'apellido', type: 'string', example: 'Lopez'),
        new OA\Property(property: 'telefono', type: 'string', nullable: true, example: '71000000'),
        new OA\Property(property: 'correo', type: 'string', format: 'email', example: 'maria@example.com'),
        new OA\Property(property: 'ci', type: 'string', example: '7654321'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'fecha_registro', type: 'string', format: 'date', nullable: true, example: '2026-08-17'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Interno',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'estado', type: 'string', enum: ['disponible', 'asignado', 'inactivo'], example: 'disponible'),
        new OA\Property(property: 'numero_interno', type: 'string', example: 'A-12'),
        new OA\Property(property: 'fecha_ingreso', type: 'string', format: 'date-time', example: '2026-08-17T08:00:00Z'),
        new OA\Property(property: 'observaciones', type: 'string', nullable: true, example: 'Disponible para asignacion'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'InternoRequest',
    required: ['numero_interno', 'fecha_ingreso'],
    properties: [
        new OA\Property(property: 'estado', type: 'string', enum: ['disponible', 'asignado', 'inactivo'], example: 'disponible'),
        new OA\Property(property: 'numero_interno', type: 'string', example: 'A-12'),
        new OA\Property(property: 'fecha_ingreso', type: 'string', format: 'date-time', example: '2026-08-17T08:00:00Z'),
        new OA\Property(property: 'observaciones', type: 'string', nullable: true, example: 'Disponible para asignacion'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Micro',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'propietario_id', type: 'integer', example: 1),
        new OA\Property(property: 'interno_id', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'placa', type: 'string', example: '1234ABC'),
        new OA\Property(property: 'chasis', type: 'string', nullable: true, example: 'CHS123456'),
        new OA\Property(property: 'anio_fabricacion', type: 'integer', nullable: true, example: 2022),
        new OA\Property(property: 'modelo', type: 'string', example: 'Coaster'),
        new OA\Property(property: 'marca', type: 'string', example: 'Toyota'),
        new OA\Property(property: 'capacidad_pasajeros', type: 'integer', example: 28),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'MicroRequest',
    required: ['propietario_id', 'placa', 'modelo', 'marca', 'capacidad_pasajeros'],
    properties: [
        new OA\Property(property: 'propietario_id', type: 'integer', example: 1),
        new OA\Property(property: 'interno_id', type: 'integer', nullable: true, example: 1),
        new OA\Property(property: 'placa', type: 'string', example: '1234ABC'),
        new OA\Property(property: 'chasis', type: 'string', nullable: true, example: 'CHS123456'),
        new OA\Property(property: 'anio_fabricacion', type: 'integer', nullable: true, example: 2022),
        new OA\Property(property: 'modelo', type: 'string', example: 'Coaster'),
        new OA\Property(property: 'marca', type: 'string', example: 'Toyota'),
        new OA\Property(property: 'capacidad_pasajeros', type: 'integer', example: 28),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Ruta',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nombre', type: 'string', example: 'Linea 61'),
        new OA\Property(property: 'descripcion', type: 'string', nullable: true, example: 'Ruta principal'),
        new OA\Property(property: 'sentido', type: 'string', enum: ['Ida', 'Vuelta'], example: 'Ida'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'RutaRequest',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Linea 61'),
        new OA\Property(property: 'descripcion', type: 'string', nullable: true, example: 'Ruta principal'),
        new OA\Property(property: 'sentido', type: 'string', enum: ['Ida', 'Vuelta'], example: 'Ida'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Parada',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nombre', type: 'string', example: 'Plaza Principal'),
        new OA\Property(property: 'referencia', type: 'string', nullable: true, example: 'Frente al mercado'),
        new OA\Property(property: 'latitud', type: 'number', format: 'float', nullable: true, example: -17.7833),
        new OA\Property(property: 'longitud', type: 'number', format: 'float', nullable: true, example: -63.1821),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'ParadaRequest',
    required: ['nombre'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', example: 'Plaza Principal'),
        new OA\Property(property: 'referencia', type: 'string', nullable: true, example: 'Frente al mercado'),
        new OA\Property(property: 'latitud', type: 'number', format: 'float', nullable: true, example: -17.7833),
        new OA\Property(property: 'longitud', type: 'number', format: 'float', nullable: true, example: -63.1821),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'Turno',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'nombre', type: 'string', enum: ['mañana', 'tarde', 'noche'], example: 'mañana'),
        new OA\Property(property: 'hora_inicio', type: 'string', example: '07:00:00'),
        new OA\Property(property: 'hora_fin', type: 'string', example: '13:00:00'),
        new OA\Property(property: 'descripcion', type: 'string', nullable: true, example: 'Turno de la manana'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
#[OA\Schema(
    schema: 'TurnoRequest',
    required: ['nombre', 'hora_inicio', 'hora_fin'],
    properties: [
        new OA\Property(property: 'nombre', type: 'string', enum: ['mañana', 'tarde', 'noche'], example: 'mañana'),
        new OA\Property(property: 'hora_inicio', type: 'string', example: '07:00:00'),
        new OA\Property(property: 'hora_fin', type: 'string', example: '13:00:00'),
        new OA\Property(property: 'descripcion', type: 'string', nullable: true, example: 'Turno de la manana'),
        new OA\Property(property: 'estado', type: 'string', enum: ['activo', 'inactivo'], example: 'activo'),
    ],
    type: 'object'
)]
#[OA\Post(path: '/api/login', summary: 'Iniciar sesion', tags: ['Autenticacion'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')), responses: [new OA\Response(response: 200, description: 'Token generado', content: new OA\JsonContent(ref: '#/components/schemas/AuthToken')), new OA\Response(response: 401, description: 'Credenciales invalidas', content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/me', summary: 'Obtener el usuario autenticado', security: [['sanctum' => []]], tags: ['Autenticacion'], responses: [new OA\Response(response: 200, description: 'Usuario autenticado', content: new OA\JsonContent(ref: '#/components/schemas/User')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/logout', summary: 'Cerrar sesion', security: [['sanctum' => []]], tags: ['Autenticacion'], responses: [new OA\Response(response: 200, description: 'Sesion cerrada', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/conductores', summary: 'Listar conductores', security: [['sanctum' => []]], tags: ['Conductores'], responses: [new OA\Response(response: 200, description: 'Listado de conductores', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Conductor'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/conductores', summary: 'Crear conductor', security: [['sanctum' => []]], tags: ['Conductores'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ConductorRequest')), responses: [new OA\Response(response: 201, description: 'Conductor creado', content: new OA\JsonContent(ref: '#/components/schemas/Conductor')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/conductores/{conductor}', summary: 'Ver conductor', security: [['sanctum' => []]], tags: ['Conductores'], parameters: [new OA\Parameter(name: 'conductor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Conductor encontrado', content: new OA\JsonContent(ref: '#/components/schemas/Conductor')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/conductores/{conductor}', summary: 'Actualizar conductor', security: [['sanctum' => []]], tags: ['Conductores'], parameters: [new OA\Parameter(name: 'conductor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ConductorRequest')), responses: [new OA\Response(response: 200, description: 'Conductor actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Conductor')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/conductores/{conductor}', summary: 'Eliminar conductor', security: [['sanctum' => []]], tags: ['Conductores'], parameters: [new OA\Parameter(name: 'conductor', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Conductor eliminado', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/propietarios', summary: 'Listar propietarios', security: [['sanctum' => []]], tags: ['Propietarios'], responses: [new OA\Response(response: 200, description: 'Listado de propietarios', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Propietario'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/propietarios', summary: 'Crear propietario', security: [['sanctum' => []]], tags: ['Propietarios'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/PropietarioRequest')), responses: [new OA\Response(response: 201, description: 'Propietario creado', content: new OA\JsonContent(ref: '#/components/schemas/Propietario')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/propietarios/{propietario}', summary: 'Ver propietario', security: [['sanctum' => []]], tags: ['Propietarios'], parameters: [new OA\Parameter(name: 'propietario', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Propietario encontrado', content: new OA\JsonContent(ref: '#/components/schemas/Propietario')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/propietarios/{propietario}', summary: 'Actualizar propietario', security: [['sanctum' => []]], tags: ['Propietarios'], parameters: [new OA\Parameter(name: 'propietario', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/PropietarioRequest')), responses: [new OA\Response(response: 200, description: 'Propietario actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Propietario')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/propietarios/{propietario}', summary: 'Eliminar propietario', security: [['sanctum' => []]], tags: ['Propietarios'], parameters: [new OA\Parameter(name: 'propietario', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Propietario eliminado', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/internos', summary: 'Listar internos', security: [['sanctum' => []]], tags: ['Internos'], responses: [new OA\Response(response: 200, description: 'Listado de internos', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Interno'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/internos', summary: 'Crear interno', security: [['sanctum' => []]], tags: ['Internos'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/InternoRequest')), responses: [new OA\Response(response: 201, description: 'Interno creado', content: new OA\JsonContent(ref: '#/components/schemas/Interno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/internos/{interno}', summary: 'Ver interno', security: [['sanctum' => []]], tags: ['Internos'], parameters: [new OA\Parameter(name: 'interno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Interno encontrado', content: new OA\JsonContent(ref: '#/components/schemas/Interno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/internos/{interno}', summary: 'Actualizar interno', security: [['sanctum' => []]], tags: ['Internos'], parameters: [new OA\Parameter(name: 'interno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/InternoRequest')), responses: [new OA\Response(response: 200, description: 'Interno actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Interno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/internos/{interno}', summary: 'Eliminar interno', security: [['sanctum' => []]], tags: ['Internos'], parameters: [new OA\Parameter(name: 'interno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Interno eliminado', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/micros', summary: 'Listar micros', security: [['sanctum' => []]], tags: ['Micros'], responses: [new OA\Response(response: 200, description: 'Listado de micros', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Micro'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/micros', summary: 'Crear micro', security: [['sanctum' => []]], tags: ['Micros'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/MicroRequest')), responses: [new OA\Response(response: 201, description: 'Micro creado', content: new OA\JsonContent(ref: '#/components/schemas/Micro')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/micros/{micro}', summary: 'Ver micro', security: [['sanctum' => []]], tags: ['Micros'], parameters: [new OA\Parameter(name: 'micro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Micro encontrado', content: new OA\JsonContent(ref: '#/components/schemas/Micro')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/micros/{micro}', summary: 'Actualizar micro', security: [['sanctum' => []]], tags: ['Micros'], parameters: [new OA\Parameter(name: 'micro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/MicroRequest')), responses: [new OA\Response(response: 200, description: 'Micro actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Micro')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/micros/{micro}', summary: 'Eliminar micro', security: [['sanctum' => []]], tags: ['Micros'], parameters: [new OA\Parameter(name: 'micro', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Micro eliminado', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/rutas', summary: 'Listar rutas', security: [['sanctum' => []]], tags: ['Rutas'], responses: [new OA\Response(response: 200, description: 'Listado de rutas', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Ruta'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/rutas', summary: 'Crear ruta', security: [['sanctum' => []]], tags: ['Rutas'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/RutaRequest')), responses: [new OA\Response(response: 201, description: 'Ruta creada', content: new OA\JsonContent(ref: '#/components/schemas/Ruta')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/rutas/{ruta}', summary: 'Ver ruta', security: [['sanctum' => []]], tags: ['Rutas'], parameters: [new OA\Parameter(name: 'ruta', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Ruta encontrada', content: new OA\JsonContent(ref: '#/components/schemas/Ruta')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/rutas/{ruta}', summary: 'Actualizar ruta', security: [['sanctum' => []]], tags: ['Rutas'], parameters: [new OA\Parameter(name: 'ruta', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/RutaRequest')), responses: [new OA\Response(response: 200, description: 'Ruta actualizada', content: new OA\JsonContent(ref: '#/components/schemas/Ruta')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/rutas/{ruta}', summary: 'Eliminar ruta', security: [['sanctum' => []]], tags: ['Rutas'], parameters: [new OA\Parameter(name: 'ruta', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Ruta eliminada', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/paradas', summary: 'Listar paradas', security: [['sanctum' => []]], tags: ['Paradas'], responses: [new OA\Response(response: 200, description: 'Listado de paradas', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Parada'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/paradas', summary: 'Crear parada', security: [['sanctum' => []]], tags: ['Paradas'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ParadaRequest')), responses: [new OA\Response(response: 201, description: 'Parada creada', content: new OA\JsonContent(ref: '#/components/schemas/Parada')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/paradas/{parada}', summary: 'Ver parada', security: [['sanctum' => []]], tags: ['Paradas'], parameters: [new OA\Parameter(name: 'parada', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Parada encontrada', content: new OA\JsonContent(ref: '#/components/schemas/Parada')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/paradas/{parada}', summary: 'Actualizar parada', security: [['sanctum' => []]], tags: ['Paradas'], parameters: [new OA\Parameter(name: 'parada', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/ParadaRequest')), responses: [new OA\Response(response: 200, description: 'Parada actualizada', content: new OA\JsonContent(ref: '#/components/schemas/Parada')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/paradas/{parada}', summary: 'Eliminar parada', security: [['sanctum' => []]], tags: ['Paradas'], parameters: [new OA\Parameter(name: 'parada', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Parada eliminada', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Get(path: '/api/turnos', summary: 'Listar turnos', security: [['sanctum' => []]], tags: ['Turnos'], responses: [new OA\Response(response: 200, description: 'Listado de turnos', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Turno'))), new OA\Response(response: 401, description: 'No autenticado')])]
#[OA\Post(path: '/api/turnos', summary: 'Crear turno', security: [['sanctum' => []]], tags: ['Turnos'], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/TurnoRequest')), responses: [new OA\Response(response: 201, description: 'Turno creado', content: new OA\JsonContent(ref: '#/components/schemas/Turno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Get(path: '/api/turnos/{turno}', summary: 'Ver turno', security: [['sanctum' => []]], tags: ['Turnos'], parameters: [new OA\Parameter(name: 'turno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Turno encontrado', content: new OA\JsonContent(ref: '#/components/schemas/Turno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado')])]
#[OA\Put(path: '/api/turnos/{turno}', summary: 'Actualizar turno', security: [['sanctum' => []]], tags: ['Turnos'], parameters: [new OA\Parameter(name: 'turno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(ref: '#/components/schemas/TurnoRequest')), responses: [new OA\Response(response: 200, description: 'Turno actualizado', content: new OA\JsonContent(ref: '#/components/schemas/Turno')), new OA\Response(response: 401, description: 'No autenticado'), new OA\Response(response: 404, description: 'No encontrado'), new OA\Response(response: 422, description: 'Datos invalidos')])]
#[OA\Delete(path: '/api/turnos/{turno}', summary: 'Eliminar turno', security: [['sanctum' => []]], tags: ['Turnos'], parameters: [new OA\Parameter(name: 'turno', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Turno eliminado', content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')), new OA\Response(response: 401, description: 'No autenticado')])]
final class OpenApiSpec
{
}
