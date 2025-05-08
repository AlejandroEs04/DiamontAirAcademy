<?php
namespace Controllers;

use MVC\Router;
use Model\Pregunta;
use Model\OpcionPregunta;

class PreguntaController {
    public static function crear(Router $router) {
        $encuesta_id = $_GET['encuesta_id'] ?? null;
        if(!$encuesta_id) header('Location: /admin/encuestas');

        $pregunta = new Pregunta(['encuesta_id' => $encuesta_id]);
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pregunta = new Pregunta($_POST);
            $errores = $pregunta->validar();

            if(empty($errores)) {
                $resultado = $pregunta->guardar();
                if($resultado) {
                    // Si es pregunta de opción múltiple, redirigir a agregar opciones
                    if($pregunta->tipo_respuesta === 'opcion_multiple') {
                        header('Location: /admin/preguntas/opciones?pregunta_id=' . $pregunta->id);
                    } else {
                        header('Location: /admin/encuestas/editar?id=' . $pregunta->encuesta_id);
                    }
                    exit;
                }
            }
        }

        $router->render('admin/preguntas/crear', [
            'pregunta' => $pregunta,
            'errores' => $errores,
            'tipos_pregunta' => [
                'texto' => 'Texto libre',
                'opcion_multiple' => 'Opción múltiple',
                'escala' => 'Escala numérica',
                'fecha' => 'Fecha'
            ]
        ]);
    }

    public static function editar(Router $router) {
        $id = $_GET['id'] ?? null;
        if(!$id) header('Location: /admin/encuestas');

        $pregunta = Pregunta::find($id);
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$pregunta) {
                header("Location: /");
                exit;
            }
            
            $pregunta->sincronizar($_POST);
            $errores = $pregunta->validar();

            if(empty($errores)) {
                $resultado = $pregunta->guardar();
                if($resultado) {
                    header('Location: /admin/encuestas/editar?id=' . $pregunta->encuesta_id);
                    exit;
                }
            }
        }

        $router->render('admin/preguntas/editar', [
            'pregunta' => $pregunta,
            'errores' => $errores,
            'tipos_pregunta' => [
                'texto' => 'Texto libre',
                'opcion_multiple' => 'Opción múltiple',
                'escala' => 'Escala numérica',
                'fecha' => 'Fecha'
            ]
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $pregunta = Pregunta::find($id);
            
            if($pregunta) {
                // Eliminar opciones si es tipo opcion_multiple
                if($pregunta->tipo_respuesta === 'opcion_multiple') {
                    OpcionPregunta::deleteWhere('pregunta_id', $pregunta->id);
                }
                
                $encuesta_id = $pregunta->encuesta_id;
                $pregunta->eliminar();
                
                header('Location: /admin/encuestas/editar?id=' . $encuesta_id);
                exit;
            }

            header('Location: /admin/encuestas');
        }
    }

    public static function opciones(Router $router) {
        $pregunta_id = $_GET['pregunta_id'] ?? null;
        if(!$pregunta_id) header('Location: /admin/encuestas');

        $pregunta = Pregunta::find($pregunta_id);
        $opciones = OpcionPregunta::where('pregunta_id', $pregunta_id);
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $opcion = new OpcionPregunta($_POST);
            $errores = $opcion->validar();

            if(empty($errores)) {
                $resultado = $opcion->guardar();
                if($resultado) {
                    header('Location: /admin/preguntas/opciones?pregunta_id=' . $pregunta_id);
                    exit;
                }
            }
        }

        $router->render('admin/preguntas/opciones', [
            'pregunta' => $pregunta,
            'opciones' => $opciones,
            'errores' => $errores
        ]);
    }
}