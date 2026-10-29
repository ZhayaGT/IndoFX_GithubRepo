<?php

namespace IndoFX\Core;

class Router
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $routes = [];

    /**
     * Mendaftarkan rute baru
     */
    public function add(string $method, string $uri, mixed $controller, ?string $action = null): void
    {
        $this->routes[] = [
            'method'     => strtoupper($method),
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action
        ];
    }

    /**
     * Helper praktis untuk request GET
     */
    public function get(string $uri, mixed $controller, ?string $action = null): void
    {
        $this->add('GET', $uri, $controller, $action);
    }

    /**
     * Helper praktis untuk request POST
     */
    public function post(string $uri, mixed $controller, ?string $action = null): void
    {
        $this->add('POST', $uri, $controller, $action);
    }

    /**
     * Menjalankan handler rute
     */
    public function dispatch(string $uri, string $method): mixed
    {
        $uri = (string) parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                if ($route['controller'] instanceof \Closure) {
                    return call_user_func($route['controller']);
                }

                $controllerClass = "\\IndoFX\\Controllers\\" . $route['controller'];

                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    $action = $route['action'];

                    if ($action && method_exists($controllerInstance, $action)) {
                        return $controllerInstance->$action();
                    }
                }
            }
        }

        http_response_code(404);
        if (defined('BASE_PATH') && file_exists(BASE_PATH . '/resources/views/errors/404.php')) {
            require BASE_PATH . '/resources/views/errors/404.php';
        } else {
            echo "404 Not Found";
        }

        return null;
    }
}
