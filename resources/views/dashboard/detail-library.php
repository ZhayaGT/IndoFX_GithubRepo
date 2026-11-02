<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// 1. Database Katalog IndoFX (Sebagai Referensi Spesifikasi)
$catalogDB = [
    '1' => [
        'title' => 'Volumetric Rain & Storm URP',
        'creator' => 'Arga Studio',
        'category' => 'VFX PARTIKEL',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP, Built-In',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    '2' => [
        'title' => 'Stylized Toon Water Shader',
        'creator' => 'Nusantara Digital',
        'category' => 'SHADER',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, Built-In',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    '3' => [
        'title' => 'Explosion VFX Pack Vol. 1',
        'creator' => 'Bayu Pratama',
        'category' => 'VFX PARTIKEL',
        'unity_version' => '2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'fire'
    ],
    '4' => [
        'title' => 'Lowpoly Indonesian Village Pack',
        'creator' => 'Citra CG',
        'category' => 'MODEL 3D',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP, Built-In',
        'format' => '.fbx, .blend, .unitypackage',
        'art' => 'castle'
    ],
    '5' => [
        'title' => 'Stylized Fire Starter Kit',
        'creator' => 'VFX Kampoeng',
        'category' => 'VFX PARTIKEL',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'fire'
    ],
    '6' => [
        'title' => 'Indonesian Road Texture Pack',
        'creator' => 'Bumi Tekstur',
        'category' => 'TEKSTUR',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'Built-In, URP',
        'format' => '.png, .tga, .mat',
        'art' => 'castle'
    ],
    '7' => [
        'title' => 'Simple UI SFX & VFX Overlay',
        'creator' => 'Andi Dev',
        'category' => 'VFX PARTIKEL',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    '8' => [
        'title' => 'Basic Hologram Shader Helper',
        'creator' => 'Arga Studio',
        'category' => 'VFX PARTIKEL',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    'free-forest-mist' => [
        'title' => 'Forest Mist & Fog Volume',
        'creator' => 'Rimba Digital',
        'category' => 'PARTICLE',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'Built-In',
        'format' => '.unitypackage',
        'art' => 'forest'
    ],
    'free-desert-dune' => [
        'title' => 'Desert Dune Terrain Pack',
        'creator' => 'Pasir Labs',
        'category' => 'MODEL',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    'neon-city' => [
        'title' => 'Neon City Wet Surface Shader',
        'creator' => 'Studio Neon',
        'category' => 'SHADER',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    'ocean-depth' => [
        'title' => 'Ocean Depth Water Shader',
        'creator' => 'Biru Nusantara',
        'category' => 'SHADER',
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ]
];

$reqId = $_GET['id'] ?? '1';
$matched = $catalogDB[$reqId] ?? null;

$assetId       = $reqId;
$assetTitle    = $_GET['title'] ?? ($matched['title'] ?? 'Volumetric Rain & Storm URP');
$assetCreator  = $_GET['creator'] ?? ($matched['creator'] ?? 'Arga Studio');
$assetCategory = $_GET['category'] ?? ($matched['category'] ?? 'VFX PARTIKEL');
$assetUnity    = $matched['unity_version'] ?? '2021.3+ / 2022.3 LTS';
$assetPipeline = $_GET['pipeline'] ?? ($matched['pipeline'] ?? 'URP, HDRP');
$assetFormat   = $matched['format'] ?? '.unitypackage';
$assetArt      = $matched['art'] ?? 'castle';

$userKey = (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($assetTitle) ?> - Library IndoFX</title>

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
            /* Cokelat Tua Figma */
            --indofx-text-muted: #7A685D;
            --indofx-border: #4A3B32;
            --indofx-orange: #ED6B1D;
            /* Oranye Tombol Unduh */
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

        .main-preview-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid var(--indofx-border);
            background-color: #2b2129;
            height: 420px;
        }

        .thumb-gallery-row {
            display: flex;
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

        /* Kartu Sisi Kanan Khusus Halaman Unduh */
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

        /* Tombol Oranye Unduh */
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
            transition: background-color 0.15s ease;
            box-shadow: 0 4px 12px rgba(237, 107, 29, 0.25);
        }

        .btn-download-orange:hover {
            background-color: #D85A0E;
        }

        /* Ulasan */
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
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                <!-- Kolom Kiri: Galeri & Tab Deskripsi -->
                <div class="col-12 col-lg-8">
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

                    <div class="detail-nav-tabs">
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <div class="detail-content-box" id="tabContentArea">
                        <div id="tab-deskripsi" class="tab-pane-detail active">
                            <p class="mb-3">
                                Paket aset visual <strong><?= htmlspecialchars($assetTitle) ?></strong> dirancang khusus oleh <strong><?= htmlspecialchars($assetCreator) ?></strong> untuk mewujudkan sistem cuaca dan efek grafis dalam pipeline rendering <?= htmlspecialchars($assetPipeline) ?> di engine Unity[cite: 26]. Dilengkapi dengan kontrol intensitas angin, density partikel kabut, dan kustomisasi parameter secara real-time[cite: 26].
                            </p>
                            <p class="mb-0">
                                Sangat cocok untuk game bertema horor, petualangan, maupun simulasi lingkungan Indonesia yang tropis dan kaya curah hujan[cite: 26].
                            </p>
                        </div>

                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h6 class="fw-bold mb-2">Versi 1.2.0</h6>
                            <p class="text-muted small">Pembaruan performa dan dukungan Unity 2022 LTS.</p>
                        </div>

                        <div id="tab-ulasan" class="tab-pane-detail d-none">
                            <div id="ratingSummaryWrapper" class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score" id="ratingBigScore">5,0</span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="my-1" style="color: var(--indofx-orange);">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <span class="d-block text-muted small" id="totalReviewsSummaryText">Dari 0 ulasan</span>
                                    </div>
                                    <div class="col-12 col-sm-8 pe-sm-3">
                                        <div class="d-flex align-items-center gap-2 small">
                                            <span style="width: 25px;">5 ★</span>
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar bg-warning" style="width: 100%;"></div>
                                            </div>
                                            <span id="reviewCountNum">1</span>
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

                <!-- Kolom Kanan: Card Download Tanpa Harga -->
                <div class="col-12 col-lg-4">
                    <div class="asset-library-card">
                        <span class="asset-category-tag d-block"><?= htmlspecialchars($assetCategory) ?></span>
                        <h2 class="asset-detail-title"><?= htmlspecialchars($assetTitle) ?></h2>

                        <div class="detail-creator-line">
                            Oleh <a href="/creator/profile"><?= htmlspecialchars($assetCreator) ?></a>
                        </div>

                        <!-- Spesifikasi Teknis -->
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

                        <!-- Tombol Unduh Oranye -->
                        <button type="button" class="btn-download-orange" onclick="downloadFile()">
                            <i class="bi bi-download"></i>
                            <span>Unduh</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="footer-creator-center py-5 mt-auto">
        <div class="container text-center">
            <h5 class="fw-bold mb-2" style="color: var(--indofx-text-main);">IndoFX</h5>
            <p class="text-muted small mb-3 mx-auto" style="max-width: 540px;">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia[cite: 26].
            </p>
            <div class="d-flex justify-content-between align-items-center text-muted small pt-3 border-top flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang[cite: 26].</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia[cite: 26]</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const currentAssetId = '<?= htmlspecialchars((string)$assetId, ENT_QUOTES, 'UTF-8') ?>';
        const assetTitle = '<?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?>';
        const assetFormat = '<?= htmlspecialchars($assetFormat, ENT_QUOTES, 'UTF-8') ?>';

        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));

            if (evt && evt.currentTarget) {
                evt.currentTarget.classList.add('active');
            }
            const target = document.getElementById(tabId);
            if (target) target.classList.remove('d-none');
        }

        // FUNGSI UNDUH PAKET UNITY OTOMATIS
        function downloadFile() {
            const fileName = assetTitle.toLowerCase().replace(/[^a-z0-9]/g, '_') + (assetFormat.startsWith('.') ? assetFormat : ('.' + assetFormat));
            const dummyPackage = `=== INDOFX ASSET FILE ===\nNama Aset: ${assetTitle}\nID: ${currentAssetId}\nFormat: ${assetFormat}\nLisensi: Full Commercial Project License\nDiunduh oleh: ${userKey}\nTanggal: ${new Date().toLocaleString()}\n`;

            const blob = new Blob([dummyPackage], {
                type: 'application/octet-stream'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }

        // MEMUAT ULASAN DINAMIS KHUSUS ASET INI
        function loadReviews() {
            const listContainer = document.getElementById('reviewsListContainer');
            if (!listContainer) return;

            let reviews = [];
            try {
                reviews = JSON.parse(localStorage.getItem('indofx_public_reviews_' + currentAssetId)) || [];
            } catch (e) {
                reviews = [];
            }

            const countBadge = document.getElementById('reviewsCountBadge');
            const summaryText = document.getElementById('totalReviewsSummaryText');
            const total = reviews.length;

            if (countBadge) countBadge.innerText = total + ' ulasan';
            if (summaryText) summaryText.innerText = 'Dari ' + total + ' ulasan';

            if (total === 0) {
                listContainer.innerHTML = `
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-chat-square-text fs-2 d-block mb-1"></i>
                        <p class="mb-0 fw-semibold">Belum ada ulasan untuk aset ini.</p>
                    </div>
                `;
                return;
            }

            listContainer.innerHTML = reviews.map(r => `
                <div class="review-item-block">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2">
                            <strong style="color: var(--indofx-text-main);">${r.user_name}</strong>
                            <span class="badge-owner">Pemilik aset</span>
                        </div>
                        <span class="text-muted small">${r.date}</span>
                    </div>
                    <div class="text-warning mb-1">${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</div>
                    ${r.title ? `<h6 class="fw-bold mb-1" style="color: var(--indofx-text-main); font-size: 0.95rem;">${r.title}</h6>` : ''}
                    <p class="mb-0 text-secondary" style="font-size: 0.88rem; line-height: 1.6;">${r.comment}</p>
                </div>
            `).join('');
        }

        document.addEventListener('DOMContentLoaded', loadReviews);
    </script>
</body>

</html>