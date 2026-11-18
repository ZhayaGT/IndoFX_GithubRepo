<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// The controller passes this row only after checking the current user's entitlement.
$libraryAsset = is_array($libraryAsset ?? null) ? $libraryAsset : (is_array($asset ?? null) ? $asset : []);
$assetId = (string)($libraryAsset['public_id'] ?? $libraryAsset['asset_public_id'] ?? $libraryAsset['asset_id'] ?? $libraryAsset['id'] ?? '');
if ($assetId === '') {
    http_response_code(404);
    exit('Aset library tidak ditemukan.');
}
$assetTitle = (string)($libraryAsset['title'] ?? 'Aset IndoFX');
$assetCreator = (string)($libraryAsset['seller_name'] ?? $libraryAsset['creator'] ?? 'Studio Kreator');
$assetCategory = (string)($libraryAsset['category_name'] ?? $libraryAsset['category'] ?? 'ASET');
$assetUnity = (string)($libraryAsset['unity_version'] ?? 'Semua Versi');
$pipelineData = $libraryAsset['render_pipeline'] ?? $libraryAsset['pipelines'] ?? 'Any';
$assetPipeline = is_array($pipelineData) ? implode(', ', $pipelineData) : (string)$pipelineData;
$assetFormat = (string)($libraryAsset['file_format'] ?? '.unitypackage');
$assetArt = (string)($libraryAsset['art'] ?? 'castle');
$userKey = (string)($_SESSION['user_id'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($assetTitle) ?> - IndoFX</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        :root {
            --indofx-cream-bg: #FAF0E6;
            --indofx-text-main: #3C2A21;
            /* Cokelat Tua Figma */
            --indofx-text-muted: #7A685D;
            --indofx-border: #4A3B32;
            --indofx-orange: #ED6B1D;
            /* Oranye IndoFX */
        }

        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--indofx-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-detail-wrapper {
            background-color: var(--indofx-cream-bg, #FAF0E6);
        }

        /* Urutan mobile: pratinjau -> kartu unduh -> tab */
        .detail-layout {
            display: grid;
            gap: 24px;
            align-items: start;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            grid-template-areas:
                "preview side"
                "tabs    side";
        }

        .detail-preview {
            grid-area: preview;
            min-width: 0;
        }

        .detail-side {
            grid-area: side;
            min-width: 0;
        }

        .detail-tabs {
            grid-area: tabs;
            min-width: 0;
        }

        @media (max-width: 991.98px) {
            .detail-layout {
                grid-template-columns: 1fr;
                grid-template-areas:
                    "preview"
                    "side"
                    "tabs";
            }
        }

        .main-preview-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid var(--indofx-border);
            background-color: #2b2129;
            height: clamp(220px, 42vw, 420px);
        }

        .thumb-gallery-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 28px;
        }

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

        .thumb-card-item.active {
            border: 2px solid var(--indofx-orange);
        }

        .detail-nav-tabs {
            display: flex;
            align-items: center;
            gap: 28px;
            margin-bottom: 16px;
        }

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

        /* Kartu Kanan Khusus Unduh */
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

        .detail-creator-line {
            font-size: 0.88rem;
            color: var(--indofx-text-muted);
            margin-bottom: 24px;
        }

        .detail-creator-line a {
            color: var(--indofx-text-main) !important;
            font-weight: 800;
            text-decoration: none;
        }

        .specs-table-box {
            border-top: 1px solid #EBDCD1;
            margin-bottom: 26px;
        }

        .spec-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid #EBDCD1;
            font-size: 0.85rem;
        }

        .spec-item-row .label-col {
            color: var(--indofx-text-muted);
        }

        .spec-item-row .val-col {
            font-weight: 800;
            color: var(--indofx-text-main);
        }

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

        .btn-download-orange:hover {
            background-color: #D85A0E;
            transform: translateY(-1px);
        }

        /* ========================================================
           RINGKASAN ULASAN & PROGRES BAR BINTANG DINAMIS
           ======================================================== */
        .rating-summary-box {
            background-color: #fdf5ef;
            border: 1px solid #ebdcd1;
            border-radius: 8px;
        }

        .rating-big-score {
            font-size: 2.3rem;
            font-weight: 800;
            color: var(--indofx-text-main);
            line-height: 1;
        }

        .rating-out-of {
            font-size: 1rem;
            font-weight: 600;
            color: var(--indofx-text-muted);
        }

        .rating-stars-gold {
            color: var(--indofx-orange);
            font-size: 0.95rem;
        }

        .rating-bar-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        .rating-bar-label {
            width: 28px;
            color: var(--indofx-text-main);
            font-weight: 700;
        }

        .rating-bar-track {
            flex-grow: 1;
            height: 6px;
            background-color: #eeddd2;
            border-radius: 999px;
            overflow: hidden;
        }

        .rating-bar-fill {
            height: 100%;
            background-color: var(--indofx-orange);
            border-radius: 999px;
            transition: width 0.3s ease;
        }

        .rating-bar-num {
            width: 22px;
            text-align: right;
            color: var(--indofx-text-muted);
            font-weight: 600;
        }

        .badge-owner {
            background-color: #fef3eb;
            color: var(--indofx-orange);
            border: 1px solid #fed7aa;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .review-item-block {
            padding: 14px 0;
            border-bottom: 1px solid #efe0d5;
        }

        .review-item-block:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body>

    <svg class="d-none">
        <defs>
            <g id="art-forest">
                <rect width="700" height="420" fill="#1E2A28" />
                <ellipse cx="350" cy="240" rx="320" ry="80" fill="#4E6D66" opacity="0.45" />
                <polygon points="120,80 70,360 170,360" fill="#141E1C" />
                <polygon points="260,50 200,380 320,380" fill="#182421" />
                <polygon points="460,40 390,390 530,390" fill="#0E1615" />
                <polygon points="590,70 540,360 640,360" fill="#141E1C" />
                <ellipse cx="350" cy="290" rx="280" ry="40" fill="#88AFA6" opacity="0.3" />
                <circle cx="350" cy="180" r="12" fill="#E8F1EF" opacity="0.75" />
            </g>
            <g id="art-castle">
                <rect width="700" height="420" fill="#241E24" />
                <ellipse cx="350" cy="210" rx="300" ry="70" fill="#433742" opacity="0.5" />
                <polygon points="180,90 140,360 220,360" fill="#151115" />
                <polygon points="350,60 290,370 410,370" fill="#100D10" />
                <polygon points="520,80 480,360 560,360" fill="#151115" />
                <circle cx="350" cy="150" r="14" fill="#C4B5A5" opacity="0.8" />
            </g>
            <g id="art-water">
                <rect width="700" height="420" fill="#14232C" />
                <ellipse cx="350" cy="240" rx="320" ry="80" fill="#234657" opacity="0.5" />
                <polygon points="180,100 130,360 230,360" fill="#0C171E" />
                <polygon points="350,70 290,370 410,370" fill="#081015" />
                <polygon points="520,90 470,360 570,360" fill="#0C171E" />
                <circle cx="350" cy="160" r="14" fill="#64B5F6" opacity="0.75" />
            </g>
            <g id="art-fire">
                <rect width="700" height="420" fill="#2D1A14" />
                <ellipse cx="350" cy="240" rx="320" ry="80" fill="#572B1B" opacity="0.5" />
                <polygon points="180,100 130,360 230,360" fill="#190E0A" />
                <polygon points="350,70 290,370 410,370" fill="#110906" />
                <polygon points="520,90 470,360 570,360" fill="#190E0A" />
                <circle cx="350" cy="160" r="14" fill="#FF8A65" opacity="0.8" />
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
            <div class="detail-layout">

                <!-- Blok 1: Galeri Pratinjau -->
                <div class="detail-preview">
                    <div id="assetDetailCarousel" class="carousel slide main-preview-card mb-3" data-bs-ride="carousel">
                        <div class="carousel-inner h-100">
                            <div class="carousel-item active h-100">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="thumb-gallery-row">
                        <div class="thumb-card-item active">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                            </svg>
                        </div>
                        <div class="thumb-card-item">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                            </svg>
                        </div>
                        <div class="thumb-card-item">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                            </svg>
                        </div>
                        <div class="thumb-card-item">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Blok 2: Card Download -->
                <aside class="detail-side">
                    <div class="asset-library-card">
                        <span class="asset-category-tag d-block"><?= htmlspecialchars($assetCategory) ?></span>
                        <h2 class="asset-detail-title"><?= htmlspecialchars($assetTitle) ?></h2>

                        <div class="detail-creator-line">
                            Oleh <a href="/creator/profile?name=<?= htmlspecialchars(urlencode($assetCreator), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($assetCreator) ?></a>
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

                        <a class="btn-download-orange text-decoration-none text-center" href="/download?id=<?= rawurlencode($assetId) ?>">
                            <i class="bi bi-download"></i>
                            <span>Unduh</span>
                        </a>
                    </div>
                </aside>

                <!-- Blok 3: Tab Deskripsi / Changelog / Ulasan -->
                <div class="detail-tabs">
                    <div class="detail-nav-tabs">
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <div class="detail-content-box" id="tabContentArea">
                        <div id="tab-deskripsi" class="tab-pane-detail d-none">
                            <p class="mb-3">
                                Paket aset visual <strong><?= htmlspecialchars($assetTitle) ?></strong> dirancang khusus oleh <strong><?= htmlspecialchars($assetCreator) ?></strong> untuk mewujudkan sistem visual berkualitas tinggi dalam pipeline rendering <?= htmlspecialchars($assetPipeline) ?> di engine Unity[cite: 26].
                            </p>
                            <p class="mb-0">
                                Dilengkapi dengan kontrol parameter material, tekstur resolusi tinggi, dan pengoptimalan menyeluruh untuk performa game[cite: 26].
                            </p>
                        </div>

                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h6 class="fw-bold mb-2">Versi 1.2.0</h6>
                            <p class="text-muted small">Pembaruan performa dan dukungan Unity 2022 LTS[cite: 26].</p>
                        </div>

                        <!-- TAB ULASAN DINAMIS -->
                        <div id="tab-ulasan" class="tab-pane-detail active">
                            <div id="ratingSummaryWrapper" class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <!-- Sisi Kiri: Nilai Rata-Rata & Bintang -->
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score" id="ratingBigScore">0,0</span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="rating-stars-gold my-1" id="ratingSummaryStars">
                                            <!-- Diisi oleh JavaScript -->
                                        </div>
                                        <span class="d-block text-muted small" id="totalReviewsSummaryText">Dari 0 ulasan</span>
                                    </div>

                                    <!-- Sisi Kanan: Progres Bar 5 Bintang Hingga 1 Bintang -->
                                    <div class="col-12 col-sm-8 pe-sm-3">
                                        <div class="rating-bar-row">
                                            <span class="rating-bar-label">5 ★</span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" id="ratingBarFill_5" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-num" id="ratingBarNum_5">0</span>
                                        </div>
                                        <div class="rating-bar-row">
                                            <span class="rating-bar-label">4 ★</span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" id="ratingBarFill_4" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-num" id="ratingBarNum_4">0</span>
                                        </div>
                                        <div class="rating-bar-row">
                                            <span class="rating-bar-label">3 ★</span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" id="ratingBarFill_3" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-num" id="ratingBarNum_3">0</span>
                                        </div>
                                        <div class="rating-bar-row">
                                            <span class="rating-bar-label">2 ★</span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" id="ratingBarFill_2" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-num" id="ratingBarNum_2">0</span>
                                        </div>
                                        <div class="rating-bar-row">
                                            <span class="rating-bar-label">1 ★</span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" id="ratingBarFill_1" style="width: 0%;"></div>
                                            </div>
                                            <span class="rating-bar-num" id="ratingBarNum_1">0</span>
                                        </div>
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

                <!-- Blok 2: Card Download -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const currentAssetId = <?= json_encode((string)$assetId, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));
            if (evt && evt.currentTarget) evt.currentTarget.classList.add('active');
            document.getElementById(tabId)?.classList.remove('d-none');
        }
        async function loadReviews() {
            const listContainer = document.getElementById('reviewsListContainer');
            if (!listContainer) return;
            const response = await fetch('/api/reviews?asset_id=' + encodeURIComponent(currentAssetId), {headers: {'Accept': 'application/json'}});
            const payload = response.ok ? await response.json() : {data: []};
            const reviews = Array.isArray(payload.data) ? payload.data : [];
            document.getElementById('reviewsCountBadge').innerText = reviews.length + ' ulasan';
            listContainer.innerHTML = reviews.length ? reviews.map(review => '<div class="review-item-block"><strong>' + esc(review.user_name || 'Anonim') + '</strong><div class="mb-1">' + '★'.repeat(Math.max(0, Math.min(5, Number(review.rating) || 0))) + '</div><p class="mb-0 text-secondary">' + esc(review.comment || review.body || '') + '</p></div>').join('') : '<div class="text-center py-4 text-muted"><p class="mb-0 fw-semibold">Belum ada ulasan untuk aset ini.</p></div>';
        }
        document.addEventListener('DOMContentLoaded', loadReviews);
    </script>

</body>

</html>