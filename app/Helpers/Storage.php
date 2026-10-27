<?php

namespace IndoFX\Helpers;

/**
 * Pembentuk URL berkas aset.
 *
 * Semua path gambar di database disimpan relatif tanpa leading slash,
 * misalnya "assets/img/catalog/01.jpg". Fungsi di sini yang menambahkan
 * basis URL, sehingga pindah ke Supabase Storage nanti cukup mengubah
 * STORAGE_BASE_URL di .env tanpa menyentuh view, model, atau seed.
 */
class Storage
{
    public static function url(?string $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        if (preg_match('#^https?://#i', $path) === 1) {
            return $path;
        }

        $base = rtrim((string) \IndoFX\Core\Env::get('STORAGE_BASE_URL', ''), '/');
        $clean = ltrim($path, '/');

        return $base === '' ? '/' . $clean : $base . '/' . $clean;
    }

    /**
     * URL gambar utama dengan cadangan bila aset belum punya gambar.
     */
    public static function image(?string $path, string $fallback = '/assets/img/placeholder.png'): string
    {
        $url = self::url($path);

        return $url === '' ? $fallback : $url;
    }
}
