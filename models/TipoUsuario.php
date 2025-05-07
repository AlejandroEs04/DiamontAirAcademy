<?php 
namespace Model;

class TipoUsuario extends ActiveRecord {
    protected static $tabla = 'tipos_usuario';
    protected static $columnasDB = ['id', 'nombre', 'descripcion'];

    public $id;
    public $nombre;
    public $descripcion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
    }
}