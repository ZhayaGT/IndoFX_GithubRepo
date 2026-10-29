<?php

namespace IndoFX\Core;

class Controller
{
    /**
     * Fungsi untuk merender tampilan (view) dan mengirimkan data.
     *
     * @param string $view Lokasi file view (contoh: 'catalog/home')
     * @param array $data Data yang akan dikirim ke view
     */
    protected function view($view, $data = [])
    {
        // Ekstrak array menjadi variabel tunggal (misal: ['nama' => 'Diaz'] menjadi $nama)
        extract($data);

        // Membentuk path absolut ke file view menggunakan BASE_PATH dari index.php
        $viewPath = BASE_PATH . '/resources/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            // Jika file view tidak ada, kembalikan pesan error
            http_response_code(404);
            die("View tidak ditemukan: " . $view);
        }
    }

    /**
     * Mengalihkan halaman lalu menghentikan eksekusi.
     */
    protected function redirect($path)
    {
        header('Location: ' . $path);
        exit;
    }
}
