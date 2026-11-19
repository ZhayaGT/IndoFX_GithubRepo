<?php
$storeName  = $storeName ?? 'Toko Saya';
$initials   = $initials ?? 'AS';
$assets     = $assets ?? [];
$categories = $categories ?? [];
$filter     = $filter ?? ['q' => '', 'status' => '', 'category_id' => 0];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Aset Saya - IndoFX</title>

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

            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Katalog">
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
                    <a href="/dashboard/assets" class="sidebar-link active">
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
                <h2 class="dash-page-title mb-0">
                    Semua Aset dari <span class="fw-normal"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?></span>
                </h2>
                <a href="/dashboard/add-asset" class="btn btn-add-new-asset">
                    + Aset Baru
                </a>
            </div>

            <form class="filter-bar-container mb-4" method="get" action="/dashboard/assets">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="q" class="form-control filter-input" placeholder="Cari Nama Aset..."
                                value="<?= htmlspecialchars($filter['q'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <select class="form-select filter-select" name="status">
                            <option value="">Semua Status Moderasi</option>
                            <option value="review" <?= $filter['status'] === 'review' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="published" <?= $filter['status'] === 'published' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="rejected" <?= $filter['status'] === 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select" name="category_id">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= (int) $category['id'] ?>" <?= $filter['category_id'] === (int) $category['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit w-100" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="table-card-wrapper">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 30%;">Judul Aset &amp; Pipeline</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Status Moderasi</th>
                                <th scope="col">Terjual / Unduhan</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col" class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($assets === []): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-box-seam fs-2 d-block mb-2 text-secondary"></i>
                                        <h6 class="fw-bold mb-1">Belum Ada Aset Ditemukan</h6>
                                        <p class="small text-muted mb-3">Mulai unggah aset Anda agar dapat ditinjau dan tampil di pasar game IndoFX.</p>
                                        <a href="/dashboard/add-asset" class="btn btn-add-new-asset">+ Tambah Aset Baru</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($assets as $asset): ?>
                                    <?php
                                    [$statusText, $statusClass] = asset_status_badge($asset['status'] ?? '');
                                    $isFree = (int) $asset['price'] === 0;
                                    $count = $isFree
                                        ? (int) $asset['download_count'] . 'x Unduhan'
                                        : (int) $asset['download_count'] . 'x Terjual';
                                    $editUrl = '/dashboard/add-asset?edit_id=' . urlencode((string) $asset['public_id']);
                                    $detailUrl = '/asset/detail?id=' . urlencode((string) $asset['public_id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark item-asset-title"><?= htmlspecialchars($asset['title'], ENT_QUOTES, 'UTF-8') ?></div>
                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                <?php foreach (pipeline_badges($asset['render_pipeline'] ?? '') as $label): ?>
                                                    <span class="badge-pipe <?= pipeline_badge_class($label) ?> me-1"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td class="text-muted small"><?= htmlspecialchars($asset['category_name'] ?? 'Kategori', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="fw-bold text-dark"><?= format_rupiah((int) $asset['price']) ?></td>
                                        <td><span class="<?= $statusClass ?>"><?= $statusText ?></span></td>
                                        <td class="text-muted small"><?= $count ?></td>
                                        <td class="text-muted small"><?= date('d M Y', strtotime((string) $asset['created_at'])) ?></td>
                                        <td class="text-end pe-4 text-nowrap">
                                            <?php if (($asset['status'] ?? '') === 'published'): ?>
                                                <a href="<?= $editUrl ?>" class="action-link-edit">Edit</a>
                                                <span class="action-divider">|</span>
                                                <a href="<?= $detailUrl ?>" class="action-link-view">Lihat</a>
                                            <?php elseif (($asset['status'] ?? '') === 'rejected'): ?>
                                                <a href="<?= $editUrl ?>" class="action-link-danger">Revisi</a>
                                            <?php else: ?>
                                                <a href="<?= $editUrl ?>" class="action-link-edit">Edit</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

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