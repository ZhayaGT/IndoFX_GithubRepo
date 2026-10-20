<?php

namespace IndoFX\Core;

class Router {
    protected $routes = [];

    // Mendaftarkan rute baru yang mendukung GET dan POST
    public function add($method, $uri, $controller, $action) {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action
        ];
    }

    // Mencari kecocokan URL dan menjalankan controller yang sesuai
    public function dispatch($uri, $method) {
        // Membersihkan parameter query string dari URI (misal: /katalog?id=1 menjadi /katalog)
        $uri = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === $method) {
                $controllerClass = "\\IndoFX\\Controllers\\" . $route['controller'];
                
                if (class_exists($controllerClass)) {
                    $controllerInstance = new $controllerClass();
                    $action = $route['action'];
                    
                    if (method_exists($controllerInstance, $action)) {
                        return $controllerInstance->$action();
                    }
                }
            }
        }

        // Jika tidak ada rute yang cocok maka kembalikan 404
        http_response_code(404);
        echo "404 - Halaman tidak ditemukan";
    }
}