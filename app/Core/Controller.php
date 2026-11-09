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

    // Halaman pembeli: tamu diarahkan ke login.
    public static function guardLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    // Halaman penjual: pembeli diarahkan ke pendaftaran penjual.
    public static function guardSeller(): void
    {
        self::guardLogin();

        if (($_SESSION['role'] ?? '') !== 'seller') {
            header('Location: /seller/register');
            exit;
        }
    }
}