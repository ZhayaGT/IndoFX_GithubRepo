<?php
// Tangkap URL Supabase dari env dan buat Base Path seperti di index.php
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? $_SERVER['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';

// 1. Ambil data sesi utama (Siapa yang sedang login)
$isLoggedIn = isset($_SESSION['user_id']);
$sessionPublicId = (string) ($_SESSION['public_id'] ?? '');

// 2. Data Pengguna yang Sedang Dilihat (Dikirim oleh Controller)
$userData = $user ?? []; 
$viewedIsSeller = ($userData['role'] ?? 'buyer') === 'seller';
$isOwnProfile = $isOwnProfile ?? false;

// 3. Penentuan Nama & Bio: Hindari bentrok dengan navbar-auth (Gunakan awalan $viewed)
$viewedName = '';
if ($viewedIsSeller && !empty($userData['store_name'])) {
    $viewedName = $userData['store_name'];
} else {
    $viewedName = $userData['name'] ?? 'Pengguna';
}
$viewedName = htmlspecialchars($viewedName, ENT_QUOTES, 'UTF-8');
$viewedBio = htmlspecialchars($userData['bio'] ?? 'Belum ada bio atau deskripsi.', ENT_QUOTES, 'UTF-8');

// 4. Menyiapkan Inisial Avatar
$viewedInitials = '';
$nameParts = preg_split('/\s+/', trim($viewedName)) ?: [];
foreach (array_slice($nameParts, 0, 2) as $p) {
    $viewedInitials .= mb_strtoupper(mb_substr($p, 0, 1));
}
if ($viewedInitials === '') $viewedInitials = 'U';

// 5. Data Katalog / Library dari Database
$assetsData = $assetsList ?? []; 
$totalAssets = count($assetsData);

// 6. Tema Visual
$bannerColor = $viewedIsSeller ? '#35211B' : 'linear-gradient(135deg, #7a1919 0%, #a32424 100%)';
$avatarTextColor = $viewedIsSeller ? '#35211B' : '#7a1919';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewedName ?> - IndoFX</title>

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
            width: 100%;
        }

        .seller-figma-body {
            padding: 0 36px 32px 36px;
            display: flex;
            align-items: flex-start;
            gap: 28px;
            position: relative;
        }

        /* Diperbarui dengan !important untuk mengalahkan Dark Mode browser */
        .seller-avatar-large {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #ffffff !important;
            border: 5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            margin-top: -65px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 800;
            z-index: 10;
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

        .pipe-urp { background-color: #c92a2a; }
        .pipe-hdrp { background-color: #5c3582; }
        .pipe-builtin { background-color: #0b72b9; }

        @media (max-width: 768px) {
            .seller-figma-body {
                flex-direction: column;
                align-items: center;
                text-align: center;
                padding: 0 20px 24px 20px;
            }
            .seller-avatar-large { margin-top: -60px; }
            .seller-title-row { justify-content: center; }
            .seller-social-links { justify-content: center; gap: 16px; }
        }
    </style>
</head>

<body>
    <!-- SVG Helper Opsional -->
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

            <?php
                $searchPartial = BASE_PATH . '/resources/views/partials/navbar-search.php';
                if (file_exists($searchPartial)) {
                    require $searchPartial;
                }
            ?>

            <div class="d-flex align-items-center gap-2">
                <?php
                // Navbar di-load di sini secara aman tanpa merusak variabel
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
                <!-- Tema warna disesuaikan oleh PHP (Merah untuk pembeli, Cokelat untuk Penjual) -->
                <div class="seller-figma-banner" style="background: <?= $bannerColor ?>;"></div>

                <div class="seller-figma-body">
                    <!-- Penambahan properti !important untuk mencegah masalah Dark Mode Browser -->
                    <div class="seller-avatar-large" style="color: <?= $avatarTextColor ?> !important; background-color: #ffffff !important;">
                        <?= $viewedInitials ?>
                    </div>

                    <div class="seller-info-content">
                        <div class="seller-title-row">
                            <h1 class="seller-name-heading"><?= $viewedName ?></h1>
                            
                            <?php if ($viewedIsSeller): ?>
                                <span class="badge-verified-figma">
                                    <i class="bi bi-patch-check-fill"></i> Penjual Terverifikasi
                                </span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.72rem;">Member Pembeli</span>
                            <?php endif; ?>
                        </div>

                        <p class="seller-bio-text"><?= nl2br($viewedBio) ?></p>

                        <!-- Tautan Sosial Khusus Penjual (Disembunyikan jika pembeli biasa) -->
                        <?php if ($viewedIsSeller): ?>
                            <div class="seller-social-links">
                                <?php if (!empty($userData['portofolio_url'])): ?>
                                    <a href="<?= htmlspecialchars($userData['portofolio_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="seller-social-link">
                                        <i class="bi bi-palette"></i> <span>ArtStation</span>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($userData['website_url'])): ?>
                                    <a href="<?= htmlspecialchars($userData['website_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="seller-social-link">
                                        <i class="bi bi-github"></i> <span>Github</span>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($userData['contact_email'] ?? $userData['email'])): ?>
                                    <a href="mailto:<?= htmlspecialchars($userData['contact_email'] ?? $userData['email'], ENT_QUOTES, 'UTF-8') ?>" class="seller-social-link">
                                        <i class="bi bi-envelope"></i> <span>Kontak E-Mail</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. SEKSI KATALOG (Privasi dilindungi oleh PHP) -->
            <?php 
            // LOGIKA PRIVASI: Jika profil pembeli biasa dilihat oleh publik/orang lain, SEMBUNYIKAN
            if (!$viewedIsSeller && !$isOwnProfile): 
                // Tidak melakukan render HTML apapun di sini
            else: 
            ?>
                <section class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="catalog-seller-heading mb-0">
                            <?= $viewedIsSeller ? "Dari <span>{$viewedName}</span>" : "Aset Dimiliki ({$totalAssets})" ?>
                        </h3>
                        
                        <?php if (!$viewedIsSeller && $isOwnProfile): ?>
                            <a href="/library" style="font-size: 0.85rem; font-weight: 700; color: #1a1a1a; text-decoration: underline;">Library Saya</a>
                        <?php endif; ?>
                    </div>

                    <!-- Wadah Kartu Aset Dinamis dari Database -->
                    <div class="row g-3">
                        <?php if ($totalAssets === 0): ?>
                            <div class="col-12">
                                <div class="card border rounded-3 text-center p-5 bg-white shadow-sm" style="border-color: #ebdcd1 !important;">
                                    <i class="bi <?= $viewedIsSeller ? 'bi-shop' : 'bi-box-seam' ?> fs-1 text-secondary mb-2"></i>
                                    <h5 class="fw-bold text-dark mb-1">
                                        <?= $viewedIsSeller ? "Belum Ada Aset dari {$viewedName}" : "Belum Ada Aset Dimiliki" ?>
                                    </h5>
                                    <p class="text-muted small mb-3">
                                        <?= $viewedIsSeller ? 'Toko ini belum merilis aset visual ke marketplace.' : 'Aset yang Anda beli atau klaim gratis akan tersimpan di Library Anda.' ?>
                                    </p>
                                    
                                    <?php if ($isOwnProfile): ?>
                                        <div>
                                            <a href="<?= $viewedIsSeller ? '/dashboard/add-asset' : '/catalog/explore' ?>" class="btn btn-hero-orange px-4 py-2" style="background-color: #ED6B1D; color:white; border:none; border-radius:6px; font-weight:600;">
                                                <?= $viewedIsSeller ? '+ Unggah Aset Baru' : 'Jelajahi Katalog' ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Melakukan perulangan data langsung dari database -->
                            <?php foreach ($assetsData as $asset): ?>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="card-asset-figma">
                                        <a href="/asset/detail?id=<?= htmlspecialchars($asset['public_id'], ENT_QUOTES, 'UTF-8') ?>" class="card-asset-thumb d-block">
                                            <?php 
                                                $thumbnailFile = $asset['thumbnail'] ?? ''; 
                                                
                                                if (empty($thumbnailFile)) {
                                                    $imageUrl = "https://via.placeholder.com/400x220/2C3A47/FFFFFF?text=Gambar+Kosong";
                                                } elseif (strpos($thumbnailFile, 'http') === 0) {
                                                    $imageUrl = $thumbnailFile; 
                                                } else {
                                                    // Menggabungkan Base Path dengan nama file (misal: 01.jpg)
                                                    $imageUrl = $storageBasePath . $thumbnailFile;
                                                }
                                            ?>

                                            <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>" 
                                                 style="width: 100%; height: 100%; object-fit: cover;" 
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Thumbnail">
                                            <svg style="display: none; width: 100%; height: 100%;" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice"><use href="#castle-asset-thumb" /></svg>
                                        </a>
                                        <div class="card-asset-body">
                                            <div>
                                                <span class="card-asset-studio"><?= htmlspecialchars($asset['seller_name'] ?? $viewedName, ENT_QUOTES, 'UTF-8') ?></span>
                                                <a href="/asset/detail?id=<?= htmlspecialchars($asset['public_id'], ENT_QUOTES, 'UTF-8') ?>" class="card-asset-title text-truncate">
                                                    <?= htmlspecialchars($asset['title'], ENT_QUOTES, 'UTF-8') ?>
                                                </a>
                                                <div class="d-flex flex-wrap gap-1 mt-1">
                                                    <?php 
                                                        // Memecah format render pipeline (misal: "URP, HDRP")
                                                        $pipelines = explode(',', $asset['render_pipeline'] ?? 'Built-In');
                                                        foreach ($pipelines as $pipe): 
                                                            $p = trim($pipe);
                                                            $cls = 'pipe-urp';
                                                            if (stripos($p, 'HDRP') !== false) $cls = 'pipe-hdrp';
                                                            if (stripos($p, 'Built') !== false) $cls = 'pipe-builtin';
                                                    ?>
                                                        <span class="pipe-badge <?= $cls ?>"><?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
            </div>
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>