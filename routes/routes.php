<?php

use Routes\RouterManager;

// Iniciamos el RouterManager
$router = new RouterManager();
// Obtenemos el método y la URI de la solicitud actual
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Definimos las rutas
$router->addRoute('GET', '/', 'Controller@home');
$router->addRoute('GET', '/users', 'UserController@index');
$router->addRoute('GET', '/users/{id}', 'UserController@show');
$router->addRoute('POST', '/users/', 'UserController@create');

// Ejecutamos el método handleRequest
$router->handleRequest($method, $uri);