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
    private $alumno_relacionado;
    public $clase = null;
    public $modalidad = null;
    public $inscripcion_id = null;

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

    public function cargarRelaciones() {
        $this->clase = Clase::find($this->clase_id);
        $this->modalidad = Modalidad::find($this->modalidad_id);
        return $this;
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

    public static function getUpcomingClasses($limit = 3) {
        $query = "SELECT * FROM " . static::$tabla . " 
                  WHERE fecha_inicio >= CURDATE() 
                  ORDER BY fecha_inicio ASC, hora_inicio ASC 
                  LIMIT ?";
        $stmt = self::$db->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horarios[] = static::crearObjeto($row);
        }
        
        return $horarios;
    }

    public static function count($conditions = []) {
        $query = "SELECT COUNT(*) as total FROM " . static::$tabla;
        $params = [];
        $types = '';
        
        // Si hay condiciones
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "$column = ?";
                $params[] = $value;
                $types .= is_int($value) ? 'i' : 's';
            }
            $query .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $stmt = self::$db->prepare($query);
        
        // Bind parameters si hay
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        $stmt->close();
        
        return $total;
    }
}