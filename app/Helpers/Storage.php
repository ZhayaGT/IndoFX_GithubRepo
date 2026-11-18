<?php

namespace IndoFX\Helpers;

use IndoFX\Core\Env;

final class Storage
{
    public static function url(?string $path): string
    {
        $path = trim((string) $path);
        if ($path === '' || preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $base = rtrim((string) Env::get('STORAGE_BASE_URL', ''), '/');
        if ($base === '') {
            return '/' . ltrim($path, '/');
        }

        $path = preg_replace('#^assets/img/#', '', ltrim($path, '/'));
        if (strpos($path, '/') === false) {
            $path = 'catalog/' . $path;
        }

        return $base . '/' . ltrim($path, '/');
    }
}
