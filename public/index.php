<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';

// cargamos las variables de entorno
try {
    \Helpers\LoadEnv::loadEnv();
} catch (Exception $e) {
    die($e->getMessage());
}

// Iniciamos la instancia de la base de datos
// Y procedemos a montar la base de datos.
$instance = \Database\MysqlConnection::getInstance();
\Helpers\ServerLogger::log($instance->mountDatabase());

