<?php

declare(strict_types=1);

namespace IndoFX\Core;

/**
 * Token CSRF per sesi untuk seluruh form POST.
 */
class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Memeriksa token dari form POST.
     */
    public static function verify(?string $token): bool
    {
        $expected = $_SESSION[self::SESSION_KEY] ?? null;

        if (!is_string($token) || !is_string($expected) || $expected === '') {
            return false;
        }

        return hash_equals($expected, $token);
    }
}
