<?php 
namespace Model;

class Inscripcion extends ActiveRecord {
    protected static $tabla = 'inscripciones';
    protected static $columnasDB = 
        ['id', 'usuario_id', 'horario_id', 'fecha_inscripcion', 'estado'];

    public $id;
    public $usuario_id;
    public $horario_id;
    public $fecha_inscripcion;
    public $estado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->horario_id = $args['horario_id'] ?? '';
        $this->fecha_inscripcion = $args['fecha_inscripcion'] ?? date('Y-m-d H:i:s');
        $this->estado = $args['estado'] ?? 'activa';
    }

    public function validar() {
        if(!$this->usuario_id) {
            self::$errores[] = "Debe seleccionar un usuario";
        }
        if(!$this->horario_id) {
            self::$errores[] = "Debe seleccionar un horario";
        }
        return self::$errores;
    }
}