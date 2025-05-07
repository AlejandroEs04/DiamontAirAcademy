<?php 

namespace Controllers;

use MVC\Router;

class AdminController {
    public static function index(Router $router) {
        $router->render('admin/index', [

        ]);
    }
    
    public static function usuarios(Router $router) {
        $router->render('admin/usuarios', [

        ]);
    }
}