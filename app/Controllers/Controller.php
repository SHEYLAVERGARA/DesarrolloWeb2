<?php

namespace App\Controllers;

use Response\ResponseManager;

class Controller
{
    public function success($data = [], $message = '', $code = 200, $headers = [], $status_event = ''): void
    {
        $response = [
            'status' => $status_event,
            'message' => $message,
            'data' => $data
        ];
        (new ResponseManager())->send($response, $code, $headers);
    }

    public function home(): void
    {
        echo "Hello, from Controller@home";
    }

    public function urlList(): void
    {
        //TODO: Implementar la lista de rutas
    }
}