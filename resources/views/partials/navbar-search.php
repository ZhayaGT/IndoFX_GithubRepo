<?php

// Kotak pencarian navbar; Enter mengirim ?q= ke halaman eksplorasi.

$searchQuery = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
?>
<form class="search-box-header d-none d-md-flex align-items-center" action="/catalog/explore" method="get" role="search">
    <i class="bi bi-search search-icon-header"></i>
    <input type="text" name="q" class="form-control search-input-header"
           value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>"
           placeholder="Cari aset, VFX, shader, tekstur..."
           aria-label="Cari aset">
</form>
