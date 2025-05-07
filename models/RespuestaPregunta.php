<?php 
namespace Model;

class RespuestaEncuesta extends ActiveRecord {
    protected static $tabla = 'respuestas_encuesta';
    protected static $columnasDB = 
        ['id', 'usuario_id', 'pregunta_id', 'respuesta_texto', 
         'respuesta_opcion_id', 'respuesta_escala', 'respuesta_fecha', 'fecha_respuesta'];

    public $id;
    public $usuario_id;
    public $pregunta_id;
    public $respuesta_texto;
    public $respuesta_opcion_id;
    public $respuesta_escala;
    public $respuesta_fecha;
    public $fecha_respuesta;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->pregunta_id = $args['pregunta_id'] ?? '';
        $this->respuesta_texto = $args['respuesta_texto'] ?? null;
        $this->respuesta_opcion_id = $args['respuesta_opcion_id'] ?? null;
        $this->respuesta_escala = $args['respuesta_escala'] ?? null;
        $this->respuesta_fecha = $args['respuesta_fecha'] ?? null;
        $this->fecha_respuesta = $args['fecha_respuesta'] ?? date('Y-m-d H:i:s');
    }

    public function validar() {
        if(!$this->usuario_id) {
            self::$errores[] = "Debe seleccionar un usuario";
        }
        if(!$this->pregunta_id) {
            self::$errores[] = "Debe seleccionar una pregunta";
        }
        
        // Cargar la pregunta relacionada con su tipo de respuesta
        $pregunta = PreguntaEncuesta::find($this->pregunta_id);
        
        if(!$pregunta) {
            self::$errores[] = "La pregunta seleccionada no existe";
            return self::$errores;
        }

        // Validación según tipo de respuesta
        switch($pregunta->tipo_respuesta) {
            case 'texto':
                if(empty($this->respuesta_texto)) {
                    self::$errores[] = "La respuesta de texto es obligatoria";
                }
                break;
                
            case 'opcion_multiple':
                if(empty($this->respuesta_opcion_id)) {
                    self::$errores[] = "Debe seleccionar una opción";
                }
                break;
                
            case 'escala':
                if($this->respuesta_escala === null) {
                    self::$errores[] = "Debe seleccionar un valor en la escala";
                }
                break;
                
            case 'fecha':
                if(empty($this->respuesta_fecha)) {
                    self::$errores[] = "Debe seleccionar una fecha";
                }
                break;
                
            default:
                self::$errores[] = "Tipo de respuesta no válido";
                break;
        }
        
        return self::$errores;
    }
}