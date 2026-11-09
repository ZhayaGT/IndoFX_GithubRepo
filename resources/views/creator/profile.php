<?php
$creator  = $creator ?? [];
$profile  = $profile ?? null;
// Disalin di awal: partial navbar memakai nama variabel $isSeller.
$creatorIsSeller = $isSeller ?? (($creator['role'] ?? '') === 'seller');
$assets   = $assets ?? [];
$stats    = $stats ?? ['total' => 0, 'sold' => 0, 'downloads' => 0, 'published' => 0];

$creatorTitle = $creatorIsSeller
    ? (string) ($profile['store_name'] ?? '') ?: (string) ($creator['name'] ?? 'Toko IndoFX')
    : (string) ($creator['name'] ?? 'Pengguna IndoFX');

$creatorBio = (string) ($creator['bio'] ?? '');

$creatorInitials = '';
foreach (array_slice(preg_split('/\s+/', trim($creatorTitle)) ?: [], 0, 2) as $word) {
    $creatorInitials .= mb_strtoupper(mb_substr($word, 0, 1));
}
$creatorInitials = $creatorInitials !== '' ? $creatorInitials : 'IX';

$joined = date('M Y', strtotime((string) ($creator['created_at'] ?? 'now')));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($creatorTitle, ENT_QUOTES, 'UTF-8') ?> - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        .creator-banner-maroon {
            height: 175px;
            background-color: #35211B;
            border-radius: 12px 12px 0 0;
        }

        .creator-card {
            background-color: #ffffff;
            border: 1px solid #ebdcd1;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(60, 42, 33, 0.08);
            margin-bottom: 36px;
        }

        .creator-avatar-float {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background-color: #FAF4EF;
            border: 5px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            margin-top: -65px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.8rem;
            font-weight: 800;
            color: #35211B;
            flex-shrink: 0;
        }

        .creator-name {
            font-size: 2.1rem;
            font-weight: 800;
            color: #2B1D16;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .creator-bio {
            color: #5C4B41;
            font-size: 0.95rem;
            line-height: 1.55;
            margin: 6px 0 0;
        }

        .badge-seller-role {
            background-color: #28a745;
            color: #ffffff;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 4px;
        }

        .creator-stat-value {
            font-size: 1.25rem;
            font-weight: 800;
            color: #2B1D16;
        }

        .creator-stat-label {
            font-size: 0.76rem;
            color: #7A685D;
        }

        .creator-section-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #2B1D16;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .creator-avatar-float {
                margin: -60px auto 0;
            }
        }
    </style>
</head>

<body>

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

    <main class="py-5 flex-grow-1">
        <div class="container-figma">

            <div class="creator-card">
                <div class="creator-banner-maroon"></div>

                <div class="d-flex gap-4 px-4 pb-4" style="align-items: flex-start; flex-wrap: wrap;">
                    <div class="creator-avatar-float"><?= htmlspecialchars($creatorInitials, ENT_QUOTES, 'UTF-8') ?></div>

                    <div class="pt-3 flex-grow-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h1 class="creator-name"><?= htmlspecialchars($creatorTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                            <?php if ($creatorIsSeller): ?>
                                <span class="badge-seller-role"><i class="bi bi-patch-check-fill"></i> Seller</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border" style="font-size: 0.72rem;">Pembeli</span>
                            <?php endif; ?>
                        </div>

                        <?php if ($creatorBio !== ''): ?>
                            <p class="creator-bio"><?= htmlspecialchars($creatorBio, ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>

                        <?php if ($creatorIsSeller): ?>
                            <div class="row g-4 mt-3">
                                <div class="col-6 col-md-3">
                                    <div class="creator-stat-value"><?= (int) $stats['total'] ?></div>
                                    <span class="creator-stat-label">Total Aset</span>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="creator-stat-value"><?= (int) $stats['sold'] ?></div>
                                    <span class="creator-stat-label">Terjual</span>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="creator-stat-value"><?= (int) $stats['downloads'] ?></div>
                                    <span class="creator-stat-label">Unduhan</span>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="creator-stat-value"><?= htmlspecialchars($joined, ENT_QUOTES, 'UTF-8') ?></div>
                                    <span class="creator-stat-label">Bergabung</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if ($creatorIsSeller): ?>
                <section class="mb-5">
                    <h3 class="creator-section-title">Aset dari <?= htmlspecialchars($creatorTitle, ENT_QUOTES, 'UTF-8') ?></h3>

                    <?php if ($assets === []): ?>
                        <p class="text-muted">Belum ada aset yang diterbitkan.</p>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($assets as $asset): ?>
                                <?php
                                $imageUrl = image_url($asset['thumbnail'] ?? '');
                                if ($imageUrl === '') {
                                    $imageUrl = 'https://via.placeholder.com/400x220/2C3A47/FFFFFF?text=Gambar+Kosong';
                                }
                                ?>
                                <div class="col-12 col-sm-6 col-lg-3">
                                    <div class="asset-card-figma h-100 d-flex flex-column">
                                        <a href="/asset/detail?id=<?= urlencode((string) $asset['public_id']) ?>" class="asset-thumb-wrapper d-block">
                                            <img src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars((string) $asset['title'], ENT_QUOTES, 'UTF-8') ?>" style="width: 100%; height: 175px; object-fit: cover;">
                                        </a>

                                        <div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">
                                            <div>
                                                <span class="asset-studio d-inline-block"><?= htmlspecialchars($creatorTitle, ENT_QUOTES, 'UTF-8') ?></span>

                                                <a href="/asset/detail?id=<?= urlencode((string) $asset['public_id']) ?>" class="text-decoration-none d-block">
                                                    <h6 class="asset-name"><?= htmlspecialchars((string) $asset['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                                                </a>

                                                <div class="d-flex gap-1 mb-2 flex-wrap">
                                                    <?php foreach (pipeline_badges($asset['render_pipeline'] ?? '') as $label): ?>
                                                        <span class="badge-pipeline <?= pipeline_badge_class($label) ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-1">
                                                <span class="asset-price"><?= format_rupiah((int) $asset['price']) ?></span>
                                                <i class="bi bi-download asset-dl-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

        </div>
    </main>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>