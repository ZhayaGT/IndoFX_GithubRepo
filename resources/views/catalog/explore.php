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

            <div class="search-box-header d-none d-md-flex align-items-center">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset VFX, shader, tekstur...">
            </div>

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
            <div class="row g-4 align-items-start">

                <!-- SISI KIRI: Card Filter Pencarian (Digeser sedikit ke kiri & ramping) -->
                <div class="col-12 col-lg-3 filter-col-left">
                    <div class="filter-search-card p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="filter-header-title">Filter Pencarian</span>
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
                                <input type="number"
                                    class="filter-range-input"
                                    id="minPrice"
                                    name="min_price"
                                    placeholder="Min"
                                    min="1"
                                    onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                                    oninput="if(this.value !== '' && Number(this.value) < 1) this.value = 1;">
                                <span class="text-muted">-</span>
                                <input type="number"
                                    class="filter-range-input"
                                    id="maxPrice"
                                    name="max_price"
                                    placeholder="Max"
                                    min="1"
                                    onkeydown="if(event.key === '-' || event.key === 'e' || event.key === '+') event.preventDefault();"
                                    oninput="if(this.value !== '' && Number(this.value) < 1) this.value = 1;">
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

                <!-- SISI KANAN: Grid 3 Kartu per Baris (Proporsi Awal Terjaga) -->
                <div class="col-12 col-lg-9">
                    <!-- Bar Jumlah Aset & Opsi Urutkan -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <span class="text-dark" style="font-size: 0.92rem;">
                            Menampilkan <strong data-catalog-count>0</strong> aset yang cocok
                        </span>

                        <div class="d-flex align-items-center gap-2">
                            <span class="small" style="color: #2b2623; font-weight: 500;">Urutkan:</span>
                            <select class="form-select form-select-sm sort-select-figma sort-select">
                                <option value="terbaru" selected>Terbaru</option>
                                <option value="populer">Terpopuler</option>
                                <option value="harga_rendah">Harga: Rendah ke Tinggi</option>
                                <option value="harga_tinggi">Harga: Tinggi ke Rendah</option>
                            </select>
                        </div>
                    </div>

                    <!-- Wadah Kartu Katalog (3 Kolom per Baris) -->
                    <div class="row g-3" data-catalog-list="explore"></div>

                    <!-- Pagination Dinamis -->
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-5 mb-3 explore-pagination" data-catalog-pagination></div>
                </div>

            </div>
        </div>
    </main>

    <footer class="footer-main">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
                <!-- Baris Kontak dan Email sesuai desain baru -->
                <div style="font-size: 0.85rem; color: #1a1a1a; margin-top: 10px;">
                    <strong style="font-weight: 800;">Kontak dan Email:</strong>
                    <span style="margin: 0 16px;">08123456xxxx</span>
                    <span>helpdesk@indofx.com</span>
                </div>
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
    <script src="/assets/js/catalog.js" defer></script>
</body>

</html>