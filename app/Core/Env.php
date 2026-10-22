<?php

namespace IndoFX\Core;

class Env {
    // Mengambil nilai string dari variabel environment (.env)
    public static function get($key, $default = null) {
        // Menggunakan $_ENV bawaan dari library vlucas/phpdotenv
        return $_ENV[$key] ?? $default;
    }

    // Mengambil nilai boolean dari variabel environment (.env)
    public static function bool($key, $default = false) {
        $value = getenv($key);
        if ($value === false) {
            return $default;
        }
        // Konversi string 'true', 'false', '1', '0' menjadi tipe boolean asli PHP
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}