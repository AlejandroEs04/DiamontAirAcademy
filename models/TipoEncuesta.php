<?php 
namespace Model;

class TipoEncuesta extends ActiveRecord {
    protected static $tabla = 'tipos_encuesta';
    protected static $columnasDB = ['id', 'nombre', 'descripcion', 'activa'];

    public $id;
    public $nombre;
    public $descripcion;
    public $activa;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->activa = $args['activa'] ?? 1;
    }
}