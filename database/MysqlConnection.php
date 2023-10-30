<?php

namespace Database;

use Exception;
use PDO;
use PDOException;

class MysqlConnection
{
    private static MysqlConnection $instance;
    private PDO $db;

    private function __construct()
    {
        $host = getenv('DB_HOST');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_DATABASE');
        try {
            $this->db = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }
    public static function getInstance(): MysqlConnection
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return PDO
     */
    public function getDb(): PDO
    {
        return $this->db;
    }


    /**
     * @throws Exception
     */
    public function __clone() {
        throw new Exception("Can't clone a singleton");
    }

    public function mountDatabase(){
        $sqlFile = __DIR__ . '/createDatabase.sql'; // Ruta al archivo createDatabase.sql
        if (file_exists($sqlFile)) {
            $sqlQueries = file_get_contents($sqlFile);
            // Verificar si la tabla users ya existe en la base de datos
            $checkTableQuery = "SHOW TABLES LIKE 'users'";
            $stmt = $this->db->prepare($checkTableQuery);
            $stmt->execute();
            $tableExists = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$tableExists) {
                // La tabla users no existe, ejecutar el script SQL
                $queries = explode(';', $sqlQueries);

                foreach ($queries as $query) {
                    if (trim($query) != '') {
                        try {
                            $this->db->exec($query);
                        } catch (PDOException $e) {
                            die("Error al ejecutar consulta SQL: " . $e->getMessage());
                        }
                    }
                }
                return "Base de datos creada y configurada correctamente.";
            } else {
                return "La tabla 'users' ya existe en la base de datos. No se realizaron modificaciones.";
            }
        } else {
            return "El archivo createDatabase.sql no existe en la ruta especificada.";
        }
    }
}