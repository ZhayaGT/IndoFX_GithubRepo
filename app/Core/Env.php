<?php

namespace IndoFX\Core;

class Env {
    // Mengambil nilai string dari variabel environment (.env)
    public static function get($key, $default = null) {
        // Variabel proses (shell/CI) diutamakan, lalu nilai dari .env.
        $value = getenv($key);

        if ($value === false) {
            $value = $_ENV[$key] ?? null;
        }

        if ($value === false || $value === null) {
            return $default;
        }

        return $value;
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