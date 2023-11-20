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
}