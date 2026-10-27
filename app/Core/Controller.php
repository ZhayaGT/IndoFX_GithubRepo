<?php

namespace IndoFX\Core;

class Controller
{
    /**
     * Render view; kunci array $data menjadi variabel di dalam view.
     */
    protected function view(string $view, array $data = []): void
    {
        $path = BASE_PATH . '/resources/views/' . $view . '.php';

        if (!is_file($path)) {
            http_response_code(404);
            exit('View tidak ditemukan: ' . $view);
        }

        extract($data);
        require $path;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }
}
