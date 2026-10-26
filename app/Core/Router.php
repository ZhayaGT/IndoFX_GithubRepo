<?php

declare(strict_types=1);

namespace IndoFX\Core;

class Router {
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
    // // Mencari kecocokan URL dan menjalankan controller yang sesuai
    // public function dispatch($uri, $method) {
    //     // Bersihkan parameter query string dari URI (misal: /katalog?id=1 menjadi /katalog)
    //     $uri = parse_url($uri, PHP_URL_PATH);

    //     foreach ($this->routes as $route) {
    //         if ($route['uri'] === $uri && $route['method'] === $method) {
    //             $controllerClass = "\\App\\Controllers\\" . $route['controller'];
                
    //             if (class_exists($controllerClass)) {
    //                 $controllerInstance = new $controllerClass();
    //                 $action = $route['action'];
                    
    //                 if (method_exists($controllerInstance, $action)) {
    //                     return $controllerInstance->$action();
    //                 }
    //             }
    //         }
    //     }

    //     // Jika tidak ada rute yang cocok, kembalikan 404
    //     http_response_code(404);
    //     echo "404 - Halaman tidak ditemukan";
    // }
// }