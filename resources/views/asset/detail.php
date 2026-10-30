<?php
// 1. Ambil data aset secara dinamis (dari variabel controller, database, atau parameter URL)
if (!isset($asset)) {
    $assetId = $_GET['id'] ?? null;
    if ($assetId && class_exists('IndoFX\Core\Database')) {
        try {
            $db = \IndoFX\Core\Database::getInstance();
            $stmt = $db->query("SELECT * FROM assets WHERE id = :id", ['id' => $assetId]);
            $asset = $stmt->fetch();
        } catch (\Throwable $e) {
            $asset = null;
        }
    }
}

// Fallback nilai dinamis sesuai data kartu yang diklik
$assetId          = $asset['id'] ?? ($_GET['id'] ?? 1);
$assetTitle       = $asset['title'] ?? ($_GET['title'] ?? 'Basic Hologram Shader Helper');
$assetCategory    = $asset['category'] ?? ($_GET['category'] ?? 'VFX PARTIKEL');
$assetCreator     = $asset['creator_name'] ?? ($asset['creator'] ?? ($_GET['creator'] ?? 'Arga Studio'));
$assetPriceRaw    = $asset['price'] ?? ($_GET['price'] ?? 150000);
$assetUnity       = $asset['unity_version'] ?? '2021.3+ / 2022.3 LTS';
$assetPipeline    = $asset['pipeline'] ?? 'URP, HDRP';
$assetFormat      = $asset['format'] ?? '.unitypackage';

// Logika penentuan status Gratis atau Berbayar
$isGratis = false;
if (is_numeric($assetPriceRaw)) {
    $isGratis = ((float)$assetPriceRaw <= 0);
} else {
    $isGratis = (strtolower(trim((string)$assetPriceRaw)) === 'gratis' || $assetPriceRaw === '0');
}

// Data akun pengguna jika sedang dalam sesi login
$isLoggedIn = isset($_SESSION['user_id']);
$userName   = (string) ($_SESSION['name'] ?? 'Pengguna');
$initials   = '';
if ($isLoggedIn) {
    $words = preg_split('/\s+/', trim($userName)) ?: [];
    foreach (array_slice($words, 0, 2) as $word) {
        $initials .= mb_strtoupper(mb_substr($word, 0, 1));
    }
    if ($initials === '') {
        $initials = 'U';
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?> - IndoFX</title>

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

    <style>
        /* Gaya tambahan untuk avatar dan dropdown profil */
        .avatar-circle-header {
            width: 36px;
            height: 36px;
            background-color: #CFCFCF;
            color: #2D2D2D;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .profile-dropdown-wrapper {
            position: relative;
        }

        .btn-profile-trigger {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            cursor: pointer;
        }

        .profile-custom-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background-color: #ffffff;
            border: 1px solid #e0d0c5;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            z-index: 1050;
            display: none;
            padding: 6px 0;
        }

        .profile-custom-menu.show {
            display: block;
        }

        .profile-custom-item {
            display: block;
            padding: 9px 18px;
            color: #2b2129;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.15s ease;
        }

        .profile-custom-item:hover {
            background-color: #fdf5f0;
            color: #ed6b1d;
        }

        .profile-custom-item.fw-bold {
            border-bottom: 1px solid #f0e6df;
            margin-bottom: 4px;
            color: #1a1a1a;
        }

        .btn-add-library {
            background-color: #2B2129;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.92rem;
            border: none;
            border-radius: 6px;
            padding: 12px;
            transition: opacity 0.2s ease;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .btn-add-library:hover {
            opacity: 0.88;
            color: #ffffff;
        }
    </style>
</head>

<body class="detail-page-body">

    <!-- SVG Definitions untuk Preview Galeri -->
    <svg class="d-none">
        <defs>
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
            <g id="preview-slide-2">
                <rect width="700" height="420" fill="#2C3A47" />
                <circle cx="580" cy="90" r="45" fill="#F8EFBA" opacity="0.9" />
                <polygon points="150,120 220,80 290,120 220,160" fill="#58B19F" />
                <polygon points="150,120 220,160 220,250 150,210" fill="#2C3A47" stroke="#58B19F" stroke-width="3" />
                <polygon points="220,160 290,120 290,210 220,250" fill="#182C61" stroke="#58B19F" stroke-width="3" />
                <circle cx="420" cy="240" r="55" fill="#00D2D3" opacity="0.35" />
                <circle cx="420" cy="240" r="30" fill="#00D2D3" opacity="0.85" />
            </g>
            <g id="preview-slide-3">
                <rect width="700" height="420" fill="#3B4B44" />
                <polygon points="150,60 90,260 210,260" fill="#2D3A34" />
                <polygon points="350,40 280,280 420,280" fill="#1C2621" />
                <polygon points="530,70 470,270 590,270" fill="#25302B" />
                <ellipse cx="350" cy="280" rx="300" ry="45" fill="#E4EEF5" opacity="0.35" />
            </g>
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

    <!-- 1. Header Navbar Oranye Dinamis -->
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
                <input type="text" class="form-control search-input-header" placeholder="Cari aset, VFX, shader, tekstur...">
            </div>

            <!-- Bagian Kanan Navbar (Guest vs Login) -->
            <div class="d-flex align-items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <a href="/cart" class="text-white text-decoration-none d-flex align-items-center" title="Keranjang">
                        <i class="bi bi-cart3 fs-4"></i>
                    </a>

                    <div class="profile-dropdown-wrapper">
                        <button type="button" class="btn-profile-trigger" id="profileDropdownBtn" aria-expanded="false">
                            <span class="avatar-circle-header"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="fw-bold fs-6"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
                        </button>

                        <div class="profile-custom-menu" id="profileDropdownMenu">
                            <a href="/creator/profile?me=1" class="profile-custom-item fw-bold">
                                <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <a href="/dashboard/ubah-profil" class="profile-custom-item">
                                Edit Profile
                            </a>
                            <a href="/invoice" class="profile-custom-item">
                                Histori Pembelian
                            </a>
                            <a href="/#tentang" class="profile-custom-item">
                                Dukungan
                            </a>
                            <a href="/logout" class="profile-custom-item text-danger">
                                Keluar
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/dashboard/add-asset" class="btn btn-nav-seller">Jadi Penjual</a>
                    <a href="/login" class="btn btn-nav-login">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- 2. Main Content Detail Area -->
    <main class="content-detail-wrapper py-4">
        <div class="container-figma">
            <div class="row g-4 align-items-start">

                <!-- ==================== KOLOM KIRI (8 KOLOM) ==================== -->
                <div class="col-12 col-lg-8">

                    <!-- Carousel Slider Gambar Utama -->
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

                    <!-- 4 Thumbnail Galeri -->
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

                        <!-- TAB 2: CHANGELOG -->
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

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 changelog-main-title">Ulasan pembeli</h6>
                                <span class="changelog-date">3 ulasan</span>
                            </div>

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
                                    <i class="bi bi-star-fill"></i>
                                </div>
                                <h6 class="fw-bold review-title mb-1">Membantu alur kerja proyek</h6>
                                <p class="mb-0 review-desc">
                                    Parameter cukup mudah diikuti. Akan lebih membantu jika ada lebih banyak contoh penggunaan untuk berbagai suasana scene.
                                </p>
                            </div>

                            <hr class="changelog-divider">

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
                        <span class="asset-category-tag d-block mb-1 text-uppercase">
                            <?= htmlspecialchars($assetCategory, ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <h2 class="asset-detail-title mb-1">
                            <?= htmlspecialchars($assetTitle, ENT_QUOTES, 'UTF-8') ?>
                        </h2>

                        <p class="detail-creator mb-3">
                            Oleh <a href="/creator/profile?name=<?= urlencode($assetCreator) ?>" class="text-decoration-none fw-semibold">
                                <?= htmlspecialchars($assetCreator, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </p>

                        <!-- Tampilan Harga Dinamis -->
                        <span class="price-label d-block mb-1">Harga Aset</span>
                        <h3 class="price-amount mb-4">
                            <?php if ($isGratis): ?>
                                Gratis
                            <?php else: ?>
                                Rp<?= number_format((float)$assetPriceRaw, 0, ',', '.') ?>
                            <?php endif; ?>
                        </h3>

                        <!-- Spesifikasi Aset -->
                        <div class="specs-table mb-4">
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Versi Unity</span>
                                <span class="spec-value"><?= htmlspecialchars($assetUnity, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom-spec">
                                <span class="spec-label">Render Pipeline</span>
                                <span class="spec-value"><?= htmlspecialchars($assetPipeline, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="spec-label">Format File</span>
                                <span class="spec-value"><?= htmlspecialchars($assetFormat, ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>

                        <!-- Tombol Aksi Dinamis (Gratis vs Berbayar) -->
                        <div class="d-grid gap-2">
                            <?php if ($isGratis): ?>
                                <!-- JIKA ASET GRATIS: Satu Tombol Gelap Sesuai Figma -->
                                <a href="/library" class="btn btn-add-library">Tambahkan ke Library</a>
                            <?php else: ?>
                                <!-- JIKA ASET BERBAYAR: Tombol Beli & Keranjang -->
                                <a href="/checkout?asset_id=<?= urlencode($assetId) ?>" class="btn btn-buy-primary">Beli</a>
                                <a href="/cart" class="btn btn-cart-secondary text-center text-decoration-none">Tambahkan ke Keranjang</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- ==================== AKHIR KOLOM KANAN ==================== -->

            </div>
        </div>
    </main>

    <!-- 3. Footer Bersih Tanpa Konflik Git -->
    <footer class="footer-main" id="tentang">
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
                            <a href="/#katalog" class="footer-link">Shader</a>
                            <a href="/#katalog" class="footer-link">VFX Partikel</a>
                            <a href="/#katalog" class="footer-link">Model 3D</a>
                        </div>
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Dukungan</h6>
                            <a href="#" class="footer-link">Dokumentasi</a>
                            <a href="#" class="footer-link">Hubungi Kami</a>
                            <a href="#" class="footer-link">Syarat &amp; Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3 border-top">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle & Skrip Interaktif -->
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

        // 3. Toggle Dropdown Profil Pengguna (Jika Sedang Login)
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdownMenu');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>