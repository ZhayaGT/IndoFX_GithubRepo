<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$asset   = $asset ?? [];
$images  = $images ?? [];
$files   = $files ?? [];
$reviews = $reviews ?? [];

$assetId        = $asset['id'] ?? 0;
$assetTitle     = $asset['title'] ?? 'Aset IndoFX';
$assetCreator   = $asset['creator_name'] ?? 'Kreator IndoFX';
$sellerPublicId = $asset['seller_public_id'] ?? '';
$assetCategory  = strtoupper($asset['category_name'] ?? 'ASET');
$assetUnity     = $asset['unity_version'] ?? '-';
$assetPipeline  = $asset['render_pipeline'] ?? '-';
$assetDesc      = $asset['description'] ?? 'Belum ada deskripsi untuk aset ini.';

$latestFile  = $files[0] ?? null;
$assetFormat = $latestFile && !empty($latestFile['extension'])
    ? ('.' . ltrim($latestFile['extension'], '.'))
    : '.unitypackage';

$supabaseUrl     = $_ENV['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';

$resolvedImages = [];
foreach ($images as $img) {
    if (!empty($img)) {
        $resolvedImages[] = (strpos($img, 'http') === 0) ? $img : $storageBasePath . $img;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($assetTitle) ?> - IndoFX</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        :root {
            --indofx-cream-bg: #FAF0E6;
            --indofx-text-main: #3C2A21;
            --indofx-text-muted: #7A685D;
            --indofx-border: #4A3B32;
            --indofx-orange: #ED6B1D;
        }

        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--indofx-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-detail-wrapper { background-color: var(--indofx-cream-bg, #FAF0E6); }

        .main-preview-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid var(--indofx-border);
            background-color: #2b2129;
            height: 420px;
        }

        .thumb-gallery-row { display: flex; gap: 12px; margin-bottom: 28px; flex-wrap: wrap; }

        .thumb-card-item {
            width: 76px;
            height: 52px;
            border-radius: 6px;
            overflow: hidden;
            border: 1.5px solid var(--indofx-border);
            cursor: pointer;
            background-color: #2b2129;
            flex-shrink: 0;
        }

        .thumb-card-item.active { border: 2px solid var(--indofx-orange); }

        .detail-nav-tabs { display: flex; align-items: center; gap: 28px; margin-bottom: 16px; }

        .detail-nav-tabs .tab-link {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--indofx-text-muted);
            text-decoration: none;
            padding-bottom: 6px;
            border-bottom: 2px solid transparent;
            transition: all 0.15s ease;
        }

        .detail-nav-tabs .tab-link.active {
            color: var(--indofx-text-main);
            font-weight: 800;
            border-bottom: 2.5px solid var(--indofx-orange);
        }

        .detail-content-box {
            background-color: #ffffff;
            border-radius: 8px;
            border: 1.5px solid var(--indofx-border);
            font-size: 0.88rem;
            line-height: 1.65;
            color: var(--indofx-text-main);
            padding: 24px;
        }

        .asset-library-card {
            background-color: #ffffff;
            border: 1.5px solid var(--indofx-border);
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(60, 42, 33, 0.05);
        }

        .asset-category-tag {
            font-size: 0.76rem;
            font-weight: 800;
            color: var(--indofx-text-main);
            letter-spacing: 0.6px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .asset-detail-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--indofx-text-main) !important;
            line-height: 1.25;
            letter-spacing: -0.3px;
            margin-top: 2px;
            margin-bottom: 6px;
        }

        .detail-creator-line { font-size: 0.88rem; color: var(--indofx-text-muted); margin-bottom: 24px; }
        .detail-creator-line a { color: var(--indofx-text-main) !important; font-weight: 800; text-decoration: none; }

        .specs-table-box { border-top: 1px solid #EBDCD1; margin-bottom: 26px; }

        .spec-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #EBDCD1;
            font-size: 0.85rem;
        }

        .spec-item-row .label-col { color: var(--indofx-text-muted); }
        .spec-item-row .val-col { font-weight: 800; color: var(--indofx-text-main); }

        .btn-download-orange {
            background-color: var(--indofx-orange);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 12px 18px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.15s ease, transform 0.1s ease;
            box-shadow: 0 4px 12px rgba(237, 107, 29, 0.25);
        }

        .btn-download-orange:hover { background-color: #D85A0E; transform: translateY(-1px); }

        .rating-summary-box { background-color: #fdf5ef; border: 1px solid #ebdcd1; border-radius: 8px; }
        .rating-big-score { font-size: 2.3rem; font-weight: 800; color: var(--indofx-text-main); line-height: 1; }
        .rating-out-of { font-size: 1rem; font-weight: 600; color: var(--indofx-text-muted); }
        .rating-stars-gold { color: var(--indofx-orange); font-size: 0.95rem; }

        .rating-bar-row { display: flex; align-items: center; gap: 10px; font-size: 0.8rem; margin-bottom: 5px; }
        .rating-bar-label { width: 28px; color: var(--indofx-text-main); font-weight: 700; }
        .rating-bar-track { flex-grow: 1; height: 6px; background-color: #eeddd2; border-radius: 999px; overflow: hidden; }
        .rating-bar-fill { height: 100%; background-color: var(--indofx-orange); border-radius: 999px; transition: width 0.3s ease; }
        .rating-bar-num { width: 22px; text-align: right; color: var(--indofx-text-muted); font-weight: 600; }

        .badge-owner {
            background-color: #fef3eb;
            color: var(--indofx-orange);
            border: 1px solid #fed7aa;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .review-item-block { padding: 14px 0; border-bottom: 1px solid #efe0d5; }
        .review-item-block:last-child { border-bottom: none; }
    </style>
</head>

<body>

    <svg class="d-none">
        <defs>
            <g id="art-castle">
                <rect width="700" height="420" fill="#241E24" />
                <ellipse cx="350" cy="210" rx="300" ry="70" fill="#433742" opacity="0.5" />
                <polygon points="180,90 140,360 220,360" fill="#151115" />
                <polygon points="350,60 290,370 410,370" fill="#100D10" />
                <polygon points="520,80 480,360 560,360" fill="#151115" />
                <circle cx="350" cy="150" r="14" fill="#C4B5A5" opacity="0.8" />
            </g>
        </defs>
    </svg>

    <!-- Header Navbar -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
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

            <div class="search-box-header d-none d-md-flex align-items-center">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset...">
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- Konten Detail Unduh -->
    <main class="content-detail-wrapper py-4 flex-grow-1">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- Kolom Kiri: Galeri & Tab -->
                <div class="col-12 col-lg-8">
                    <div id="assetDetailCarousel" class="carousel slide main-preview-card mb-3" data-bs-ride="false">
                        <div class="carousel-inner h-100">
                            <?php if (!empty($resolvedImages)): ?>
                                <?php foreach ($resolvedImages as $idx => $imgUrl): ?>
                                    <div class="carousel-item h-100 <?= $idx === 0 ? 'active' : '' ?>">
                                        <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" class="w-100 h-100" style="object-fit: cover;" alt="Preview <?= $idx + 1 ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="carousel-item active h-100">
                                    <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                        <use href="#art-castle" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($resolvedImages)): ?>
                        <div class="thumb-gallery-row">
                            <?php foreach ($resolvedImages as $idx => $imgUrl): ?>
                                <div class="thumb-card-item <?= $idx === 0 ? 'active' : '' ?>" onclick="selectGallerySlide(<?= $idx ?>, this)">
                                    <img src="<?= htmlspecialchars($imgUrl, ENT_QUOTES, 'UTF-8') ?>" class="w-100 h-100" style="object-fit: cover;" alt="Thumb <?= $idx + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="detail-nav-tabs">
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <div class="detail-content-box" id="tabContentArea">
                        <div id="tab-deskripsi" class="tab-pane-detail d-none">
                            <p class="mb-0"><?= nl2br(htmlspecialchars($assetDesc, ENT_QUOTES, 'UTF-8')) ?></p>
                        </div>

                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <?php if (!empty($files)): ?>
                                <?php foreach ($files as $f): ?>
                                    <div class="mb-3 pb-2 border-bottom">
                                        <h6 class="fw-bold mb-1">Versi <?= htmlspecialchars($f['version'] ?? '1.0.0', ENT_QUOTES, 'UTF-8') ?></h6>
                                        <p class="text-muted small mb-0">
                                            <?= nl2br(htmlspecialchars($f['changelog'] ?? 'Rilis paket aset.', ENT_QUOTES, 'UTF-8')) ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted small mb-0">Belum ada catatan perubahan versi.</p>
                            <?php endif; ?>
                        </div>

                        <!-- TAB ULASAN DINAMIS -->
                        <div id="tab-ulasan" class="tab-pane-detail active">
                            <div id="ratingSummaryWrapper" class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score" id="ratingBigScore">0,0</span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="rating-stars-gold my-1" id="ratingSummaryStars"></div>
                                        <span class="d-block text-muted small" id="totalReviewsSummaryText">Dari 0 ulasan</span>
                                    </div>

                                    <div class="col-12 col-sm-8 pe-sm-3">
                                        <?php for ($s = 5; $s >= 1; $s--): ?>
                                            <div class="rating-bar-row">
                                                <span class="rating-bar-label"><?= $s ?> ★</span>
                                                <div class="rating-bar-track">
                                                    <div class="rating-bar-fill" id="ratingBarFill_<?= $s ?>" style="width: 0%;"></div>
                                                </div>
                                                <span class="rating-bar-num" id="ratingBarNum_<?= $s ?>">0</span>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-dark">Ulasan pembeli</h5>
                                <span class="text-muted small" id="reviewsCountBadge">0 ulasan</span>
                            </div>

                            <div id="reviewsListContainer"></div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Card Download -->
                <div class="col-12 col-lg-4">
                    <div class="asset-library-card">
                        <span class="asset-category-tag d-block"><?= htmlspecialchars($assetCategory) ?></span>
                        <h2 class="asset-detail-title"><?= htmlspecialchars($assetTitle) ?></h2>

                        <div class="detail-creator-line">
                            Oleh <a href="/creator/profile<?= $sellerPublicId ? '?id=' . urlencode($sellerPublicId) : '' ?>"><?= htmlspecialchars($assetCreator) ?></a>
                        </div>

                        <div class="specs-table-box">
                            <div class="spec-item-row">
                                <span class="label-col">Versi Unity</span>
                                <span class="val-col"><?= htmlspecialchars($assetUnity) ?></span>
                            </div>
                            <div class="spec-item-row">
                                <span class="label-col">Render Pipeline</span>
                                <span class="val-col"><?= htmlspecialchars($assetPipeline) ?></span>
                            </div>
                            <div class="spec-item-row">
                                <span class="label-col">Format File</span>
                                <span class="val-col"><?= htmlspecialchars($assetFormat) ?></span>
                            </div>
                        </div>

                        <button type="button" class="btn-download-orange" onclick="downloadFile()">
                            <i class="bi bi-download"></i>
                            <span>Unduh</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer Resmi IndoFX -->
    <?php
    $footerPartial = BASE_PATH . '/resources/views/partials/footer.php';
    if (file_exists($footerPartial)) {
        require $footerPartial;
    } else {
    ?>
        <footer class="footer-main">
            <div class="container-figma">
                <div class="footer-center-content">
                    <h5 class="footer-brand-title-center">IndoFX</h5>
                    <p class="footer-brand-desc-center">
                        Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
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
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const currentAssetId = '<?= htmlspecialchars((string)$assetId, ENT_QUOTES, 'UTF-8') ?>';
        const reviewsFromDB  = <?= json_encode($reviews ?? []) ?>;

        function selectGallerySlide(index, el) {
            const carouselEl = document.getElementById('assetDetailCarousel');
            if (carouselEl) {
                const carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl);
                carousel.to(index);
            }
            document.querySelectorAll('.thumb-card-item').forEach(t => t.classList.remove('active'));
            if (el) el.classList.add('active');
        }

        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));

            if (evt && evt.currentTarget) {
                evt.currentTarget.classList.add('active');
            }
            const target = document.getElementById(tabId);
            if (target) target.classList.remove('d-none');
        }

        function downloadFile() {
            window.location.href = '/library/download?id=' + encodeURIComponent(currentAssetId);
        }

        function loadReviews() {
            const listContainer = document.getElementById('reviewsListContainer');
            if (!listContainer) return;

            const reviews = reviewsFromDB;
            const countBadge = document.getElementById('reviewsCountBadge');
            const summaryBox = document.getElementById('ratingSummaryWrapper');
            const summaryText = document.getElementById('totalReviewsSummaryText');
            const total = reviews.length;

            if (countBadge) countBadge.innerText = total + ' ulasan';

            if (total === 0) {
                if (summaryBox) summaryBox.style.display = 'none';
                listContainer.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-square-text fs-2 d-block mb-1"></i>
                        <p class="mb-0 fw-semibold">Belum ada ulasan untuk aset ini.</p>
                    </div>
                `;
                return;
            }

            if (summaryBox) summaryBox.style.display = 'block';

            let sumRating = 0;
            const starCounts = { 5: 0, 4: 0, 3: 0, 2: 0, 1: 0 };

            reviews.forEach(r => {
                const star = Math.min(5, Math.max(1, parseInt(r.rating, 10) || 5));
                starCounts[star] = (starCounts[star] || 0) + 1;
                sumRating += star;
            });

            const avgRatingFloat = sumRating / total;
            const avgScoreFormatted = avgRatingFloat.toFixed(1).replace('.', ',');

            const bigScoreEl = document.getElementById('ratingBigScore');
            if (bigScoreEl) bigScoreEl.innerText = avgScoreFormatted;
            if (summaryText) summaryText.innerText = `Dari ${total} ulasan`;

            const starsSummaryContainer = document.getElementById('ratingSummaryStars');
            if (starsSummaryContainer) {
                let starsSummaryHtml = '';
                const roundedStar = Math.round(avgRatingFloat);
                for (let s = 1; s <= 5; s++) {
                    starsSummaryHtml += s <= roundedStar
                        ? '<i class="bi bi-star-fill me-1"></i>'
                        : '<i class="bi bi-star me-1" style="color: #eeddd2;"></i>';
                }
                starsSummaryContainer.innerHTML = starsSummaryHtml;
            }

            for (let s = 1; s <= 5; s++) {
                const count = starCounts[s] || 0;
                const percentage = Math.round((count / total) * 100);
                const fillEl = document.getElementById('ratingBarFill_' + s);
                const numEl  = document.getElementById('ratingBarNum_' + s);
                if (fillEl) fillEl.style.width = percentage + '%';
                if (numEl) numEl.innerText = count;
            }

            listContainer.innerHTML = reviews.map(r => {
                const rating = Math.min(5, Math.max(1, parseInt(r.rating, 10) || 5));
                let starIcons = '';
                for (let s = 1; s <= 5; s++) {
                    starIcons += s <= rating
                        ? '<i class="bi bi-star-fill text-warning me-1"></i>'
                        : '<i class="bi bi-star text-muted me-1"></i>';
                }

                return `
                    <div class="review-item-block">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <strong style="color: var(--indofx-text-main);">${r.user_name}</strong>
                                <span class="badge-owner">Pemilik aset</span>
                            </div>
                            <span class="text-muted small">${r.date}</span>
                        </div>
                        <div class="mb-1" style="font-size: 0.85rem;">${starIcons}</div>
                        ${r.title ? `<h6 class="fw-bold mb-1" style="color: var(--indofx-text-main); font-size: 0.95rem;">${r.title}</h6>` : ''}
                        <p class="mb-0 text-secondary" style="font-size: 0.88rem; line-height: 1.6;">${r.comment}</p>
                    </div>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', loadReviews);
    </script>
</body>

</html>