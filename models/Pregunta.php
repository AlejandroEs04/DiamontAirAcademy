<?php
namespace Model;

class Pregunta extends ActiveRecord {
    protected static $tabla = 'preguntas_encuesta';
    protected static $columnasDB = ['id', 'encuesta_id', 'texto_pregunta', 'tipo_respuesta', 'orden', 'requerida'];

    public $id;
    public $encuesta_id;
    public $texto_pregunta;
    public $tipo_respuesta; 
    public $orden;
    public $requerida;
    public $opciones = []; 
    
    public static function whereManyCondition($conditions, $orderBy = '') {
        $whereParts = [];
        $values = [];
        
        foreach ($conditions as $field => $value) {
            $whereParts[] = "$field = ?";
            $values[] = $value;
        }
        
        $query = "SELECT * FROM " . static::$tabla;
        $query .= " WHERE " . implode(' AND ', $whereParts);
        
        if ($orderBy) {
            $query .= " ORDER BY " . $orderBy;
        }
        
        $stmt = self::$db->prepare($query);
        $types = str_repeat('s', count($values));
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $objects = [];
        
        while ($row = $result->fetch_assoc()) {
            $objects[] = static::crearObjeto($row);
        }
        
        return $objects;
    }

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->encuesta_id = $args['encuesta_id'] ?? null;
        $this->texto_pregunta = $args['texto_pregunta'] ?? '';
        $this->tipo_respuesta = $args['tipo_respuesta'] ?? 'texto';
        $this->orden = $args['orden'] ?? 0;
        $this->requerida = $args['requerida'] ?? 1;
    }

    public function validar() {
        if(!$this->texto_pregunta) {
            self::$errores[] = "El texto de la pregunta es obligatorio";
        }
        if(!$this->encuesta_id) {
            self::$errores[] = "Debe asociarse a una encuesta";
        }
        return self::$errores;
    }
}