<?php

declare(strict_types=1);

namespace IndoFX\Core;

/**
 * Basis seluruh controller: render view dan redirect.
 */
abstract class Controller
{
    /**
     * Merender view di resources/views.
     *
     * @param array<string, mixed> $data
     */
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = BASE_PATH . '/resources/views/' . $view . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View tidak ditemukan: {$view}");
        }

        require $viewPath;
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path, true, 302);
        exit;
    }

    /**
     * Redirect kembali ke form sambil menyimpan pesan error dan input lama.
     *
     * @param array<string, string> $errors
     * @param array<string, mixed>  $old
     */
    protected function redirectWithErrors(string $path, array $errors, array $old = []): never
    {
        Session::flash('errors', $errors);
        Session::flash('old', $old);
        $this->redirect($path);
    }
}
