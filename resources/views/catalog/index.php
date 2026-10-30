<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IndoFX - Marketplace Aset Unity CG & VFX</title>

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Terpisah IndoFX (Dipanggil Langsung dari public) -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <!-- SVG Helper untuk Gambar Kartu Aset -->
    <svg class="d-none">
        <defs>
            <g id="castle-asset-thumb">
                <rect width="400" height="200" fill="#3D3B54" />
                <path d="M 0 140 Q 100 120 200 140 T 400 140 L 400 200 L 0 200 Z" fill="#2A283C" />
                <rect x="130" y="80" width="140" height="80" fill="#1C1B2A" />
                <rect x="150" y="50" width="30" height="110" fill="#151421" />
                <polygon points="150,50 165,20 180,50" fill="#ED6B1D" />
                <rect x="220" y="50" width="30" height="110" fill="#151421" />
                <polygon points="220,50 235,20 250,50" fill="#ED6B1D" />
                <circle cx="320" cy="60" r="30" fill="#FFEAA7" opacity="0.85" />
            </g>
        </defs>
    </svg>

    <!-- 1. Header Navbar Oranye -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container d-flex justify-content-between align-items-center">

<<<<<<< Updated upstream
            <!-- Logo Brand -->
            <a class="navbar-brand m-0 fw-bold text-white fs-4" href="/">IndoFX</a>

            <!-- Search Bar Tengah -->
            <div class="search-box-header d-none d-md-block">
=======
            <div class="search-box-header d-flex align-items-center flex-grow-1 mx-2 mx-md-4">
>>>>>>> Stashed changes
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header search-input" placeholder="Cari aset VFX, shader, tekstur...">
            </div>

            <!-- Tombol Aksi Kanan -->
            <div class="d-flex align-items-center gap-2">
<<<<<<< Updated upstream
                <a href="/dashboard/add-asset" class="btn btn-nav-light">Unggah Aset</a>
                <a href="/auth/login" class="btn btn-nav-orange-light">Login</a>
=======
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                } else {
                    echo '<a href="/login" class="btn btn-nav-login">Masuk</a>';
                }
                ?>
>>>>>>> Stashed changes
            </div>

        </div>
    </nav>

    <!-- 2. Hero Section Putih -->
    <section class="hero-section py-5" id="beranda">
        <div class="container">
            <div class="row align-items-center g-4">

                <!-- Teks Hero Left -->
                <div class="col-lg-6">
                    <h1 class="hero-title mb-3 fw-extrabold">
                        Marketplace Aset Unity CG & VFX untuk Developer Indonesia
                    </h1>
                    <p class="hero-desc mb-4 text-secondary">
                        Percepat pengembangan game Anda dengan aset visual lokal berkualitas tinggi. Dukungan pembayaran lokal (QRIS, GoPay, Transfer Bank) serta dokumentasi lengkap dalam Bahasa Indonesia.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#katalog" class="btn btn-hero-orange">Jelajahi Katalog</a>
                        <a href="/dashboard/add-asset" class="btn btn-hero-cream">Unggah Aset</a>
                    </div>
                </div>

                <!-- Gambar Preview Right -->
                <div class="col-lg-6">
                    <div class="hero-image-container">
                        <svg class="img-fluid hero-img-shadow w-100" viewBox="0 0 600 320" xmlns="http://www.w3.org/2000/svg">
                            <rect width="600" height="320" fill="#3D3B54" />
                            <path d="M 0 240 Q 150 200 300 240 T 600 320 L 600 320 L 0 320 Z" fill="#2A283C" />
                            <rect x="200" y="120" width="200" height="120" fill="#1C1B2A" />
                            <rect x="230" y="80" width="40" height="160" fill="#151421" />
                            <polygon points="230,80 250,40 270,80" fill="#ED6B1D" />
                            <rect x="330" y="80" width="40" height="160" fill="#151421" />
                            <polygon points="330,80 350,40 370,80" fill="#ED6B1D" />
                            <circle cx="480" cy="90" r="45" fill="#FFEAA7" opacity="0.9" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 3. Content Body (Warm Cream) -->
    <div class="content-body-wrapper py-5">
        <div class="container">

            <!-- Section Kategori -->
            <section class="mb-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-dark mb-1">Telusuri Berdasarkan Kategori</h4>
                    <p class="text-muted small m-0">Temukan spesifikasi aset yang tepat untuk game engine Anda</p>
                </div>

                <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 g-3 justify-content-center">
                    <div class="col">
                        <a href="javascript:void(0)" class="category-card" data-category="Shader">
                            <div class="category-icon-box mb-2">
                                <i class="bi bi-code-slash fs-4"></i>
                            </div>
                            <span class="d-block fw-bold small text-dark">Shader</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="javascript:void(0)" class="category-card" data-category="Material">
                            <div class="category-icon-box mb-2">
                                <i class="bi bi-stack fs-4"></i>
                            </div>
                            <span class="d-block fw-bold small text-dark">Material</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="javascript:void(0)" class="category-card" data-category="VFX">
                            <div class="category-icon-box mb-2">
                                <i class="bi bi-activity fs-4"></i>
                            </div>
                            <span class="d-block fw-bold small text-dark">VFX Partikel</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="javascript:void(0)" class="category-card" data-category="Model 3D">
                            <div class="category-icon-box mb-2">
                                <i class="bi bi-box fs-4"></i>
                            </div>
                            <span class="d-block fw-bold small text-dark">Model 3D</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="javascript:void(0)" class="category-card" data-category="Tekstur">
                            <div class="category-icon-box mb-2">
                                <i class="bi bi-image fs-4"></i>
                            </div>
                            <span class="d-block fw-bold small text-dark">Tekstur</span>
                        </a>
                    </div>
                </div>
            </section>

<<<<<<< Updated upstream
            <!-- Section Aset CG & VFX Terbaru -->
            <section class="mb-5" id="katalog">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Aset CG & VFX Terbaru</h5>
                        <p class="text-muted small m-0">Aset terbaru yang diunggah oleh kreator game berbakat Indonesia</p>
=======
            <!-- Aset Terbaru -->
            <section class="catalog-block mb-5" id="katalog">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="catalog-heading mb-1">Aset CG &amp; VFX Terbaru</h4>
                        <p class="catalog-subheading m-0">Aset terbaru yang diunggah oleh kreator game berbakat Indonesia</p>
>>>>>>> Stashed changes
                    </div>
                    <a href="#katalog" class="text-dark small fw-bold text-decoration-underline">Lihat Semua terbaru</a>
                </div>

<<<<<<< Updated upstream
                <div class="row g-3">
                    <!-- Card 1 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Arga Studio</span>
                                    <h6 class="asset-title" title="Volumetric Rain & Storm URP">Volumetric Rain & Storm URP</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                        <span class="badge-pipeline">HDRP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">Rp150.000</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Nusantara Digital</span>
                                    <h6 class="asset-title" title="Stylized Toon Water Shader">Stylized Toon Water Shader</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                        <span class="badge-pipeline">Built-in</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">Rp75.000</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Bayu Pratama</span>
                                    <h6 class="asset-title" title="Explosion VFX Pack Vol. 1">Explosion VFX Pack Vol. 1</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">HDRP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">Rp200.000</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card 4 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Citra CG</span>
                                    <h6 class="asset-title" title="Lowpoly Indonesian Village Pack">Lowpoly Indonesian Village Pack</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                        <span class="badge-pipeline">HDRP</span>
                                        <span class="badge-pipeline">Built-in</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">Rp350.000</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <!-- Section Aset Gratis Unggulan -->
            <section class="mb-4">
                <div class="mb-3">
                    <h5 class="fw-bold text-dark mb-1">Aset Gratis Unggulan</h5>
                    <p class="text-muted small m-0">Mulai kembangkan prototype game Anda tanpa biaya</p>
                </div>

                <div class="row g-3">
                    <!-- Card Gratis 1 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">VFX Kampoeng</span>
                                    <h6 class="asset-title" title="Stylized Fire Starter Kit">Stylized Fire Starter Kit</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">GRATIS</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Gratis 2 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Bumi Tekstur</span>
                                    <h6 class="asset-title" title="Indonesian Road Texture Pack">Indonesian Road Texture Pack</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">Built-in</span>
                                        <span class="badge-pipeline">URP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">GRATIS</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Gratis 3 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Andi Dev</span>
                                    <h6 class="asset-title" title="Simple UI SFX & VFX Overlay">Simple UI SFX & VFX Overlay</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                        <span class="badge-pipeline">HDRP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">GRATIS</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Gratis 4 -->
                    <div class="col-6 col-md-4 col-lg-3 asset-item-col">
                        <a href="/asset/detail" class="text-decoration-none d-block">
                            <div class="asset-card">
                                <div class="asset-preview-box">
                                    <svg width="100%" height="100%" viewBox="0 0 400 200">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </div>
                                <div class="asset-card-body">
                                    <span class="asset-author">Bayu Pratama</span>
                                    <h6 class="asset-title" title="Basic Hologram Shader Helper">Basic Hologram Shader Helper</h6>
                                    <div class="asset-tags d-flex gap-1">
                                        <span class="badge-pipeline">URP</span>
                                    </div>
                                    <div class="asset-card-footer">
                                        <span class="asset-price">GRATIS</span>
                                        <span class="btn-download-icon"><i class="bi bi-download"></i></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
=======
                <!-- Kontainer kartu otomatis diisi oleh catalog.js -->
                <div class="row g-3" data-catalog-list="latest"></div>
            </section>

            <!-- Aset Gratis Unggulan -->
            <section class="catalog-block mb-4">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="catalog-heading mb-1">Aset Gratis Unggulan</h4>
                        <p class="catalog-subheading m-0">Mulai kembangkan prototype game Anda tanpa biaya</p>
                    </div>
                    <a href="/catalog/explore?gratis=1&amp;sort=populer" class="catalog-side-link">Selengkapnya</a>
                </div>

                <!-- Kontainer kartu otomatis diisi oleh catalog.js -->
                <div class="row g-3" data-catalog-list="free"></div>
>>>>>>> Stashed changes
            </section>

        </div>
    </div>

    <!-- 4. Footer Putih -->
    <footer class="footer-main" id="tentang">
<<<<<<< Updated upstream
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-12 col-lg-6">
                    <h5 class="fw-bold text-dark mb-2">IndoFX</h5>
                    <p class="text-muted footer-brand-desc">
                        Portal distribusi dan marketplace aset CG & VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold text-dark mb-3 footer-section-title">Katalog</h6>
                    <a href="#katalog" class="footer-link">Shader</a>
                    <a href="#katalog" class="footer-link">VFX Partikel</a>
                    <a href="#katalog" class="footer-link">Model 3D</a>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold text-dark mb-3 footer-section-title">Dukungan</h6>
                    <a href="#" class="footer-link">Dokumentasi</a>
                    <a href="#" class="footer-link">Hubungi Kami</a>
                    <a href="#" class="footer-link">Syarat & Ketentuan</a>
=======
        <div class="container-figma">
            <div class="row g-4 justify-content-between mb-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <h5 class="footer-brand-title mb-2">IndoFX</h5>
                    <p class="footer-brand-desc m-0">
                        Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
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
                            <a href="#" class="footer-link">Syarat &amp; Ketentuan</a>
                        </div>
                    </div>
>>>>>>> Stashed changes
                </div>
            </div>
            <div class="border-top pt-3 text-muted d-flex justify-content-between align-items-center flex-wrap gap-2 footer-copyright">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/main.js"></script>
</body>

</html>