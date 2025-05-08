<?php
namespace Controllers;

use MVC\Router;
use Model\Encuesta;
use Model\Pregunta;
use Model\OpcionPregunta;
use Model\RespuestaEncuesta;

class EncuestaController {
    public static function index(Router $router) {
        $encuestas = Encuesta::all();
        $router->render('admin/encuestas/index', [
            'encuestas' => $encuestas
        ]);
    }

    public static function crear(Router $router) {
        $encuesta = new Encuesta();
        $errores = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $encuesta = new Encuesta($_POST);
            $errores = $encuesta->validar();

            if(empty($errores)) {
                $resultado = $encuesta->guardar();
                if($resultado) {
                    header('Location: /admin/encuestas/editar?id=' . $encuesta->id);
                    exit;
                }
            }
        }

        $router->render('admin/encuestas/crear', [
            'encuesta' => $encuesta,
            'errores' => $errores
        ]);
    }

    public static function editar(Router $router) {
        $id = $_GET['id'] ?? null;
        if(!$id) header('Location: /admin/encuestas');

        $encuesta = Encuesta::find($id);
        $preguntas = Pregunta::whereMany('encuesta_id', $id);
        
        // Para cada pregunta, cargar sus opciones si es tipo opcion_multiple
        foreach($preguntas as $pregunta) {
            if($pregunta->tipo_respuesta === 'opcion_multiple') {
                $pregunta->opciones = OpcionPregunta::where('pregunta_id', $pregunta->id);
            }
        }

        $router->render('admin/encuestas/editar', [
            'encuesta' => $encuesta,
            'preguntas' => $preguntas,
            'errores' => $errores ?? []
        ]);
    }

    public static function eliminar() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $encuesta = Encuesta::find($id);
            
            if($encuesta) {
                // Eliminar preguntas y opciones relacionadas primero
                $preguntas = Pregunta::where('encuesta_id', $id);
                foreach($preguntas as $pregunta) {
                    OpcionPregunta::deleteWhere('pregunta_id', $pregunta->id);
                }
                Pregunta::deleteWhere('encuesta_id', $id);
                
                // Finalmente eliminar la encuesta
                $encuesta->eliminar();
            }

            header('Location: /admin/encuestas');
        }
    }

    public static function listar(Router $router) {
        $todasEncuestas = Encuesta::whereMany('activa', 1);
        
        $encuestasPendientes = array_filter($todasEncuestas, function($encuesta) {
            $respuestas = RespuestaEncuesta::whereManyCondition([
                'usuario_id' => $_SESSION['id'],
                'encuesta_id' => $encuesta->id
            ]);
            return empty($respuestas);
        });
        
        $router->render('encuestas/listado', [
            'encuestas' => array_values($encuestasPendientes)
        ]);
    }

    public static function contestar(Router $router) {
        $id = $_GET['id'] ?? null;
        if(!$id) header('Location: /encuestas');
        
        $encuesta = Encuesta::find($id);
        if(!$encuesta || !$encuesta->activa) {
            header('Location: /encuestas?error=encuesta_no_disponible');
            exit;
        }
        
        // Obtener preguntas ordenadas
        $preguntas = Pregunta::whereManyCondition([
            'encuesta_id' => $id
        ], 'orden ASC');
        
        // Cargar opciones para preguntas de opción múltiple
        foreach($preguntas as $pregunta) {
            if($pregunta->tipo_respuesta === 'opcion_multiple') {
                $pregunta->opciones = OpcionPregunta::whereManyCondition([
                    'pregunta_id' => $pregunta->id
                ], 'orden ASC');
            }
        }
    
        $router->render('encuestas/contestar', [
            'encuesta' => $encuesta,
            'preguntas' => $preguntas
        ]);
    }

    public static function guardarRespuestas(Router $router) {
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /encuestas');
            exit;
        }
        
        $usuarioId = $_SESSION['id'] ?? null;
        if(!$usuarioId) {
            header('Location: /login');
            exit;
        }
        
        $encuestaId = $_POST['encuesta_id'] ?? null;
        $respuestas = $_POST['respuestas'] ?? [];
        
        try {
            // Verificar si el usuario ya respondió esta encuesta
            $existeRespuesta = RespuestaEncuesta::whereManyCondition([
                'usuario_id' => $usuarioId,
                'encuesta_id' => $encuestaId
            ]);
            
            if(count($existeRespuesta) > 0) {
                header('Location: /encuestas?error=ya_respondiste');
                exit;
            }
            
            // Guardar cada respuesta
            foreach($respuestas as $preguntaId => $respuesta) {
                $pregunta = Pregunta::find($preguntaId);

                if (!$pregunta) {
                    header("Location: /");
                    exit;
                }
                
                $dataRespuesta = [
                    'usuario_id' => $usuarioId,
                    'pregunta_id' => $preguntaId,
                    'encuesta_id' => $encuestaId
                ];
                
                switch($pregunta->tipo_respuesta) {
                    case 'texto':
                        $dataRespuesta['respuesta_texto'] = $respuesta;
                        break;
                    case 'opcion_multiple':
                        $dataRespuesta['respuesta_opcion_id'] = $respuesta;
                        break;
                    case 'escala':
                        $dataRespuesta['respuesta_escala'] = $respuesta;
                        break;
                    case 'fecha':
                        $dataRespuesta['respuesta_fecha'] = $respuesta;
                        break;
                }
                
                $nuevaRespuesta = new RespuestaEncuesta($dataRespuesta);
                $nuevaRespuesta->guardar();
            }
            
            header('Location: /encuestas?success=1');
            exit;
            
        } catch(\Exception $e) {
            header("Location: /encuestas/contestar?id=$encuestaId&error=1");
            exit;
        }
    }
}