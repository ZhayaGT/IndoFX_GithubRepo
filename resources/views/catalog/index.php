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
                        <img class="hero-image" src="/assets/img/hero.jpg"
                             alt="Ilustrasi pengembangan aset Unity CG dan VFX" loading="eager">
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
                    <a href="/catalog/explore?kategori=vfx-and-shaders" class="btn category-pill">VFX &amp; Shader</a>
                    <a href="/catalog/explore?kategori=environments-landscapes" class="btn category-pill">Environment</a>
                    <a href="/catalog/explore?gratis=1" class="btn category-pill">Gratis</a>
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

                <div class="row g-3">
                    <?php foreach ($latest as $item): ?>
                        <?php $colClass = 'col-12 col-sm-6 col-lg-3'; require BASE_PATH . '/resources/views/partials/asset-card.php'; ?>
                    <?php endforeach; ?>

                    <?php if ($latest === []): ?>
                        <p class="text-muted small mb-0">Belum ada aset terbit.</p>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Aset Gratis -->
            <section class="catalog-block mb-2">
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="catalog-heading mb-1">Aset Gratis Unggulan</h4>
                        <p class="catalog-subheading m-0">Mulai kembangkan prototype game Anda tanpa biaya</p>
                    </div>
                    <a href="/catalog/explore?gratis=1&amp;sort=populer" class="catalog-side-link">Selengkapnya</a>
                </div>

                <div class="row g-3">
                    <?php foreach ($freeAssets as $item): ?>
                        <?php $colClass = 'col-12 col-sm-6 col-lg-3'; require BASE_PATH . '/resources/views/partials/asset-card.php'; ?>
                    <?php endforeach; ?>

                    <?php if ($freeAssets === []): ?>
                        <p class="text-muted small mb-0">Belum ada aset gratis.</p>
                    <?php endif; ?>
                </div>
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
</body>

</html>