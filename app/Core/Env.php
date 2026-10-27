<?php

namespace IndoFX\Core;

class Env
{
    public static function get($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        }

        if ($value === null || $value === false || $value === '') {
            return $default;
        }

        return $value;
    }

    public static function bool($key, $default = false)
    {
        $value = self::get($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function int($key, $default = 0)
    {
        $value = self::get($key);

        return $value === null ? $default : (int) $value;
    }
}
