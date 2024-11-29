<?php

namespace Utils;

class Response
{
    public static function send($data, $statusCode = 200)
    {
        // Establecer el tipo de contenido como JSON
        header('Content-Type: application/json');
        http_response_code($statusCode); 
        echo json_encode($data);
        exit();
    }
}
