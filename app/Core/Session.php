<?php

declare(strict_types=1);

namespace IndoFX\Core;

/**
 * Pembungkus PHP session.
 * Menangani konfigurasi cookie, regenerasi ID, dan pesan flash.
 */
class Session
{
    private const FLASH_KEY = '_flash';

    /**
     * Memulai sesi dengan nama dan masa berlaku dari konfigurasi aplikasi.
     */
    public static function start(string $name, int $lifetimeMinutes): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name($name);

        session_set_cookie_params([
            'lifetime' => $lifetimeMinutes * 60,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        ]);

        session_start();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Menyimpan pesan yang hanya bertahan sampai request berikutnya.
     */
    public static function flash(string $key, mixed $value): void
    {
        $_SESSION[self::FLASH_KEY][$key] = $value;
    }

    /**
     * Mengambil sekaligus menghapus pesan flash.
     */
    public static function pullFlash(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION[self::FLASH_KEY][$key] ?? $default;
        unset($_SESSION[self::FLASH_KEY][$key]);

        return $value;
    }

    /**
     * Mengganti ID sesi untuk mencegah session fixation setelah login.
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Menghapus seluruh data sesi dan mengganti ID-nya.
     * Dipakai saat logout agar sesi lama tidak bisa dipakai ulang,
     * tetapi sesi baru tetap tersedia untuk pesan flash.
     */
    public static function invalidate(): void
    {
        $_SESSION = [];
        session_regenerate_id(true);
    }
}
