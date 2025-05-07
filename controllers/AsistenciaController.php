<?php
namespace Controllers;

use MVC\Router;
use Model\Asistencia;
use Model\Horario;
use Model\Usuario;

class AsistenciaController {
    public static function registrarAsistencia(Router $router) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datosQR = json_decode(file_get_contents('php://input'), true);
            
            // Validar datos del QR
            $errores = self::validarDatosQR($datosQR);
            
            if (!empty($errores)) {
                echo json_encode([
                    'success' => false,
                    'errors' => $errores
                ]);
                return;
            }

            // Verificar que el horario existe y está activo
            $horario = Horario::find($datosQR['horario_id']);
            if (!$horario || !$horario->activo) {
                echo json_encode([
                    'success' => false,
                    'errors' => ['El horario no está activo']
                ]);
                return;
            }
            $alumno = Usuario::find($datosQR['alumno_id']);
            if (!$alumno || $alumno->tipo_usuario_id != 2) {
                echo json_encode([
                    'success' => false,
                    'errors' => ['Alumno no válido']
                ]);
                return;
            }
            $asistenciaExistente = Asistencia::whereManyCondition([
                'horario_id' => $datosQR['horario_id'],
                'alumno_id' => $datosQR['alumno_id'],
                'fecha' => $datosQR['fecha']
            ]);

            if (!empty($asistenciaExistente)) {
                echo json_encode([
                    'success' => false,
                    'errors' => ['Ya se registró asistencia hoy']
                ]);
                return;
            }

            // Registrar nueva asistencia
            $asistencia = new Asistencia([
                'horario_id' => $datosQR['horario_id'],
                'alumno_id' => $datosQR['alumno_id'],
                'fecha' => $datosQR['fecha'],
                'hora' => date('H:i:s'),
                'metodo' => 'qr',
                'token' => $datosQR['token'],
                'estado' => 'presente'
            ]);

            $resultado = $asistencia->guardar();

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Asistencia registrada correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'errors' => ['Error al guardar la asistencia']
                ]);
            }
        } else {
            header('Location: /alumno/horarios');
        }
    }

    private static function validarDatosQR($datos) {
        $errores = [];
        
        // Validar estructura básica
        $camposRequeridos = ['horario_id', 'alumno_id', 'fecha', 'token'];
        foreach ($camposRequeridos as $campo) {
            if (empty($datos[$campo])) {
                $errores[] = "El campo $campo es requerido";
            }
        }

        // Validar que la fecha sea hoy
        if (isset($datos['fecha']) && $datos['fecha'] != date('Y-m-d')) {
            $errores[] = "El QR no es válido para la fecha actual";
        }

        // Validar que el alumno coincide con la sesión (si se escanea en el sistema)
        if (isset($datos['alumno_id']) && $datos['alumno_id'] != $_SESSION['id']) {
            $errores[] = "El código QR no corresponde a este alumno";
        }

        return $errores;
    }
}