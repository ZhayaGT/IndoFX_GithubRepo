<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eksplorasi Katalog Aset - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets Utama -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body style="background-color: var(--ifx-cream-bg, #FDECE2);">

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

    <!-- 2. Konten Eksplorasi Katalog & Sidebar Filter -->
    <main class="py-4">
        <div class="container-figma">

            <form method="get" action="/catalog/explore" class="row g-4 align-items-start">

                <div class="col-12 col-lg-9">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <span class="text-dark fw-bold fs-7">
                            Menampilkan <?= (int) $total ?> aset yang cocok
                        </span>

                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0" for="sort">Urutkan:</label>
                            <select class="form-select form-select-sm sort-select" id="sort" name="sort" onchange="this.form.submit()">
                                <?php foreach ($sortOptions as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $filter['sort'] === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3">
                        <?php foreach ($assets as $item): ?>
                            <?php $colClass = 'col-12 col-sm-6 col-md-4'; require BASE_PATH . '/resources/views/partials/asset-card.php'; ?>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($assets === []): ?>
                        <p class="text-muted small mt-3">Belum ada aset yang cocok dengan filter ini.</p>
                    <?php endif; ?>

                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-3 explore-pagination">
                            <?php
                            $baseQuery = array_filter([
                                'q'        => $filter['q'],
                                'gratis'   => $filter['gratis'] ? '1' : '',
                                'kategori' => $filter['category'],
                                'pipeline' => $filter['pipeline'],
                                'sort'     => $filter['sort'] !== 'terbaru' ? $filter['sort'] : '',
                            ]);
                            $pageUrl = static fn (int $n): string => '/catalog/explore?' . http_build_query($baseQuery + ['page' => $n]);
                            ?>
                            <a href="<?= $page > 1 ? $pageUrl($page - 1) : 'javascript:void(0)' ?>"
                               class="page-nav-link <?= $page === 1 ? 'text-muted' : 'text-dark fw-bold' ?>">&laquo; Sebelumnya</a>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= $pageUrl($i) ?>" class="page-num-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <a href="<?= $page < $totalPages ? $pageUrl($page + 1) : 'javascript:void(0)' ?>"
                               class="page-nav-link <?= $page === $totalPages ? 'text-muted' : 'text-dark fw-bold' ?>">Selanjutnya &raquo;</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- KOLOM KANAN: Card Filter Pencarian -->
                <div class="col-12 col-lg-3">
                    <div class="filter-search-card p-3">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-7">Filter Pencarian</span>
                            <a href="/catalog/explore" class="filter-reset-link">Reset</a>
                        </div>

                        <input type="hidden" name="q" value="<?= htmlspecialchars($filter['q'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="sort" value="<?= htmlspecialchars($filter['sort'], ENT_QUOTES, 'UTF-8') ?>">

                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Kategori</label>
                            <?php foreach ($categories as $category): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox" type="radio"
                                           id="cat_<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>"
                                           name="kategori" value="<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>"
                                           onchange="this.form.submit()"
                                        <?= $filter['category'] === $category['slug'] ? 'checked' : '' ?>>
                                    <label class="form-check-label filter-check-text"
                                           for="cat_<?= htmlspecialchars($category['slug'], ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Harga</label>
                            <div class="form-check">
                                <input class="form-check-input filter-radio" type="checkbox" id="price_free_radio"
                                       name="gratis" value="1" onchange="this.form.submit()"
                                    <?= $filter['gratis'] ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="price_free_radio">Gratis</label>
                            </div>
                        </div>

                        <div class="filter-group mb-1">
                            <label class="filter-group-label d-block mb-2">Render Pipeline</label>
                            <?php foreach ($pipelineOptions as $value => $label): ?>
                                <div class="form-check mb-1">
                                    <input class="form-check-input filter-checkbox" type="radio"
                                           id="pipe_<?= md5($value) ?>" name="pipeline" value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?>"
                                           onchange="this.form.submit()"
                                        <?= $filter['pipeline'] === $value ? 'checked' : '' ?>>
                                    <label class="form-check-label filter-check-text" for="pipe_<?= md5($value) ?>">
                                        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- 3. Footer Putih Konsisten -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="row g-4 justify-content-between mb-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <h5 class="footer-brand-title mb-2">IndoFX</h5>
                    <p class="footer-brand-desc m-0">
                        Portal distribusi dan marketplace aset CG & VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Katalog</h6>
                            <a href="/#katalog" class="footer-link">Shader</a>
                            <a href="/#katalog" class="footer-link">VFX Partikel</a>
                            <a href="/#katalog" class="footer-link">Model 3D</a>
                        </div>
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Dukungan</h6>
                            <a href="#" class="footer-link">Dokumentasi</a>
                            <a href="#" class="footer-link">Hubungi Kami</a>
                            <a href="#" class="footer-link">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
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
</body>

</html>