<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$publicId = (string) ($_SESSION['public_id'] ?? $user['public_id'] ?? '');
$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');

// Ambil data murni dari database (dikirim oleh ProfileController::dashboardProfile)
$storeName         = (string) ($sellerProfile['store_name'] ?? $user['store_name'] ?? '');
$contactEmail      = (string) ($sellerProfile['contact_email'] ?? $user['email'] ?? $_SESSION['email'] ?? '');
$bio               = (string) ($sellerProfile['bio'] ?? $user['bio'] ?? '');
$portfolioUrl      = (string) ($sellerProfile['portfolio_url'] ?? $user['portfolio_url'] ?? '');
$websiteUrl        = (string) ($sellerProfile['website_url'] ?? $user['website_url'] ?? '');
$payoutNumber      = (string) ($sellerProfile['payout_number'] ?? $sellerProfile['dana_number'] ?? $user['payout_number'] ?? '');
$payoutAccountName = (string) ($sellerProfile['payout_account_name'] ?? $sellerProfile['dana_account_name'] ?? $user['payout_account_name'] ?? '');

// Inisial & nama tampilan header navbar
$headerDisplayName = $storeName !== '' ? $storeName : $userName;
$initials = '';
$words = preg_split('/\s+/', trim($headerDisplayName)) ?: [];
foreach (array_slice($words, 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
if ($initials === '') {
    $initials = 'S';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Penjual - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <!-- 1. Header Navbar Oranye Dashboard -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            <!-- Logo IndoFX Kiri -->
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

            <!-- Profil Kreator Kanan Dinamis -->
            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Beranda">
                <span class="avatar-circle" id="navDashAvatar"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="fw-bold fs-7 tracking-wide text-uppercase" id="navDashStoreName"><?= htmlspecialchars($headerDisplayName, ENT_QUOTES, 'UTF-8') ?></span>
            </a>

        </div>
    </nav>

    <!-- 2. Kontainer Utama -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI -->
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

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="dash-page-title mb-1">Profil Penjual</h2>
                    <p class="text-muted small m-0">Kelola identitas publik toko dan rekening pencairan hasil penjualan Anda.</p>
                </div>
                <a href="/creator/profile?id=<?= htmlspecialchars($publicId, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-outline-secondary btn-sm fw-bold px-3">
                    <i class="bi bi-eye me-1"></i>Lihat Profil Publik Toko
                </a>
            </div>

            <!-- Alert Notifikasi dari Backend -->
            <?php if (!empty($_SESSION['success_msg'])): ?>
                <div class="alert alert-success py-2 px-3 mb-4 rounded-3 small">
                    <i class="bi bi-check-circle-fill me-1"></i> <?= htmlspecialchars($_SESSION['success_msg'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['success_msg']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 small">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['error_msg']); ?>
            <?php endif; ?>

            <form id="formSellerProfile" action="/dashboard/ubah-profil-toko" method="POST">

                <!-- CARD 1: INFORMASI PENJUAL -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Penjual</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="store_name" class="form-label form-field-label">Nama Toko / Kreator*</label>
                            <input type="text" class="form-control form-field-input" id="store_name" name="store_name"
                                value="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contact_email" class="form-label form-field-label">Email Kontak*</label>
                            <input type="email" class="form-control form-field-input" id="contact_email" name="contact_email"
                                value="<?= htmlspecialchars($contactEmail, ENT_QUOTES, 'UTF-8') ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label form-field-label">Bio &amp; Deskripsi Kreator</label>
                        <textarea class="form-control form-field-input" id="bio" name="bio" rows="3"
                            placeholder="Ceritakan tentang spesialisasi aset yang Anda buat..."><?= htmlspecialchars($bio, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="portfolio_url" class="form-label form-field-label">Tautan Portofolio (ArtStation / Behance)</label>
                            <input type="url" class="form-control form-field-input" id="portfolio_url" name="portfolio_url"
                                value="<?= htmlspecialchars($portfolioUrl, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="https://www.artstation.com/nama_anda">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="website_url" class="form-label form-field-label">Tautan GitHub / Website</label>
                            <input type="text" class="form-control form-field-input" id="website_url" name="website_url"
                                value="<?= htmlspecialchars($websiteUrl, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="https://github.com/nama_anda">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: PENCAIRAN DANA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pencairan Dana (E-Wallet)</h5>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="dana_number" class="form-label form-field-label">Nomor DANA / Rekening</label>
                            <input type="text" class="form-control form-field-input" id="dana_number" name="dana_number"
                                value="<?= htmlspecialchars($payoutNumber, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Contoh: 08123456789">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="dana_account_name" class="form-label form-field-label">Nama Pemilik Akun</label>
                            <input type="text" class="form-control form-field-input" id="dana_account_name" name="dana_account_name"
                                value="<?= htmlspecialchars($payoutAccountName, ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Sesuai nama rekening">
                        </div>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex justify-content-end align-items-center gap-3 mb-5">
                    <a href="/dashboard" class="btn btn-profile-cancel">Batal</a>
                    <button type="submit" id="btnSubmitSeller" class="btn btn-profile-save">Simpan Perubahan</button>
                </div>

            </form>

        </main>
    </div>

    <!-- 3. Footer -->
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