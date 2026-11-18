<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');

$creatorParam = $_GET['name'] ?? $_GET['creator'] ?? null;
$isOwnProfile = isset($_GET['me']) && $_GET['me'] === '1';

if ($creatorParam !== null && trim($creatorParam) !== '') {
    $profileName = trim($creatorParam);
    $profileBio  = 'Game Asset Creator & VFX Artist';
} elseif ($isOwnProfile && $isLoggedIn) {
    $profileName = $userName;
    $profileBio  = !empty($_SESSION['bio']) ? $_SESSION['bio'] : 'Pengembang independen aset visual Unity berkualitas tinggi.';
} else {
    $profileName = $userName ?: 'Arga Studio';
    $profileBio  = !empty($_SESSION['bio']) ? $_SESSION['bio'] : 'Pengembang independen aset visual Unity berkualitas tinggi.';
}

$profileInitials = '';
$nameParts = preg_split('/\s+/', trim($profileName)) ?: [];
foreach (array_slice($nameParts, 0, 2) as $p) {
    $profileInitials .= mb_strtoupper(mb_substr($p, 0, 1));
}
if ($profileInitials === '') {
    $profileInitials = 'AS';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?> - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #FAF0E6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        /* 1. KARTU PROFIL PENJUAL SESUAI FIGMA */
        .seller-figma-card {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(60, 42, 33, 0.08);
            border: 1px solid #ebdcd1;
            margin-bottom: 36px;
        }

        .seller-figma-banner {
            height: 175px;
            background-color: #35211B;
            width: 100%;
        }

        .seller-figma-body {
            padding: 0 36px 32px 36px;
            display: flex;
            align-items: flex-start;
            gap: 28px;
            position: relative;
        }

        .seller-avatar-large {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #ffffff;
            border: 5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            margin-top: -65px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 800;
            color: #35211B;
            background-color: #FAF4EF;
        }

        .seller-info-content {
            padding-top: 16px;
            flex-grow: 1;
        }

        .seller-title-row {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 6px;
        }

        .seller-name-heading {
            font-size: 2.1rem;
            font-weight: 800;
            color: #2B1D16;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .badge-verified-figma {
            background-color: #28a745;
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .seller-bio-text {
            color: #5C4B41;
            font-size: 0.95rem;
            line-height: 1.55;
            margin-bottom: 22px;
            max-width: 720px;
        }

        .seller-social-links {
            display: flex;
            align-items: center;
            gap: 26px;
            flex-wrap: wrap;
        }

        .seller-social-link {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.88rem;
            font-weight: 600;
            color: #6C5A50;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        .seller-social-link:hover {
            color: var(--ifx-orange, #ED6B1D);
        }

        .seller-social-link i {
            font-size: 1.15rem;
        }

        /* 2. DAFTAR ASET TOKO FIGMA */
        .catalog-seller-heading {
            font-size: 1.4rem;
            font-weight: 800;
            color: #2B1D16;
            margin-bottom: 20px;
        }

        .catalog-seller-heading span {
            font-weight: 800;
        }

        .card-asset-figma {
            background-color: #ffffff;
            border: 1px solid #ebdcd1;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(60, 42, 33, 0.04);
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .card-asset-figma:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(60, 42, 33, 0.08);
        }

        .card-asset-thumb {
            height: 175px;
            width: 100%;
            background-color: #2b2129;
            overflow: hidden;
        }

        .card-asset-thumb svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .card-asset-body {
            padding: 12px 14px 14px 14px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .card-asset-studio {
            font-size: 0.74rem;
            color: #7A685D;
            margin-bottom: 2px;
            display: block;
        }

        .card-asset-title {
            font-size: 0.92rem;
            font-weight: 800;
            color: #2B1D16;
            text-decoration: none;
            display: block;
            margin-bottom: 8px;
            line-height: 1.35;
        }

        .card-asset-title:hover {
            color: var(--ifx-orange, #ED6B1D);
        }

        .pipe-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 1.5px 6px;
            border-radius: 3px;
            color: #ffffff;
            display: inline-block;
        }

        .pipe-urp {
            background-color: #c92a2a;
        }

        .pipe-hdrp {
            background-color: #5c3582;
        }

        .pipe-builtin {
            background-color: #0b72b9;
        }

        @media (max-width: 768px) {
            .seller-figma-body {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 0 20px 24px 20px;
            }

            .seller-avatar-large {
                margin-top: -60px;
            }

            .seller-title-row {
                justify-content: center;
            }

            .seller-social-links {
                justify-content: center;
                gap: 16px;
            }
        }
    </style>
</head>

<body>

    <!-- SVG Helper untuk Thumbnail Kartu Aset -->
    <svg class="d-none">
        <defs>
            <g id="castle-asset-thumb">
                <rect width="400" height="220" fill="#241E24" />
                <ellipse cx="200" cy="120" rx="170" ry="40" fill="#433742" opacity="0.5" />
                <polygon points="100,50 80,190 125,190" fill="#151115" />
                <polygon points="200,35 165,195 235,195" fill="#100D10" />
                <polygon points="300,45 275,190 320,190" fill="#151115" />
                <circle cx="200" cy="85" r="8" fill="#C4B5A5" opacity="0.8" />
            </g>
            <g id="forest-mist-art">
                <rect width="400" height="220" fill="#1E2A28" />
                <ellipse cx="200" cy="130" rx="180" ry="45" fill="#4E6D66" opacity="0.45" />
                <polygon points="70,45 40,200 100,200" fill="#141E1C" />
                <polygon points="150,30 115,210 185,210" fill="#182421" />
                <polygon points="260,25 220,215 300,215" fill="#0E1615" />
                <polygon points="340,40 310,200 370,200" fill="#141E1C" />
                <circle cx="200" cy="100" r="7" fill="#E8F1EF" opacity="0.75" />
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
                <span class="brand-text">IndoFX</span>
            </a>

            <div class="search-box-header d-none d-md-flex align-items-center">
                <i class="bi bi-search search-icon-header"></i>
                <input type="text" class="form-control search-input-header" placeholder="Cari aset...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- Konten Profil Kreator / Pengguna -->
    <main class="py-5 flex-grow-1">
        <div class="container-figma">

            <!-- 1. KARTU BANNER & IDENTITAS KREATOR SESUAI FIGMA -->
            <div class="seller-figma-card">
                <div class="seller-figma-banner"></div>

                <div class="seller-figma-body">
                    <div class="seller-avatar-large" id="creatorAvatarText">
                        <?= htmlspecialchars($profileInitials, ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div class="seller-info-content">
                        <div class="seller-title-row">
                            <h1 class="seller-name-heading" id="creatorNameText"><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?></h1>
                            <span id="roleBadgeContainer">
                                <span class="badge-verified-figma">
                                    <i class="bi bi-patch-check-fill"></i> Penjual Terverifikasi
                                </span>
                            </span>
                        </div>

                        <p class="seller-bio-text" id="creatorBioText"><?= htmlspecialchars($profileBio, ENT_QUOTES, 'UTF-8') ?></p>

                        <!-- Tautan Sosial Khusus Penjual (Sesuai Figma) -->
                        <div class="seller-social-links" id="sellerSocialLinksContainer">
                            <a href="#" id="linkArtStation" target="_blank" class="seller-social-link">
                                <i class="bi bi-palette"></i> <span>ArtStation</span>
                            </a>
                            <a href="#" id="linkGithub" target="_blank" class="seller-social-link">
                                <i class="bi bi-github"></i> <span>Github</span>
                            </a>
                            <a href="#" id="linkEmail" class="seller-social-link">
                                <i class="bi bi-envelope"></i> <span>Kontak E-Mail</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SEKSI KATALOG: DARI [NAMA TOKO] -->
            <section class="mb-5">
                <h3 class="catalog-seller-heading" id="sectionCatalogTitle">
                    Dari <span id="sectionStoreName"><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?></span>
                </h3>

                <!-- Wadah Kartu Aset Dinamis -->
                <div class="row g-3" id="assetsDisplayContainer"></div>
            </section>

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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <script>
        const sellerProfile = <?= json_encode($profile ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const sellerAssets = <?= json_encode($assets ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        document.addEventListener('DOMContentLoaded', function() {
            const storeName = <?= json_encode((string) ($profileName ?? ''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const displayName = sellerProfile.store_name || storeName;
            document.getElementById('creatorNameText').textContent = displayName;
            document.getElementById('sectionStoreName').textContent = displayName;
            document.getElementById('creatorBioText').textContent = sellerProfile.bio || <?= json_encode((string) $profileBio, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const links = { linkArtStation: sellerProfile.portfolio_url, linkGithub: sellerProfile.website_url, linkEmail: sellerProfile.email ? 'mailto:' + sellerProfile.email : '' };
            Object.keys(links).forEach(id => { const link = document.getElementById(id); if (link && links[id]) link.href = links[id]; });
            const container = document.getElementById('assetsDisplayContainer');
            container.innerHTML = sellerAssets.length ? sellerAssets.map(asset => `<div class="col-12 col-sm-6 col-md-3"><div class="card-asset-figma"><a href="/asset/detail?id=${encodeURIComponent(asset.public_id || '')}" class="card-asset-thumb d-block"><svg viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice"><use href="#castle-asset-thumb" /></svg></a><div class="card-asset-body"><span class="card-asset-studio">${esc(displayName)}</span><a href="/asset/detail?id=${encodeURIComponent(asset.public_id || '')}" class="card-asset-title text-truncate">${esc(asset.title || '')}</a></div></div></div>`).join('') : '<div class="col-12"><div class="card border rounded-3 text-center p-5 bg-white"><h5 class="fw-bold text-dark mb-1">Belum Ada Aset</h5><p class="text-muted small mb-0">Toko ini belum merilis aset visual ke marketplace.</p></div></div>';
        });
    </script>
</body>

</html>