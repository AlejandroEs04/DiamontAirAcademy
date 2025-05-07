<?php 

namespace Controllers;

use Model\Clase;
use Model\Horario;
use Model\Modalidad;
use MVC\Router;
use Model\Usuario;

class AdminController {
    public static function index(Router $router) {
        $router->render('admin/index', [
            
        ]);
    }
    
    public static function usuarios(Router $router) {
        $usuarios = Usuario::all();
        $router->render('admin/users/usuarios', [
            'usuarios' => $usuarios
        ]);
    }
    
    public static function createUser(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST['usuario']);
            $errores = $usuario->validar();

            $usuario->hashPassword();

            if (empty($errores)) {
                $usuario->guardar();
                header('Location: /admin/usuarios');
            } else {
                debuguear($errores);
            }
        }

        $router->render('admin/users/create', [

        ]);
    }

    public static function editUser(Router $router) {
        $id = validarORedireccionar('/admin/usuarios');
        $usuario = Usuario::find($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST['usuario'];
            
            $usuario->sincronizar($args);
            
            if (!empty($args['contrasena'])) {
                $usuario->contrasena = $args['contrasena'];
                $usuario->hashPassword();
            } else {
                $usuarioActual = Usuario::find($id);
                $usuario->contrasena = $usuarioActual->contrasena;
            }
    
            $errores = $usuario->validar();
    
            if (empty($errores)) {
                $resultado = $usuario->guardar();
                
                if ($resultado) {
                    header('Location: /admin/usuarios');
                    exit;
                }
            }
        }
    
        $router->render('admin/users/edit', [
            'usuario' => $usuario,
            'errores' => $errores ?? []
        ]);
    }

    public static function deleteUser(Router $router) {
        $id = validarORedireccionar('/admin/usuarios');
        Usuario::delete($id);

        header('Location: /admin/usuarios');

        $router->render('admin/users/edit', [
        ]);
    }

    public static function clases(Router $router) {
        $clases = Clase::all();

        $id = $_GET["id"];

        if(!is_null($id)) {
            $schedules = Horario::whereMany("clase_id", $id);
        }
        $router->render('admin/clases/clases', [
            'clases' => $clases,
            'schedules' => $schedules
        ]);
    }

    public static function createClase(Router $router) {
        $router->render('admin/users/edit', [
            
        ]);
    }

    public static function editClase(Router $router) {
        $id = validarORedireccionar('/admin/clases');

        $clase = Clase::find($id);
        $modalidades = Modalidad::all();
        $schedules = Horario::whereMany("clase_id", $id);

        $router->render('admin/clases/edit', [
            'clase' => $clase,
            'schedules' => $schedules,
            'modalidades' => $modalidades,
        ]);
    }

    public static function deleteClase(Router $router) {
        $router->render('admin/users/edit', [
            
        ]);
    }
}