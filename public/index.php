<?php

use Controllers\AuthController;
use Controllers\PaginasController;
use Controllers\AdminController;
use MVC\Router;

require_once __DIR__ . "../../includes/app.php";

$router = new Router();

$router->get('/', [PaginasController::class, 'index']);
$router->get('/nosotros', [PaginasController::class, 'nosotros']);
$router->get('/contactanos', [PaginasController::class, 'contactanos']);
$router->get('/eventos', [PaginasController::class, 'eventos']);

$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/usuarios', [AdminController::class, 'usuarios']);
$router->get('/admin/usuarios/create', [AdminController::class, 'createUser']);
$router->post('/admin/usuarios/create', [AdminController::class, 'createUser']);
$router->get('/admin/usuarios/edit', [AdminController::class, 'editUser']);
$router->post('/admin/usuarios/edit', [AdminController::class, 'editUser']);
$router->get('/admin/usuarios/delete', [AdminController::class, 'deleteUser']);

$router->get('/admin/clases', [AdminController::class, 'clases']);
$router->get('/admin/clases/create', [AdminController::class, 'createClase']);
$router->post('/admin/clases/create', [AdminController::class, 'createClase']);
$router->get('/admin/clases/edit', [AdminController::class, 'editClase']);
$router->post('/admin/clases/edit', [AdminController::class, 'editClase']);
$router->get('/admin/clases/delete', [AdminController::class, 'deleteClase']);

$router->comprobarRutas();