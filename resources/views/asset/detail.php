<?php
// Menangkap SUPABASE_URL dari .env untuk adaptasi gambar saat pindah ke database
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? $_SERVER['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';

// Fungsi pintar untuk menentukan apakah gambar dari lokal (dummy) atau Supabase (DB)
function getDetailImageUrl($path, $basePath) {
    if (empty($path)) {
        return "https://via.placeholder.com/700x420/2C3A47/FFFFFF?text=Gambar+Kosong";
    }
    // Jika path adalah URL utuh atau file lokal bawaan
    if (strpos($path, 'http') === 0 || strpos($path, '/assets/') === 0) {
        return $path;
    }
    // Jika path hanya nama file (dari database), gabungkan dengan Supabase
    return $basePath . $path;
}

// Menyiapkan array galeri (cegah error jika kosong)
$gallery = $detail['gallery'] ?? [];
if (empty($gallery)) $gallery = [''];

// Logika status gratis untuk penyesuaian tombol dinamis
$numericPrice = (float)($asset['price'] ?? 0);
$isGratis = ($numericPrice <= 0);
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

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

    <!-- 1. Header Navbar Oranye -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="navbar-figma-container">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold">IndoFX</span>
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

    <!-- 2. Main Content Detail Area -->
    <main class="content-detail-wrapper py-4">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- ==================== KOLOM KIRI (8 KOLOM) ==================== -->
                <div class="col-12 col-lg-8">

                    <!-- Carousel Slider Gambar Utama Dinamis -->
                    <div id="assetDetailCarousel" class="carousel slide main-preview-card mb-3" data-bs-ride="carousel" data-bs-interval="3500">
                        <div class="carousel-inner h-100">
                            <?php foreach ($gallery as $index => $img): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?> h-100">
                                    <img src="<?= htmlspecialchars(getDetailImageUrl($img, $storageBasePath)) ?>" class="d-block w-100 h-100" style="object-fit: cover;" alt="Preview <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 4 Thumbnail Galeri Dinamis -->
                    <div class="row g-2 mb-4 thumb-row" style="max-width: 480px;">
                        <?php foreach ($gallery as $index => $img): ?>
                            <div class="col-3">
                                <div class="thumb-card <?= $index === 0 ? 'active' : '' ?>" data-bs-target="#assetDetailCarousel" data-bs-slide-to="<?= $index ?>">
                                    <img src="<?= htmlspecialchars(getDetailImageUrl($img, $storageBasePath)) ?>" class="d-block w-100 h-100" style="object-fit: cover;" alt="Thumb <?= $index + 1 ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Tab Navigasi Konten -->
                    <div class="detail-nav-tabs d-flex align-items-center gap-4 mb-3">
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <!-- Kontainer Konten Tab Putih -->
                    <div class="detail-content-box p-4" id="tabContentArea">

                        <!-- TAB 1: DESKRIPSI DINAMIS -->
                        <div id="tab-deskripsi" class="tab-pane-detail active">
                            <?php if (!empty($detail['description'])): ?>
                                <?php foreach ($detail['description'] as $descParagraf): ?>
                                    <p class="mb-3"><?= nl2br(htmlspecialchars($descParagraf)) ?></p>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="mb-0 text-muted">Belum ada deskripsi untuk aset ini.</p>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 2: CHANGELOG DINAMIS -->
                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h5 class="fw-bold mb-4 changelog-main-title">Riwayat pembaruan</h5>

                            <?php if (!empty($detail['changelog'])): ?>
                                <?php 
                                $totalChangelog = count($detail['changelog']);
                                foreach ($detail['changelog'] as $idx => $log): 
                                ?>
                                    <div class="changelog-item">
                                        <div class="d-flex justify-content-between align-items-baseline mb-2">
                                            <h6 class="fw-bold mb-0 changelog-version-title">Versi <?= htmlspecialchars($log['version']) ?></h6>
                                            <span class="changelog-date"><?= htmlspecialchars($log['date']) ?></span>
                                        </div>
                                        <ul class="changelog-list mb-0">
                                            <?php foreach ($log['notes'] as $note): ?>
                                                <li><?= htmlspecialchars($note) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php if ($idx < $totalChangelog - 1): ?>
                                        <hr class="changelog-divider">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Belum ada riwayat pembaruan.</p>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 3: ULASAN DINAMIS -->
                        <div id="tab-ulasan" class="tab-pane-detail d-none">
                            <!-- Box Ringkasan Rating Atas -->
                            <div class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score"><?= number_format($reviewSummary['average'] ?? 0, 1, ',', '.') ?></span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="rating-stars my-1">
                                            <?php
                                            $avgRating = round($reviewSummary['average'] ?? 0);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $avgRating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                                            }
                                            ?>
                                        </div>
                                        <span class="rating-subtext d-block">Dari <?= $reviewSummary['total'] ?? 0 ?> ulasan</span>
                                    </div>

                                    <div class="col-12 col-sm-8 pe-sm-3">
                                        <?php for ($star = 5; $star >= 1; $star--): 
                                            $count = $reviewSummary['counts'][$star] ?? 0;
                                            $percent = ($reviewSummary['total'] ?? 0) > 0 ? ($count / ($reviewSummary['total'] ?? 1)) * 100 : 0;
                                        ?>
                                            <div class="rating-bar-row d-flex align-items-center gap-2 mb-1">
                                                <span class="rating-bar-label"><?= $star ?> ★</span>
                                                <div class="progress flex-grow-1 rating-progress">
                                                    <div class="progress-bar bg-orange-rating" style="width: <?= $percent ?>%;"></div>
                                                </div>
                                                <span class="rating-bar-count"><?= $count ?></span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Header List Ulasan -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 changelog-main-title">Ulasan pembeli</h6>
                                <span class="changelog-date"><?= $reviewSummary['total'] ?? 0 ?> ulasan</span>
                            </div>

                            <!-- List Ulasan Dinamis -->
                            <?php if (!empty($reviews)): ?>
                                <?php 
                                $totalReviews = count($reviews);
                                foreach ($reviews as $idx => $review): 
                                ?>
                                    <div class="review-item mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold reviewer-name"><?= htmlspecialchars($review['name'] ?? 'Anonim') ?></span>
                                                <span class="badge-owner">Pembeli terverifikasi</span>
                                            </div>
                                            <span class="changelog-date"><?= htmlspecialchars($review['date'] ?? '') ?></span>
                                        </div>
                                        <div class="rating-stars small mb-2">
                                            <?php
                                            $ratingScore = (int)($review['rating'] ?? 0);
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $ratingScore ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
                                            }
                                            ?>
                                        </div>
                                        <h6 class="fw-bold review-title mb-1"><?= htmlspecialchars($review['title'] ?? '') ?></h6>
                                        <p class="mb-0 review-desc">
                                            <?= nl2br(htmlspecialchars($review['body'] ?? '')) ?>
                                        </p>
                                    </div>
                                    <?php if ($idx < $totalReviews - 1): ?>
                                        <hr class="changelog-divider">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center mt-4">Belum ada ulasan untuk aset ini.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="asset-purchase-card p-4">
                        <span class="asset-category-tag d-block mb-1"><?= mb_strtoupper(htmlspecialchars($categoryLabel ?? 'KATEGORI')) ?></span>
                        <h2 class="asset-detail-title mb-1"><?= htmlspecialchars($asset['title'] ?? 'Judul Aset') ?></h2>
                        <p class="detail-creator mb-3">
                            Oleh <a href="/creator/profile?id=<?= htmlspecialchars($asset['seller_public_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="creator-link fw-bold">
                                    <?= htmlspecialchars($asset['seller_name'] ?? 'Studio Kreator', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                        </p>

                        <span class="price-label d-block mb-1">Harga Aset</span>
                        <h3 class="price-amount mb-4">
                            <?= ($isGratis) ? 'Gratis' : 'Rp' . number_format($numericPrice, 0, ',', '.') ?>
                        </h3>

                        <div class="specs-table mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Versi Unity</span>
                                <?php $unityVersion = $asset['unity_version'] ?? ($detail['unity_versions'] ?? 'Semua Versi'); ?>
                                <span class="spec-value"><?= htmlspecialchars($unityVersion) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Render Pipeline</span>
                                <?php 
                                // Mendukung output berbentuk array maupun string murni
                                $pipelineData = $asset['render_pipeline'] ?? ($detail['pipelines'] ?? ['Any']);
                                $pipelineText = is_array($pipelineData) ? implode(', ', $pipelineData) : $pipelineData;
                                ?>
                                <span class="spec-value"><?= htmlspecialchars($pipelineText) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="spec-label">Format File</span>
                                <span class="spec-value"><?= htmlspecialchars($detail['file_format'] ?? '.unitypackage') ?></span>
                            </div>
                        </div>

                        <!-- Tombol Aksi: Terdeteksi Otomatis Guest vs Login -->
                        <div id="actionButtonWrapper" class="d-grid gap-2">
                            <?php if ($isGratis): ?>
                                <?php if ($isLoggedIn): ?>
                                    <button type="button" id="btnClaimLibrary" class="btn btn-cart-secondary">
                                        Tambahkan ke Library
                                    </button>
                                <?php else: ?>
                                    <a href="/login" class="btn btn-cart-secondary text-decoration-none">
                                        <span class="link-masuk fw-bold">Masuk untuk menambahkan ke Library</span>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- URL Checkout disesuaikan untuk UUID public_id -->
                                <?php
                                $checkoutUrl = '/checkout?' . http_build_query(['asset_id' => $asset['public_id'] ?? '']);
                                ?>
                                <a href="<?= $isLoggedIn ? htmlspecialchars($checkoutUrl, ENT_QUOTES, 'UTF-8') : '/login' ?>" class="btn btn-buy-primary" id="btnBuyPrimary">
                                    Beli
                                </a>
                                <button type="button" class="btn btn-cart-secondary">
                                    Tambahkan ke Keranjang
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- ==================== AKHIR KOLOM KANAN ==================== -->

            </div>
        </div>
    </main>

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
            <div class="footer-bottom-bar flex-wrap gap-2">
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

            if (evt && evt.currentTarget) {
                 evt.currentTarget.classList.add('active');
            } else {
                 const targetLink = document.querySelector(`.detail-nav-tabs .tab-link[onclick*="${tabId}"]`);
                 if(targetLink) targetLink.classList.add('active');
            }
            
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
        }

        // 2. Sinkronisasi Border Aktif pada Thumbnail saat Carousel Bergeser
        document.addEventListener('DOMContentLoaded', function() {
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

            // Tangani tab dari URL hash (misal: /detail#ulasan)
            if (window.location.hash === '#tab-ulasan' || window.location.hash === '#ulasan') {
                switchDetailTab(null, 'tab-ulasan');
            }
        });
    </script>
</body>

</html>