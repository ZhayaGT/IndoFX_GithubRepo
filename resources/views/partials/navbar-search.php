<?php

/**
 * Kotak pencarian pada navbar.
 *
 * Form GET biasa: menekan Enter mengirim `?q=<kata kunci>` ke halaman
 * eksplorasi katalog, sehingga pencarian selalu berakhir di
 * /catalog/explore (lihat CatalogController::explore dan renderExplore di
 * public/assets/js/catalog.js).
 *
 * Nilai yang sedang dicari diisi ulang dari $_GET['q'] supaya kotak tidak
 * kosong setelah pindah halaman.
 */

$searchQuery = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
?>
<form class="search-box-header d-flex align-items-center" action="/catalog/explore" method="get" role="search">
    <i class="bi bi-search search-icon-header"></i>
    <input type="text" name="q" class="form-control search-input-header"
           value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"
           placeholder="Cari aset, VFX, shader, tekstur..."
           aria-label="Cari aset">
</form>
