<?php

use App\Controllers\UsuariosController;

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

$routes = [
    'usuarios' => [
        'listar' => [
            'controller' => UsuariosController::class,
            'action' => 'index'
        ],
        'obtener' => [
            'controller' => UsuariosController::class,
            'action' => 'show'
        ],
        'crear' => [
            'controller' => UsuariosController::class,
            'action' => 'create'
        ],
        'actualizar' => [
            'controller' => UsuariosController::class,
            'action' => 'update'
        ],
        'eliminar' => [
            'controller' => UsuariosController::class,
            'action' => 'delete'
        ]
    ]
];

// Obtenemos de la url el recurso solicitado controller={}&action={}&id=
$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

// Validamos que el recurso solicitado exista en la lista de rutas
if (!array_key_exists($controller, $routes) || !array_key_exists($action, $routes[$controller])) {
    die('La ruta solicitada no existe');
}

// Ejecutamos el controlador y la acción solicitada, pasando como parámetro el id y el requestManager
return (new ($routes[$controller][$action]['controller']))->{$routes[$controller][$action]['action']}(new \Request\RequestManager(), $id);