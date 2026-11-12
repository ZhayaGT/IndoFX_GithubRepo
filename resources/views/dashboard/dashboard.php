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
                            <option value="VFX Partikel">VFX Partikel</option>
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

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <!-- SCRIPT HIDRASI DATA DINAMIS & PENGHITUNG TERJUAL -->
    <script>
        const userKey = '<?= $userKey ?>';
        const defaultUserName = <?= json_encode($userName, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        let allAssets = [];
        let storeName = defaultUserName || 'Arga Studio';
        let sellerSales = [];

        document.addEventListener('DOMContentLoaded', function() {
            const profile = IndoFXStorage.readSellerProfile(userKey, [defaultUserName]);
            if (profile.store_name) storeName = profile.store_name;

            document.getElementById('pageStoreTitle').innerText = storeName;
            document.getElementById('navDashStoreName').innerText = storeName.toUpperCase();

            const words = storeName.trim().split(/\s+/);
            let inits = '';
            words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
            document.getElementById('navDashAvatar').innerText = inits || 'AS';

            loadAssets();

            document.getElementById('filterKeyword').addEventListener('input', renderTable);
            document.getElementById('filterStatus').addEventListener('change', renderTable);
            document.getElementById('filterCategory').addEventListener('change', renderTable);
            document.getElementById('btnApplyFilter').addEventListener('click', renderTable);
        });

        function loadAssets() {
            let combined = [];
            const seenTitles = new Set();

            // 1. Tarik dari antrean moderasi admin (vfx_modern & asset_bagus)
            try {
                const adminQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                adminQueue.forEach(aq => {
                    const cleanTitle = (aq.title || '').trim().toLowerCase();
                    if (cleanTitle) {
                        const studio = (aq.studio || aq.creator || aq.seller || '').toLowerCase();
                        const ownerId = String(aq.seller_user_id || '');
                        const isMine = (ownerId !== '' && ownerId === String(userKey)) ||
                            studio === storeName.toLowerCase() ||
                            studio === defaultUserName.toLowerCase();

                        if (isMine) {
                            seenTitles.add(cleanTitle);

                            let mappedStatus = IndoFXStorage.normalizeStatus(aq.status);

                            combined.push({
                                id: aq.id || ('asset-' + Date.now()),
                                title: aq.title,
                                category: aq.category || 'VFX Partikel',
                                pipelines: Array.isArray(aq.pipelines) ? aq.pipelines : [aq.pipeline || 'Built-in'],
                                price: aq.price_num || parseFloat(String(aq.price).replace(/[^0-9]/g, '')) || 0,
                                price_text: aq.price || (aq.price_num === 0 ? 'Gratis' : 'Rp' + Number(aq.price_num || 0).toLocaleString('id-ID')),
                                status: mappedStatus,
                                downloads: aq.downloads || aq.sales || '0x Terjual',
                                created_at: aq.date || aq.created_at || '10 Nov 2026',
                                reject_reason: aq.reject_reason || ''
                            });
                        }
                    }
                });
            } catch (e) {}

            // 2. Ambil aset milik penjual ini saja (kunci user_id + fallback lama)
            IndoFXStorage.readSellerAssets(userKey, [defaultUserName, storeName]).forEach(item => {
                const cleanTitle = (item.title || '').trim().toLowerCase();
                if (!cleanTitle || seenTitles.has(cleanTitle)) {
                    return;
                }

                seenTitles.add(cleanTitle);
                combined.push({
                    id: item.id || ('asset-' + Date.now()),
                    title: item.title,
                    category: item.category || 'VFX Partikel',
                    pipelines: Array.isArray(item.pipelines) ? item.pipelines : [item.pipeline || 'Built-in'],
                    price: item.price_num || parseFloat(String(item.price).replace(/[^0-9]/g, '')) || 0,
                    price_text: item.price_text || (typeof item.price === 'string' ? item.price : ('Rp' + Number(item.price || 0).toLocaleString('id-ID'))),
                    status: IndoFXStorage.normalizeStatus(item.status),
                    downloads: item.sales || item.downloads || '0x Terjual',
                    created_at: item.date || item.created_at || '6 Nov 2026',
                    reject_reason: item.reject_reason || ''
                });
            });

            // 3. Fallback 3 aset awal Figma
            const defaultBase = [{
                    id: 'asset-vfx-2',
                    title: 'vfx_test2',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in', 'URP'],
                    price: 200000,
                    price_text: 'Rp200.000',
                    status: 'Disetujui',
                    created_at: '6 Nov 2026',
                    downloads: '1x Terjual'
                },
                {
                    id: 'asset-vfx-1',
                    title: 'vfx_test1',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in', 'URP'],
                    price: 300000,
                    price_text: 'Rp300.000',
                    status: 'Disetujui',
                    created_at: '5 Nov 2026',
                    downloads: '3x Terjual'
                },
                {
                    id: 'asset-black-24',
                    title: 'black-24dp',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in'],
                    price: 150000,
                    price_text: 'Rp150.000',
                    status: 'Disetujui',
                    created_at: '5 Nov 2026',
                    downloads: '0x Terjual'
                }
            ];

            defaultBase.forEach(dba => {
                const cleanTitle = dba.title.trim().toLowerCase();
                if (!seenTitles.has(cleanTitle)) {
                    seenTitles.add(cleanTitle);
                    combined.push(dba);
                }
            });

            allAssets = combined;

            // CROSS-CHECK TRANSAKSI PEMBELIAN PEMBELI
            const myTitles = new Set(allAssets.map(a => a.title.trim().toLowerCase()));
            const myIds = new Set(allAssets.map(a => String(a.id)));
            let combinedSales = [];

            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('indofx_transactions_')) {
                    try {
                        const txList = JSON.parse(localStorage.getItem(key)) || [];
                        txList.forEach(tx => {
                            if (Array.isArray(tx.items)) {
                                tx.items.forEach(it => {
                                    if (myTitles.has((it.title || '').trim().toLowerCase()) || myIds.has(String(it.id))) {
                                        combinedSales.push({
                                            invoice_no: tx.invoice_no,
                                            asset_id: it.id,
                                            asset_title: it.title,
                                            price: it.price
                                        });
                                    }
                                });
                            }
                        });
                    } catch (e) {}
                }
            });

            let seen = new Set();
            sellerSales = [];
            combinedSales.forEach(s => {
                const uniqKey = (s.invoice_no || '') + '_' + (s.asset_title || '');
                if (!seen.has(uniqKey) && (s.invoice_no || s.asset_title)) {
                    seen.add(uniqKey);
                    sellerSales.push(s);
                }
            });

            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('assetsTableBody');
            const keyword = document.getElementById('filterKeyword').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatus').value;
            const categoryFilter = document.getElementById('filterCategory').value;

            const filtered = allAssets.filter(item => {
                const matchKeyword = !keyword || (item.title && item.title.toLowerCase().includes(keyword));
                const matchStatus = statusFilter === 'all' ||
                    IndoFXStorage.normalizeStatus(item.status) === IndoFXStorage.normalizeStatus(statusFilter);
                const matchCategory = categoryFilter === 'all' || item.category === categoryFilter;
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
                const pipes = Array.isArray(item.pipelines) ?
                    item.pipelines : [item.pipelines || item.render_pipeline || 'Built-in'];
                const primaryPipe = pipes[0] || 'Built-in';

                const pipeHtml = pipes.map(p => {
                    const pLower = String(p).toLowerCase();
                    let cls = 'badge-urp';
                    if (pLower.includes('hdrp')) cls = 'badge-hdrp';
                    else if (pLower.includes('built')) cls = 'badge-builtin';
                    return `<span class="badge-pipe ${cls} me-1">${esc(p)}</span>`;
                }).join('');

                let priceNum = 0;
                let isGratis = false;
                if (typeof item.price === 'number') {
                    priceNum = item.price;
                    isGratis = (priceNum <= 0);
                } else if (typeof item.price === 'string') {
                    const cleaned = item.price.replace(/[^0-9]/g, '');
                    priceNum = cleaned ? parseInt(cleaned, 10) : 0;
                    isGratis = (item.price.toLowerCase().trim() === 'gratis' || priceNum <= 0);
                }

                // Hitung Terjual / Unduhan Dinamis
                let downloadOrSalesText = item.downloads || '0x Terjual';
                if (IndoFXStorage.normalizeStatus(item.status) === 'Published') {
                    if (isGratis) {
                        const rawCount = parseInt(String(item.downloads || '0').replace(/[^0-9]/g, ''), 10) || 0;
                        downloadOrSalesText = `${Math.max(rawCount, 1)}x Unduhan`;
                    } else {
                        const salesCount = sellerSales.filter(s =>
                            String(s.asset_id) === String(item.id) || s.asset_title === item.title
                        ).length;

                        const storedCount = parseInt(String(item.downloads || '0').replace(/[^0-9]/g, ''), 10) || 0;
                        const finalCount = Math.max(salesCount, storedCount, item.title === 'vfx_test1' ? 3 : (item.title === 'vfx_test2' ? 1 : 0));
                        downloadOrSalesText = `${finalCount}x Terjual`;
                    }
                } else {
                    downloadOrSalesText = '-';
                }

                const detailUrl = `/asset/detail?id=${encodeURIComponent(item.id || '')}` +
                    `&slug=${encodeURIComponent(item.slug || item.id || '')}` +
                    `&title=${encodeURIComponent(item.title || '')}` +
                    `&category=${encodeURIComponent(item.category || 'VFX Partikel')}` +
                    `&creator=${encodeURIComponent(storeName)}` +
                    `&price=${encodeURIComponent(priceNum)}` +
                    `&pipeline=${encodeURIComponent(primaryPipe)}`;

                let statusBadge = `<span class="badge-status-pending">Menunggu</span>`;
                let actionBtn = ``;

                if (IndoFXStorage.normalizeStatus(item.status) === 'Published') {
                    statusBadge = `<span class="badge-status-approved">Disetujui</span>`;
                    actionBtn = `
                        <a href="/dashboard/add-asset?edit_id=${encodeURIComponent(item.id)}" class="action-link-edit">Edit</a>
                        <span class="action-divider">|</span>
                        <a href="${detailUrl}" class="action-link-view">Lihat</a>
                    `;
                } else if (IndoFXStorage.normalizeStatus(item.status) === 'Ditolak') {
                    statusBadge = `
                        <div class="d-inline-flex align-items-center gap-1">
                            <span class="badge-status-rejected">Ditolak</span>
                        </div>
                    `;
                    actionBtn = `<a href="/dashboard/add-asset?edit_id=${encodeURIComponent(item.id)}" class="action-link-danger">Revisi</a>`;
                } else {
                    statusBadge = `<span class="badge-status-pending">Menunggu</span>`;
                    actionBtn = `<span class="text-muted small fst-italic">Ditinjau Admin</span>`;
                }

                return `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark item-asset-title">${esc(item.title)}</div>
                            <div class="d-flex flex-wrap gap-1 mt-1">${pipeHtml}</div>
                        </td>
                        <td class="text-muted small">${esc(item.category || 'VFX Partikel')}</td>
                        <td class="fw-bold text-dark">${esc(item.price_text || (isGratis ? 'Gratis' : 'Rp' + priceNum.toLocaleString('id-ID')))}</td>
                        <td>${statusBadge}</td>
                        <td class="text-muted small">${esc(downloadOrSalesText)}</td>
                        <td class="text-muted small">${esc(item.created_at || '15 Jan 2026')}</td>
                        <td class="text-end pe-4 text-nowrap">${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }
    </script>
</body>

</html>