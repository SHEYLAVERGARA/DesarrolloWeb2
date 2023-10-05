<?php

namespace App\Traits;

use App\Models\Model;
use Database\MysqlConnection;
use PDO;

/**
 * Trait QueryBuilderTrait
 * Este trait se encarga de construir las consultas SQL
 *
 */
trait QueryBuilderTrait
{
    protected PDO $db;
    protected string $table;

    /**
     * PrimaryKey es un string que contiene el nombre de la llave primaria
     * @var string
    */
    protected string $primaryKey = 'id';
    /**
     * Fillable es un arreglo que contiene los atributos que se pueden llenar
     * @var array
    */
    protected $fillable = [];

    /**
     * Relations es un arreglo que contiene las relaciones de la tabla
     * @var array
    */
    protected $relations = [];

    protected array $relationsData = [];

    /**
     * Model constructor.
     * Carga la conexión a la base de datos y el nombre de la tabla del modelo
     *
     */
    public function __construct()
    {
        $this->db = MysqlConnection::getInstance()->getDb();
        $this->table = $this->table ?? $this->getClassNamePlural();
    }

    /**
     * getClassNamePlural es un método que obtiene el nombre de la clase en plural
     *
     *
     */
    protected function getClassNamePlural(): string
    {
        $className = get_called_class(); // Obtiene el nombre de la clase que llama al método
        $className = substr($className, strrpos($className, '\\') + 1); // Extrae el nombre de la clase sin el namespace (si lo tiene)
        // Convierte el nombre de la clase en formato "CamelCase" a "snake_case" y agrega 's' al final
        $className = $this->CamelCaseToSnakeCase($className);
        // Verifica si el nombre de la clase ya termina con 's'
        if (!str_ends_with($className, 's')) {
            $className .= 's'; // Agrega 's' al final si no termina con 's'
        }
        return $className;
    }

    /**
     * CamelCaseToSnakeCase es un método que convierte un string de formato "CamelCase" a "snake_case"
     */
    protected function CamelCaseToSnakeCase($string): string
    {
//        $className = preg_replace('/([a-z])([A-Z])/', '$1_$2', $className);
        $string = preg_replace('/(?<!^)[A-Z]/', '_$0', $string);
        return strtolower($string);
    }

    /**
     * getFillable es un método que obtiene los atributos que se pueden llenar
     */
    protected function getFillable(): array
    {
        return $this->fillable;
    }

    /**
     * validateFillable es un método que valida que los datos a insertar o actualizar estén en la lista blanca
     */
    protected function validateFillable($data): array
    {
        $fillable = $this->getFillable();
        $data = array_filter($data, function ($key) use ($fillable) {
            return in_array($key, $fillable);
        }, ARRAY_FILTER_USE_KEY);
        return $data;
    }

    /**
     * buildWhereClause es un método que construye la cláusula WHERE de una consulta SQL
     * Recibe como parámetros:
     * - $where: un arreglo asociativo con las condiciones de la cláusula WHERE
     * - $params: un arreglo asociativo con los parámetros de la consulta preparada (se pasa por referencia)
     * - $query: un string con la consulta SQL (se pasa por referencia)
     *
     * (se pasa por referencia): significa que los cambios que se hagan a la variable dentro de la función, se reflejarán en la variable original
     * esto se consigue con & antes del nombre de la variable
     *
     */
    protected function buildWhereClause($where, &$params, &$query): void
    {
        if (!empty($where)) {
            $conditions = [];

            foreach ($where as $column => $condition) {
                if (is_array($condition)) {
                    $operator = $condition[0];
                    $value = $condition[1];

                    // Verificar si el operador es válido
                    $validOperators = ['=', '!=', '<', '>', '<=', '>=', 'LIKE'];
                    if (in_array($operator, $validOperators)) {
                        $paramName = ":$column";
                        $conditions[] = "$column $operator $paramName";
                        $params[$paramName] = $value;
                    } else {
                        // Operador no válido, ignorar esta condición
                        continue;
                    }
                } else {
                    // Si no se proporciona un operador, se usa "=" por defecto
                    $paramName = ":$column";
                    $conditions[] = "$column = $paramName";
                    $params[$paramName] = $condition;
                }
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }
        }
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
        $this->{$this->primaryKey} = $data[$this->primaryKey] ?? null;
        $fillable = $this->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable)) {
                $this->{$key} = $value;
            }
        }

    }

    /**
     * Esta funcion afterFetch, se encarga de devolver los datos de la base de datos
     */
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
                $model->fill($data);
                $models[] = $model;
            }
            return $models;
        }
    }

    /**
     * Esta funcion select, se encarga de hacer una consulta a la base de datos,
     */
    public function select($columns = '*', $where = ''): Model|array
    {
        $params = [];
        // Verifica si las columnas solicitadas están en la lista blanca
        if ($columns !== '*' && !empty($this->getFillable())) {
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
        return $this->afterFetch($result);
    }

    /**
     * Esta funcion find, se encarga de buscar un registro en la base de datos,
     */
    public function find($id): Model|array|null
    {
        return $this->select(where: [
            "{$this->primaryKey}" => $id
        ]);
    }

    /**
     * Esta funcion all, se encarga de buscar todos los registros en la base de datos,
     */
    public function insert($data): Model|array|null
    {
        $params = [];
        // Verifica si los datos a insertar están en la lista blanca
        if (!empty($this->getFillable())) {
            $data = $this->validateFillable($data);
        }
        // Crea la consulta INSERT
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        // Prepara y ejecuta la consulta
        $stmt = $this->db->prepare($query);
        $stmt->execute($data);
        // Devuelve el resultado como un modelo o un arreglo
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $id = $this->db->lastInsertId();

        // Verificamos que el id no sea 0
        if ($id != 0){
            //Regresamos el registro insertado
            return $this->find($id);
        }

        // Si el registro esta vacio, regresamos el resultado de los datos insertados
        if (empty($result)) {
            $result = [$data];
        }
        return $this->afterFetch($result);
    }

    /**
     * Esta funcion updateSQL, se encarga de actualizar un registro en la base de datos,
     */
    public function updateSQL($data, $where): QueryBuilderTrait|array|Model|null
    {
        $params = [];
        // Construye la consulta UPDATE
        $query = "UPDATE {$this->table} SET ";
        $updateData = [];

        foreach ($data as $key => $value) {
            $updateData[] = "$key = :$key";
        }

        $query .= implode(', ', $updateData);

        $this->buildWhereClause($where, $params, $query);
        // Prepara y ejecuta la consulta
        $stmt = $this->db->prepare($query);

        // Bind de los parámetros de data
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }


        $stmt->execute();

        $this->updateFillableData($data);
        // Devuelve el resultado como un modelo o un arreglo
        return $this->afterFetch([$data]); // Devuelve la cantidad de filas actualizadas
    }

    /**
     * Esta funcion update, se encarga de actualizar un registro en la base de datos,
     */
    public function update($data): QueryBuilderTrait|array|Model|null
    {
        $datasql = [];
        if (!empty($this->getFillable())) {
            $datasql = $this->validateFillable($data);
        }
        return $this->updateSQL($datasql, [
            "{$this->primaryKey}" => $this->{$this->primaryKey}
        ]);
    }

    /**
     * Esta funcion updateFillableData, se encarga de actualizar los datos de un modelo,
     */
    protected function updateFillableData($data): void
    {
        $fillable = $this->getFillable();
        foreach ($data as $key => $value) {
            if (in_array($key, $fillable))
                $this->{$key} = $value;
        }
    }


    /**
     * Esta funcion deleteSQL, se encarga de eliminar un registro en la base de datos,
     */
    public function deleteSQL($where = ''): int
    {
        $params = [];
        // Construye la consulta DELETE
        $query = "DELETE FROM {$this->table}";

        $this->buildWhereClause($where, $params, $query);

        // Prepara y ejecuta la consulta
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // Devuelve la cantidad de filas afectadas por la eliminación
        return $stmt->rowCount();
    }

    /**
     * Esta funcion deleteFillableData, se encarga de eliminar los datos de un modelo,
     */
    protected function deleteFillableData(): void
    {
        $fillable = $this->getFillable();
        foreach ($fillable as $key) {
            $this->{$key} = null;
        }
    }

    /**
     * Esta funcion delete, se encarga de eliminar un registro en la base de datos,
     */
    public function delete(): int
    {
        $result = $this->deleteSQL([
            "{$this->primaryKey}" => $this->{$this->primaryKey}
        ]);
        if ($result > 0) {
            $this->deleteFillableData();
        }
        return $result;
    }

    /**
     * Esta funcion addRelation, se encarga de agregar una relacion a un modelo,
     */
    public function addRelation($relation): void
    {
        $relate = new ($this->{$relation}())();
        $relate_id = $this->CamelCaseToSnakeCase($relation)."_id";
        $findData = $relate->find($this->{$relate_id});
        $this->relationsData[$relation] = $findData;
        $this->{$relation} = $findData;
    }

    /**
     * Esta funcion loadRelation, se encarga de cargar una relacion a un modelo,
     */
    public function loadRelation( $relations ): Model
    {
        if (is_array($relations)) {
            foreach ($relations as $relation) {
                $this->addRelation($relation);
            }
        }
        return $this;
    }

    /**
     * Esta funcion toArray, se encarga de convertir un modelo a un arreglo,
     */
    public function attributesToArray(): array
    {
        $attributes = [];
        foreach ($this->getFillable() as $key) {
            $attributes[$key] = $this->{$key};
        }
        return $attributes;
    }

    /**
     * Esta funcion relationsToArray, se encarga de convertir las relaciones de un modelo a un arreglo,
     */
    public function relationsToArray(): array
    {
        $relations = [];
        foreach ($this->relationsData as $relation) {
            $relations[$relation] = $this->{$relation};
        }
        return $relations;
    }

}