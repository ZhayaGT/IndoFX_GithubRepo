<?php

namespace IndoFX\Core;

class Router
{
    private array $routes = [];

    public function add(string $method, string $uri, mixed $controller, ?string $action = null): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action,
        ];
    }

    public function get(string $uri, mixed $controller, ?string $action = null): void
    {
        $this->add('GET', $uri, $controller, $action);
    }

    public function post(string $uri, mixed $controller, ?string $action = null): void
    {
        $this->add('POST', $uri, $controller, $action);
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['uri'] !== $path || $route['method'] !== strtoupper($method)) {
                continue;
            }

            if ($route['controller'] instanceof \Closure) {
                ($route['controller'])();
                return;
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
