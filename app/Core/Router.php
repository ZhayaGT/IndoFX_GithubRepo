<?php

declare(strict_types=1);

namespace IndoFX\Core;

class router {
    protected $routes = [];

    // Mendaftarkan rute baru (mendukung GET dan POST)
    public function add($method, $uri, $controller, $action) {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action
        ];
    }

    public function dispatch($uri, $method) {
    // Bersihkan URI dari query string (misal: /?abc=1 menjadi /)
    $uri = parse_url($uri, PHP_URL_PATH);
    
    foreach ($this->routes as $route) {
        if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
            // Panggil controller dan method terkait
            $controllerName = "IndoFX\\Controllers\\" . $route['controller'];
            $controller = new $controllerName();
            return $controller->{$route['action']}();
        }
    }

    // Jika tidak ada yang cocok, tampilkan 404
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
    }
}