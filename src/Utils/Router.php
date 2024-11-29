<?php

namespace Utils;

class Router {
    private $routes = [];

    // Registrar ruta
    public function addRoute($method, $pattern, $controllerMethod) {
        $pattern = "#^" . $pattern . "$#";
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'controllerMethod' => $controllerMethod
        ];
    }

    // Verificar y ejecutar la ruta
    public function dispatch($requestUri, $requestMethod) {
        foreach ($this->routes as $route) {
            if ($requestMethod === $route['method'] && preg_match($route['pattern'], $requestUri, $matches)) {
                $controllerMethod = $route['controllerMethod'];
                $controllerMethod($matches);
                return;
            }
        }

        // Ruta no válida
        echo json_encode(['status' => 'error', 'message' => 'Ruta no válida']);
    }
}
