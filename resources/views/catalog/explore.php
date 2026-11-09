<?php
// Menangkap SUPABASE_URL dari .env menggunakan 3 metode untuk kompatibilitas server lokal/produksi
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? $_SERVER['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';

// Merakit kerangka URL storage (pastikan .env kamu berisi URL yang benar)
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';
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
        <!-- ... (SVG biarkan sama persis dengan yang ada sebelumnya) ... -->
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

    <!-- 2. Konten Eksplorasi: Layout Proporsional Filter Kiri & Grid Kanan -->
    <main class="py-4">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- SISI KIRI: Card Filter Pencarian (Digeser sedikit ke kiri & ramping) -->
                <div class="col-12 col-lg-3 filter-col-left">
                    <div class="filter-search-card p-3">
                        <form action="/catalog/explore" method="GET">

                            <!-- Jaga parameter pencarian teks agar tidak hilang saat memfilter -->
                            <?php if(isset($_GET['q'])): ?>
                                <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q']) ?>">
                            <?php endif; ?>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="filter-header-title">Filter Pencarian</span>
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
                                    <input type="number" name="min_price" class="form-control form-control-sm filter-range-input" placeholder="Min" value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>"
                                        min="1" onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                                        oninput="if(this.value !== '' && Number(this.value) < 1) this.value = 1;">
                                    <span class="text-muted">-</span>
                                    <input type="number" name="max_price" class="form-control form-control-sm filter-range-input" placeholder="Max" value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>"
                                        min="1" onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                                        oninput="if(this.value !== '' && Number(this.value) < 1) this.value = 1;">
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

                            <button type="submit" class="btn btn-sm btn-hero-orange w-100 mt-2">Terapkan Filter</button>
                        </form>
                    </div>
                </div>

                <!-- SISI KANAN: Grid 3 Kartu per Baris -->
                <div class="col-12 col-lg-9">
                    
                    <!-- Bar Jumlah Aset & Opsi Urutkan -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span class="text-dark" style="font-size: 0.92rem;">
                            Menampilkan <strong><?= $totalAssets ?></strong> aset yang cocok
                        </span>

                        <div class="d-flex align-items-center gap-2">
                            <span class="small" style="color: #2b2623; font-weight: 500;">Urutkan:</span>
                            <!-- Form Pengurutan terpisah agar onChange bisa langsung eksekusi GET request ke Controller -->
                            <form id="sortForm" action="/catalog/explore" method="GET">
                                <!-- Sertakan parameter filter sebelumnya agar tidak mereset filter saat kita urutkan data -->
                                <?php foreach($_GET as $key => $val): if($key !== 'sort'): ?>
                                    <?php if(is_array($val)): ?>
                                        <?php foreach($val as $v): ?>
                                            <input type="hidden" name="<?= $key ?>[]" value="<?= htmlspecialchars($v) ?>">
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($val) ?>">
                                    <?php endif; ?>
                                <?php endif; endforeach; ?>

                                <select name="sort" class="form-select form-select-sm sort-select-figma sort-select" onchange="document.getElementById('sortForm').submit()">
                                    <option value="terbaru" <?= (!isset($filters['sort']) || $filters['sort'] === 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                                    <option value="populer" <?= ($filters['sort'] ?? '') === 'populer' ? 'selected' : '' ?>>Terpopuler</option>
                                    <option value="harga_rendah" <?= ($filters['sort'] ?? '') === 'harga_rendah' ? 'selected' : '' ?>>Harga: Rendah ke Tinggi</option>
                                    <option value="harga_tinggi" <?= ($filters['sort'] ?? '') === 'harga_tinggi' ? 'selected' : '' ?>>Harga: Tinggi ke Rendah</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Wadah Kartu Katalog Dinamis (Looping PHP dari Database) -->
                    <div class="row g-3">
                        <?php foreach ($assets as $asset): ?>
                            <div class="col-12 col-sm-6 col-lg-4"> 
                                <div class="asset-card-figma h-100 d-flex flex-column">
                                    
                                    <!-- Thumbnail -->
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
                                            <!-- Tautan Penjual Dinamis Menuju Profil -->
                                            <a href="/creator/profile?id=<?= htmlspecialchars($asset['seller_public_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none text-muted small seller-link-hover d-block mb-1">
                                                <?= htmlspecialchars($asset['seller_name'] ?? 'Kreator', ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                            
                                            <a href="/asset/detail?id=<?= $asset['public_id'] ?>" class="text-decoration-none d-block">
                                                <h6 class="asset-name"><?= htmlspecialchars($asset['title'] ?? '') ?></h6>
                                            </a>
                                            
                                            <!-- Badge Render Pipeline Dinamis -->
                                            <div class="d-flex gap-1 mb-2 flex-wrap">
                                                <?php 
                                                $pipeline = $asset['render_pipeline'] ?? '';
                                                
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
                    
                    </div> 
                    <?php 
                        // Default nilai (Nantinya variabel ini dikirim dari CatalogController)
                        $currentPage = $page ?? (isset($_GET['page']) ? (int)$_GET['page'] : 1);
                        $totalPages = $totalPages ?? 3; // Contoh statis: pastikan Controller mengirimkan $totalPages
                    ?>

                    <?php if ($totalPages > 1): ?>
                        <div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-3 explore-pagination">
                            <?php
                                // Menyalin parameter filter saat ini agar tidak hilang saat pindah halaman
                                $queryParams = $_GET;
                                unset($queryParams['page']);
                                $queryString = http_build_query($queryParams);
                                $baseUrl = '/catalog/explore' . ($queryString ? '?' . $queryString . '&' : '?') . 'page=';
                            ?>

                            <!-- Tombol Sebelumnya -->
                            <?php if ($currentPage > 1): ?>
                                <a href="<?= htmlspecialchars($baseUrl . ($currentPage - 1)) ?>" class="page-nav-link text-dark">Sebelumnya</a>
                            <?php else: ?>
                                <span class="page-nav-link text-muted" style="text-decoration: none; cursor: default;">Sebelumnya</span>
                            <?php endif; ?>

                            <!-- Angka Halaman -->
                            <div class="d-flex gap-1">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="<?= htmlspecialchars($baseUrl . $i) ?>" class="page-num-link <?= $i == $currentPage ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>

                            <!-- Tombol Selanjutnya -->
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?= htmlspecialchars($baseUrl . ($currentPage + 1)) ?>" class="page-nav-link text-dark">Selanjutnya</a>
                            <?php else: ?>
                                <span class="page-nav-link text-muted" style="text-decoration: none; cursor: default;">Selanjutnya</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
            </div>
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