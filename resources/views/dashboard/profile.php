<?php
$storeName = $storeName ?? 'Toko Saya';
$initials  = $initials ?? 'AS';
$profile   = $profile ?? null;
$user      = $user ?? [];
$error     = $error ?? null;
$saved     = $saved ?? false;

$publicId = (string) ($_SESSION['public_id'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Penjual - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <nav class="navbar navbar-orange sticky-top">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
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

            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Beranda">
                <span class="avatar-circle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="fw-bold fs-7 tracking-wide"><?= htmlspecialchars(mb_strtoupper($storeName), ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        </div>
    </nav>

    <div class="dashboard-layout-container">

        <aside class="dashboard-sidebar">
            <ul class="nav flex-column sidebar-nav-list">
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/assets" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>Semua Aset Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/transactions" class="sidebar-link">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/profile" class="sidebar-link active">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="dashboard-main-content">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="dash-page-title mb-1">Profil Penjual</h2>
                    <p class="text-muted small m-0">Kelola identitas publik toko dan rekening pencairan hasil penjualan Anda.</p>
                </div>
                <a href="/creator/profile?id=<?= urlencode($publicId) ?>" class="btn btn-outline-secondary btn-sm fw-bold px-3">
                    <i class="bi bi-eye me-1"></i>Lihat Profil Publik Toko
                </a>
            </div>

            <?php if ($saved): ?>
                <div class="alert alert-success py-2 px-3 mb-4 rounded-3 small">
                    <i class="bi bi-check-circle-fill me-1"></i> Perubahan profil penjual berhasil disimpan dan diperbarui!
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 small"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form method="post" action="/dashboard/profile">

                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Penjual</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="seller_name" class="form-label form-field-label">Nama Toko / Kreator*</label>
                            <input type="text" class="form-control form-field-input" id="seller_name" name="store_name"
                                value="<?= htmlspecialchars((string) ($profile['store_name'] ?? $user['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                maxlength="150" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contact_email" class="form-label form-field-label">Email Kontak*</label>
                            <input type="email" class="form-control form-field-input" id="contact_email"
                                value="<?= htmlspecialchars((string) ($user['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                readonly style="background-color: #faf5f0;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label form-field-label">Bio &amp; Deskripsi Kreator</label>
                        <textarea class="form-control form-field-input" id="bio" name="bio" rows="3"
                            placeholder="Ceritakan tentang spesialisasi aset yang Anda buat..."><?= htmlspecialchars((string) ($user['bio'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="portfolio_url" class="form-label form-field-label">Tautan Portofolio (ArtStation / Behance)</label>
                            <input type="url" class="form-control form-field-input" id="portfolio_url" name="portfolio_url"
                                value="<?= htmlspecialchars((string) ($profile['portfolio_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="https://www.artstation.com/nama_anda">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="website_url" class="form-label form-field-label">Tautan GitHub / Website</label>
                            <input type="url" class="form-control form-field-input" id="website_url" name="website_url"
                                value="<?= htmlspecialchars((string) ($profile['website_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="https://github.com/nama_anda">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="address" class="form-label form-field-label">Alamat Domisili</label>
                        <textarea class="form-control form-field-input" id="address" name="address" rows="2"
                            placeholder="Kota, Provinsi"><?= htmlspecialchars((string) ($profile['address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>

                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pencairan Dana (E-Wallet)</h5>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="payout_number" class="form-label form-field-label">Nomor DANA / Rekening</label>
                            <input type="text" class="form-control form-field-input" id="payout_number" name="payout_number"
                                value="<?= htmlspecialchars((string) ($profile['payout_number'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Contoh: 08123456789">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="payout_account_name" class="form-label form-field-label">Nama Pemilik Akun</label>
                            <input type="text" class="form-control form-field-input" id="payout_account_name" name="payout_account_name"
                                value="<?= htmlspecialchars((string) ($profile['payout_account_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Sesuai nama rekening">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-3 mb-5">
                    <a href="/dashboard" class="btn btn-profile-cancel">Batal</a>
                    <button type="submit" class="btn btn-profile-save">Simpan Perubahan</button>
                </div>

            </form>

        </main>
    </div>

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