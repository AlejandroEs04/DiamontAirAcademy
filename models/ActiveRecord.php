<?php

namespace Model;

class ActiveRecord {
    protected static $db;
    public $id;
    protected static $tabla = '';
    protected static $columnasDB = [];

    protected static $errores = [];

    /** DEFINIR LA CONEXION A LA BASE DE DATOS */
    public static function setDB($database) {
        self::$db = $database;
    }

    public function guardar() {
        if(!is_null($this->id)) {
            // actualizar
            $this->actualizar();
        } else {
            // Creando un nuevo registro
            $this->crear();
        }

        return true;
    }

    public function crear() {

        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .=  " ) VALUES (' ";
        $query .= join("' , '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);

        // Mensaje de exito
        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=1');
        }
    }

    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado) {
            // Redireccionar al usuario.
            header('Location: /admin?resultado=2');
        }
    }

    // Eliminar un registro
    public function eliminar() {
        // Eliminar archivo
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            header('location: /admin?resultado=3');
        }
    }

    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }

    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    // Validacion
    public static function getErrores() {
        return static::$errores;
    }

    // Validacion
    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    // Lista todas las 
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    // Obtiene determinado numero de registros
    public static function get($cantidad) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantidad;

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    public static function delete($id) {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . $id;
        $resultado = self::$db->query($query);
        return $resultado;
    }

    public static function deleteWhere($column, $value) {
        $query = "DELETE FROM " . static::$tabla . " WHERE {$column} = ?";
        $stmt = self::$db->prepare($query);
        
        // Determinar el tipo de parámetro
        $type = is_int($value) ? 'i' : (is_float($value) ? 'd' : 's');
        $stmt->bind_param($type, $value);
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }

    /**
     * @template T of ActiveRecord
     * @param int $id
     * @return ($id is int ? T|null : null)
     * @phpstan-return T|null
     */
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = " . $id;
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado );
    }
    
    /**
     * @template T of ActiveRecord
     * @param int $id
     * @return T|null
     */
    public static function where($field, $id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $field = '$id'";

        $resultado = self::consultarSQL($query);

        return array_shift( $resultado );
    }

    /**
     * @return static[]
     * @phpstan-return array<static>
     */
    public static function whereMany($field, $id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $field = '$id'";

        $resultado = self::consultarSQL($query);

        return $resultado;
    }

    /**
     * @return static[]
     * @phpstan-return array<static>
     */
    public static function whereManyCondition($conditions) {
        // Si recibe parámetros antiguos (field, value) para compatibilidad
        if (!is_array($conditions)) {
            $conditions = [$conditions => func_get_arg(1)];
        }
    
        $whereParts = [];
        $values = [];
        $types = '';
    
        foreach ($conditions as $field => $value) {
            $whereParts[] = "{$field} = ?";
            $values[] = $value;
            $types .= is_int($value) ? 'i' : 's'; // 'i' para integer, 's' para string
        }
    
        $query = "SELECT * FROM " . static::$tabla . " WHERE " . implode(' AND ', $whereParts);
        $stmt = self::$db->prepare($query);
    
        if (!$stmt) {
            throw new \Exception("Error preparing query: " . self::$db->error);
        }
    
        // Bind parameters dinámicamente
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $objects = [];
    
        while ($row = $result->fetch_assoc()) {
            $objects[] = static::crearObjeto($row);
        }
    
        $stmt->close();
        return $objects;
    }

    /**
     * @return array<static>
     */
    public static function consultarSQL($query) {
        $resultado = self::$db->query($query);
        
        if(!$resultado) {
            // Debug: muestra el error SQL
            die("Error en consulta: " . self::$db->error . " | Query: " . $query);
        }
        
        $array = [];
        while ($registro = $resultado->fetch_assoc()) {
            $obj = static::crearObjeto($registro);
            $array[] = $obj;
        }
        
        $resultado->free();
        return $array;
    }

    protected static function crearObjeto($registro) {
        $objeto = new static;
        
        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Sincroniza el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar( $args = [] ) {
        foreach($args as $key => $value) {
            if (property_exists( $this, $key ) && !is_null($value) && $value != "" ) {
                $this->$key = $value;
            }
        }
    }
}