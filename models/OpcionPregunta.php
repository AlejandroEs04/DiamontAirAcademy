<?php 
namespace Model;

class OpcionPregunta extends ActiveRecord {
    protected static $tabla = 'opciones_pregunta';
    protected static $columnasDB = ['id', 'pregunta_id', 'valor', 'texto_opcion', 'orden'];

    public $id;
    public $pregunta_id;
    public $valor;
    public $texto_opcion;
    public $orden;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->pregunta_id = $args['pregunta_id'] ?? '';
        $this->valor = $args['valor'] ?? '';
        $this->texto_opcion = $args['texto_opcion'] ?? '';
        $this->orden = $args['orden'] ?? 0;
    }

    public function validar() {
        if(!$this->pregunta_id) {
            self::$errores[] = "Debe seleccionar una pregunta";
        }
        if(!$this->texto_opcion) {
            self::$errores[] = "El texto de la opción es obligatorio";
        }
        return self::$errores;
    }
}