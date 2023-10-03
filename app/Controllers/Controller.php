<?php

namespace app\Controllers;

use Response\ResponseManager;

class Controller
{
    const  USER_INSERT_ERROR = 'USER_INSERT_ERROR';
    const  USER_INSERT_OK = 'USER_INSERT_OK';
    const  GET_USER_OK = 'GET_USER_OK';
    public function success($data = [], $message = '', $code = 200, $headers = [], $status_event = self::USER_INSERT_OK): void
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
}