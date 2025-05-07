<?php 
namespace Model;

class Clase extends ActiveRecord {
    protected static $tabla = 'clases';
    protected static $columnasDB = 
        ['id', 'categoria_id', 'nombre', 'descripcion', 'nivel', 'duracion_minutos', 'activa'];

    public $id;
    public $categoria_id;
    public $nombre;
    public $descripcion;
    public $nivel;
    public $duracion_minutos;
    public $activa;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->categoria_id = $args['categoria_id'] ?? '';
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->nivel = $args['nivel'] ?? 'Principiante';
        $this->duracion_minutos = $args['duracion_minutos'] ?? 60;
        $this->activa = $args['activa'] ?? 1;
    }

    public function validar() {
        if(!$this->nombre) {
            self::$errores[] = "El nombre de la clase es obligatorio";
        }
        if(!$this->categoria_id) {
            self::$errores[] = "Debe seleccionar una categoría";
        }
        return self::$errores;
    }
}