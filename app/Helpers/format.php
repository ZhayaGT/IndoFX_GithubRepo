<?php

// Helper format tampilan.

/**
 * Harga aset: 0 menjadi "Gratis".
 */
function format_rupiah(int $price): string
{
    return $price === 0 ? 'Gratis' : 'Rp' . number_format($price, 0, ',', '.');
}

/**
 * Kelas warna tag render pipeline. Warna dasar .badge-pipeline dipakai
 * bila nilainya tidak dikenal.
 */
function pipeline_badge_class(string $pipeline): string
{
    return match (true) {
        str_contains($pipeline, 'HDRP') => 'badge-pipeline-hdrp',
        str_contains($pipeline, 'URP')  => 'badge-pipeline-urp',
        str_contains($pipeline, 'Built-in') => 'badge-pipeline-builtin',
        default => '',
    };
}
