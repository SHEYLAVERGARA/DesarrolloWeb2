<?php

namespace request;

use Validation\Validator;

class RequestManager
{
    protected array $data;

    public function __construct()
    {
        // Initialize the request data based on the HTTP method
        $method = $_SERVER['REQUEST_METHOD'];
        $inputData = [];

        if ($method === 'GET') {
            $inputData = $_GET;
        } elseif ($method === 'DELETE'){
            $rawData = file_get_contents('php://input');
            // Decodificar los datos si es JSON
            $inputData = $rawData ?  json_decode($rawData, true) : []; // El segundo argumento convierte el objeto en un array
        }
        else {
            // Obtener el cuerpo de la solicitud
            $rawData = file_get_contents('php://input'); // de esta forma se obtiene el cuerpo de la solicitud, ya que $_POST, $_GET, $_REQUEST no funcionan
            // Verificar si la solicitud tiene un encabezado "Content-Type" de tipo JSON
            if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
                // Obtener el cuerpo de la solicitud en formato JSON y decodificarlo
                $inputData = json_decode($rawData, true);
            } else {
                // Si no es JSON, usar $_POST
                $inputData = $rawData;
            }
        }

        $this->data = $inputData;
    }

    public function all(): array
    {
        // Return all request data
        return $this->data;
    }

    public function input($key, $default = null)
    {
        // Get a specific input value by key or return the default value
        return $this->data[$key] ?? $default;
    }
    public function validate($data = [], $rules = []): void
    {
//        print_r($data);
        if ($data) {
            $this->data = $data;
        }
        // Create a Validator instance with the input data
        $validator = new Validator($this->data, $rules);
        $validator->validate();
        // Perform validation using the Validator class
        if ($validator->fails())
            $validator->printErrors();

    }

    public function get($key, $default = null)
    {
        // Get a specific input value from GET data
        return $_GET[$key] ?? $default;
    }

    public function post($key, $default = null)
    {
        // Get a specific input value from POST data
        return $_POST[$key] ?? $default;
    }

    public function put($key, $default = null)
    {
        // Get a specific input value from PUT, PATCH, or DELETE data
        return $this->data[$key] ?? $default;
    }
}