<?php

declare(strict_types=1);

namespace IndoFX\Core;

use IndoFX\Models\User;

/**
 * Status autentikasi pengguna pada sesi aktif.
 */
class Auth
{
    private const SESSION_KEY = 'auth_user';

    private static ?array $cached = null;

    /**
     * Memuat pengguna dari database berdasarkan ID di sesi.
     * Baris pengguna di-cache selama satu request.
     */
    public static function user(): ?array
    {
        if (self::$cached !== null) {
            return self::$cached;
        }

        $id = Session::get(self::SESSION_KEY . '_id');

        if (!is_string($id) || $id === '') {
            return null;
        }

        $user = (new User())->findById($id);

        // Akun yang dinonaktifkan diperlakukan sebagai belum login.
        if ($user === null || !(bool) $user['is_active']) {
            Session::forget(self::SESSION_KEY . '_id');
            return null;
        }

        return self::$cached = $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    /**
     * Menandai pengguna login dan mengganti ID sesi.
     */
    public static function login(array $user): void
    {
        Session::regenerate();
        Session::put(self::SESSION_KEY . '_id', $user['id']);
        self::$cached = $user;
    }

    public static function logout(): void
    {
        self::$cached = null;
        Session::invalidate();
    }
}
