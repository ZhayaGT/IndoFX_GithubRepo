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

// Slug judul aset: "Forest Mist!" menjadi "forest-mist".
function slugify(string $text): string
{
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $text), '-'));

    return $slug === '' ? 'aset' : substr($slug, 0, 120);
}

// Label dan kelas badge untuk status moderasi aset penjual.
function asset_status_badge(?string $status): array
{
    return match ((string) $status) {
        'published' => ['Disetujui', 'badge-status-approved'],
        'rejected'  => ['Ditolak', 'badge-status-rejected'],
        default     => ['Menunggu', 'badge-status-pending'],
    };
}

// Nominal uang yang selalu berupa angka, termasuk nol: "Rp0".
function format_rupiah_amount(int $amount): string
{
    return 'Rp' . number_format($amount, 0, ',', '.');
}
