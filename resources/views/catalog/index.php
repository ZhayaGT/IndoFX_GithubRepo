<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndoFX - Marketplace Aset Unity CG & VFX</title>

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

    <!-- SVG Definitions untuk Ilustrasi -->
    <svg class="d-none">
        <defs>
            <g id="castle-sunset-art">
                <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#D4A39B" />
                    <stop offset="50%" stop-color="#E8C5B0" />
                    <stop offset="100%" stop-color="#F7D69D" />
                </linearGradient>
                <rect width="400" height="220" fill="url(#skyGrad)" />
                <circle cx="310" cy="85" r="42" fill="#FFFDF2" opacity="0.95" />
                <circle cx="310" cy="85" r="65" fill="#FFF7D6" opacity="0.4" />
                <ellipse cx="90" cy="50" rx="65" ry="18" fill="#B88E8D" opacity="0.35" />
                <ellipse cx="220" cy="35" rx="80" ry="20" fill="#B88E8D" opacity="0.25" />
                <path d="M0 165 Q120 135 250 160 T400 155 L400 220 L0 220 Z" fill="#6E575F" />
                <rect x="85" y="105" width="185" height="90" fill="#4A3B47" />
                <rect x="105" y="70" width="32" height="125" fill="#3B2E39" />
                <polygon points="100,70 121,32 142,70" fill="#2B2129" />
                <rect x="165" y="55" width="38" height="140" fill="#3B2E39" />
                <polygon points="160,55 184,15 208,55" fill="#2B2129" />
                <rect x="235" y="65" width="36" height="130" fill="#59484F" />
                <polygon points="230,65 253,25 276,65" fill="#3B2E39" />
                <rect x="179" y="85" width="8" height="14" rx="4" fill="#FFAE42" />
                <rect x="117" y="95" width="6" height="12" rx="3" fill="#FFAE42" />
                <rect x="249" y="90" width="7" height="12" rx="3" fill="#FFAE42" />
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
                <polygon points="355,50 330,160 380,160" fill="#1C2621" />
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

<<<<<<< Updated upstream
            <?php require BASE_PATH . '/resources/views/partials/navbar-search.php'; ?>
=======
            <div class="search-box-header d-flex align-items-center flex-grow-1 mx-2 mx-md-4">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset...">
            </div>
>>>>>>> Stashed changes

            <div class="d-flex align-items-center gap-2">
                <?php require BASE_PATH . '/resources/views/partials/navbar-auth.php'; ?>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section Putih -->
    <section class="hero-section" id="beranda">
        <div class="container-figma">
            <div class="row align-items-center g-4">

                <div class="col-lg-6 pe-lg-4">
                    <h1 class="hero-title mb-3">
                        <span class="text-nowrap">Marketplace Aset Unity CG &amp;</span><br>
                        VFX untuk Developer<br>
                        Indonesia
                    </h1>
                    <p class="hero-desc mb-4">
                        Percepat pengembangan game Anda dengan aset visual lokal berkualitas tinggi. Dukungan pembayaran lokal (QRIS, GoPay, Transfer Bank) serta dokumentasi lengkap dalam Bahasa Indonesia.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#katalog" class="btn btn-hero-orange">Jelajahi Katalog</a>
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="/login" class="btn btn-hero-cream">Masuk</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="hero-image-wrapper">
                        <svg class="w-100 h-100 d-block" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                            <use href="#castle-sunset-art" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. Content Body (Warm Cream) -->
    <main class="content-body-wrapper">
        <div class="container-figma">

            <!-- Kategori -->
            <section class="category-section text-center">
                <h3 class="section-heading-center mb-1">Telusuri Berdasarkan Kategori</h3>
                <p class="section-sub-center mb-4">Temukan spesifikasi aset yang tepat untuk game engine Anda</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="javascript:void(0)" class="btn category-pill">Shader</a>
                    <a href="javascript:void(0)" class="btn category-pill">Particle</a>
                    <a href="javascript:void(0)" class="btn category-pill">Gratis</a>
                </div>
            </section>

            <!-- Aset Terbaru -->
            <section class="catalog-block" id="katalog">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="catalog-heading mb-1">Aset CG & VFX Terbaru</h4>
                        <p class="catalog-subheading m-0">Aset terbaru yang diunggah oleh kreator game berbakat Indonesia</p>
                    </div>
                    <a href="/catalog/explore?sort=terbaru" class="catalog-side-link">Selengkapnya</a>
                </div>

<<<<<<< Updated upstream
                <div class="row g-3" data-catalog-list="latest"></div>
=======
                <div class="row g-3">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="asset-card-figma h-100 d-flex flex-column">
                                <!-- Thumbnail mengarah ke Detail Aset -->
                                <a href="/asset/detail" class="asset-thumb-wrapper d-block">
                                    <svg width="100%" height="175" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                                        <use href="#castle-sunset-art" />
                                    </svg>
                                </a>
                                <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <!-- Nama Studio mengarah ke Profil Kreator -->
                                        <a href="/creator/profile" class="asset-studio text-decoration-none d-inline-block">Arga Studio</a>
                                        <!-- Judul mengarah ke Detail Aset -->
                                        <a href="/asset/detail" class="text-decoration-none d-block">
                                            <h6 class="asset-name">Volumetric Rain & Storm URP</h6>
                                        </a>
                                        <div class="d-flex gap-1 mb-2">
                                            <span class="badge-pipeline badge-urp">URP</span>
                                            <span class="badge-pipeline badge-hdrp">HDRP</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-1">
                                        <span class="asset-price">Rp150.000</span>
                                        <i class="bi bi-download asset-dl-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
>>>>>>> Stashed changes
            </section>

            <!-- Aset Gratis Unggulan -->
            <section class="catalog-block mb-2">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="catalog-heading mb-1">Aset Gratis Unggulan</h4>
                        <p class="catalog-subheading m-0">Mulai kembangkan prototype game Anda tanpa biaya</p>
                    </div>
                    <a href="/catalog/explore?gratis=1&amp;sort=populer" class="catalog-side-link">Selengkapnya</a>
                </div>

<<<<<<< Updated upstream
                <div class="row g-3" data-catalog-list="free"></div>
=======
                <div class="row g-3">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="asset-card-figma h-100 d-flex flex-column">
                                <!-- Thumbnail mengarah ke Detail Aset -->
                                <a href="/asset/detail" class="asset-thumb-wrapper d-block">
                                    <svg width="100%" height="175" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                                        <use href="#forest-mist-art" />
                                    </svg>
                                </a>
                                <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <!-- Nama Studio mengarah ke Profil Kreator -->
                                        <a href="/creator/profile" class="asset-studio text-decoration-none d-inline-block">Arga Studio</a>
                                        <!-- Judul mengarah ke Detail Aset -->
                                        <a href="/asset/detail" class="text-decoration-none d-block">
                                            <h6 class="asset-name">Volumetric Rain & Storm URP</h6>
                                        </a>
                                        <div class="d-flex gap-1 mb-2">
                                            <span class="badge-pipeline badge-urp">URP</span>
                                            <span class="badge-pipeline badge-builtin">Built-In</span>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-1">
                                        <span class="asset-price">Gratis</span>
                                        <i class="bi bi-download asset-dl-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
>>>>>>> Stashed changes
            </section>

        </div>
    </main>

    <!-- 4. Footer -->
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
                            <a href="#katalog" class="footer-link">Shader</a>
                            <a href="#katalog" class="footer-link">VFX Partikel</a>
                            <a href="#katalog" class="footer-link">Model 3D</a>
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
    <script src="/assets/js/catalog.js" defer></script>
</body>

</html>