<?php
// Basis URL gambar katalog: STORAGE_BASE_URL + folder catalog/ di bucket.
$storageBasePath = rtrim((string) \IndoFX\Core\Env::get('STORAGE_BASE_URL', ''), '/') . '/catalog/';
?>

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

    <style>
        /* Menggeser filter sedikit lebih ke kiri dan memberi jarak lega ke kumpulan aset */
        .filter-col-left {
            max-width: 260px;
            transform: translateX(-16px);
        }

        @media (max-width: 991.98px) {
            .filter-col-left {
                max-width: 100%;
                transform: none;
                margin-bottom: 20px;
            }
        }

        .filter-search-card {
            background-color: #ffffff;
            border: 1.5px solid var(--ifx-border-dark, #2b2623);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(60, 42, 33, 0.04);
        }

        .filter-header-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1a1a1a;
        }

        .filter-reset-link {
            font-size: 0.8rem;
            font-weight: 600;
            color: #736b66;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .filter-reset-link:hover {
            color: var(--indofx-orange, #ed6b1d);
        }

        .filter-group-label {
            font-size: 0.82rem;
            font-weight: 800;
            color: #1a1a1a;
        }

        .filter-check-text {
            font-size: 0.8rem;
            color: #2b2623;
            font-weight: 500;
        }

        .sort-select-figma {
            background-color: #ffffff;
            border: 1.5px solid #2b2623;
            border-radius: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #1a1a1a;
            padding: 5px 28px 5px 12px;
            cursor: pointer;
        }

        .sort-select-figma:focus {
            border-color: var(--indofx-orange, #ed6b1d);
            box-shadow: none;
            outline: none;
        }
    </style>
</head>

<body style="background-color: var(--ifx-cream-bg, #FAF0E6);">

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
        <div class="navbar-figma-container">
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

            <?php
                $searchPartial = BASE_PATH . '/resources/views/partials/navbar-search.php';
                if (file_exists($searchPartial)) {
                    require $searchPartial;
                }
            ?>

            <div class="d-flex align-items-center">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                } else {
                    echo '<a href="/login" class="btn btn-nav-login">Masuk</a>';
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- 2. Konten Eksplorasi: Menggunakan container-figma agar kartu kembali proporsional -->
    <main class="py-4">
        <div class="container-figma">

            <!-- Header Ringkasan & Urutkan -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <span class="text-dark fw-bold fs-7">Menampilkan <span><?= $totalAssets ?></span> aset yang cocok</span>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">Urutkan:</span>
                    <form id="filterForm" action="/catalog/explore" method="GET">
                        <select name="sort" class="form-select form-select-sm sort-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="terbaru" <?= (!isset($filters['sort']) || $filters['sort'] === 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                            <option value="populer" <?= ($filters['sort'] ?? '') === 'populer' ? 'selected' : '' ?>>Terpopuler</option>
                            <option value="harga_rendah" <?= ($filters['sort'] ?? '') === 'harga_rendah' ? 'selected' : '' ?>>Harga: Rendah ke Tinggi</option>
                            <option value="harga_tinggi" <?= ($filters['sort'] ?? '') === 'harga_tinggi' ? 'selected' : '' ?>>Harga: Tinggi ke Rendah</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row g-4 align-items-start">

                <!-- KOLOM KIRI: Grid 3 Kartu per Baris -->
                <div class="col-12 col-lg-9">
                    <div class="row g-3">
                        <?php foreach ($assets as $asset): ?>
                            <div class="col-12 col-sm-6 col-lg-4"> 
                                <div class="asset-card-figma h-100 d-flex flex-column">
                                    
                                    <!-- 1. Perbaikan URL Gambar & htmlspecialchars -->
                                    <a href="/asset/detail?id=<?= $asset['public_id'] ?>" class="asset-thumb-wrapper d-block">
                                        <?php 
                                        $thumbnailFile = $asset['thumbnail'] ?? ''; 
                                        
                                        if (empty($thumbnailFile)) {
                                            $imageUrl = "https://via.placeholder.com/400x220/2C3A47/FFFFFF?text=Gambar+Kosong";
                                        } elseif (strpos($thumbnailFile, 'http') === 0) {
                                            $imageUrl = $thumbnailFile; 
                                        } else {
                                            $imageUrl = $storageBasePath . $thumbnailFile;
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= htmlspecialchars($asset['title'] ?? 'Aset IndoFX') ?>" style="width: 100%; height: 175px; object-fit: cover;">
                                    </a>

                                    <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                        <div>
                                            <!-- 2. Nama Kreator Dinamis -->
                                            <a href="/creator/profile" class="asset-studio text-decoration-none d-inline-block">
                                                <?= htmlspecialchars($asset['seller_name'] ?? 'Studio Kreator') ?>
                                            </a>
                                            
                                            <a href="/asset/detail?id=<?= $asset['public_id'] ?>" class="text-decoration-none d-block">
                                                <h6 class="asset-name"><?= htmlspecialchars($asset['title'] ?? '') ?></h6>
                                            </a>
                                            
                                            <!-- 3. Badge Pipeline Dinamis -->
                                            <div class="d-flex gap-1 mb-2 flex-wrap">
                                                <?php 
                                                $pipeline = $asset['render_pipeline'] ?? '';
                                                
                                                // Perhatikan penambahan kata -pipeline- pada nama class
                                                if (stripos($pipeline, 'Built-in') !== false) {
                                                    echo '<span class="badge-pipeline badge-pipeline-builtin">Built-In</span> ';
                                                }
                                                if (stripos($pipeline, 'URP') !== false) {
                                                    echo '<span class="badge-pipeline badge-pipeline-urp">URP</span> ';
                                                }
                                                if (stripos($pipeline, 'HDRP') !== false) {
                                                    echo '<span class="badge-pipeline badge-pipeline-hdrp">HDRP</span> ';
                                                }
                                                if (stripos($pipeline, 'Any') !== false) {
                                                    echo '<span class="badge-pipeline bg-secondary text-white border-0 px-2 rounded-1" style="font-size: 0.75rem;">Any</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <!-- 4. Format Harga Dinamis (Mencegah "Rp0") -->
                                        <div class="d-flex justify-content-between align-items-center pt-1">
                                            <span class="asset-price">
                                                <?= (!isset($asset['price']) || $asset['price'] == 0) ? 'Gratis' : 'Rp' . number_format($asset['price'], 0, ',', '.') ?>
                                            </span>
                                            <i class="bi bi-download asset-dl-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <?php
                        // Tautan halaman membawa seluruh filter yang sedang aktif.
                        $baseQuery = [];

                        if ($filters['q'] !== '') {
                            $baseQuery['q'] = $filters['q'];
                        }
                        if (!empty($filters['category'])) {
                            $baseQuery['category'] = $filters['category'];
                        }
                        if ($filters['min_price'] !== '') {
                            $baseQuery['min_price'] = $filters['min_price'];
                        }
                        if ($filters['max_price'] !== '') {
                            $baseQuery['max_price'] = $filters['max_price'];
                        }
                        if ($filters['is_free'] === '1') {
                            $baseQuery['price_filter_radio'] = 'free';
                        }
                        if (!empty($filters['pipeline'])) {
                            $baseQuery['pipeline'] = $filters['pipeline'];
                        }
                        if ($filters['unity_version'] !== '') {
                            $baseQuery['unity_version'] = $filters['unity_version'];
                        }
                        if ($filters['sort'] !== 'terbaru') {
                            $baseQuery['sort'] = $filters['sort'];
                        }

                        $pageUrl = static fn (int $n): string => '/catalog/explore?' . http_build_query($baseQuery + ['page' => $n]);
                        ?>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-3 explore-pagination">
                            <a href="<?= $page > 1 ? $pageUrl($page - 1) : 'javascript:void(0)' ?>"
                               class="page-nav-link <?= $page === 1 ? 'text-muted' : 'text-dark fw-bold' ?>">Sebelumnya</a>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?= $pageUrl($i) ?>" class="page-num-link <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <a href="<?= $page < $totalPages ? $pageUrl($page + 1) : 'javascript:void(0)' ?>"
                               class="page-nav-link <?= $page === $totalPages ? 'text-muted' : 'text-dark fw-bold' ?>">Selanjutnya</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- KOLOM KANAN: Card Filter Pencarian -->
                <div class="col-12 col-lg-3">
                    <div class="filter-search-card p-3">
                        <form action="/catalog/explore" method="GET">

                        <?php if(isset($_GET['q'])): ?>
                            <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q']) ?>">
                        <?php endif; ?>

                        <!-- Header Filter -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-7">Filter Pencarian</span>
                            <a href="/catalog/explore" class="filter-reset-link">Reset</a>
                        </div>

                        <!-- 1. Kategori -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Kategori</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="category[]" value="shader" id="cat_shader" <?= in_array('shader', $_GET['category'] ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="cat_shader">Shader</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="category[]" value="particle" id="cat_particle" <?= in_array('particle', $_GET['category'] ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="cat_particle">Particle</label>
                            </div>
                        </div>

                        <!-- 2. Rentang Harga (Rp) -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Rentang Harga (Rp)</label>
                            <div class="d-flex align-items-center gap-1 mb-2">
                                <input type="number" name="min_price" class="form-control form-control-sm filter-range-input" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>">
                                <span class="text-muted">-</span>
                                <input type="number" name="max_price" class="form-control form-control-sm filter-range-input" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-radio" type="radio" name="price_filter_radio" value="free" id="price_free_radio" <?= (isset($_GET['price_filter_radio']) && $_GET['price_filter_radio'] === 'free') ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="price_free_radio">Gratis</label>
                            </div>
                        </div>

                        <!-- 3. Render Pipeline -->
                        <div class="filter-group mb-3">
                            <label class="filter-group-label d-block mb-2">Render Pipeline</label>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="pipeline[]" value="Built-in" id="pipe_builtin" <?= in_array('Built-in', $filters['pipeline'] ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="pipeline[]" value="URP" id="pipe_urp" <?= in_array('URP', $filters['pipeline'] ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="pipe_urp">URP</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input filter-checkbox" type="checkbox" name="pipeline[]" value="HDRP" id="pipe_hdrp" <?= in_array('HDRP', $filters['pipeline'] ?? []) ? 'checked' : '' ?>>
                                <label class="form-check-label filter-check-text" for="pipe_hdrp">HDRP</label>
                            </div>
                        </div>

                        <!-- 4. Versi Unity -->
                        <div class="filter-group mb-1">
                            <label class="filter-group-label d-block mb-2">Versi Unity</label>
                            <select name="unity_version" class="form-select form-select-sm filter-range-input">
                                <option value="Semua Versi" <?= ($filters['unity_version'] ?? '') === 'Semua Versi' ? 'selected' : '' ?>>Semua Versi</option>
                                <option value="2021" <?= ($filters['unity_version'] ?? '') === '2021' ? 'selected' : '' ?>>2021.3 LTS</option>
                                <option value="2022" <?= ($filters['unity_version'] ?? '') === '2022' ? 'selected' : '' ?>>2022.3 LTS</option>
                                <option value="2023" <?= ($filters['unity_version'] ?? '') === '2023' ? 'selected' : '' ?>>2023.x</option>
                            </select>
                        </div>

                        <!-- Tombol Terapkan -->
                        <button type="submit" class="btn btn-sm btn-hero-orange w-100 mt-2">Terapkan Filter</button>

                    </div>
                </div>

    <!-- Footer Putih Solid Full-Width Sesuai Figma -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <!-- Bagian Tengah: Judul & Deskripsi -->
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
            </div>

            <!-- Bagian Bawah: Copyright Kiri & Slogan Kanan -->
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
</body>

</html>