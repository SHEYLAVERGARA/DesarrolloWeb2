<?php

namespace Response;

class ResponseManager
{
    /**
     * El código de estado HTTP de la respuesta.
     * @var int
     */
    protected int $statusCode = 200;

    /**
     * Las cabeceras HTTP de la respuesta.
     * @var array
     */
    protected array $headers = [];

    /**
     * Establece el código de estado HTTP de la respuesta.
     * @param int $statusCode El código de estado HTTP.
     * @return void
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Establece una cabecera HTTP de la respuesta.
     * @param string $name El nombre de la cabecera.
     * @param string $value El valor de la cabecera.
     * @return void
     */
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Envia la respuesta al cliente.
     * @param mixed $data Los datos de la respuesta.
     * @return void
     */
    public function sendJson(mixed $data): void
    {
        // Establecer el código de estado HTTP
        http_response_code($this->statusCode);

        // Establecer la cabecera de tipo de contenido JSON
        $this->setHeader('Content-Type', 'application/json');

        // Establecer cabeceras personalizadas
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Convertir los datos a JSON
        $json = json_encode($data);

        // Enviar la respuesta JSON
        echo $json;
    }

    /**
     * Envia la respuesta al cliente.
     * @param string $text El texto de la respuesta.
     * @return void
     */
    public function sendText(string $text): void
    {
        // Establecer el código de estado HTTP
        http_response_code($this->statusCode);

        // Establecer la cabecera de tipo de contenido de texto plano
        $this->setHeader('Content-Type', 'text/plain');

        // Establecer cabeceras personalizadas
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Enviar la respuesta de texto plano
        echo $text;
    }

    /**
     * Envia la respuesta al cliente.
     * @param mixed $data Los datos de la respuesta.
     * @param int $statusCode El código de estado HTTP.
     * @param array $headers Las cabeceras HTTP.
     * @return void
     */
    function send(mixed $data, int $statusCode = 200, array $headers = []): void
    {
        // Establecer el código de estado HTTP
        $this->setStatusCode($statusCode);
        // Establecer cabeceras personalizadas
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        $is_string = is_string($data);
        // Determinar el tipo de contenido
        if ($is_string)
            $this->sendText($data);
        else {
            $data['code'] = $statusCode;
            // Enviar la respuesta JSON
            $this->sendJson($data);
        }
    }

    /**
     * Envia la respuesta al cliente.
     * @param mixed $Erros errores de validacion.
     * @param string $status_event El código de estado.
     * @param array $headers Las cabeceras HTTP.
     * @return void
     */

    public function sendError(mixed $Erros, string $status_event = 'ERROR_FOUND', array $headers = []): void
    {
        $response = [
            'message' => 'Validation failed.',
            'errors' => $Erros,
            'code' => 200,
            "status" => $status_event
        ];
        $this->send($response, 403, $headers);
        die();
    }
}
