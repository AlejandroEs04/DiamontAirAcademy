<?php
namespace Model;

class Encuesta extends ActiveRecord {
    protected static $tabla = 'encuestas';
    protected static $columnasDB = ['id', 'titulo', 'descripcion', 'fecha_creacion', 'activa'];

    public $id;
    public $titulo;
    public $descripcion;
    public $fecha_creacion;
    public $activa;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->fecha_creacion = $args['fecha_creacion'] ?? date('Y-m-d H:i:s');
        $this->activa = $args['activa'] ?? 1;
    }

    public function validar() {
        if(!$this->titulo) {
            self::$errores[] = "El título es obligatorio";
        }
        return self::$errores;
    }

    public static function count($conditions = []) {
        $query = "SELECT COUNT(*) as total FROM " . static::$tabla;
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "$column = ?";
            }
            $query .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $stmt = self::$db->prepare($query);
        
        if (!empty($conditions)) {
            $types = str_repeat('s', count($conditions));
            $stmt->bind_param($types, ...array_values($conditions));
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }
}