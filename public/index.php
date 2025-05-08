<?php

use Controllers\AuthController;
use Controllers\PaginasController;
use Controllers\EncuestaController;
use Controllers\PreguntaController;
use Controllers\AdminController;
use Controllers\AlumnoController;
use Controllers\AsistenciaController;
use MVC\Router;

require_once __DIR__ . "../../includes/app.php";

$router = new Router();

$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);
$router->get('/contactanos', [PaginasController::class, 'contactanos']);
$router->get('/eventos', [PaginasController::class, 'eventos']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/usuarios', [AdminController::class, 'usuarios']);
$router->get('/admin/usuarios/create', [AdminController::class, 'createUser']);
$router->post('/admin/usuarios/create', [AdminController::class, 'createUser']);
$router->get('/admin/usuarios/edit', [AdminController::class, 'editUser']);
$router->post('/admin/usuarios/edit', [AdminController::class, 'editUser']);
$router->get('/admin/usuarios/delete', [AdminController::class, 'deleteUser']);

$router->get('/admin/clases', [AdminController::class, 'clases']);
$router->get('/admin/clases/create', [AdminController::class, 'createClase']);
$router->post('/admin/clases/create', [AdminController::class, 'guardarClase']);
$router->get('/admin/clases/edit', [AdminController::class, 'editClase']);
$router->post('/admin/clases/edit', [AdminController::class, 'guardarClase']);
$router->get('/admin/clases/delete', [AdminController::class, 'deleteClase']);

$router->post('/admin/schedules/edit', [AdminController::class, 'editHorario']);
$router->get('/admin/schedules/edit', [AdminController::class, 'editHorario']);
$router->post('/admin/schedules/create', [AdminController::class, 'crearHorario']);
$router->get('/admin/schedules/delete', [AdminController::class, 'deleteHorario']);

$router->post('/admin/inscripciones/crear', [AdminController::class, 'crearInscripcion']);
$router->post('/admin/inscripciones/actualizar', [AdminController::class, 'actualizarInscripcion']);
$router->post('/admin/inscripciones/eliminar', [AdminController::class, 'eliminarInscripcion']);

$router->get('/admin/encuestas', [EncuestaController::class, 'index']);
$router->get('/admin/encuestas/crear', [EncuestaController::class, 'crear']);
$router->post('/admin/encuestas/crear', [EncuestaController::class, 'crear']);
$router->get('/admin/encuestas/editar', [EncuestaController::class, 'editar']);
$router->post('/admin/encuestas/eliminar', [EncuestaController::class, 'eliminar']);

$router->get('/admin/preguntas/crear', [PreguntaController::class, 'crear']);
$router->post('/admin/preguntas/crear', [PreguntaController::class, 'crear']);
$router->get('/admin/preguntas/editar', [PreguntaController::class, 'editar']);
$router->post('/admin/preguntas/eliminar', [PreguntaController::class, 'eliminar']);
$router->get('/admin/preguntas/opciones', [PreguntaController::class, 'opciones']);
$router->post('/admin/preguntas/opciones', [PreguntaController::class, 'opciones']);

$router->get('/admin/qr', [AdminController::class, 'asistencia']);

$router->get('/alumno', [AlumnoController::class, 'index']);
$router->post('/asistencia/registrar', [AsistenciaController::class, 'registrarAsistencia']);

$router->get('/encuestas', [EncuestaController::class, 'listar']);
$router->get('/encuestas/contestar', [EncuestaController::class, 'contestar']);
$router->post('/encuestas/guardar-respuestas', [EncuestaController::class, 'guardarRespuestas']);

$router->comprobarRutas();