<?php
namespace Controllers;

use MVC\Router;
use Model\Asistencia;
use Model\Horario;
use Model\Usuario;

class AsistenciaController {
    public static function registrarAsistencia(Router $router) {
        header('Content-Type: application/json');
        
        try {
            // Verificar método HTTP
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Método no permitido', 405);
            }
    
            // Obtener y validar JSON
            $json = file_get_contents('php://input');
            if ($json === false) {
                throw new \Exception('Error al leer los datos de entrada', 400);
            }
    
            $datosQR = json_decode($json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('JSON inválido: ' . json_last_error_msg(), 400);
            }
    
            // Debug detallado (registra en archivo log)
            self::logDebug([
                'endpoint' => '/asistencia/registrar',
                'method' => $_SERVER['REQUEST_METHOD'],
                'payload' => $datosQR,
                'headers' => getallheaders()
            ]);
    
            // Validar datos del QR
            $errores = self::validarDatosQR($datosQR);
            if (!empty($errores)) {
                throw new \Exception(implode(', ', $errores), 422);
            }
    
            // Verificar horario
            $horario = Horario::find($datosQR['horario_id']);
            if (!$horario || !$horario->activo) {
                throw new \Exception('El horario no está activo', 400);
            }
    
            // Verificar alumno
            $alumno = Usuario::find($datosQR['alumno_id']);
            if (!$alumno || $alumno->tipo_usuario_id != 2) {
                throw new \Exception('Alumno no válido', 400);
            }
    
            // Verificar asistencia existente
            $asistenciaExistente = Asistencia::whereManyCondition([
                'horario_id' => $datosQR['horario_id'],
                'usuario_id' => $datosQR['alumno_id'],
                'fecha' => $datosQR['fecha']
            ]);
    
            if (!empty($asistenciaExistente)) {
                throw new \Exception('Ya se registró asistencia hoy', 409);
            }
    
            // Registrar nueva asistencia
            $asistencia = new Asistencia([
                'horario_id' => $datosQR['horario_id'],
                'usuario_id' => $datosQR['alumno_id'],
                'fecha' => $datosQR['fecha'],
                'hora' => date('H:i:s'),
                'metodo' => 'qr',
                'token' => $datosQR['token'],
                'estado' => 'presente'
            ]);

            $asistencia->guardar();

            // Respuesta exitosa
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Asistencia registrada correctamente',
                'data' => [
                    'asistencia_id' => $asistencia->id,
                    'fecha' => $asistencia->fecha,
                    'hora' => $asistencia->hora
                ]
            ]);
    
        } catch (\Exception $e) {
            // Manejo centralizado de errores
            $statusCode = $e->getCode() ?: 500;
            http_response_code($statusCode);
            
            self::logError($e->getMessage(), [
                'trace' => $e->getTrace(),
                'payload' => $datosQR ?? null
            ]);
            
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $statusCode
            ]);
        }
    }
    
    private static function ensureLogsDirectory() {
        $logsDir = __DIR__ . '/../logs';
        if (!file_exists($logsDir)) {
            mkdir($logsDir, 0777, true);
        }
    }
    
    private static function logDebug($data) {
        self::ensureLogsDirectory();
        $log = '[' . date('Y-m-d H:i:s') . '] DEBUG: ' . json_encode($data) . PHP_EOL;
        file_put_contents(__DIR__ . '/../logs/debug.log', $log, FILE_APPEND);
    }
    
    private static function logError($message, $context = []) {
        self::ensureLogsDirectory();
        $log = '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $message . PHP_EOL;
        $log .= 'Context: ' . json_encode($context) . PHP_EOL . PHP_EOL;
        file_put_contents(__DIR__ . '/../logs/errors.log', $log, FILE_APPEND);
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
        // if (isset($datos['alumno_id']) && $datos['alumno_id'] != $_SESSION['id']) {
        //     $errores[] = "El código QR no corresponde a este alumno";
        // }

        return $errores;
    }
}