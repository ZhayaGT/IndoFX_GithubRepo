<?php

// Kumpulan fungsi helper untuk format data

/**
 * Format harga aset menjadi teks rupiah.
 *
 * Harga 0 tampil sebagai "Gratis" supaya sejalan dengan kartu katalog
 * (lihat formatPrice di public/assets/js/catalog.js).
 */
function format_rupiah(int $price): string
{
    return $price === 0 ? 'Gratis' : 'Rp' . number_format($price, 0, ',', '.');
}

/**
 * Kelas warna tag untuk render pipeline.
 *
 * Warna diambil dari desain: URP merah, HDRP ungu, Built-in biru.
 * Nilai yang tidak dikenal memakai warna dasar .badge-pipeline.
 * Padanan di sisi klien: PIPELINE_CLASSES di public/assets/js/catalog.js.
 */
function pipeline_badge_class(string $pipeline): string
{
    $classes = [
        'URP'      => 'badge-pipeline-urp',
        'HDRP'     => 'badge-pipeline-hdrp',
        'Built-in' => 'badge-pipeline-builtin',
    ];

    return $classes[$pipeline] ?? '';
}
