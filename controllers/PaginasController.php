<?php

namespace Controllers;
use MVC\Router;


class PaginasController {
    public static function index(Router $router) {
        $router->render('public/index', [
            
        ]);
    }
    
    public static function nosotros(Router $router) {
        $router->render('public/nosotros', [
            
        ]);
    }

    public static function contactanos(Router $router) {
        $router->render('public/contactanos', [
            
        ]);
    }

    public static function eventos(Router $router) {
        $router->render('public/eventos', [
            
        ]);
    }
}