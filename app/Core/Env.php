<?php

declare(strict_types=1);

namespace IndoFX\Core;

class Env
{
    /**
     * Mengambil nilai environment variable
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Variabel proses (mis. dari CI atau shell) menang atas isi .env.
        $value = getenv($key);

        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        if ($value === false || $value === null) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true', '(true)' => true,
            'false', '(false)' => false,
            'empty', '(empty)' => '',
            'null', '(null)' => null,
            default => $value,
        };
    }

    /**
     * Mengambil nilai boolean dari environment variable
     */
    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default);

        if (is_bool($value)) {
            return $value;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Mengambil nilai integer dari environment variable
     */
    public static function int(string $key, int $default = 0): int
    {
        $value = self::get($key, $default);
        return (int) $value;
    }
}
