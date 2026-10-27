<?php

// Helper format tampilan.

// Harga aset: 0 menjadi "Gratis".
function format_rupiah(int $price): string
{
    return $price === 0 ? 'Gratis' : 'Rp' . number_format($price, 0, ',', '.');
}

// Label badge render pipeline: "Built-in / URP" menjadi ["Built-in", "URP"].
function pipeline_badges(?string $pipeline): array
{
    $pipeline = trim((string) $pipeline);

    if ($pipeline === '') {
        return [];
    }

    if (preg_match('/\((URP|HDRP)\)/', $pipeline, $match) === 1) {
        return [$match[1]];
    }

    $labels = array_map('trim', explode('/', $pipeline));

    return array_values(array_filter($labels, static fn ($label) => $label !== ''));
}

// Kelas warna badge sesuai jenis pipeline.
function pipeline_badge_class(string $label): string
{
    return match (true) {
        str_contains($label, 'HDRP') => 'badge-pipeline-hdrp',
        str_contains($label, 'URP')  => 'badge-pipeline-urp',
        str_contains($label, 'Built-in') => 'badge-pipeline-builtin',
        default => '',
    };
}
