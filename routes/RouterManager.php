<?php

namespace Routes;

use Request\RequestManager;

class RouterManager
{
    // Definir rutas
    protected array $routes = [];

    // Definir espacio de nombres de controladores
    const controllerNamespace = 'App\\Controllers\\'; // Namespace donde residen los controladores

    /**
     * AddRoute agrega una ruta a la lista de rutas
     * Guarda la ruta en el array $routes
     *
     * Este método recibe 3 parámetros:
     *
     * @param string $method
     * @param string $path
     * @param string $controller
    */
    public function addRoute(string $method, string $path, string|array $controller): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
        ];
    }


    /**
     * handleRequest recibe el método y la URI de la solicitud actual
     * y busca una ruta que coincida con estos datos
     *
     * Si encuentra una ruta, ejecuta el controlador correspondiente
     *
     * @param string $method
     * @param string $uri
     */
    public function handleRequest(string $method, string $uri): void
    {
        // Recorrer las rutas registradas
        foreach ($this->routes as $route) {
            // Verificar si la ruta coincide con la solicitud actual
            if ($route['method'] == $method && $this->matchRoute($route['path'], $uri)) {
                // Obtener el controlador y la acción
                list($controllerClass, $action) = $this->getClassesAndAction($route['controller']);
                // Instanciar el controlador
                $controllerInstance = new $controllerClass();

                // Analizar la URL para obtener datos
                $urlParts = explode('/', $uri);
                $urlParts = array_filter($urlParts); // Eliminar elementos vacíos

                // Obtener parámetros dinámicos de la ruta
                $routeParts = explode('/', $route['path']);
                $params = [];

                // Recorrer las partes de la ruta
                foreach ($routeParts as $index => $routePart) {
                    // Verificar si la parte de la ruta es un parámetro dinámico
                    if (preg_match('/\{(.+?)\}/', $routePart, $matches)) {
                        // Esto es un parámetro dinámico
                        $paramName = $matches[1];
                        // Obtener el valor del parámetro de la URL
                        $paramValue = $urlParts[$index] ?? null;
                        // Agregar el parámetro a la lista de parámetros
                        $params[$paramName] = $paramValue;
                    }
                }

                // Pasar datos y parámetros al controlador
                $controllerInstance->$action(new RequestManager(), ...$params);

                return;
            }
        }

        // Manejar caso de ruta no encontrada
        echo "404 Not Found";
    }

    /**
     * matchRoute verifica si la ruta coincide con la URI (Identificador de recursos uniforme)
     * @link https://es.wikipedia.org/wiki/Identificador_de_recursos_uniforme   -> URI
     *
     * @param string $pattern
     * @param string $uri
     * @return bool
     */
    protected function matchRoute(string $pattern, string $uri): bool
    {
        // Convertir la ruta en una expresión regular
        $pattern = str_replace('/', '\/', $pattern);
        // Convertir los parámetros dinámicos en expresiones regulares
        $pattern = preg_replace('/\{(.+?)\}/', '(.+?)', $pattern);
        // Agregar delimitadores
        $pattern = '/^' . $pattern . '\/?$/'; // Agregar la barra diagonal opcional
        // Para manter la similitud de cuando la url finaliza en / o no.

        // Verificar si la URI coincide con la expresión regular
        return preg_match($pattern, $uri);
    }


    /**
     * getClassesAndAction obtiene el nombre de la clase y el nombre del método
     * de una cadena de texto que contiene el nombre de la clase y el nombre del método
     * separados por el carácter @ o de un arreglo que contiene la clase y el método
     *
     * @param $route
     * @return array
     */
    function getClassesAndAction($route): array
    {
         $classes = null;
         $action = null;
         // Verificar si la ruta es una cadena de texto
        if (is_string($route)) {
            // Obtener el nombre de la clase y el nombre del método
            $parts = explode('@', $route);
            // Verificar si la cadena contiene el carácter @ y tiene 2 partes
            if (count($parts) === 2) {
                // Obtener el nombre de la clase y el nombre del método
                $classes = self::controllerNamespace.$parts[0];
                $action = $parts[1];
            }
            // Verificar si la ruta es un arreglo
        } elseif (is_array($route) && count($route) === 2) {
            // Obtener el nombre de la clase y el nombre del método
            $controller = is_string($route[0]) ? $route[0] : get_class($route[0]);
            // Obtener el nombre del método
            $action = $route[1];
            $classes = $controller;
        }

        return [
            $classes,
            $action
        ];
    }
}