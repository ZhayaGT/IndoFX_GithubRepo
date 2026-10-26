<?php

declare(strict_types=1);

namespace IndoFX\Core;

class Router
{
    private array $routes = [];

    /**
     * Mendaftarkan route dengan method GET
     */
    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Mendaftarkan route dengan method POST
     */
    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    /**
     * Menambahkan route ke dalam koleksi
     */
    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => rtrim($path, '/') === '' ? '/' : rtrim($path, '/'),
            'handler' => $handler,
        ];
    }

    /**
     * Mengeksekusi handler sesuai URL yang diakses
     */
    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/') === '' ? '/' : rtrim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['path'] === $path) {
                $handler = $route['handler'];

                if (is_callable($handler)) {
                    call_user_func($handler);
                    return;
                }

                if (is_array($handler) && count($handler) === 2) {
                    [$controllerClass, $action] = $handler;
                    if (class_exists($controllerClass)) {
                        $controller = new $controllerClass();
                        if (method_exists($controller, $action)) {
                            $controller->$action();
                            return;
                        }
                    }
                }
            }
        }

        // Tampilan 404 jika route tidak ditemukan
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}
