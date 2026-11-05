<?php
// 1. Database Aset Katalog IndoFX
$catalogDB = [
    '1' => [
        'title' => 'Volumetric Rain & Storm URP',
        'creator' => 'Arga Studio',
        'category' => 'SHADER',
        'price' => 150000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    '2' => [
        'title' => 'Stylized Toon Water Shader',
        'creator' => 'Nusantara Digital',
        'category' => 'SHADER',
        'price' => 75000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, Built-In',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    '3' => [
        'title' => 'Explosion VFX Pack Vol. 1',
        'creator' => 'Bayu Pratama',
        'category' => 'VFX PARTIKEL',
        'price' => 200000,
        'unity_version' => '2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'fire'
    ],
    '4' => [
        'title' => 'Lowpoly Indonesian Village Pack',
        'creator' => 'Citra CG',
        'category' => 'MODEL 3D',
        'price' => 350000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP, Built-In',
        'format' => '.fbx, .blend, .unitypackage',
        'art' => 'castle'
    ],
    '5' => [
        'title' => 'Stylized Fire Starter Kit',
        'creator' => 'VFX Kampoeng',
        'category' => 'VFX PARTIKEL',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'fire'
    ],
    '6' => [
        'title' => 'Indonesian Road Texture Pack',
        'creator' => 'Bumi Tekstur',
        'category' => 'TEKSTUR',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'Built-In, URP',
        'format' => '.png, .tga, .mat',
        'art' => 'castle'
    ],
    '7' => [
        'title' => 'Simple UI SFX & VFX Overlay',
        'creator' => 'Andi Dev',
        'category' => 'VFX PARTIKEL',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    '8' => [
        'title' => 'Basic Hologram Shader Helper',
        'creator' => 'Arga Studio',
        'category' => 'VFX PARTIKEL',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    'free-forest-mist' => [
        'title' => 'Forest Mist & Fog Volume',
        'creator' => 'Rimba Digital',
        'category' => 'PARTICLE',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'Built-In',
        'format' => '.unitypackage',
        'art' => 'forest'
    ],
    'free-desert-dune' => [
        'title' => 'Desert Dune Terrain Pack',
        'creator' => 'Pasir Labs',
        'category' => 'MODEL',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ],
    'neon-city' => [
        'title' => 'Neon City Wet Surface Shader',
        'creator' => 'Studio Neon',
        'category' => 'SHADER',
        'price' => 225000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ],
    'ocean-depth' => [
        'title' => 'Ocean Depth Water Shader',
        'creator' => 'Biru Nusantara',
        'category' => 'SHADER',
        'price' => 180000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'HDRP',
        'format' => '.unitypackage',
        'art' => 'water'
    ]
];

// 2. Baca Parameter URL
$reqId = $_GET['id'] ?? '1';
$matched = $catalogDB[$reqId] ?? null;

$assetId       = $reqId;
$assetTitle    = $_GET['title'] ?? ($matched['title'] ?? 'Volumetric Rain & Storm URP');
$assetCreator  = $_GET['creator'] ?? ($matched['creator'] ?? 'Arga Studio');
$assetCategory = $_GET['category'] ?? ($matched['category'] ?? 'SHADER');
$assetPriceRaw = isset($_GET['price']) ? $_GET['price'] : ($matched['price'] ?? 150000);
$assetUnity    = $matched['unity_version'] ?? '2021.3+ / 2022.3 LTS';
$assetPipeline = $_GET['pipeline'] ?? ($matched['pipeline'] ?? 'URP');
$assetFormat   = $matched['format'] ?? '.unitypackage';
$assetArt      = $matched['art'] ?? 'castle';

$isGratis = false;
if (is_numeric($assetPriceRaw)) {
    $isGratis = ((float)$assetPriceRaw <= 0);
} else {
    $isGratis = (strtolower(trim((string)$assetPriceRaw)) === 'gratis' || $assetPriceRaw === '0');
}

$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = $isLoggedIn ? (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user') : '';
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
            --indofx-text-muted: #7A685D;
            --indofx-border: #4A3B32;
            --indofx-orange: #ED6B1D;
        }

        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            font-family: 'Inter', sans-serif;
            color: var(--indofx-text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Pembungkus Konten Utama Stabil */
        .content-detail-wrapper {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            padding: 32px 0 54px 0;
            flex-grow: 1;
        }

        /* Frame Pratinjau Utama */
        .main-preview-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid var(--indofx-border);
            background-color: #2b2129;
            height: 420px;
            width: 100%;
        }

        /* 4 Thumbnail Galeri */
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
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .thumb-card-item.active {
            border: 2px solid var(--indofx-orange);
        }

        .thumb-card-item:hover {
            opacity: 0.9;
        }

        /* Tab Navigasi */
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

        /* Kartu Sisi Kanan */
        .asset-purchase-card {
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
            margin-bottom: 22px;
        }

        .detail-creator-line a {
            color: var(--indofx-text-main) !important;
            font-weight: 800;
            text-decoration: none;
        }

        .detail-creator-line a:hover {
            text-decoration: underline;
        }

        .price-label-text {
            font-size: 0.8rem;
            color: var(--indofx-text-muted);
            margin-bottom: 2px;
        }

        .price-amount-text {
            font-size: 1.95rem;
            font-weight: 800;
            color: var(--indofx-text-main) !important;
            letter-spacing: -0.5px;
            margin-bottom: 24px;
        }

        .specs-table-box {
            border-top: 1px solid #EBDCD1;
            margin-bottom: 24px;
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

        /* Tombol Aksi */
        .btn-figma-buy-orange {
            background-color: var(--indofx-orange);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
            text-decoration: none;
            display: block;
            width: 100%;
            transition: background-color 0.15s ease;
        }

        .btn-figma-buy-orange:hover {
            background-color: #D85A0E;
        }

        .btn-figma-add-cart-outline {
            background-color: #FFFDFC;
            color: var(--indofx-text-main) !important;
            border: 1.5px solid var(--indofx-border);
            font-weight: 800;
            font-size: 0.92rem;
            border-radius: 6px;
            padding: 11px 16px;
            text-align: center;
            text-decoration: none;
            display: block;
            width: 100%;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-figma-add-cart-outline:hover {
            background-color: #F8EFE9;
        }

        .btn-figma-free-library {
            background-color: var(--indofx-border);
            color: #ffffff !important;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 12px 16px;
            text-align: center;
            cursor: pointer;
            width: 100%;
            display: block;
            text-decoration: none;
            transition: opacity 0.15s ease;
        }

        .btn-figma-free-library:hover {
            opacity: 0.92;
        }

        .btn-figma-guest-login {
            background-color: #FCEEE6 !important;
            color: var(--indofx-text-main, #3C2A21) !important;
            border: 1.5px solid var(--indofx-border, #3C2A21) !important;
            font-weight: 700;
            font-size: 0.88rem;
            border-radius: 6px;
            padding: 12px 14px;
            text-align: center;
            text-decoration: none !important;
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            width: 100%;
            transition: all 0.15s ease;
        }

        .btn-figma-guest-login:hover {
            background-color: #F8DFD2 !important;
        }

        .btn-figma-guest-login .link-masuk {
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-thickness: 1.5px;
            font-weight: 800;
        }

        /* Status Kepemilikan & Unduh Oranye */
        .owned-badge-card {
            background-color: #F0FDF4;
            border: 1.5px solid #86EFAC;
            border-radius: 8px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #15803D;
            font-weight: 700;
            font-size: 0.88rem;
            text-align: center;
            line-height: 1.35;
        }

        .owned-badge-card i {
            color: #16A34A;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .btn-download-package {
            background-color: var(--indofx-orange, #ED6B1D) !important;
            color: #ffffff !important;
            font-weight: 800;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 12px 18px;
            text-align: center;
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

        .btn-download-package:hover {
            background-color: #D85A0E !important;
            transform: translateY(-1px);
        }

        /* Ringkasan Ulasan */
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
            gap: 8px;
            font-size: 0.78rem;
            margin-bottom: 4px;
        }

        .rating-bar-label {
            width: 25px;
            color: var(--indofx-text-main);
            font-weight: 600;
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
        }

        .rating-bar-num {
            width: 20px;
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

        /* Footer Putih Solid Full-Width Sesuai Figma */
        .footer-figma-white {
            background-color: #ffffff !important;
            width: 100%;
            padding: 42px 0 24px 0;
            margin-top: auto;
            border-top: 1px solid #efe0d5;
        }
    </style>
</head>

<body class="detail-page-body">

    <!-- Definisi Ilustrasi SVG Game Art -->
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

            <div class="search-box-header d-none d-md-flex align-items-center">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset VFX, shader, tekstur...">
            </div>

            <div class="d-flex align-items-center">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- Konten Detail Produk -->
    <main class="content-detail-wrapper">
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

                    <!-- 4 Thumbnail Galeri -->
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

                    <!-- Tab Navigasi -->
                    <div class="detail-nav-tabs">
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <div class="detail-content-box" id="tabContentArea">
                        <div id="tab-deskripsi" class="tab-pane-detail active">
                            <p class="mb-3">
                                Paket aset visual <strong><?= htmlspecialchars($assetTitle) ?></strong> dirancang khusus oleh <strong><?= htmlspecialchars($assetCreator) ?></strong> untuk mewujudkan sistem visual berkualitas tinggi dalam pipeline rendering <?= htmlspecialchars($assetPipeline) ?> di engine Unity[cite: 27]. Dilengkapi dengan kontrol intensitas, shader material yang teroptimasi, dan performa tinggi tanpa membebani draw calls.
                            </p>
                            <p class="mb-0">
                                Sangat cocok untuk proyek game bertema petualangan, aksi, simulasi, maupun kebutuhan prototipe karya game developer Indonesia[cite: 27].
                            </p>
                        </div>

                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h6 class="fw-bold mb-2">Versi 1.2.0</h6>
                            <p class="text-muted small">Pembaruan shader dan optimasi performa partikel untuk rilis Unity 2022 LTS[cite: 26].</p>
                        </div>

                        <div id="tab-ulasan" class="tab-pane-detail d-none">
                            <div id="ratingSummaryWrapper" class="rating-summary-box p-3 mb-4">
                                <div class="row align-items-center g-3">
                                    <div class="col-12 col-sm-4 text-center text-sm-start ps-sm-3">
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-sm-start gap-1">
                                            <span class="rating-big-score" id="ratingBigScore">5,0</span>
                                            <span class="rating-out-of">/ 5</span>
                                        </div>
                                        <div class="rating-stars-gold my-1">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <span class="d-block text-muted small" id="totalReviewsSummaryText">Dari 0 ulasan</span>
                                    </div>

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

                <!-- Kolom Kanan: Card Detail & Pembelian -->
                <div class="col-12 col-lg-4">
                    <div class="asset-purchase-card">
                        <!-- Kategori -->
                        <span class="asset-category-tag d-block"><?= htmlspecialchars($assetCategory) ?></span>

                        <!-- Judul Aset -->
                        <h2 class="asset-detail-title"><?= htmlspecialchars($assetTitle) ?></h2>

                        <!-- Pembuat Aset -->
                        <div class="detail-creator-line">
                            Oleh <a href="/creator/profile"><?= htmlspecialchars($assetCreator) ?></a>
                        </div>

                        <!-- Label & Angka Harga -->
                        <div class="price-label-text">Harga Aset</div>
                        <div class="price-amount-text">
                            <?php if ($isGratis): ?>
                                Gratis
                            <?php else: ?>
                                Rp<?= number_format((float)$assetPriceRaw, 0, ',', '.') ?>
                            <?php endif; ?>
                        </div>

                        <!-- Spesifikasi Teknis Tabel -->
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

                        <!-- Tombol Aksi: Terdeteksi Otomatis Guest vs Login -->
                        <div id="actionButtonWrapper" class="d-grid gap-2">
                            <?php if ($isGratis): ?>
                                <?php if ($isLoggedIn): ?>
                                    <button type="button" id="btnClaimLibrary" class="btn-figma-free-library" onclick="claimAsset()">
                                        Tambahkan ke Library
                                    </button>
                                <?php else: ?>
                                    <a href="/login" class="btn-figma-guest-login">
                                        <span><span class="link-masuk">Masuk</span> untuk menambahkan ke Library</span>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="<?= $isLoggedIn ? ('/checkout?asset_id=' . urlencode((string)$assetId)) : '/login' ?>" class="btn-figma-buy-orange" id="btnBuyPrimary">
                                    Beli
                                </a>
                                <button type="button" class="btn-figma-add-cart-outline" onclick="addToCart()">
                                    Tambahkan ke Keranjang
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Footer Putih Solid Full-Width Sesuai Figma -->
    <footer class="footer-figma-white">
        <div class="container-figma text-center">
            <h5 class="fw-bold mb-2" style="color: var(--indofx-text-main);">IndoFX</h5>
            <p class="text-muted small mb-4 mx-auto" style="max-width: 580px;">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia[cite: 26].
            </p>
            <div class="d-flex justify-content-between align-items-center text-muted small pt-2 border-top flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang[cite: 26].</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia[cite: 26]</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const currentAssetId = '<?= htmlspecialchars((string)$assetId, ENT_QUOTES, 'UTF-8') ?>';
        const isAssetGratis = <?= $isGratis ? 'true' : 'false' ?>;
        const assetTitle = '<?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?>';
        const assetFormat = '<?= htmlspecialchars($assetFormat, ENT_QUOTES, 'UTF-8') ?>';

        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));

            if (evt && evt.currentTarget) {
                evt.currentTarget.classList.add('active');
            } else {
                const targetLink = document.querySelector(`.detail-nav-tabs .tab-link[onclick*='${tabId}']`);
                if (targetLink) targetLink.classList.add('active');
            }

            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
        }

        function downloadAssetPackage() {
            const fileName = assetTitle.toLowerCase().replace(/[^a-z0-9]/g, '_') + (assetFormat.startsWith('.') ? assetFormat : ('.' + assetFormat));
            const dummyContent = `=== INDOFX ASSET PACKAGE ===\nJudul: ${assetTitle}\nID: ${currentAssetId}\nFormat: ${assetFormat}\nLisensi: Single Developer Commercial License\nDiunduh oleh: ${userKey}\nTanggal: ${new Date().toLocaleString()}\n`;

            const blob = new Blob([dummyContent], {
                type: 'application/octet-stream'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        }

        function loadAssetReviews() {
            const listContainer = document.getElementById('reviewsListContainer');
            if (!listContainer) return;

            const initialReviewsDatabase = {
                '1': [{
                        user_name: 'Dimas Pratama',
                        rating: 5,
                        title: 'Mudah dipelajari untuk prototipe',
                        comment: 'Contoh scene membantu saya memahami pengaturan material. Panduan Bahasa Indonesia juga memudahkan proses mencoba aset.',
                        date: '18 September 2026'
                    },
                    {
                        user_name: 'Nadia Putri',
                        rating: 4,
                        title: 'Membantu alur kerja proyek',
                        comment: 'Parameter cukup mudah diikuti. Akan lebih membantu jika ada lebih banyak contoh penggunaan untuk berbagai suasana scene.',
                        date: '12 September 2026'
                    },
                    {
                        user_name: 'Raka Saputra',
                        rating: 5,
                        title: 'Contoh penggunaan yang jelas',
                        comment: 'Struktur paket mudah dipahami dan nyaman untuk eksplorasi. Saya bisa mulai mencoba tanpa banyak penyesuaian.',
                        date: '8 September 2026'
                    }
                ]
            };

            const baseReviews = initialReviewsDatabase[currentAssetId] || [];

            let publicReviews = [];
            try {
                publicReviews = JSON.parse(localStorage.getItem('indofx_public_reviews_' + currentAssetId)) || [];
            } catch (e) {
                publicReviews = [];
            }

            const allReviews = [...publicReviews, ...baseReviews];
            const totalCount = allReviews.length;

            const countBadge = document.getElementById('reviewsCountBadge');
            const summaryBox = document.getElementById('ratingSummaryWrapper');
            const summaryText = document.getElementById('totalReviewsSummaryText');

            if (countBadge) countBadge.innerText = totalCount + ' ulasan';

            if (totalCount === 0) {
                if (summaryBox) summaryBox.style.display = 'none';
                listContainer.innerHTML = `
                    <div class="text-center py-5 bg-white rounded-3 border" style="border-color: #ebdcd1 !important;">
                        <i class="bi bi-chat-square-text text-muted fs-1 d-block mb-2"></i>
                        <h6 class="fw-bold mb-1" style="color: var(--indofx-text-main);">Belum ada ulasan untuk aset ini</h6>
                        <p class="text-muted small m-0">Klaim aset ini ke Library Anda untuk menjadi yang pertama memberikan ulasan.</p>
                    </div>
                `;
                return;
            }

            if (summaryBox) summaryBox.style.display = 'block';

            let sumRating = 0;
            const starCounts = {
                5: 0,
                4: 0,
                3: 0,
                2: 0,
                1: 0
            };

            allReviews.forEach(r => {
                const star = Math.min(5, Math.max(1, parseInt(r.rating, 10) || 5));
                starCounts[star] = (starCounts[star] || 0) + 1;
                sumRating += star;
            });

            const avgScore = (sumRating / totalCount).toFixed(1).replace('.', ',');
            const bigScoreEl = document.getElementById('ratingBigScore');
            if (bigScoreEl) bigScoreEl.innerText = avgScore;
            if (summaryText) summaryText.innerText = `Dari ${totalCount} ulasan`;

            for (let s = 1; s <= 5; s++) {
                const pct = Math.round((starCounts[s] / totalCount) * 100);
                const fillEl = document.getElementById('ratingBarFill_' + s);
                const numEl = document.getElementById('ratingBarNum_' + s);
                if (fillEl) fillEl.style.width = pct + '%';
                if (numEl) numEl.innerText = starCounts[s];
            }

            listContainer.innerHTML = allReviews.map(rev => {
                let starsHtml = '';
                for (let s = 1; s <= 5; s++) {
                    starsHtml += s <= rev.rating ?
                        '<i class="bi bi-star-fill text-warning me-1"></i>' :
                        '<i class="bi bi-star text-muted me-1"></i>';
                }

                const titleHtml = rev.title ? `<h6 class="fw-bold mt-2 mb-1" style="font-size: 0.95rem; color: var(--indofx-text-main);">${rev.title}</h6>` : '';

                return `
                    <div class="review-item-block">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="d-flex align-items-center gap-2">
                                <strong style="font-size: 0.95rem; color: var(--indofx-text-main);">${rev.user_name}</strong>
                                <span class="badge-owner">Pemilik aset</span>
                            </div>
                            <span class="text-muted small">${rev.date}</span>
                        </div>
                        <div class="mb-1" style="font-size: 0.85rem;">${starsHtml}</div>
                        ${titleHtml}
                        <p class="mb-0 text-secondary" style="font-size: 0.88rem; line-height: 1.6;">${rev.comment}</p>
                    </div>
                `;
            }).join('');
        }

        function addToCart() {
            if (!userKey) {
                window.location.href = '/login';
                return;
            }

            const cartStorageKey = 'indofx_cart_' + userKey;
            let currentCart = [];
            try {
                currentCart = JSON.parse(localStorage.getItem(cartStorageKey)) || [];
            } catch (e) {
                currentCart = [];
            }

            const cartItem = {
                id: currentAssetId,
                title: '<?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?>',
                creator: '<?= htmlspecialchars($assetCreator, ENT_QUOTES, 'UTF-8') ?>',
                price: <?= (float)$assetPriceRaw ?>,
                date: '<?= date("d M Y") ?>',
                pipelines: <?= json_encode(array_values(array_filter(array_map('trim', explode(',', $assetPipeline))))) ?>,
                art: '<?= htmlspecialchars($assetArt, ENT_QUOTES, 'UTF-8') ?>'
            };

            const exists = currentCart.some(item => String(item.id) === String(cartItem.id));
            if (!exists) {
                currentCart.push(cartItem);
                localStorage.setItem(cartStorageKey, JSON.stringify(currentCart));
            }

            window.location.href = '/cart';
        }

        function claimAsset() {
            if (!userKey) {
                window.location.href = '/login';
                return;
            }

            const btn = document.getElementById('btnClaimLibrary');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
            }

            const storageKey = 'indofx_library_' + userKey;
            let currentLibrary = [];
            try {
                currentLibrary = JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                currentLibrary = [];
            }

            const newAsset = {
                id: currentAssetId,
                title: '<?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?>',
                creator: '<?= htmlspecialchars($assetCreator, ENT_QUOTES, 'UTF-8') ?>',
                type: 'Gratis',
                date: '<?= date("d M Y") ?>',
                pipelines: <?= json_encode(array_values(array_filter(array_map('trim', explode(',', $assetPipeline))))) ?>,
                art: '<?= htmlspecialchars($assetArt, ENT_QUOTES, 'UTF-8') ?>'
            };

            const alreadyExists = currentLibrary.some(item => String(item.id) === String(newAsset.id));
            if (!alreadyExists) {
                currentLibrary.unshift(newAsset);
                localStorage.setItem(storageKey, JSON.stringify(currentLibrary));
            }

            window.location.href = '/library';
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadAssetReviews();

            if (window.location.hash === '#tab-ulasan' || window.location.hash === '#ulasan') {
                switchDetailTab(null, 'tab-ulasan');
            }

            if (!userKey) return;

            const storageKey = 'indofx_library_' + userKey;
            let currentLibrary = [];
            try {
                currentLibrary = JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                currentLibrary = [];
            }

            const isOwned = currentLibrary.some(item => String(item.id) === String(currentAssetId));

            if (isOwned) {
                const actionWrapper = document.getElementById('actionButtonWrapper');
                if (actionWrapper) {
                    actionWrapper.innerHTML = `
                        <div class="owned-badge-card mb-2">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Aset ini sudah Anda miliki di library Anda</span>
                        </div>
                        <button type="button" class="btn-download-package" onclick="downloadAssetPackage()">
                            <i class="bi bi-download"></i>
                            <span>Unduh Aset (${assetFormat})</span>
                        </button>
                    `;
                }
            }
        });
    </script>
</body>

</html>