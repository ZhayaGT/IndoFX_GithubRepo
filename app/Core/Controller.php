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

    // Halaman admin hanya boleh dibuka oleh sesi dengan role admin.
    public static function guardAdmin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if (($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Akses admin diperlukan.';
            exit;
        }
    }

    public static function guardLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    protected static function guardSeller(): void
    {
        self::guardLogin();

        if (($_SESSION['role'] ?? '') !== 'seller') {
            http_response_code(403);
            echo 'Akses penjual diperlukan.';
            exit;
        }
    }
}
