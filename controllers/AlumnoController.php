<?php
namespace Controllers;

use MVC\Router;
use Model\Horario;
use Model\Clase;
use Model\Modalidad;
use Model\Asistencia;
use Model\Inscripcion;

class AlumnoController {
    public static function index(Router $router) {
        // Verificar que el usuario es alumno
        if ($_SESSION['type'] != 2) {
            header('Location: /');
            exit;
        }

        // Obtener horarios activos del alumno
        // Obtener inscripciones activas del alumno
        $inscripciones = Inscripcion::whereManyCondition([
            'usuario_id' => $_SESSION['id'],
            'estado' => 'activa'
        ]);

        $horariosActivos = [];
        
        // Obtener los horarios relacionados con las inscripciones
        foreach ($inscripciones as $inscripcion) {
            $horario = Horario::find($inscripcion->horario_id);
            
            if ($horario && $horario->activo) {
                // Enriquecer los datos del horario
                $horario->clase = Clase::find($horario->clase_id);
                $horario->modalidad = Modalidad::find($horario->modalidad_id);
                $horario->inscripcion_id = $inscripcion->id; // Guardar referencia a la inscripción
                
                $horariosActivos[] = $horario;
            }
        }
        $router->render('alumnos/index', [
            'horariosActivos' => $horariosActivos,
            'nombre' => $_SESSION['nombre'] ?? ''
        ]);
    }
}