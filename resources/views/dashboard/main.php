<?php
$storeName = $storeName ?? 'Toko Saya';
$initials  = $initials ?? 'AS';
$stats     = $stats ?? ['total' => 0, 'published' => 0, 'review' => 0, 'downloads' => 0, 'sold' => 0, 'revenue' => 0];
$assets    = $assets ?? [];
$transactions = $transactions ?? [];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - IndoFX</title>

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

        <main class="dashboard-main-content">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0">Dashboard Penjual</h2>
                <span class="fw-bold fs-6 text-dark"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Aset</span>
                        <div class="stat-card-value mb-1"><?= (int) $stats['total'] ?></div>
                        <span class="stat-card-sub text-muted"><?= (int) $stats['published'] ?> Terpublikasi</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Aset Terjual</span>
                        <div class="stat-card-value mb-1"><?= (int) $stats['sold'] ?>x</div>
                        <span class="stat-card-sub text-muted">Transaksi Berhasil</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Unduhan</span>
                        <div class="stat-card-value mb-1"><?= (int) $stats['downloads'] ?></div>
                        <span class="stat-card-sub text-muted">Termasuk Aset Gratis</span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3 h-100">
                        <span class="stat-card-title d-block mb-2">Total Pendapatan</span>
                        <div class="stat-card-value mb-1"><?= format_rupiah_amount((int) $stats['revenue']) ?></div>
                        <span class="stat-card-sub text-muted">Developer &amp; Penjual</span>
                    </div>
                </div>
            </div>

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
                            <tbody>
                                <?php if ($assets === []): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted small">
                                            <i class="bi bi-box-seam fs-3 d-block mb-1 text-secondary"></i>
                                            Belum ada aset yang diunggah. Klik <a href="/dashboard/add-asset" class="fw-bold text-primary">Tambah Aset</a> untuk mulai mengunggah.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($assets as $asset): ?>
                                        <?php [$statusText, $statusClass] = asset_status_badge($asset['status'] ?? ''); ?>
                                        <tr>
                                            <td class="fw-bold text-dark item-asset-title"><?= htmlspecialchars($asset['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-muted small"><?= htmlspecialchars($asset['category_name'] ?? 'Kategori', ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="fw-bold text-dark"><?= format_rupiah((int) $asset['price']) ?></td>
                                            <td><span class="<?= $statusClass ?>"><?= $statusText ?></span></td>
                                            <td class="text-muted small"><?= date('d M Y', strtotime((string) $asset['created_at'])) ?></td>
                                            <td class="text-end pe-4 text-nowrap">
                                                <a href="/dashboard/add-asset?edit_id=<?= urlencode((string) $asset['public_id']) ?>" class="action-link me-2">Edit</a>
                                                <span class="text-muted mx-1">|</span>
                                                <a href="/asset/detail?id=<?= urlencode((string) $asset['public_id']) ?>" class="action-link">Lihat</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

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
                            <tbody>
                                <?php if ($transactions === []): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            <i class="bi bi-receipt text-secondary fs-3 d-block mb-1"></i>
                                            Belum ada transaksi penjualan terbaru.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $transaction): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <a href="/dashboard/transactions" class="action-link"><?= htmlspecialchars($transaction['invoice_number'], ENT_QUOTES, 'UTF-8') ?></a>
                                            </td>
                                            <td class="fw-bold text-dark item-asset-title"><?= htmlspecialchars($transaction['asset_title'], ENT_QUOTES, 'UTF-8') ?></td>
                                            <td class="text-muted small"><?= date('d M Y', strtotime((string) ($transaction['paid_at'] ?? $transaction['created_at']))) ?></td>
                                            <td class="text-end pe-4 fw-bold text-success"><?= format_rupiah_amount((int) $transaction['amount']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

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