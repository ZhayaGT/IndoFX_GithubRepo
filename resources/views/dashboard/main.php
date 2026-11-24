<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$sellerUsername = (string) ($_SESSION['username'] ?? $_SESSION['name'] ?? 'naufalhaidarazmi');
$sellerUserId   = (string) ($_SESSION['user_id'] ?? '');
$sellerInitials = mb_strtoupper(mb_substr($sellerUsername, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets Eksternal Murni -->
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
                <span class="avatar-circle" id="navDashAvatar"><?= htmlspecialchars($sellerInitials) ?></span>
                <span class="fw-bold fs-7 tracking-wide text-uppercase" id="navDashStoreName"><?= htmlspecialchars($sellerUsername) ?></span>
            </a>

        </div>
    </nav>

    <!-- 2. Kontainer Utama -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI (PUTIH) -->
        <aside class="dashboard-sidebar">
            <ul class="nav flex-column sidebar-nav-list">
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link active">
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
                    <a href="/dashboard/profile" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <!-- Header Judul Dinamis -->
            <div class="mb-4">
                <h2 class="dash-page-title mb-0">
                    Dashboard <span class="fw-normal" id="dashContentStoreName"><?= htmlspecialchars($storeName ?? $sellerUsername, ENT_QUOTES, 'UTF-8') ?></span>
                </h2>
            </div>

            <!-- 4 Summary Stat Cards 100% Dinamis -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Aset</span>
                        <div class="stat-card-value mb-1" id="statTotalAsset">0</div>
                        <span class="stat-card-sub text-muted" id="statAssetSub">0 Terpublikasi</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Aset Terjual</span>
                        <div class="stat-card-value mb-1" id="statSold">0x</div>
                        <span class="stat-card-sub text-muted">Transaksi Berhasil</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Unduhan</span>
                        <div class="stat-card-value mb-1" id="statDownloads">0</div>
                        <span class="stat-card-sub text-muted">Termasuk Aset Gratis</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Pendapatan</span>
                        <div class="stat-card-value mb-1" id="statRevenue">Rp0</div>
                        <span class="stat-card-sub text-muted">Developer &amp; Penjual</span>
                    </div>
                </div>
            </div>

            <!-- Bagian 1: Tabel Aset Baru Dinamis -->
            <section class="mb-5">
                <h5 class="fw-bold text-dark mb-3 fs-6">Aset Baru</h5>

                <div class="table-card-wrapper">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 32%;">Judul Aset</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Status Moderasi</th>
                                    <th scope="col">Tanggal Dibuat</th>
                                    <th scope="col" class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableAssetsBody">
                                <!-- Diisi otomatis dari gabungan antrean & storage -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Bagian 2: Tabel Transaksi Penjualan Dinamis -->
            <section class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0 fs-6">Transaksi Penjualan Terbaru</h5>
                    <a href="/dashboard/transactions" class="catalog-side-link">Lihat Semua Transaksi</a>
                </div>

                <div class="table-card-wrapper">
                    <div class="table-responsive">
                        <table class="table table-custom align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 25%;">No. Invoice</th>
                                    <th scope="col" style="width: 40%;">Judul Aset</th>
                                    <th scope="col">Tanggal Transaksi</th>
                                    <th scope="col" class="text-end pe-4">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody id="tableTransactionsBody">
                                <!-- Diisi otomatis dari riwayat transaksi -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- 3. Footer Minimal Baru -->
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
    <script src="/assets/js/indofx-utils.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const storeName    = <?= json_encode($storeName ?? $sellerUsername, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const stats        = <?= json_encode($stats ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const recentAssets = <?= json_encode($recentAssets ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const recentSales  = <?= json_encode($recentSales ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

            // 1. Nama Toko & Inisial
            const titleEl    = document.getElementById('dashContentStoreName');
            const navStoreEl = document.getElementById('navDashStoreName');
            const navAvEl    = document.getElementById('navDashAvatar');
            if (titleEl) titleEl.innerText = storeName;
            if (navStoreEl) navStoreEl.innerText = storeName;
            if (navAvEl) {
                const words = storeName.trim().split(/\s+/);
                navAvEl.innerText = words.slice(0, 2).map(w => w.charAt(0).toUpperCase()).join('') || 'S';
            }

            // 2. 4 Kartu Statistik
            document.getElementById('statTotalAsset').innerText = stats.total_assets || 0;
            document.getElementById('statAssetSub').innerText   = (stats.published_assets || 0) + ' Terpublikasi';
            document.getElementById('statSold').innerText       = (stats.total_sold || 0) + 'x';
            document.getElementById('statDownloads').innerText  = stats.total_downloads || 0;
            document.getElementById('statRevenue').innerText    = 'Rp' + Number(stats.total_revenue || 0).toLocaleString('id-ID');

            // 3. Tabel Aset Baru
            const assetsTbody = document.getElementById('tableAssetsBody');
            if (recentAssets.length === 0) {
                assetsTbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">Belum ada aset yang diunggah.</td>
                    </tr>
                `;
            } else {
                assetsTbody.innerHTML = recentAssets.map(item => {
                    const detailUrl = `/asset/detail?id=${encodeURIComponent(item.public_id)}`;
                    let badgeHtml = '<span class="badge-status-approved">Disetujui</span>';
                    if (item.status === 'Pending') {
                        badgeHtml = '<span class="badge-status-pending" style="background:#fef3c7; color:#92400e;">Pending</span>';
                    } else if (item.status === 'Ditolak') {
                        badgeHtml = '<span class="badge-status-rejected">Ditolak</span>';
                    }

                    return `
                        <tr>
                            <td class="fw-bold text-dark item-asset-title">${esc(item.title)}</td>
                            <td class="text-muted small">${esc(item.category)}</td>
                            <td class="fw-bold text-dark">${esc(item.price_text)}</td>
                            <td>${badgeHtml}</td>
                            <td class="text-muted small">${esc(item.created_at)}</td>
                            <td class="text-end pe-4 text-nowrap">
                                <a href="/dashboard/add-asset?edit_id=${encodeURIComponent(item.id)}" class="action-link me-2">Edit</a>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // 4. Tabel Transaksi Penjualan Terbaru
            const transTbody = document.getElementById('tableTransactionsBody');
            if (recentSales.length === 0) {
                transTbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi penjualan yang berhasil.</td>
                    </tr>
                `;
            } else {
                transTbody.innerHTML = recentSales.map(item => `
                    <tr>
                        <td class="fw-semibold">
                            <a href="/dashboard/transactions" class="action-link">${esc(item.invoice_no)}</a>
                        </td>
                        <td class="fw-bold text-dark item-asset-title">${esc(item.asset_title)}</td>
                        <td class="text-muted small">${esc(item.date)}</td>
                        <td class="text-end pe-4 fw-bold text-success">Rp${Number(item.price || 0).toLocaleString('id-ID')}</td>
                    </tr>
                `).join('');
            }
        });
    </script>
</body>

</html>
