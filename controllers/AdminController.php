<?php 

namespace Controllers;

use Model\CategoriaClase;
use Model\Clase;
use Model\Horario;
use Model\Inscripcion;
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

        $id = $_GET["id"] ?? null;

        if(!is_null($id)) {
            $schedules = Horario::whereMany("clase_id", $id);
        }
        $router->render('admin/clases/clases', [
            'clases' => $clases,
            'schedules' => $schedules ?? null
        ]);
    }

    public static function guardarClase(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST;
            $clase = new Clase($args);
            $errores = $clase->validar();
            
            if (empty($errores)) {
                $resultado = $clase->guardar();
                if ($resultado) {
                    header('Location: /admin/clases/edit?id=' . $clase->id);
                    exit;
                }
            }
            
            // Mostrar errores si hay
            $router->render('admin/clases/edit', [
                'clase' => $clase,
                'errores' => $errores,
                'categorias' => CategoriaClase::all(),
                'modalidades' => Modalidad::all(),
                'schedules' => Horario::where('clase_id', $clase->id)
            ]);
        }
    }

    public static function editClase(Router $router) {
        $id = validarORedireccionar('/admin/clases');

        $clase = Clase::find($id);
        $categorias = CategoriaClase::all();
        $modalidades = Modalidad::all();
        $schedules = Horario::whereMany("clase_id", $id);

        $router->render('admin/clases/edit', [
            'clase' => $clase,
            'schedules' => $schedules,
            'modalidades' => $modalidades,
            'categorias' => $categorias,
        ]);
    }

    public static function deleteClase(Router $router) {
        $router->render('admin/users/edit', [
            
        ]);
    }

    public static function crearHorario(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = $_POST;
            $horario = new Horario($args);
            $errores = $horario->validar();
            
            if (empty($errores)) {
                $horario->guardar();
            }
            
            header('Location: /admin/clases');
        }
    }

    public static function deleteHorario(Router $router) {
        $id = validarORedireccionar('/admin/clases');

        Horario::delete($id);
        header('/admin/clases');
    }

    public static function editHorario(Router $router) {
        $id = validarORedireccionar('/admin/clases');
        $schedule = Horario::find($id);
        $inscripciones = Inscripcion::whereMany("horario_id", $id);

        $router->render('admin/schedules/edit', [
            'schedule' => $schedule, 
            'inscripciones' => $inscripciones
        ]);
    }

    public static function actualizarHorario(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $horario = Horario::find($id);
            
            if (!$horario) {
                header('Location: /admin/clases');
                exit;
            }
            
            // Solo actualizar los campos permitidos
            $args = [
                'dia_semana' => $_POST['dia_semana'] ?? $horario->dia_semana,
                'hora_inicio' => $_POST['hora_inicio'] ?? $horario->hora_inicio,
                'hora_fin' => $_POST['hora_fin'] ?? $horario->hora_fin
            ];
            
            $horario->sincronizar($args);
            $errores = $horario->validar();
            
            if (empty($errores)) {
                $resultado = $horario->guardar();
                if ($resultado) {
                    header('Location: /admin/horarios/editar?id=' . $horario->id);
                    exit;
                }
            }
            
            // Si hay errores, mostrar el formulario nuevamente
            $inscripciones = Inscripcion::whereMany("horario_id", $horario->id);
            $router->render('admin/clases/edit', [
                'schedule' => $horario,
                'inscripciones' => $inscripciones,
                'errores' => $errores
            ]);
        }
    }

    public static function actualizarInscripcion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $inscripcion = Inscripcion::find($id);
            
            if ($inscripcion) {
                $inscripcion->estado = $_POST['estado'] ?? 'activa';
                $inscripcion->guardar();
            }
            
            // Redirigir de vuelta a la página de edición
            $horario_id = $inscripcion ? $inscripcion->horario_id : null;
            header('Location: /admin/schedules/edit?id='.$horario_id);
            exit;
        }
    }

    public static function eliminarInscripcion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $inscripcion = Inscripcion::find($id);
            $horario_id = $inscripcion ? $inscripcion->horario_id : null;
            
            if ($inscripcion) {
                $inscripcion->eliminar();
            }
            
            header('Location: /admin/schedules/edit?id='.$horario_id);
            exit;
        }
    }

    public static function crearInscripcion(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $args = [
                'horario_id' => $_POST['horario_id'],
                'usuario_id' => $_POST['usuario_id'],
                'estado' => $_POST['estado'] ?? 'activa',
                'fecha_inscripcion' => date('Y-m-d H:i:s')
            ];
            
            $inscripcion = new Inscripcion($args);
            $errores = $inscripcion->validar();
            
            if (empty($errores)) {
                $inscripcion->guardar();
            }
            
            header('Location: /admin/schedules/edit?id='.$_POST['horario_id']);
        }
    }

    public static function asistencia(Router $router) {
        $router->render('admin/qrcode', [

        ]);
    }
}