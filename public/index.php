<?php

use App\Controllers\UsuariosController;
use App\Controllers\CursosController;
use App\Controllers\ActividadesController;
use App\Controllers\UnidadesController;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';

// Cargamos las variables de entorno
try {
    \Helpers\LoadEnv::loadEnv();
} catch (Exception $e) {
    die($e->getMessage());
}

// Iniciamos la instancia de la base de datos
$instance = \Database\MysqlConnection::getInstance();

$routes = [
    'usuarios' => [
        'listar' => [UsuariosController::class, 'index'],
        'obtener' => [UsuariosController::class, 'show'],
        'crear' => [UsuariosController::class, 'create'],
        'actualizar' => [UsuariosController::class, 'update'],
        'eliminar' => [UsuariosController::class, 'delete'],
    ],
    'cursos' => [
        'listar' => [CursosController::class, 'index'],
        'obtener' => [CursosController::class, 'show'],
        'crear' => [CursosController::class, 'create'],
        'actualizar' => [CursosController::class, 'update'],
        'eliminar' => [CursosController::class, 'delete'],
    ],
    'actividades' => [
        'listar' => [ActividadesController::class, 'index'],
        'obtener' => [ActividadesController::class, 'show'],
        'crear' => [ActividadesController::class, 'create'],
        'actualizar' => [ActividadesController::class, 'update'],
        'eliminar' => [ActividadesController::class, 'delete'],
    ],
    'unidades' => [
        'listar' => [UnidadesController::class, 'index'],
        'obtener' => [UnidadesController::class, 'show'],
        'crear' => [UnidadesController::class, 'create'],
        'actualizar' => [UnidadesController::class, 'update'],
        'eliminar' => [UnidadesController::class, 'delete'],
    ],
];

// Obtenemos de la URL el recurso solicitado controller={} & action={} & id=
$controller = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;
$id = $_GET['id'] ?? null;

// Validamos que el recurso solicitado exista en la lista de rutas
if (!array_key_exists($controller, $routes) || !array_key_exists($action, $routes[$controller])) {
    die('La ruta solicitada no existe');
}

// Ejecutamos el controlador y la acción solicitada, pasando como parámetro el id y el requestManager
[$controllerClass, $action] = $routes[$controller][$action];
return (new $controllerClass)->$action(new \Request\RequestManager(), $id);
