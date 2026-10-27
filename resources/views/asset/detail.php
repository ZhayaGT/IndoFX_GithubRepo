<?php

/**
 * Halaman detail aset.
 *
 * @var string $title
 * @var array  $asset      baris aset dari IndoFX\Models\Asset::findBySlug()
 * @var array  $gallery    daftar URL gambar, sudah lengkap dengan basis storage
 * @var array  $paragraphs paragraf deskripsi
 * @var array  $howToUse   langkah cara pakai
 * @var ?array $file       berkas unduhan terbaru
 * @var string $fileFormat ekstensi berkas, mis. ".unitypackage"
 */

$mainImage    = $gallery[0] ?? '';
$price        = (int) $asset['price'];
$pipelines    = [$asset['render_pipeline']];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Utama IndoFX -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <!-- 1. Header Navbar Oranye -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text">IndoFX</span>
            </a>

            <?php require BASE_PATH . '/resources/views/partials/navbar-search.php'; ?>

            <div class="d-flex align-items-center gap-2">
                <?php require BASE_PATH . '/resources/views/partials/navbar-auth.php'; ?>
            </div>
        </div>
    </nav>

    <!-- 2. Detail Aset -->
    <main class="asset-detail-page">
        <div class="container-figma">
            <div class="row g-4">

                <!-- KOLOM KIRI: Galeri + tab -->
                <div class="col-12 col-lg-8">
                    <div class="asset-detail-main">
                        <img class="asset-detail-main-img"
                             src="<?= htmlspecialchars($mainImage, ENT_QUOTES, 'UTF-8') ?>"
                             alt="Pratinjau <?= htmlspecialchars($asset['title'], ENT_QUOTES, 'UTF-8') ?>"
                             data-detail-main-img>
                    </div>

                    <?php if (count($gallery) > 1): ?>
                        <div class="asset-detail-thumbs" data-detail-thumbs>
                            <?php foreach ($gallery as $index => $image): ?>
                                <button type="button"
                                        class="asset-detail-thumb<?= $index === 0 ? ' is-active' : '' ?>"
                                        data-detail-thumb="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>"
                                        aria-label="Pratinjau <?= $index + 1 ?>">
                                    <img src="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="" loading="lazy">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Tab Deskripsi / Changelog / Ulasan -->
                    <div class="asset-tabs" role="tablist" data-detail-tabs>
                        <button type="button" class="asset-tab-link active" role="tab" id="tab-deskripsi"
                                aria-controls="panel-deskripsi" aria-selected="true"
                                data-detail-tab="deskripsi">Deskripsi</button>
                        <button type="button" class="asset-tab-link" role="tab" id="tab-changelog"
                                aria-controls="panel-changelog" aria-selected="false"
                                data-detail-tab="changelog">Changelog</button>
                        <button type="button" class="asset-tab-link" role="tab" id="tab-ulasan"
                                aria-controls="panel-ulasan" aria-selected="false"
                                data-detail-tab="ulasan">Ulasan</button>
                    </div>

                    <!-- Panel: Deskripsi -->
                    <div class="asset-tab-panel" role="tabpanel" id="panel-deskripsi"
                         aria-labelledby="tab-deskripsi" data-detail-panel="deskripsi">
                        <?php if ($paragraphs === []): ?>
                            <p class="asset-panel-empty">Belum ada deskripsi untuk aset ini.</p>
                        <?php else: ?>
                            <?php foreach ($paragraphs as $paragraph): ?>
                                <p class="asset-description-text"><?= htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if ($howToUse !== []): ?>
                            <h2 class="asset-panel-heading mt-4">Cara pakai</h2>
                            <?php foreach ($howToUse as $step): ?>
                                <p class="asset-description-text"><?= htmlspecialchars($step, ENT_QUOTES, 'UTF-8') ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Panel: Changelog -->
                    <div class="asset-tab-panel" role="tabpanel" id="panel-changelog"
                         aria-labelledby="tab-changelog" data-detail-panel="changelog" hidden>
                        <?php if ($file === null): ?>
                            <p class="asset-panel-empty">Belum ada berkas yang diunggah untuk aset ini.</p>
                        <?php else: ?>
                            <h2 class="asset-panel-heading">Berkas aset</h2>
                            <div class="changelog-item">
                                <div class="changelog-head">
                                    <span class="changelog-version"><?= htmlspecialchars($file['original_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="changelog-date"><?= htmlspecialchars($fileFormat, ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <ul class="changelog-notes">
                                    <li>Ukuran berkas: <?= number_format((int) $file['file_size'] / 1048576, 1, ',', '.') ?> MB</li>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Panel: Ulasan -->
                    <div class="asset-tab-panel" role="tabpanel" id="panel-ulasan"
                         aria-labelledby="tab-ulasan" data-detail-panel="ulasan" hidden>
                        <p class="asset-panel-empty">Ulasan pembeli belum tersedia pada versi ini.</p>
                    </div>
                </div>

                <!-- KOLOM KANAN: Kartu pembelian -->
                <div class="col-12 col-lg-4">
                    <div class="asset-buy-card">
                        <span class="asset-buy-category"><?= htmlspecialchars($asset['category_name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <h1 class="asset-buy-title"><?= htmlspecialchars($asset['title'], ENT_QUOTES, 'UTF-8') ?></h1>
                        <p class="asset-buy-seller">Oleh <?= htmlspecialchars($asset['seller_name'], ENT_QUOTES, 'UTF-8') ?></p>

                        <p class="asset-buy-price-label">Harga Aset</p>
                        <p class="asset-buy-price"><?= htmlspecialchars(format_rupiah($price), ENT_QUOTES, 'UTF-8') ?></p>

                        <dl class="asset-buy-meta">
                            <div class="asset-buy-meta-row">
                                <dt>Versi Unity</dt>
                                <dd><?= htmlspecialchars((string) $asset['unity_version'], ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                            <div class="asset-buy-meta-row">
                                <dt>Render Pipeline</dt>
                                <dd class="asset-buy-meta-badges">
                                    <?php foreach (pipeline_badges($asset['render_pipeline'] ?? '') as $label): ?>
                                        <span class="badge-pipeline <?= pipeline_badge_class($label) ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                                    <?php endforeach; ?>
                                </dd>
                            </div>
                            <div class="asset-buy-meta-row">
                                <dt>Format File</dt>
                                <dd><?= htmlspecialchars($fileFormat, ENT_QUOTES, 'UTF-8') ?></dd>
                            </div>
                        </dl>

                        <button type="button" class="btn btn-detail-buy" data-detail-buy>
                            <?= $price === 0 ? 'Unduh Gratis' : 'Beli' ?>
                        </button>
                        <button type="button" class="btn btn-detail-cart" data-detail-cart>Tambahkan ke Keranjang</button>

                        <p class="asset-buy-status" data-detail-status role="status" aria-live="polite"></p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- 3. Footer -->
    <footer class="footer-main footer-main-centered">
        <div class="container-figma">
            <h5 class="footer-brand-title mb-2">IndoFX</h5>
            <p class="footer-brand-desc mx-auto mb-4">
                Portal distribusi dan marketplace aset CG & VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Pergantian navbar guest/pembeli -->
    <script src="/assets/js/navbar-auth.js"></script>
    <!-- Tab dan galeri halaman detail aset -->
    <script src="/assets/js/asset-detail.js" defer></script>
</body>

</html>
