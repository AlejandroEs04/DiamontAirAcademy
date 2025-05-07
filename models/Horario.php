<?php 
namespace Model;

class Horario extends ActiveRecord {
    protected static $tabla = 'horarios';
    protected static $columnasDB = 
        ['id', 'clase_id', 'modalidad_id', 'dia_semana', 'hora_inicio', 'hora_fin', 
         'fecha_inicio', 'fecha_fin', 'capacidad_maxima', 'activo'];

    public $id;
    public $clase_id;
    public $modalidad_id;
    public $dia_semana;
    public $hora_inicio;
    public $hora_fin;
    public $fecha_inicio;
    public $fecha_fin;
    public $capacidad_maxima;
    public $activo;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->clase_id = $args['clase_id'] ?? '';
        $this->modalidad_id = $args['modalidad_id'] ?? '';
        $this->dia_semana = $args['dia_semana'] ?? '';
        $this->hora_inicio = $args['hora_inicio'] ?? '';
        $this->hora_fin = $args['hora_fin'] ?? '';
        $this->fecha_inicio = $args['fecha_inicio'] ?? '';
        $this->fecha_fin = $args['fecha_fin'] ?? null;
        $this->capacidad_maxima = $args['capacidad_maxima'] ?? 15;
        $this->activo = $args['activo'] ?? 1;
    }

    public function validar() {
        if(!$this->clase_id) {
            self::$errores[] = "Debe seleccionar una clase";
        }
        if(!$this->modalidad_id) {
            self::$errores[] = "Debe seleccionar una modalidad";
        }
        if(!$this->hora_inicio || !$this->hora_fin) {
            self::$errores[] = "Debe especificar horario de inicio y fin";
        }
        if(!$this->fecha_inicio) {
            self::$errores[] = "Debe especificar fecha de inicio";
        }
        return self::$errores;
    }
}