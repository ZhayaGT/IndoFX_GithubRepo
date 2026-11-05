<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');

// 1. Cek parameter URL
$creatorParam = $_GET['name'] ?? $_GET['creator'] ?? null;
$isOwnProfile = isset($_GET['me']) && $_GET['me'] === '1';

if ($creatorParam !== null && trim($creatorParam) !== '') {
    $profileName = trim($creatorParam);
    $profileBio  = 'Game Asset Creator & VFX Artist';
} elseif ($isOwnProfile && $isLoggedIn) {
    $profileName = $userName;
    $profileBio  = !empty($_SESSION['bio']) ? $_SESSION['bio'] : 'Pengembang game dan visual enthusiast';
} else {
    $profileName = $userName ?: 'Arga Studio';
    $profileBio  = 'Pengembang game dan visual enthusiast';
}

// 2. Kalkulasi inisial avatar
$profileInitials = '';
$nameParts = preg_split('/\s+/', trim($profileName)) ?: [];
foreach (array_slice($nameParts, 0, 2) as $p) {
    $profileInitials .= mb_strtoupper(mb_substr($p, 0, 1));
}
if ($profileInitials === '') {
    $profileInitials = 'U';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Kreator - <?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?> | IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Utama Terpisah (Tanpa Inline CSS) -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>

    <!-- SVG Helper untuk Thumbnail Kartu Aset -->
    <svg class="d-none">
        <defs>
            <g id="castle-asset-thumb">
                <linearGradient id="skyGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#D4A39B" />
                    <stop offset="50%" stop-color="#E8C5B0" />
                    <stop offset="100%" stop-color="#F7D69D" />
                </linearGradient>
                <rect width="400" height="220" fill="url(#skyGrad)" />
                <circle cx="310" cy="85" r="42" fill="#FFFDF2" opacity="0.95" />
                <circle cx="310" cy="85" r="65" fill="#FFF7D6" opacity="0.4" />
                <ellipse cx="90" cy="50" rx="65" ry="18" fill="#B88E8D" opacity="0.35" />
                <path d="M0 165 Q120 135 250 160 T400 155 L400 220 L0 220 Z" fill="#6E575F" />
                <rect x="85" y="105" width="185" height="90" fill="#4A3B47" />
                <rect x="105" y="70" width="32" height="125" fill="#3B2E39" />
                <polygon points="100,70 121,32 142,70" fill="#2B2129" />
                <rect x="165" y="55" width="38" height="140" fill="#3B2E39" />
                <polygon points="160,55 184,15 208,55" fill="#2B2129" />
                <rect x="235" y="65" width="36" height="130" fill="#59484F" />
                <polygon points="230,65 253,25 276,65" fill="#3B2E39" />
                <rect x="179" y="85" width="8" height="14" rx="4" fill="#FFAE42" />
                <path d="M0 185 Q160 170 400 185 L400 220 L0 220 Z" fill="#2B2122" />
            </g>

            <g id="forest-mist-art">
                <linearGradient id="forestSky" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#8FA3B0" />
                    <stop offset="60%" stop-color="#C9D6DF" />
                    <stop offset="100%" stop-color="#3A4740" />
                </linearGradient>
                <rect width="400" height="220" fill="url(#forestSky)" />
                <path d="M0 140 Q100 110 220 135 T400 120 L400 220 L0 220 Z" fill="#52615B" opacity="0.6" />
                <polygon points="50,40 25,150 75,150" fill="#2D3A34" />
                <polygon points="85,55 65,155 105,155" fill="#3B4B44" />
                <polygon points="310,30 280,155 340,155" fill="#25302B" />
                <ellipse cx="200" cy="135" rx="180" ry="25" fill="#E4EEF5" opacity="0.45" />
                <path d="M0 155 Q110 140 175 220 L0 220 Z" fill="#1A241E" />
                <path d="M400 150 Q280 140 225 220 L400 220 Z" fill="#16201A" />
                <polygon points="175,220 198,148 212,148 225,220" fill="#3E3A35" />
            </g>
        </defs>
    </svg>

    <!-- 1. Header Navbar Oranye Presisi -->
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

    <!-- 2. Konten Profil Kreator / Pengguna -->
    <main class="py-5">
        <div class="container-figma">

            <!-- Card Banner & Informasi Kreator Sesuai Figma -->
            <div class="creator-profile-card mb-5">
                <div class="creator-banner-maroon"></div>

                <div class="creator-info-body">
                    <div class="creator-avatar-float">
                        <span id="creatorAvatarText"><?= htmlspecialchars($profileInitials, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="creator-details">
                        <h2 class="creator-name" id="creatorNameText"><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="creator-bio mb-0" id="creatorBioText"><?= htmlspecialchars($profileBio, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>

            <!-- Seksi Aset Dimiliki (Dinamis dari Library) -->
            <section class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="catalog-heading mb-0">Aset dimiliki (<span id="ownedAssetCount">0</span>)</h4>
                    <a href="/library" class="catalog-side-link">Library Saya</a>
                </div>

                <!-- Wadah Grid Kartu Aset Dinamis -->
                <div class="row g-3" id="ownedAssetsContainer"></div>
            </section>

        </div>
    </main>

    <!-- 3. Footer Putih Full-Width Sesuai Figma -->
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
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Hidrasi Data Profil & Aset Dimiliki dari Library -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userKey = '<?= $userKey ?>';
            const isOwnProfile = <?= $isOwnProfile ? 'true' : 'false' ?>;

            // 1. Sinkronisasi Data Profil (Nama, Inisial Avatar, Bio)
            if (isOwnProfile || !window.location.search.includes('name=')) {
                const sellerProfileRaw = localStorage.getItem('indofx_seller_profile_' + userKey) || localStorage.getItem('indofx_seller_profile');
                if (sellerProfileRaw) {
                    try {
                        const seller = JSON.parse(sellerProfileRaw);
                        if (seller.store_name) {
                            const nameEl = document.getElementById('creatorNameText');
                            if (nameEl) nameEl.innerText = seller.store_name;

                            const avatarEl = document.getElementById('creatorAvatarText');
                            if (avatarEl) {
                                const parts = seller.store_name.trim().split(/\s+/);
                                let inits = '';
                                parts.slice(0, 2).forEach(p => inits += p.charAt(0).toUpperCase());
                                avatarEl.innerText = inits || 'AS';
                            }
                        }
                        if (seller.reason || seller.address) {
                            const bioEl = document.getElementById('creatorBioText');
                            if (bioEl) bioEl.innerText = seller.reason || 'Kreator dan developer aset game di IndoFX';
                        }
                    } catch (e) {
                        console.error('Gagal memuat profil:', e);
                    }
                }
            }

            // 2. Baca Aset Asli yang Ada di Library Saya (Milik Akun Ini)
            const container = document.getElementById('ownedAssetsContainer');
            const countEl = document.getElementById('ownedAssetCount');

            let ownedAssets = [];
            try {
                const rawLib = localStorage.getItem('indofx_library_' + userKey) || localStorage.getItem('indofx_library');
                if (rawLib) {
                    ownedAssets = JSON.parse(rawLib);
                }
            } catch (e) {
                ownedAssets = [];
            }

            // Fallback: periksa juga penyimpanan pembelian bila ada
            if (!Array.isArray(ownedAssets) || ownedAssets.length === 0) {
                try {
                    const rawPurchases = localStorage.getItem('indofx_purchases_' + userKey) || localStorage.getItem('indofx_user_purchases');
                    if (rawPurchases) {
                        ownedAssets = JSON.parse(rawPurchases);
                    }
                } catch (e) {}
            }

            // Perbarui indikator angka jumlah aset dimiliki
            if (countEl) {
                countEl.innerText = Array.isArray(ownedAssets) ? ownedAssets.length : 0;
            }

            // Tampilan jika pengguna belum mengklaim / membeli aset sama sekali
            if (!Array.isArray(ownedAssets) || ownedAssets.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="card border rounded-3 text-center p-5 bg-white shadow-sm">
                            <div class="mb-3">
                                <i class="bi bi-box-seam text-secondary fs-1"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Belum Ada Aset Dimiliki</h5>
                            <p class="text-muted small mb-4">Aset yang Anda klaim gratis atau beli di marketplace IndoFX akan otomatis tersimpan di sini dan dapat diunduh kapan saja melalui Library Saya.</p>
                            <div>
                                <a href="/catalog/explore" class="btn btn-hero-orange px-4 py-2">Jelajahi Katalog Aset</a>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            // Render Kartu Aset yang Dimiliki
            container.innerHTML = ownedAssets.map(asset => {
                const title = asset.title || asset.name || 'Game Asset Pack';
                const studio = asset.creator || asset.author || asset.studio || 'Studio Kreator';
                const detailUrl = asset.slug ? ('/asset/detail?slug=' + encodeURIComponent(asset.slug)) : ('/asset/detail?id=' + encodeURIComponent(asset.id || '1'));
                const pipelines = Array.isArray(asset.pipelines) ? asset.pipelines : (asset.pipeline ? [asset.pipeline] : ['URP']);

                const pipelineHtml = pipelines.map(pipe => {
                    const p = String(pipe).toLowerCase();
                    let badgeClass = 'badge-urp';
                    if (p.includes('hdrp')) badgeClass = 'badge-hdrp';
                    else if (p.includes('built')) badgeClass = 'badge-builtin';
                    return `<span class="badge-pipeline ${badgeClass} me-1">${pipe}</span>`;
                }).join('');

                const artId = (asset.art === 'forest' || asset.art === 'forest-mist-art') ? 'forest-mist-art' : 'castle-asset-thumb';

                return `
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="asset-card-figma h-100 d-flex flex-column">
                            <a href="${detailUrl}" class="asset-thumb-wrapper d-block">
                                <svg width="100%" height="175" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                                    <use href="#${artId}" />
                                </svg>
                            </a>
                            <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                <div>
                                    <span class="asset-studio">${studio}</span>
                                    <a href="${detailUrl}" class="text-decoration-none d-block">
                                        <h6 class="asset-name text-truncate" title="${title}">${title}</h6>
                                    </a>
                                    <div class="d-flex flex-wrap gap-1 mb-2">
                                        ${pipelineHtml}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="small fw-bold text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Dimiliki
                                    </span>
                                    <a href="/library" class="text-decoration-none text-muted small fw-bold" title="Buka di Library">
                                        Unduh <i class="bi bi-download ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        });
    </script>
</body>

</html>