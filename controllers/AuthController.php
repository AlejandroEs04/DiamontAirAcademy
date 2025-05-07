<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class AuthController {
    public static function login(Router $router) {

        $usuario = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            // $alertas = $usuario->validar();

            if(empty($alertas)) {
                $usuarioDB = Usuario::where('email', $usuario->email);
                
                if($usuarioDB) {
                    if($usuario->verificarPassword($usuario->contrasena)) {
                        session_start();

                        $_SESSION['id'] = $usuarioDB->id;
                        $_SESSION['login'] = true;

                        header('location: /');

                    } else {
                        debuguear('no es correcto');
                    }
                } else {
                    $alertas = Usuario::getErrores();
                }
            }
        }

        $router->render('auth/login', [

        ]);
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /login');
    }
}