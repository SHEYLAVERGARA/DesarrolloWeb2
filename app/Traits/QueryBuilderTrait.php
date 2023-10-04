<?php

namespace App\Traits;

use App\Models\Model;
use Database\MysqlConnection;
use PDO;

trait QueryBuilderTrait
{
    protected PDO $db;
    protected string $table;
    protected $fillable = [];

    public function __construct()
    {
        $this->db = MysqlConnection::getInstance()->getDb();
        $this->table = $this->table ?? $this->getClassNamePlural();
    }
    // Método para obtener el nombre de la clase en plural
    protected function getClassNamePlural(): string
    {
        $className = get_called_class(); // Obtiene el nombre de la clase que llama al método
        $className = substr($className, strrpos($className, '\\') + 1); // Extrae el nombre de la clase sin el namespace (si lo tiene)
        return strtolower($className) . 's'; // Agrega 's' para convertirlo a plural
    }

    protected function getFillable(): array
    {
        return $this->fillable;
    }

    protected function validateFillable($data): array
    {
        $fillable = $this->getFillable();
        $data = array_filter($data, function ($key) use ($fillable) {
            return in_array($key, $fillable);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    protected function buildWhereClause($where, &$params, &$query): void {
        $conditions = [];
        foreach ($where as $column => $condition) {
            if (is_array($condition) && count($condition) === 2) {
                list($operator, $value) = $condition;
                $conditions[] = "$column $operator :$column";
                $params[":$column"] = $value;
            } else {
                // Si no se proporciona un operador, se usa "=" por defecto
                $conditions[] = "$column = :$column";
                $params[":$column"] = $condition;
            }
        }
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    /**
     * Esta funcion fill, se encarga de llenar los datos de un modelo,
     * para que esto suceda se debe pasar un arreglo asociativo con los datos,
     * y se debe permitir que los atributos del modelo sean dinamicos con
     * la anotacion #[AllowDynamicProperties], de lo contrario no se podra.
     * @param $data
     * @return void
    */
    public function fill($data): void
    {
        $fillable = $this->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $this->$key = $value;
            }
        }
    }

    public function afterFetch($rows): array|Model|QueryBuilderTrait|null
    {
        // Este método se ejecuta después de obtener los datos de la base de datos
        if (count($rows) === 1) {
            // Si solo se obtuvo un resultado, devuelve un modelo
            $model = new static();
            $model->fill($rows[0]);
            return $model;
        } else {
            // Si se obtuvo más de un resultado, devuelve un arreglo de modelos
            $models = [];
            foreach ($rows as $data) {
                $model = new static();
                $model->fill($rows[0]);
                $models[] = $model;
            }
            return $models;
        }
    }



    public function select($columns = '*', $where = ''): Model|array
    {
        $params = [];
        // Verifica si las columnas solicitadas están en la lista blanca
        if ($columns !== '*' && !empty($this->fillable)) {
            $columns = $this->validateFillable($columns);
        }
        // Crea la consulta SELECT
        $query = "SELECT $columns FROM {$this->table}";

        if (!empty($where)) {
            $this->buildWhereClause($where, $params, $query);
        }

        // Prepara y ejecuta la consulta
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // Devuelve el resultado como un modelo o un arreglo
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = $this->afterFetch($result);
//        print_r($data);
        return $data;
    }






//    protected function buildWhereClause($where, &$params): string
//    {
//        $whereClause = '';
//        foreach ($where as $condition) {
//            $column = $condition[0];
//            $operator = $condition[1];
//            $value = $condition[2];
//
//            $paramName = ":{$column}_param";
//            $whereClause .= "$column $operator $paramName AND ";
//
//            // Asociar el valor al parámetro
//            $params[$paramName] = $value;
//        }
//        // Eliminar el último "AND" del whereClause
//        return rtrim($whereClause, ' AND ');
//    }
//
//    // Método para realizar una consulta SELECT genérica
//    public function select($columns = '*', $where = '', $params = []): bool|array
//    {
//        $query = "SELECT $columns FROM {$this->table}";
//
//        if (!empty($where)) {
//            $query .= " WHERE ";
//            $query .= $this->buildWhereClause($where, $params);
//        }
//
//        $stmt = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
//        $stmt->execute($params);
//
//        return $stmt->fetchAll(PDO::FETCH_ASSOC);
//    }
//
//
//    // Método para realizar una consulta INSERT genérica
//    public function insert($data)
//    {
//        $columns = implode(', ', array_keys($data));
//        $placeholders = ':' . implode(', :', array_keys($data));
//
//        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
//
//        $stmt = $this->db->prepare($query);
//
//        foreach ($data as $key => &$value) {
//            // Usamos '&' para pasar la referencia de $value
//            $stmt->bindParam(':' . $key, $value);
//        }
//
//        $stmt->execute();
//
//        return $data;
//    }
//
//
//    // Método para realizar una consulta UPDATE genérica
//    public function update($data, $where = '', $params = [])
//    {
//        $query = "UPDATE {$this->table} SET ";
//
//        foreach ($data as $key => $value) {
//            $query .= "$key = :$key, ";
//        }
//
//        // Eliminar la última coma
//        $query = rtrim($query, ', ');
//
//        if (!empty($where)) {
//            $query .= " WHERE $where";
//        }
//
//        $stmt = $this->db->prepare($query);
//
//        foreach ($data as $key => &$value) {
//            // Usamos '&' para pasar la referencia de $value
//            $stmt->bindParam(':' . $key, $value);
//        }
//
//        $stmt->execute($params);
//
//        return $stmt->rowCount();
//    }
//
//
//    // Método para realizar una consulta DELETE genérica
//    public function delete($where = '', $params = [])
//    {
//        $query = "DELETE FROM {$this->table}";
//
//        if (!empty($where)) {
//            $query .= " WHERE $where";
//        }
//
//        $stmt = $this->db->prepare($query);
//        $stmt->execute($params);
//
//        return $stmt->rowCount();
//    }
}