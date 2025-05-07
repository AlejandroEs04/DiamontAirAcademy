<?php 
namespace Model;

class Asistencia extends ActiveRecord {
    protected static $tabla = 'asistencias';
    protected static $columnasDB = 
        ['id', 'usuario_id', 'horario_id', 'fecha', 'hora', 'estado', 'metodo_registro', 'observaciones'];

    public $id;
    public $usuario_id;
    public $horario_id;
    public $fecha;
    public $hora;
    public $estado;
    public $metodo_registro;
    public $observaciones;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->horario_id = $args['horario_id'] ?? '';
        $this->fecha = $args['fecha'] ?? date('Y-m-d');
        $this->hora = $args['hora'] ?? date('H:i:s');
        $this->estado = $args['estado'] ?? 'presente';
        $this->metodo_registro = $args['metodo_registro'] ?? 'qr';
        $this->observaciones = $args['observaciones'] ?? '';
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