<?php

declare(strict_types=1);

// Kumpulan fungsi helper untuk format data

if (!function_exists('format_rupiah')) {
    /**
     * Format harga aset menjadi teks rupiah.
     * Harga 0 tampil sebagai "Gratis".
     */
    function format_rupiah(int|float $price): string
    {
        return $price === 0 || $price === 0.0 ? 'Gratis' : 'Rp' . number_format($price, 0, ',', '.');
    }
}

if (!function_exists('formatRupiah')) {
    /**
     * Alias camelCase untuk format Rupiah
     */
    function formatRupiah(int|float $amount): string
    {
        return format_rupiah($amount);
    }
}

if (!function_exists('pipeline_badge_class')) {
    /**
     * Kelas warna tag untuk render pipeline.
     */
    function pipeline_badge_class(string $pipeline): string
    {
        $classes = [
            'URP'      => 'badge-pipeline badge-urp',
            'HDRP'     => 'badge-pipeline badge-hdrp',
            'Built-in' => 'badge-pipeline badge-builtin',
            'Built-In' => 'badge-pipeline badge-builtin',
        ];

        return $classes[$pipeline] ?? 'badge-pipeline';
    }
}
