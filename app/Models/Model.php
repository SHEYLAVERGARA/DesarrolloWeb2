<?php

namespace App\Models;

use database\MysqlConnection;
use PDO;

class Model
{
    protected $db;
    protected string $table;
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

    protected function buildWhereClause($where, &$params): string
    {
        $whereClause = '';

        foreach ($where as $condition) {
            $column = $condition[0];
            $operator = $condition[1];
            $value = $condition[2];

            $paramName = ":{$column}_param";
            $whereClause .= "$column $operator $paramName AND ";

            // Asociar el valor al parámetro
            $params[$paramName] = $value;
        }

        // Eliminar el último "AND" del whereClause
        return rtrim($whereClause, ' AND ');
    }

    // Método para realizar una consulta SELECT genérica
    public function select($columns = '*', $where = '', $params = []): bool|array
    {
        $query = "SELECT $columns FROM {$this->table}";

        if (!empty($where)) {
            $query .= " WHERE ";
            $query .= $this->buildWhereClause($where, $params);
        }

        $stmt = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Método para realizar una consulta INSERT genérica
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->db->prepare($query);

        foreach ($data as $key => &$value) {
            // Usamos '&' para pasar la referencia de $value
            $stmt->bindParam(':' . $key, $value);
        }

        $stmt->execute();

        return $data;
    }


    // Método para realizar una consulta UPDATE genérica
    public function update($data, $where = '', $params = [])
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);

        $query = "UPDATE {$this->table} SET $set";

        if (!empty($where)) {
            $query .= " WHERE ";
            $query .= $this->buildWhereClause($where, $params);
        }

        $stmt = $this->db->prepare($query);

        // Enlazar valores de $data
        foreach ($data as $key => &$value) {
            $stmt->bindParam(':' . $key, $value);
        }

        // Enlazar valores de $params
        foreach ($params as $paramName => &$paramValue) {
            $stmt->bindParam($paramName, $paramValue);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }


    // Método para realizar una consulta DELETE genérica
    public function delete($where = '', $params = [])
    {
        $query = "DELETE FROM {$this->table}";

        if (!empty($where)) {
            $query .= " WHERE $where";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->rowCount();
    }
}