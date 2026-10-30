<?php
// 1. Cek apakah ada parameter nama kreator di URL (misal: /creator/profile?name=Arga+Studio)
$creatorParam = $_GET['name'] ?? $_GET['creator'] ?? null;

if ($creatorParam !== null && trim($creatorParam) !== '') {
    // Menampilkan profil publik kreator yang diklik
    $profileName = trim($creatorParam);
    $profileRole = 'seller';
    $profileBio  = 'Game Asset Creator & VFX Artist';
} elseif (isset($_GET['me']) && isset($_SESSION['name'])) {
    // Mengambil nama dan bio asli akun yang sedang login
    $profileName = $_SESSION['name'];
    $profileRole = $_SESSION['role'] ?? 'buyer';
    $profileBio  = !empty($_SESSION['bio']) ? $_SESSION['bio'] : 'Hanya game developer pemula :v';
} else {
    // Default jika dibuka tanpa parameter URL
    $profileName = 'Arga Studio';
    $profileRole = 'seller';
    $profileBio  = 'Game Asset Creator & VFX Artist';
}

// 2. Kalkulasi inisial avatar (misal: "Arga Studio" -> "AS", "Naufal Haidar" -> "NH")
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
    <title>Profil Kreator - <?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?> | IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Utama -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body style="background-color: var(--ifx-cream-bg, #FDECE2);">

    <!-- SVG Helper Castle Asset -->
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
        </defs>
    </svg>

    <!-- 1. Navbar Oranye Responsif -->
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
                <span class="brand-text">IndoFX</span>
            </a>

            <div class="search-box-header d-flex align-items-center flex-grow-1 mx-2 mx-md-4">
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

    <!-- 2. Konten Profil Publik -->
    <main class="py-5">
        <div class="container-figma">

            <!-- Card Banner & Informasi Kreator Sesuai Figma -->
            <div class="creator-profile-card mb-5">
                <div class="creator-banner-maroon"></div>

                <div class="creator-info-body">
                    <div class="creator-avatar-float">
                        <span><?= htmlspecialchars($profileInitials, ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="creator-details">
                        <h2 class="creator-name"><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?></h2>
                        <p class="creator-bio mb-0"><?= htmlspecialchars($profileBio, ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </div>
            </div>

            <!-- Seksi Aset Dimiliki -->
            <section class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="catalog-heading mb-0">Aset dimiliki</h4>
                    <a href="/library" class="catalog-side-link">Library Saya</a>
                </div>

                <div class="row g-3">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="asset-card-figma h-100 d-flex flex-column">
                                <a href="/asset/detail" class="asset-thumb-wrapper d-block">
                                    <svg width="100%" height="175" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">
                                        <use href="#castle-asset-thumb" />
                                    </svg>
                                </a>
                                <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                    <div>
                                        <a href="/creator/profile" class="asset-studio text-decoration-none d-inline-block"><?= htmlspecialchars($profileName, ENT_QUOTES, 'UTF-8') ?></a>
                                        <a href="/asset/detail" class="text-decoration-none d-block">
                                            <h6 class="asset-name text-truncate">Volumetric Rain &amp; Storm...</h6>
                                        </a>
                                        <div class="d-flex gap-1 mb-1">
                                            <span class="badge-pipeline badge-urp">URP</span>
                                            <span class="badge-pipeline badge-hdrp">HDRP</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </section>

        </div>
    </main>

    <!-- 3. Footer Sederhana Terpusat Sesuai Desain -->
    <footer class="footer-creator-center py-5">
        <div class="container text-center">
            <h5 class="fw-bold mb-2 text-dark">IndoFX</h5>
            <p class="text-muted small mb-3 mx-auto" style="max-width: 540px;">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>

            <div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
                <a href="#" class="footer-link-inline">Dukungan</a>
                <a href="#" class="footer-link-inline">Dokumentasi</a>
                <a href="#" class="footer-link-inline">Hubungi Kami</a>
                <a href="#" class="footer-link-inline">Syarat &amp; Ketentuan</a>
            </div>

            <div class="d-flex justify-content-between align-items-center text-muted small pt-3 border-top flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
</body>

</html>