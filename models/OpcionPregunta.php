<?php
namespace Model;

class OpcionPregunta extends ActiveRecord {
    protected static $tabla = 'opciones_pregunta';
    protected static $columnasDB = ['id', 'pregunta_id', 'texto_opcion', 'valor', 'orden'];

    public $id;
    public $pregunta_id;
    public $texto_opcion;
    public $valor;
    public $orden;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->pregunta_id = $args['pregunta_id'] ?? null;
        $this->texto_opcion = $args['texto_opcion'] ?? '';
        $this->valor = $args['valor'] ?? '';
        $this->orden = $args['orden'] ?? 0;
    }

    public function validar() {
        if(!$this->texto_opcion) {
            self::$errores[] = "El texto de la opción es obligatorio";
        }
        if(!$this->pregunta_id) {
            self::$errores[] = "Debe asociarse a una pregunta";
        }
        return self::$errores;
    }
}