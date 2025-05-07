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


$router->comprobarRutas();