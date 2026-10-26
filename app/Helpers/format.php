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
