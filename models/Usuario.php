<?php 
namespace Model;

/**
 * @method static Usuario|null find(int $id)
 * @method static Usuario|null where(string $field, mixed $value)
 */
class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = 
        ['id', 'tipo_usuario_id', 'nombre', 'apellido', 'email', 'contrasena', 'fecha_registro', 'activo'];

    public $id;
    public $tipo_usuario_id;
    public $nombre;
    public $apellido;
    public $email;
    public $contrasena;
    public $fecha_registro;
    public $activo;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->tipo_usuario_id = $args['tipo_usuario_id'] ?? 2; // Por defecto alumno
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->contrasena = $args['contrasena'] ?? '';
        $this->fecha_registro = $args['fecha_registro'] ?? date('Y-m-d H:i:s');
        $this->activo = $args['activo'] ?? 1;
    }

    public function validar() {
        if(!$this->email) {
            self::$errores[] = "El email es obligatorio";
        }
        if(!$this->nombre) {
            self::$errores[] = "El nombre es obligatorio";
        }
        if(!$this->apellido) {
            self::$errores[] = "El apellido es obligatorio";
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = "El email no es válido";
        }
        return self::$errores;
    }

    public function hashPassword() {
        $this->contrasena = password_hash($this->contrasena, PASSWORD_BCRYPT);
    }

    public function verificarPassword($contrasena) {
        return password_verify($contrasena, $this->contrasena);
    }
}