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
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0">Dashboard Penjual</h2>
                <span class="fw-bold fs-6 text-dark" id="dashContentStoreName"><?= htmlspecialchars($sellerUsername) ?></span>
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

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <!-- Script Hidrasi Data Lintas Storage Dinamis -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentSeller = '<?= $sellerUsername ?>';
            const currentUserId = '<?= $sellerUserId ?>';

            // 1. Profil Toko Penjual (kunci user_id, fallback nama lama)
            const profile = IndoFXStorage.readSellerProfile(currentUserId, [currentSeller]);
            let storeName = profile.store_name || currentSeller;

            const titleEl = document.getElementById('dashContentStoreName');
            const navStoreEl = document.getElementById('navDashStoreName');
            if (titleEl) titleEl.innerText = storeName;
            if (navStoreEl) navStoreEl.innerText = storeName.toUpperCase();

            // 2. AMBIL DATA ASET LINTAS STORAGE (TOKO + ANTREAN MODERASI ADMIN)
            let combinedAssets = [];
            const seenTitles = new Set();

            // A. Ambil dari antrean moderasi admin (vfx_modern & asset_bagus)
            try {
                const adminQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                adminQueue.forEach(aq => {
                    const cleanTitle = (aq.title || '').trim().toLowerCase();
                    if (cleanTitle) {
                        const studio = (aq.studio || aq.creator || aq.seller || '').toLowerCase();
                        const ownerId = String(aq.seller_user_id || '');
                        const isMine = (ownerId !== '' && ownerId === String(currentUserId)) ||
                            studio === currentSeller.toLowerCase();

                        if (isMine) {
                            seenTitles.add(cleanTitle);

                            let mappedStatus = IndoFXStorage.normalizeStatus(aq.status);

                            combinedAssets.push({
                                id: aq.id || ('asset-' + Date.now()),
                                title: aq.title,
                                category: aq.category || 'VFX Partikel',
                                pipelines: Array.isArray(aq.pipelines) ? aq.pipelines : [aq.pipeline || 'Built-in'],
                                price: aq.price_num || parseFloat(String(aq.price).replace(/[^0-9]/g, '')) || 0,
                                price_text: aq.price || (aq.price_num === 0 ? 'Gratis' : 'Rp' + Number(aq.price_num || 0).toLocaleString('id-ID')),
                                status: mappedStatus,
                                sales: aq.downloads || aq.sales || '0x Terjual',
                                created_at: aq.date || aq.created_at || '10 Nov 2026'
                            });
                        }
                    }
                });
            } catch (e) {}

            // B. Ambil aset milik penjual ini saja (kunci user_id + fallback lama)
            IndoFXStorage.readSellerAssets(currentUserId, [currentSeller, storeName]).forEach(item => {
                const cleanTitle = (item.title || '').trim().toLowerCase();
                if (!cleanTitle || seenTitles.has(cleanTitle)) {
                    return;
                }

                seenTitles.add(cleanTitle);
                combinedAssets.push({
                    id: item.id || ('asset-' + Date.now()),
                    title: item.title,
                    category: item.category || 'VFX Partikel',
                    pipelines: Array.isArray(item.pipelines) ? item.pipelines : [item.pipeline || 'Built-in'],
                    price: item.price_num || parseFloat(String(item.price).replace(/[^0-9]/g, '')) || 0,
                    price_text: item.price_text || (typeof item.price === 'string' ? item.price : ('Rp' + Number(item.price || 0).toLocaleString('id-ID'))),
                    status: IndoFXStorage.normalizeStatus(item.status),
                    sales: item.sales || item.downloads || '0x Terjual',
                    created_at: item.date || item.created_at || '10 Nov 2026'
                });
            });

            // C. 3 Aset dasar awal sebagai pelengkap
            const defaultBase = [{
                    id: 'asset-vfx-2',
                    title: 'vfx_test2',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in', 'URP'],
                    price: 200000,
                    price_text: 'Rp200.000',
                    status: 'Disetujui',
                    created_at: '6 Nov 2026'
                },
                {
                    id: 'asset-vfx-1',
                    title: 'vfx_test1',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in', 'URP'],
                    price: 300000,
                    price_text: 'Rp300.000',
                    status: 'Disetujui',
                    created_at: '5 Nov 2026'
                },
                {
                    id: 'asset-black-24',
                    title: 'black-24dp',
                    category: 'VFX Partikel',
                    pipelines: ['Built-in'],
                    price: 150000,
                    price_text: 'Rp150.000',
                    status: 'Disetujui',
                    created_at: '5 Nov 2026'
                }
            ];

            defaultBase.forEach(dba => {
                const cleanTitle = dba.title.trim().toLowerCase();
                if (!seenTitles.has(cleanTitle)) {
                    seenTitles.add(cleanTitle);
                    combinedAssets.push(dba);
                }
            });

            // Render Tabel Aset Baru di Dashboard Utama
            const assetsTbody = document.getElementById('tableAssetsBody');
            assetsTbody.innerHTML = combinedAssets.slice(0, 5).map(item => {
                const primaryPipe = Array.isArray(item.pipelines) ? (item.pipelines[0] || 'Built-in') : (item.pipelines || 'Built-in');
                const priceNum = (typeof item.price === 'number') ? item.price : (parseFloat(String(item.price).replace(/[^0-9]/g, '')) || 0);

                const detailUrl = `/asset/detail?id=${encodeURIComponent(item.id || '')}` +
                    `&title=${encodeURIComponent(item.title || '')}` +
                    `&category=${encodeURIComponent(item.category || 'VFX Partikel')}` +
                    `&creator=${encodeURIComponent(storeName)}` +
                    `&price=${encodeURIComponent(priceNum)}` +
                    `&pipeline=${encodeURIComponent(primaryPipe)}`;

                let badgeHtml = '<span class="badge-status-approved">Disetujui</span>';
                const normalizedStatus = IndoFXStorage.normalizeStatus(item.status);
                if (normalizedStatus === 'Menunggu') {
                    badgeHtml = '<span class="badge-status-pending" style="background:#fef3c7; color:#92400e;">Menunggu</span>';
                } else if (normalizedStatus === 'Ditolak') {
                    badgeHtml = '<span class="badge-status-rejected">Ditolak</span>';
                }

                return `
                    <tr>
                        <td class="fw-bold text-dark item-asset-title">${item.title}</td>
                        <td class="text-muted small">${item.category || 'VFX Partikel'}</td>
                        <td class="fw-bold text-dark">${item.price_text || (priceNum === 0 ? 'Gratis' : 'Rp' + priceNum.toLocaleString('id-ID'))}</td>
                        <td>${badgeHtml}</td>
                        <td class="text-muted small">${item.created_at}</td>
                        <td class="text-end pe-4 text-nowrap">
                            <a href="/dashboard/assets" class="action-link me-2">Edit</a>
                            <span class="text-muted mx-1">|</span>
                            <a href="${detailUrl}" class="action-link">Lihat</a>
                        </td>
                    </tr>
                `;
            }).join('');

            // Perbarui Total Aset dan Terpublikasi secara Dinamis
            const totalAsetCount = combinedAssets.length;
            const approvedCount = combinedAssets.filter(a => IndoFXStorage.normalizeStatus(a.status) === 'Published').length;

            document.getElementById('statTotalAsset').innerText = totalAsetCount;
            document.getElementById('statAssetSub').innerText = approvedCount + ' Terpublikasi';

            // 3. TRANSAKSI PENJUALAN
            let sellerSales = [];
            const myTitles = new Set(combinedAssets.map(a => a.title.trim().toLowerCase()));

            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('indofx_transactions_')) {
                    try {
                        const txList = JSON.parse(localStorage.getItem(key)) || [];
                        txList.forEach(tx => {
                            if (Array.isArray(tx.items)) {
                                tx.items.forEach(it => {
                                    if (myTitles.has((it.title || '').trim().toLowerCase())) {
                                        sellerSales.push({
                                            invoice_no: tx.invoice_no,
                                            asset_title: it.title,
                                            price: it.price || 0,
                                            date: tx.short_date || tx.date || 'Hari ini'
                                        });
                                    }
                                });
                            }
                        });
                    } catch (e) {}
                }
            });

            // Hitung Pendapatan
            let totalRevenue = 1100000; // Baseline awal 4x transaksi
            sellerSales.forEach(s => totalRevenue += (parseFloat(s.price) || 0));

            const totalTransactionsCount = 4 + sellerSales.length;
            document.getElementById('statSold').innerText = totalTransactionsCount + 'x';
            document.getElementById('statDownloads').innerText = totalTransactionsCount;
            document.getElementById('statRevenue').innerText = 'Rp' + totalRevenue.toLocaleString('id-ID');

            // Render Riwayat Transaksi
            const transTbody = document.getElementById('tableTransactionsBody');
            if (sellerSales.length > 0) {
                transTbody.innerHTML = sellerSales.slice(0, 5).map(item => `
                    <tr>
                        <td class="fw-semibold">
                            <a href="/dashboard/transactions" class="action-link">${item.invoice_no}</a>
                        </td>
                        <td class="fw-bold text-dark item-asset-title">${item.asset_title}</td>
                        <td class="text-muted small">${item.date}</td>
                        <td class="text-end pe-4 fw-bold text-success">Rp${(parseFloat(item.price) || 0).toLocaleString('id-ID')}</td>
                    </tr>
                `).join('');
            } else {
                transTbody.innerHTML = `
                    <tr>
                        <td class="fw-semibold"><a href="/dashboard/transactions" class="action-link">INV-20261109-3170</a></td>
                        <td class="fw-bold text-dark item-asset-title">vfx_test1</td>
                        <td class="text-muted small">9 Nov 2026</td>
                        <td class="text-end pe-4 fw-bold text-success">Rp300.000</td>
                    </tr>
                    <tr>
                        <td class="fw-semibold"><a href="/dashboard/transactions" class="action-link">INV-20261106-1120</a></td>
                        <td class="fw-bold text-dark item-asset-title">vfx_test2</td>
                        <td class="text-muted small">6 Nov 2026</td>
                        <td class="text-end pe-4 fw-bold text-success">Rp200.000</td>
                    </tr>
                `;
            }
        });
    </script>
</body>

</html>