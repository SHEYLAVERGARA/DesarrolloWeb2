<?php

use App\Controllers\CiudadesController;
use App\Controllers\Controller;
use App\Controllers\PersonaController;
use App\Controllers\PersonaTipoController;
use App\Controllers\SexoController;
use App\Controllers\UserController;
use Routes\RouterManager;

// Iniciamos el RouterManager
$router = new RouterManager();
// Obtenemos el método y la URI de la solicitud actual
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Definimos las rutas


//Rutas para el controllador de usuario
$router->addRoute('GET', '/', [Controller::class, 'home']);
$router->addRoute('GET', '/users', [UserController::class, 'index']);
$router->addRoute('GET', '/users/{id}', [UserController::class, 'show']);
$router->addRoute('POST', '/users', [UserController::class, 'create']);
$router->addRoute('PUT', '/users/{id}', [UserController::class, 'update']);


//Rutas para el controllador de persona
$router->addRoute('GET', '/personas', [PersonaController::class, 'index']);
$router->addRoute('GET', '/personas/{id}', [PersonaController::class, 'show']);
$router->addRoute('POST', '/personas', [PersonaController::class, 'create']);
$router->addRoute('PUT', '/personas/{id}', [PersonaController::class, 'update']);
$router->addRoute('DELETE', '/personas/{id}', [PersonaController::class, 'delete']);


//Rutas para el controllador de sexo
$router->addRoute('GET', '/sexos', [SexoController::class, 'index']);
$router->addRoute('GET', '/sexos/{id}', [SexoController::class, 'show']);
$router->addRoute('POST', '/sexos', [SexoController::class, 'create']);
$router->addRoute('PUT', '/sexos/{id}', [SexoController::class, 'update']);
$router->addRoute('DELETE', '/sexos/{id}', [SexoController::class, 'delete']);

//Rutas para el controllador de ciudades
$router->addRoute('GET', '/ciudades', [CiudadesController::class, 'index']);
$router->addRoute('GET', '/ciudades/{id}', [CiudadesController::class, 'show']);
$router->addRoute('POST', '/ciudades', [CiudadesController::class, 'create']);
$router->addRoute('PUT', '/ciudades/{id}', [CiudadesController::class, 'update']);
$router->addRoute('DELETE', '/ciudades/{id}', [CiudadesController::class, 'delete']);

//Rutas para el controllador de PersonaTipos
$router->addRoute('GET', '/personatipos', [PersonaTipoController::class, 'index']);
$router->addRoute('GET', '/personatipos/{id}', [PersonaTipoController::class, 'show']);
$router->addRoute('POST', '/personatipos', [PersonaTipoController::class, 'create']);
$router->addRoute('PUT', '/personatipos/{id}', [PersonaTipoController::class, 'update']);
$router->addRoute('DELETE', '/personatipos/{id}', [PersonaTipoController::class, 'delete']);


// Ejecutamos el método handleRequest
$router->handleRequest($method, $uri);