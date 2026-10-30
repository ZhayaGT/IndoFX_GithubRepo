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

    <!-- SVG Helper untuk Gambar Kartu Aset -->
    <svg class="d-none">
        <defs>
            <g id="castle-asset-thumb">
                <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#D4A39B" />
                    <stop offset="50%" stop-color="#E8C5B0" />
                    <stop offset="100%" stop-color="#F7D69D" />
                </linearGradient>
                <rect width="400" height="220" fill="url(#skyGrad)" />
                <circle cx="310" cy="85" r="42" fill="#FFFDF2" opacity="0.95" />
                <circle cx="310" cy="85" r="65" fill="#FFF7D6" opacity="0.4" />
                <ellipse cx="90" cy="50" rx="65" ry="18" fill="#B88E8D" opacity="0.35" />
                <path d="M0 165 Q120 135 250 160 T400 155 L400 220 L0 220 Z" fill="#6E575F" />
                <rect x="85" y="105" width="185" height="90" fill="#4A3B47" />
                <rect x="105" y="70" width="32" height="125" fill="#3B2E39" />
                <polygon points="100,70 121,32 142,70" fill="#2B2129" />
                <rect x="165" y="55" width="38" height="140" fill="#3B2E39" />
                <polygon points="160,55 184,15 208,55" fill="#2B2129" />
                <rect x="235" y="65" width="36" height="130" fill="#59484F" />
                <polygon points="230,65 253,25 276,65" fill="#3B2E39" />
                <rect x="179" y="85" width="8" height="14" rx="4" fill="#FFAE42" />
                <path d="M0 185 Q160 170 400 185 L400 220 L0 220 Z" fill="#2B2122" />
            </g>

            <g id="forest-mist-art">
                <linearGradient id="forestSky" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#8FA3B0" />
                    <stop offset="60%" stop-color="#C9D6DF" />
                    <stop offset="100%" stop-color="#3A4740" />
                </linearGradient>
                <rect width="400" height="220" fill="url(#forestSky)" />
                <path d="M0 140 Q100 110 220 135 T400 120 L400 220 L0 220 Z" fill="#52615B" opacity="0.6" />
                <polygon points="50,40 25,150 75,150" fill="#2D3A34" />
                <polygon points="85,55 65,155 105,155" fill="#3B4B44" />
                <polygon points="310,30 280,155 340,155" fill="#25302B" />
                <ellipse cx="200" cy="135" rx="180" ry="25" fill="#E4EEF5" opacity="0.45" />
                <path d="M0 155 Q110 140 175 220 L0 220 Z" fill="#1A241E" />
                <path d="M400 150 Q280 140 225 220 L400 220 Z" fill="#16201A" />
                <polygon points="175,220 198,148 212,148 225,220" fill="#3E3A35" />
            </g>
        </defs>
    </svg>

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

            <div class="search-box-header d-flex align-items-center flex-grow-1 mx-2 mx-md-4">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php require BASE_PATH . '/resources/views/partials/navbar-auth.php'; ?>
            </div>
        </div>
    </nav>

    <!-- 2. Konten Eksplorasi Katalog & Sidebar Filter -->
    <main class="py-4">
        <div class="container-figma">

            <!-- Header Ringkasan & Urutkan -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <span class="text-dark fw-bold fs-7">Menampilkan <span><?= $totalAssets ?></span> aset yang cocok</span>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Urutkan:</span>
                    <select class="form-select form-select-sm sort-select">
                        <option value="terbaru" selected>Terbaru</option>
                        <option value="populer">Terpopuler</option>
                        <option value="harga_rendah">Harga: Rendah ke Tinggi</option>
                        <option value="harga_tinggi">Harga: Tinggi ke Rendah</option>
                    </select>
                </div>
            </div>

            <div class="row g-4 align-items-start">

                <!-- KOLOM KIRI: Grid 3 Kartu per Baris -->
                <div class="col-12 col-lg-9">
                    <div class="row g-3">
                        <?php foreach ($assets as $asset): ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="asset-card-figma h-100 d-flex flex-column">
                                    <a href="/asset/detail?id=<?= $asset['id'] ?>" class="asset-thumb-wrapper d-block">
                                        <svg width="100%" height="175" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                                            <use href="#castle-asset-thumb" />
                                        </svg>
                                    </a>
                                    <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <a href="/creator/profile" class="asset-studio text-decoration-none d-inline-block">Studio Kreator</a>
                                            <a href="/asset/detail?id=<?= $asset['id'] ?>" class="text-decoration-none d-block">
                                                <h6 class="asset-name"><?= htmlspecialchars($asset['title']) ?></h6>
                                            </a>
                                            <div class="d-flex gap-1 mb-2">
                                                <span class="badge-pipeline badge-urp">URP</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center pt-1">
                                            <span class="asset-price">
                                                <?= $asset['price'] == 0 ? 'Gratis' : 'Rp' . number_format($asset['price'], 0, ',', '.') ?>
                                            </span>
                                            <i class="bi bi-download asset-dl-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination dinamis -->
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-3 explore-pagination" data-catalog-pagination></div>
                </div>

                <!-- KOLOM KANAN: Card Filter Pencarian -->
                <div class="col-12 col-lg-3">
                    <div class="filter-search-card p-3">

                        <!-- Header Filter -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-7">Filter Pencarian</span>
                            <a href="javascript:void(0)" class="filter-reset-link">Reset</a>
                        </div>

                        <!-- 1. Kategori -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Kategori</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" id="cat_shader">
                                <label class="form-check-label filter-check-text" for="cat_shader">Shader</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" id="cat_particle">
                                <label class="form-check-label filter-check-text" for="cat_particle">Particle</label>
                            </div>
                        </div>

                        <!-- 2. Rentang Harga (Rp) -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Rentang Harga (Rp)</label>
                            <div class="d-flex align-items-center gap-1 mb-2">
                                <input type="number" class="form-control form-control-sm filter-range-input" placeholder="Min">
                                <span class="text-muted">-</span>
                                <input type="number" class="form-control form-control-sm filter-range-input" placeholder="Max">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-radio" type="radio" name="price_filter_radio" id="price_free_radio">
                                <label class="form-check-label filter-check-text" for="price_free_radio">Gratis</label>
                            </div>
                        </div>

                        <!-- 3. Render Pipeline -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Render Pipeline</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" id="pipe_builtin">
                                <label class="form-check-label filter-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" id="pipe_urp">
                                <label class="form-check-label filter-check-text" for="pipe_urp">URP</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" id="pipe_hdrp">
                                <label class="form-check-label filter-check-text" for="pipe_hdrp">HDRP</label>
                            </div>
                        </div>

                        <!-- 4. Versi Unity -->
                        <div class="filter-group mb-1">
                            <label class="filter-group-label d-block mb-2">Versi Unity</label>
                            <select class="form-select form-select-sm filter-range-input">
                                <option selected>Semua Versi</option>
                                <option value="2021">2021.3 LTS</option>
                                <option value="2022">2022.3 LTS</option>
                                <option value="2023">2023.x</option>
                            </select>
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
    <!-- Katalog dari data dummy -->
    <!-- <script src="/assets/js/catalog.js" defer></script> -->
</body>

</html>