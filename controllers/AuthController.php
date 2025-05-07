<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class AuthController {
    public static function login(Router $router) {
        $usuario = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);

            if(empty($alertas)) {
                $usuarioDB = Usuario::where('email', $usuario->email);

                if($usuarioDB) {
                    if($usuarioDB->verificarPassword($usuario->contrasena)) {
                        session_start();

                        $_SESSION['id'] = $usuarioDB->id;
                        $_SESSION['type'] = $usuarioDB->tipo_usuario_id;
                        $_SESSION['login'] = true;

                        if($usuarioDB->tipo_usuario_id == 1) {
                            header('location: /admin');
                        } else {
                            header('location: /');
                        }
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