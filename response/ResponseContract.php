<?php

namespace Response;

interface ResponseContract
{
    public function setStatusCode($statusCode);

    public function setHeader($name, $value);

    public function sendJson($data);

    public function sendText($text);

    public function sendMultipart($object);

    public function send($statusCode, $data, $headers = [], $contentType = 'application/json');

    function clearMemory();
}