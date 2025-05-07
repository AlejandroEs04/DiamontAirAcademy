<?php
namespace Model;

class Pregunta extends ActiveRecord {
    protected static $tabla = 'preguntas_encuesta';
    protected static $columnasDB = ['id', 'encuesta_id', 'texto_pregunta', 'tipo_respuesta', 'orden', 'requerida'];

    public $id;
    public $encuesta_id;
    public $texto_pregunta;
    public $tipo_respuesta; // texto, opcion_multiple, escala, fecha
    public $orden;
    public $requerida;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->encuesta_id = $args['encuesta_id'] ?? null;
        $this->texto_pregunta = $args['texto_pregunta'] ?? '';
        $this->tipo_respuesta = $args['tipo_respuesta'] ?? 'texto';
        $this->orden = $args['orden'] ?? 0;
        $this->requerida = $args['requerida'] ?? 1;
    }

    public function validar() {
        if(!$this->texto_pregunta) {
            self::$errores[] = "El texto de la pregunta es obligatorio";
        }
        if(!$this->encuesta_id) {
            self::$errores[] = "Debe asociarse a una encuesta";
        }
        return self::$errores;
    }
}