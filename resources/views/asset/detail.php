<?php
// 1. Database Aset Katalog IndoFX
$catalogDB = [
    '1' => [
        'title' => 'Volumetric Rain & Storm URP',
        'creator' => 'Arga Studio',
        'category' => 'VFX PARTIKEL',
        'price' => 150000,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP, HDRP, Built-In',
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
        'creator' => 'Bayu Pratama',
        'category' => 'SHADER',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.shadergraph, .unitypackage',
        'art' => 'water'
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
        'category' => 'MODEL 3D',
        'price' => 0,
        'unity_version' => '2021.3+ / 2022.3 LTS',
        'pipeline' => 'URP',
        'format' => '.unitypackage',
        'art' => 'castle'
    ]
];

// 2. Baca Parameter URL yang diklik pengguna
$reqId = $_GET['id'] ?? '1';
$matched = $catalogDB[$reqId] ?? null;

$assetId       = $reqId;
$assetTitle    = $_GET['title'] ?? ($matched['title'] ?? 'Volumetric Rain & Storm URP');
$assetCreator  = $_GET['creator'] ?? ($matched['creator'] ?? 'Arga Studio');
$assetCategory = $_GET['category'] ?? ($matched['category'] ?? 'VFX PARTIKEL');
$assetPriceRaw = isset($_GET['price']) ? $_GET['price'] : ($matched['price'] ?? 150000);
$assetUnity    = $matched['unity_version'] ?? '2021.3+ / 2022.3 LTS';
$assetPipeline = $_GET['pipeline'] ?? ($matched['pipeline'] ?? 'URP, HDRP, Built-In');
$assetFormat   = $matched['format'] ?? '.unitypackage';
$assetArt      = $matched['art'] ?? 'castle';

// Cek status gratis
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        .content-detail-wrapper {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            min-height: calc(100vh - 70px);
        }

        .main-preview-card {
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid #2B2129;
            background-color: #2b2129;
            height: 380px;
        }

        .thumb-card {
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            height: 70px;
            background-color: #2b2129;
            transition: border-color 0.15s ease;
        }

        .thumb-card.active,
        .thumb-card:hover {
            border-color: #ED6B1D;
        }

        .detail-nav-tabs .tab-link {
            font-size: 0.92rem;
            font-weight: 600;
            color: #736b66;
            text-decoration: none;
            padding-bottom: 4px;
            border-bottom: 2px solid transparent;
        }

        .detail-nav-tabs .tab-link.active {
            color: #2B2129;
            font-weight: 800;
            border-bottom-color: #ED6B1D;
        }

        .detail-content-box {
            background-color: #ffffff;
            border-radius: 8px;
            border: 1.5px solid #2B2129;
            font-size: 0.88rem;
            line-height: 1.6;
        }

        .asset-purchase-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .asset-category-tag {
            font-size: 0.72rem;
            font-weight: 800;
            color: #736b66;
            letter-spacing: 0.5px;
        }

        .asset-detail-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #2B2129;
            line-height: 1.25;
        }

        .detail-creator {
            font-size: 0.84rem;
            color: #736b66;
        }

        .price-label {
            font-size: 0.75rem;
            color: #736b66;
        }

        .price-amount {
            font-size: 1.8rem;
            font-weight: 800;
            color: #2B2129;
        }

        .specs-table {
            border-top: 1px solid #efe0d5;
            border-bottom: 1px solid #efe0d5;
        }

        .border-bottom-spec {
            border-bottom: 1px solid #efe0d5;
        }

        .spec-label {
            font-size: 0.8rem;
            color: #736b66;
        }

        .spec-value {
            font-size: 0.8rem;
            font-weight: 700;
            color: #2B2129;
        }

        /* Tombol Tambahkan ke Library (Default) */
        .btn-add-library {
            background-color: #2B2129;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 6px;
            padding: 12px;
            transition: background-color 0.2s ease;
            text-align: center;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
            display: inline-block;
        }

        .btn-add-library:hover {
            background-color: #151114;
        }

        /* Tombol Khusus Saat Aset SUDAH DIMILIKI di Library */
        .btn-already-owned {
            background-color: #f0fdf4 !important;
            color: #15803d !important;
            border: 1.5px solid #86efac !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            padding: 12px 14px !important;
            border-radius: 6px !important;
            text-align: center !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            text-decoration: none !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.08) !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        .btn-already-owned:hover {
            background-color: #dcfce7 !important;
            color: #166534 !important;
        }

        .btn-buy-primary {
            background-color: #ED6B1D;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .btn-cart-secondary {
            background-color: #F4E4D7;
            color: #2B2129 !important;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
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

    <!-- Konten Detail -->
    <main class="content-detail-wrapper py-4">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- Kolom Kiri -->
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

                    <!-- Thumbnail Galeri -->
                    <div class="row g-2 mb-4 thumb-row" style="max-width: 240px;">
                        <div class="col-6">
                            <div class="thumb-card active">
                                <svg class="w-100 h-100 d-block" viewBox="0 0 700 420" preserveAspectRatio="xMidYMid slice">
                                    <use href="#art-<?= htmlspecialchars($assetArt) ?>" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Navigasi -->
                    <div class="detail-nav-tabs d-flex align-items-center gap-4 mb-3">
                        <a href="javascript:void(0)" class="tab-link active" onclick="switchDetailTab(event, 'tab-deskripsi')">Deskripsi</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-changelog')">Changelog</a>
                        <a href="javascript:void(0)" class="tab-link" onclick="switchDetailTab(event, 'tab-ulasan')">Ulasan</a>
                    </div>

                    <div class="detail-content-box p-4" id="tabContentArea">
                        <div id="tab-deskripsi" class="tab-pane-detail active">
                            <p class="mb-3">
                                Paket aset visual <strong><?= htmlspecialchars($assetTitle) ?></strong> dirancang khusus oleh <strong><?= htmlspecialchars($assetCreator) ?></strong> untuk pipeline rendering <?= htmlspecialchars($assetPipeline) ?> di engine Unity.
                            </p>
                            <p class="mb-0">
                                Sangat mudah diintegrasikan ke dalam game Anda dan telah dilengkapi dokumentasi standar industri game Indonesia.
                            </p>
                        </div>
                        <div id="tab-changelog" class="tab-pane-detail d-none">
                            <h6 class="fw-bold mb-2">Versi 1.2.0</h6>
                            <p class="text-muted small">Pembaruan shader dan optimasi performa.</p>
                        </div>
                        <div id="tab-ulasan" class="tab-pane-detail d-none">
                            <h6 class="fw-bold mb-2">Ulasan Pengguna</h6>
                            <p class="text-muted small">Aset ini memiliki ulasan bintang 5 dari para developer.</p>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-12 col-lg-4">
                    <div class="asset-purchase-card p-4">
                        <span class="asset-category-tag d-block mb-1 text-uppercase"><?= htmlspecialchars($assetCategory) ?></span>
                        <h2 class="asset-detail-title mb-1"><?= htmlspecialchars($assetTitle) ?></h2>
                        <p class="detail-creator mb-3">
                            Oleh <a href="/creator/profile" class="text-decoration-none fw-semibold text-primary"><?= htmlspecialchars($assetCreator) ?></a>
                        </p>

                        <span class="price-label d-block mb-1">Harga Aset</span>
                        <h3 class="price-amount mb-4">
                            <?php if ($isGratis): ?>
                                Gratis
                            <?php else: ?>
                                Rp<?= number_format((float)$assetPriceRaw, 0, ',', '.') ?>
                            <?php endif; ?>
                        </h3>

                        <!-- Spesifikasi Teknis -->
                        <div class="specs-table mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Versi Unity</span>
                                <span class="spec-value"><?= htmlspecialchars($assetUnity) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Render Pipeline</span>
                                <span class="spec-value"><?= htmlspecialchars($assetPipeline) ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="spec-label">Format File</span>
                                <span class="spec-value"><?= htmlspecialchars($assetFormat) ?></span>
                            </div>
                        </div>

                        <!-- Wadah Tombol Aksi Dinamis -->
                        <div class="d-grid gap-2" id="actionButtonWrapper">
                            <?php if ($isGratis): ?>
                                <!-- Aset Gratis: Default tombol tambah -->
                                <button type="button" id="btnClaimLibrary" class="btn btn-add-library" onclick="claimAsset()">
                                    Tambahkan ke Library
                                </button>
                            <?php else: ?>
                                <!-- Aset Berbayar -->
                                <a href="/checkout?asset_id=<?= urlencode((string)$assetId) ?>" class="btn btn-buy-primary" id="btnBuyPrimary">Beli</a>
                                <a href="/cart" class="btn btn-cart-secondary">Tambahkan ke Keranjang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const currentAssetId = '<?= htmlspecialchars((string)$assetId, ENT_QUOTES, 'UTF-8') ?>';
        const isAssetGratis = <?= $isGratis ? 'true' : 'false' ?>;

        function switchDetailTab(evt, tabId) {
            document.querySelectorAll('.detail-nav-tabs .tab-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane-detail').forEach(pane => pane.classList.add('d-none'));

            evt.currentTarget.classList.add('active');
            const targetPane = document.getElementById(tabId);
            if (targetPane) {
                targetPane.classList.remove('d-none');
            }
        }

        // PERIKSA STATUS KEPEMILIKAN SAAT HALAMAN DIBUKA
        document.addEventListener('DOMContentLoaded', function() {
            if (!userKey) return;

            const storageKey = 'indofx_library_' + userKey;
            let currentLibrary = [];
            try {
                currentLibrary = JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                currentLibrary = [];
            }

            // Cek apakah aset dengan ID ini sudah ada di Library milik user ini
            const isOwned = currentLibrary.some(item => String(item.id) === String(currentAssetId));

            if (isOwned) {
                const actionWrapper = document.getElementById('actionButtonWrapper');
                if (actionWrapper) {
                    // Ubah tombol menjadi pemberitahuan bahwa aset sudah dimiliki
                    actionWrapper.innerHTML = `
                        <a href="/library" class="btn-already-owned" title="Klik untuk membuka Library Anda">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <span>Aset ini sudah Anda miliki di library Anda</span>
                        </a>
                    `;
                }
            }
        });

        // FUNGSI KLAIM ASET KE LIBRARY
        function claimAsset() {
            if (!userKey) {
                window.location.href = '/login';
                return;
            }

            const btn = document.getElementById('btnClaimLibrary');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            const storageKey = 'indofx_library_' + userKey;
            let currentLibrary = [];
            try {
                currentLibrary = JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                currentLibrary = [];
            }

            // Data Aset Dinamis
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

            // Alihkan ke Library untuk melihat hasilnya
            window.location.href = '/library';
        }
    </script>
</body>

</html>