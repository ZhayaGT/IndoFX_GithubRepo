<?php

namespace IndoFX\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, string $controller, string $action): void
    {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['uri'] !== $path || $route['method'] !== $method) {
                continue;
            }

            $class = '\\IndoFX\\Controllers\\' . $route['controller'];

            if (class_exists($class) && method_exists($class, $route['action'])) {
                (new $class())->{$route['action']}();
                return;
            }
        }

        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}
