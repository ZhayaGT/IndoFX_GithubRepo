<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['username'] ?? $_SESSION['name'] ?? 'naufalhaidarazmi');
$sellerInitials = mb_strtoupper(mb_substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Aset Saya - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets Asli Dashboard Figma (Konsisten dengan main.php) -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <!-- 1. Header Navbar Oranye Dashboard -->
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

            <!-- Profil Penjual Kanan -->
            <a href="/dashboard" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Dashboard Utama">
                <span class="avatar-circle" id="navDashAvatar"><?= htmlspecialchars($sellerInitials) ?></span>
                <span class="fw-bold fs-7 tracking-wide text-uppercase" id="navDashStoreName"><?= htmlspecialchars($userName) ?></span>
            </a>
        </div>
    </nav>

    <!-- 2. Kontainer Utama Dashboard (Menggunakan .dashboard-layout-container agar ukuran/zoom identik dengan main.php) -->
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

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <!-- Header Judul & Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0">
                    Semua Aset dari <span class="fw-normal" id="pageStoreTitle"><?= htmlspecialchars($userName) ?></span>
                </h2>
                <a href="/dashboard/add-asset" class="btn btn-add-new-asset">
                    + Aset Baru
                </a>
            </div>

            <!-- Bar Pencarian & Filter -->
            <div class="filter-bar-container mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="filterKeyword" class="form-control filter-input" placeholder="Cari Nama Aset...">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <select class="form-select filter-select" id="filterStatus">
                            <option value="all" selected>Semua Status Moderasi</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Pending">Pending / Menunggu</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select" id="filterCategory">
                            <option value="all" selected>Semua Kategori</option>
                            <option value="Particle">Particle</option>
                            <option value="Shader">Shader</option>
                            <option value="Model 3D">Model 3D</option>
                            <option value="Tekstur & Material">Tekstur &amp; Material</option>
                            <option value="Script / Tool">Script / Tool</option>
                        </select>
                    </div>
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit w-100" type="button" id="btnApplyFilter">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Aset Dinamis -->
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
                        <tbody id="assetsTableBody">
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

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

    <script>
        const storeName = <?= json_encode($storeName ?? $userName) ?>;
        const allAssets = <?= json_encode($assets ?? []) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('pageStoreTitle').innerText = storeName;
            document.getElementById('navDashStoreName').innerText = storeName.toUpperCase();

            const words = storeName.trim().split(/\s+/);
            document.getElementById('navDashAvatar').innerText = words.slice(0, 2).map(w => w.charAt(0).toUpperCase()).join('') || 'S';

            renderTable();

            document.getElementById('filterKeyword').addEventListener('input', renderTable);
            document.getElementById('filterStatus').addEventListener('change', renderTable);
            document.getElementById('filterCategory').addEventListener('change', renderTable);
            document.getElementById('btnApplyFilter').addEventListener('click', renderTable);
        });

        function renderTable() {
            const tbody          = document.getElementById('assetsTableBody');
            const keyword        = document.getElementById('filterKeyword').value.toLowerCase().trim();
            const statusFilter   = document.getElementById('filterStatus').value;
            const categoryFilter = document.getElementById('filterCategory').value;

            const filtered = allAssets.filter(item => {
                const matchKeyword  = !keyword || (item.title && item.title.toLowerCase().includes(keyword));
                const matchStatus   = statusFilter === 'all' || item.status === statusFilter;
                const matchCategory = categoryFilter === 'all' || (item.category || '').toLowerCase() === categoryFilter.toLowerCase();
                return matchKeyword && matchStatus && matchCategory;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-box-seam fs-2 d-block mb-2 text-secondary"></i>
                            <h6 class="fw-bold mb-1">Belum Ada Aset Ditemukan</h6>
                            <p class="small text-muted mb-3">Mulai unggah aset Anda agar dapat ditinjau dan tampil di pasar game IndoFX.</p>
                            <a href="/dashboard/add-asset" class="btn btn-add-new-asset">+ Tambah Aset Baru</a>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
                const pipes = Array.isArray(item.pipelines) && item.pipelines.length > 0 ? item.pipelines : ['Built-in'];
                const pipeHtml = pipes.map(p => {
                    const pLower = String(p).toLowerCase();
                    let cls = 'badge-urp';
                    if (pLower.includes('hdrp')) cls = 'badge-hdrp';
                    else if (pLower.includes('built')) cls = 'badge-builtin';
                    return `<span class="badge-pipe ${cls} me-1">${p}</span>`;
                }).join('');

                const isGratis = Number(item.price) <= 0;
                let downloadOrSalesText = '-';
                if (item.status === 'Disetujui') {
                    downloadOrSalesText = isGratis
                        ? `${item.download_count}x Unduhan`
                        : `${item.sold_count}x Terjual`;
                }

                const detailUrl = `/asset/detail?id=${encodeURIComponent(item.public_id)}`;

                let statusBadge = `<span class="badge-status-pending">Pending</span>`;
                let actionBtn   = `<span class="text-muted small fst-italic">Ditinjau Admin</span>`;

                if (item.status === 'Disetujui') {
                    statusBadge = `<span class="badge-status-approved">Published</span>`;
                    actionBtn = `
                        <a href="/dashboard/add-asset?edit_id=${encodeURIComponent(item.id)}" class="action-link-edit">Edit</a>
                        <span class="action-divider">|</span>
                        <a href="${detailUrl}" class="action-link-view">Lihat</a>
                    `;
                } else if (item.status === 'Ditolak') {
                    statusBadge = `<span class="badge-status-rejected">Ditolak</span>`;
                    actionBtn   = `<a href="/dashboard/add-asset?edit_id=${encodeURIComponent(item.id)}" class="action-link-danger">Revisi</a>`;
                }

                return `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark item-asset-title">${item.title}</div>
                            <div class="d-flex flex-wrap gap-1 mt-1">${pipeHtml}</div>
                        </td>
                        <td class="text-muted small">${item.category}</td>
                        <td class="fw-bold text-dark">${item.price_text}</td>
                        <td>${statusBadge}</td>
                        <td class="text-muted small">${downloadOrSalesText}</td>
                        <td class="text-muted small">${item.created_at}</td>
                        <td class="text-end pe-4 text-nowrap">${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }
    </script>
</body>

</html>