<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Hologram Shader Helper - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Utama & CSS Detail -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/detail.css">
</head>

<body class="detail-page-body">

    <!-- SVG Definitions untuk Preview Galeri Interaktif -->
    <svg class="d-none">
        <defs>
            <!-- Slide 1: Isometric Game Village & Props -->
            <g id="preview-slide-1">
                <rect width="700" height="420" fill="#4E5D3E" />
                <pattern id="isoGrid" width="40" height="20" patternUnits="userSpaceOnUse">
                    <path d="M 0 10 L 20 0 L 40 10 L 20 20 Z" fill="none" stroke="#455336" stroke-width="0.8" />
                </pattern>
                <rect width="700" height="420" fill="url(#isoGrid)" />
                <polygon points="90,70 130,50 170,70 130,90" fill="#E8C39E" />
                <polygon points="90,70 130,90 130,135 90,115" fill="#B35446" />
                <polygon points="130,90 170,70 170,115 130,135" fill="#8C3B30" />
                <polygon points="190,70 230,50 270,70 230,90" fill="#E8C39E" />
                <polygon points="190,70 230,90 230,135 190,115" fill="#4B6584" />
                <polygon points="230,90 270,70 270,115 230,135" fill="#384C63" />
                <polygon points="290,65 320,50 350,65 320,80" fill="#A5B1C2" />
                <polygon points="290,65 320,80 320,130 290,115" fill="#778CA3" />
                <polygon points="320,80 350,65 350,115 320,130" fill="#4B6584" />
                <path d="M430,90 Q480,50 530,90 Q560,140 500,160 Q450,170 430,90 Z" fill="#2C3A2F" />
                <rect x="470" y="140" width="22" height="45" fill="#4A3525" />
                <polygon points="370,170 395,155 420,170 395,185" fill="#D35400" />
                <polygon points="370,170 395,185 395,205 370,190" fill="#A04000" />
                <polygon points="395,185 420,170 420,190 395,205" fill="#873600" />
                <circle cx="340" cy="270" r="16" fill="#00D2D3" opacity="0.9" />
                <rect x="336" y="246" width="8" height="12" fill="#E056FD" />
                <circle cx="190" cy="380" r="22" fill="#F1F2F6" />
                <rect x="180" y="392" width="20" height="15" fill="#F1F2F6" />
                <circle cx="182" cy="378" r="4.5" fill="#2F3542" />
                <circle cx="198" cy="378" r="4.5" fill="#2F3542" />
                <ellipse cx="500" cy="370" rx="36" ry="18" fill="#2F3542" />
                <path d="M464,370 Q460,410 500,420 Q540,410 536,370 Z" fill="#1E272C" />
                <ellipse cx="500" cy="370" rx="26" ry="11" fill="#70A1FF" opacity="0.8" />
            </g>

            <!-- Slide 2: Night Shader Preview -->
            <g id="preview-slide-2">
                <rect width="700" height="420" fill="#2C3A47" />
                <circle cx="580" cy="90" r="45" fill="#F8EFBA" opacity="0.9" />
                <polygon points="150,120 220,80 290,120 220,160" fill="#58B19F" />
                <polygon points="150,120 220,160 220,250 150,210" fill="#2C3A47" stroke="#58B19F" stroke-width="3" />
                <polygon points="220,160 290,120 290,210 220,250" fill="#182C61" stroke="#58B19F" stroke-width="3" />
                <circle cx="420" cy="240" r="55" fill="#00D2D3" opacity="0.35" />
                <circle cx="420" cy="240" r="30" fill="#00D2D3" opacity="0.85" />
            </g>

            <!-- Slide 3: Forest Environment -->
            <g id="preview-slide-3">
                <rect width="700" height="420" fill="#3B4B44" />
                <polygon points="150,60 90,260 210,260" fill="#2D3A34" />
                <polygon points="350,40 280,280 420,280" fill="#1C2621" />
                <polygon points="530,70 470,270 590,270" fill="#25302B" />
                <ellipse cx="350" cy="280" rx="300" ry="45" fill="#E4EEF5" opacity="0.35" />
            </g>

            <!-- Slide 4: Sunset Castle -->
            <g id="preview-slide-4">
                <rect width="700" height="420" fill="#D4A39B" />
                <circle cx="520" cy="140" r="65" fill="#FFFDF2" opacity="0.9" />
                <path d="M0 300 Q200 240 450 290 T700 280 L700 420 L0 420 Z" fill="#4A3B47" />
                <rect x="220" y="160" width="220" height="150" fill="#3B2E39" />
                <polygon points="210,160 260,80 310,160" fill="#2B2129" />
                <polygon points="350,160 400,65 450,160" fill="#2B2129" />
            </g>
        </defs>
    </svg>

    <!-- 1. Header Navbar Oranye -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <span class="brand-icon-box">
                    <i class="bi bi-layers-half"></i>
                </span>
                <span class="brand-text">IndoFX</span>
            </a>

            <div class="search-box-header d-none d-md-flex align-items-center">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset, VFX, shader, tekstur...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="/dashboard/add-asset" class="btn btn-nav-seller">Jadi Penjual</a>
                <a href="/auth/login" class="btn btn-nav-login">Masuk</a>
            </div>
        </div>
    </nav>

    <!-- 2. Main Content Detail Area -->
    <main class="content-detail-wrapper py-4">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- ==================== KOLOM KIRI (8 KOLOM) ==================== -->
                <div class="col-12 col-lg-8">

                    <!-- Carousel Slider Gambar Utama (Autoplay & Bisa Diklik) -->
                    <div id="assetDetailCarousel" class="carousel slide main-preview-card mb-3" data-bs-ride="carousel" data-bs-interval="3500">
                        <div class="carousel-inner h-100">
                            <div class="carousel-item active h-100">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#preview-slide-1" />
                                </svg>
                            </div>
                            <div class="carousel-item h-100">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#preview-slide-2" />
                                </svg>
                            </div>
                            <div class="carousel-item h-100">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#preview-slide-3" />
                                </svg>
                            </div>
                            <div class="carousel-item h-100">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#preview-slide-4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- 4 Thumbnail Galeri di Bawah Preview -->
                    <div class="row g-2 mb-4 thumb-row" style="max-width: 480px;">
                        <?php for ($k = 1; $k <= 4; $k++): ?>
                            <div class="col-3">
                                <div class="thumb-card <?= $k === 1 ? 'active' : '' ?>" data-bs-target="#assetDetailCarousel" data-bs-slide-to="<?= $k - 1 ?>">
                                    <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                        <use href="#preview-slide-<?= $k ?>" />
                                    </svg>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- Tab Navigasi Konten -->
                    <div class="detail-nav-tabs d-flex align-items-center gap-4 mb-3">
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <!-- Kontainer Konten Tab Putih -->
                    <div class="detail-content-box p-4" id="tabContentArea">

                        <!-- TAB 1: DESKRIPSI -->
                        <div id="tab-deskripsi" class="tab-pane-detail active">
                            <p class="mb-3">
                                Volumetric Rain &amp; Storm VFX dirancang khusus untuk mewujudkan sistem cuaca dramatis dalam pipeline rendering URP dan HDRP di Unity. Dilengkapi dengan kontrol intensitas angin, density partikel kabut, dan kustomisasi sambaran petir secara real-time.
                            </p>
                            <p class="mb-0">
                                Sangat cocok untuk game bertema horor, petualangan, maupun simulasi lingkungan Indonesia yang tropis dan kaya curah hujan.
                            </p>
                        </div>

                        <!-- TAB 2: CHANGELOG (RIWAYAT PEMBARUAN) -->
                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h5 class="fw-bold mb-4 changelog-main-title">Riwayat pembaruan</h5>

                            <div class="changelog-item">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="fw-bold mb-0 changelog-version-title">Versi 1.2.0</h6>
                                    <span class="changelog-date">15 September 2026</span>
                                </div>
                                <ul class="changelog-list mb-0">
                                    <li>Penambahan contoh scene untuk memudahkan eksplorasi parameter shader.</li>
                                    <li>Penyempurnaan dokumentasi pengaturan material dalam Bahasa Indonesia.</li>
                                </ul>
                            </div>

                            <hr class="changelog-divider">

                            <div class="changelog-item">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="fw-bold mb-0 changelog-version-title">Versi 1.1.0</h6>
                                    <span class="changelog-date">20 Agustus 2026</span>
                                </div>
                                <ul class="changelog-list mb-0">
                                    <li>Penyesuaian susunan parameter pada Inspector agar lebih mudah dipahami.</li>
                                    <li>Perbaikan referensi material pada contoh scene.</li>
                                </ul>
                            </div>

                            <hr class="changelog-divider">

                            <div class="changelog-item">
                                <div class="d-flex justify-content-between align-items-baseline mb-2">
                                    <h6 class="fw-bold mb-0 changelog-version-title">Versi 1.0.0</h6>
                                    <span class="changelog-date">1 Agustus 2026</span>
                                </div>
                                <ul class="changelog-list mb-0">
                                    <li>Contoh rilis awal paket aset dan scene demonstrasi.</li>
                                    <li>Penyertaan panduan instalasi dan penggunaan dasar.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- TAB 3: ULASAN PEMBELI -->
                        <div id="tab-ulasan" class="tab-pane-detail d-none">
                            <!-- Box Ringkasan Rating Atas -->
                            <div class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score">4,7</span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="rating-stars my-1">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <span class="rating-subtext d-block">Dari 3 ulasan contoh</span>
                                    </div>

                                    <div class="col-12 col-sm-8 pe-sm-3">
                                        <div class="rating-bar-row d-flex align-items-center gap-2 mb-1">
                                            <span class="rating-bar-label">5 ★</span>
                                            <div class="progress flex-grow-1 rating-progress">
                                                <div class="progress-bar bg-orange-rating" style="width: 66%;"></div>
                                            </div>
                                            <span class="rating-bar-count">2</span>
                                        </div>
                                        <div class="rating-bar-row d-flex align-items-center gap-2 mb-1">
                                            <span class="rating-bar-label">4 ★</span>
                                            <div class="progress flex-grow-1 rating-progress">
                                                <div class="progress-bar bg-orange-rating" style="width: 33%;"></div>
                                            </div>
                                            <span class="rating-bar-count">1</span>
                                        </div>
                                        <div class="rating-bar-row d-flex align-items-center gap-2 mb-1">
                                            <span class="rating-bar-label">3 ★</span>
                                            <div class="progress flex-grow-1 rating-progress">
                                                <div class="progress-bar bg-orange-rating" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-count">0</span>
                                        </div>
                                        <div class="rating-bar-row d-flex align-items-center gap-2 mb-1">
                                            <span class="rating-bar-label">2 ★</span>
                                            <div class="progress flex-grow-1 rating-progress">
                                                <div class="progress-bar bg-orange-rating" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-count">0</span>
                                        </div>
                                        <div class="rating-bar-row d-flex align-items-center gap-2">
                                            <span class="rating-bar-label">1 ★</span>
                                            <div class="progress flex-grow-1 rating-progress">
                                                <div class="progress-bar bg-orange-rating" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-count">0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Header List Ulasan -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 changelog-main-title">Ulasan pembeli</h6>
                                <span class="changelog-date">3 ulasan</span>
                            </div>

                            <!-- Review 1 -->
                            <div class="review-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold reviewer-name">Dimas Pratama</span>
                                        <span class="badge-owner">Pemilik aset</span>
                                    </div>
                                    <span class="changelog-date">18 September 2026</span>
                                </div>
                                <div class="rating-stars small mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <h6 class="fw-bold review-title mb-1">Mudah dipelajari untuk prototipe</h6>
                                <p class="mb-0 review-desc">
                                    Contoh scene membantu saya memahami pengaturan material. Panduan Bahasa Indonesia juga memudahkan proses mencoba aset.
                                </p>
                            </div>

                            <hr class="changelog-divider">

                            <!-- Review 2 -->
                            <div class="review-item mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold reviewer-name">Nadia Putri</span>
                                        <span class="badge-owner">Pemilik aset</span>
                                    </div>
                                    <span class="changelog-date">12 September 2026</span>
                                </div>
                                <div class="rating-stars small mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star"></i>
                                </div>
                                <h6 class="fw-bold review-title mb-1">Membantu alur kerja proyek</h6>
                                <p class="mb-0 review-desc">
                                    Parameter cukup mudah diikuti. Akan lebih membantu jika ada lebih banyak contoh penggunaan untuk berbagai suasana scene.
                                </p>
                            </div>

                            <hr class="changelog-divider">

                            <!-- Review 3 -->
                            <div class="review-item">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold reviewer-name">Raka Saputra</span>
                                        <span class="badge-owner">Pemilik aset</span>
                                    </div>
                                    <span class="changelog-date">8 September 2026</span>
                                </div>
                                <div class="rating-stars small mb-2">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <h6 class="fw-bold review-title mb-1">Contoh penggunaan yang jelas</h6>
                                <p class="mb-0 review-desc">
                                    Struktur paket mudah dipahami dan nyaman untuk eksplorasi. Saya bisa mulai mencoba tanpa banyak penyesuaian.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- ==================== AKHIR KOLOM KIRI ==================== -->

                <!-- ==================== KOLOM KANAN (4 KOLOM) ==================== -->
                <div class="col-12 col-lg-4">
                    <div class="asset-purchase-card p-4">
                        <span class="asset-category-tag d-block mb-1">VFX PARTIKEL</span>
                        <h2 class="asset-detail-title mb-1">Basic Hologram Shader Helper</h2>
                        <p class="detail-creator mb-3">
                            Oleh <a href="/creator/profile" class="creator-link fw-bold">Arga Studio</a>
                        </p>

                        <span class="price-label d-block mb-1">Harga Aset</span>
                        <h3 class="price-amount mb-4">Rp150.000</h3>

                        <div class="specs-table mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Versi Unity</span>
                                <span class="spec-value">2021.3+ / 2022.3 LTS</span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Render Pipeline</span>
                                <span class="spec-value">URP, HDRP</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="spec-label">Format File</span>
                                <span class="spec-value">.unitypackage</span>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="/checkout?asset_id=1" class="btn btn-buy-primary">Beli</a>
                            <button type="button" class="btn btn-cart-secondary">Tambahkan ke Keranjang</button>
                        </div>
                    </div>
                </div>
                <!-- ==================== AKHIR KOLOM KANAN ==================== -->

            </div>
        </div>
    </main>

    <!-- 3. Footer Putih -->
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // 1. Fungsi Berpindah Tab (Deskripsi, Changelog, Ulasan)
        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));

            evt.currentTarget.classList.add('active');
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
        }

        // 2. Sinkronisasi Border Aktif pada Thumbnail saat Carousel Bergeser
        const detailCarousel = document.getElementById('assetDetailCarousel');
        const thumbCards = document.querySelectorAll('.thumb-row .thumb-card');

        if (detailCarousel) {
            detailCarousel.addEventListener('slide.bs.carousel', function(event) {
                thumbCards.forEach(card => card.classList.remove('active'));
                if (thumbCards[event.to]) {
                    thumbCards[event.to].classList.add('active');
                }
            });
        }
    </script>
</body>

</html>