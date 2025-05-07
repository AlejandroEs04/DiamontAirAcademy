<?php 
namespace Model;

class PreguntaEncuesta extends ActiveRecord {
    protected static $tabla = 'preguntas_encuesta';
    protected static $columnasDB = 
        ['id', 'tipo_encuesta_id', 'texto_pregunta', 'tipo_respuesta', 'orden', 'obligatoria'];

    public $id;
    public $tipo_encuesta_id;
    public $texto_pregunta;
    public $tipo_respuesta;
    public $orden;
    public $obligatoria;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->tipo_encuesta_id = $args['tipo_encuesta_id'] ?? '';
        $this->texto_pregunta = $args['texto_pregunta'] ?? '';
        $this->tipo_respuesta = $args['tipo_respuesta'] ?? 'texto';
        $this->orden = $args['orden'] ?? 0;
        $this->obligatoria = $args['obligatoria'] ?? 1;
    }

    public function validar() {
        if(!$this->texto_pregunta) {
            self::$errores[] = "El texto de la pregunta es obligatorio";
        }
        if(!$this->tipo_encuesta_id) {
            self::$errores[] = "Debe seleccionar un tipo de encuesta";
        }
        return self::$errores;
    }
}