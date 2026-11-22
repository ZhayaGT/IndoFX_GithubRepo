<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$sellerUsername = (string) ($_SESSION['username'] ?? $_SESSION['name'] ?? 'Pengguna');
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
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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

            <!-- 4 Summary Stat Cards 100% Dinamis Per User -->
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0 fs-6">Aset Baru</h5>
                    <a href="/dashboard/add-asset" class="btn btn-add-new-asset btn-sm">+ Aset Baru</a>
                </div>

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
                            <tbody id="tableAssetsBody"></tbody>
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
                            <tbody id="tableTransactionsBody"></tbody>
                        </table>
                    </div>
                </div>
            </section>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Hidrasi Data Terisolasi Per User -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentSeller = <?= json_encode($sellerUsername, JSON_UNESCAPED_UNICODE) ?>;
            const currentUserId = <?= json_encode($sellerUserId, JSON_UNESCAPED_UNICODE) ?>;

            // 1. Profil Toko Penjual (Khusus milik user yang login)
            let storeName = currentSeller;
            const profileRaw = localStorage.getItem('indofx_seller_profile_' + currentUserId) ||
                localStorage.getItem('indofx_seller_profile_' + currentSeller);

            if (profileRaw) {
                try {
                    const profile = JSON.parse(profileRaw);
                    if (profile.store_name) storeName = profile.store_name;
                } catch (e) {}
            }

            const titleEl = document.getElementById('dashContentStoreName');
            const navStoreEl = document.getElementById('navDashStoreName');
            const navAvatarEl = document.getElementById('navDashAvatar');
            if (titleEl) titleEl.innerText = storeName;
            if (navStoreEl) navStoreEl.innerText = storeName.toUpperCase();
            if (navAvatarEl) {
                const words = storeName.trim().split(/\s+/);
                let inits = '';
                words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
                navAvatarEl.innerText = inits || 'US';
            }

            // Himpunan identitas pemilik sah akun ini
            const ownerIdentifiers = new Set(
                [currentUserId, currentSeller, storeName]
                .map(v => String(v || '').trim().toLowerCase())
                .filter(Boolean)
            );
            const myStorageKeys = new Set([
                'indofx_seller_assets_' + currentUserId,
                'indofx_seller_assets_' + currentSeller,
                'indofx_seller_assets_' + storeName
            ]);

            function belongsToCurrentSeller(record) {
                const sKey = String(record.seller_storage_key || record.seller_key || '').trim();
                if (sKey && myStorageKeys.has(sKey)) return true;

                const ownerFields = [record.studio, record.creator, record.seller]
                    .map(v => String(v || '').trim().toLowerCase())
                    .filter(Boolean);
                return ownerFields.some(f => ownerIdentifiers.has(f));
            }

            // 2. AMBIL ASET HANYA MILIK USER INI
            let combinedAssets = [];
            const seenKeys = new Set();

            // A. Antrean moderasi admin yang dimiliki akun ini (status terbaru dari admin)
            try {
                const adminQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                adminQueue.forEach(aq => {
                    if (!belongsToCurrentSeller(aq)) return;
                    const uniq = String(aq.id || aq.title || '').trim().toLowerCase();
                    if (!uniq || seenKeys.has(uniq)) return;
                    seenKeys.add(uniq);

                    let mappedStatus = 'Menunggu';
                    if (aq.status === 'Published' || aq.status === 'Disetujui') mappedStatus = 'Disetujui';
                    else if (aq.status === 'Ditolak') mappedStatus = 'Ditolak';

                    combinedAssets.push({
                        id: aq.id || ('asset-' + Date.now()),
                        title: aq.title,
                        category: aq.category || 'VFX Partikel',
                        pipelines: Array.isArray(aq.pipelines) ? aq.pipelines : [aq.pipeline || 'Built-in'],
                        price: aq.price_num || parseFloat(String(aq.price).replace(/[^0-9]/g, '')) || 0,
                        price_text: aq.price || (aq.price_num === 0 ? 'Gratis' : 'Rp' + Number(aq.price_num || 0).toLocaleString('id-ID')),
                        status: mappedStatus,
                        downloads: aq.downloads || aq.sales || '0x Terjual',
                        created_at: aq.date || aq.created_at || 'Hari ini'
                    });
                });
            } catch (e) {}

            // B. Penyimpanan lokal khusus toko user ini
            myStorageKeys.forEach(sKey => {
                try {
                    const parsed = JSON.parse(localStorage.getItem(sKey)) || [];
                    if (!Array.isArray(parsed)) return;
                    parsed.forEach(item => {
                        const uniq = String(item.id || item.title || '').trim().toLowerCase();
                        if (!uniq || seenKeys.has(uniq)) return;
                        seenKeys.add(uniq);

                        let mappedStatus = item.status || 'Menunggu';
                        if (mappedStatus === 'Pending' || mappedStatus === 'Pending Review') mappedStatus = 'Menunggu';
                        else if (mappedStatus === 'Published') mappedStatus = 'Disetujui';

                        combinedAssets.push({
                            id: item.id || ('asset-' + Date.now()),
                            title: item.title,
                            category: item.category || 'VFX Partikel',
                            pipelines: Array.isArray(item.pipelines) ? item.pipelines : [item.pipeline || 'Built-in'],
                            price: item.price_num || parseFloat(String(item.price).replace(/[^0-9]/g, '')) || 0,
                            price_text: item.price_text || (typeof item.price === 'string' ? item.price : ('Rp' + Number(item.price || 0).toLocaleString('id-ID'))),
                            status: mappedStatus,
                            downloads: item.sales || item.downloads || '0x Terjual',
                            created_at: item.date || item.created_at || 'Hari ini'
                        });
                    });
                } catch (e) {}
            });

            // Render Tabel Aset Baru
            const assetsTbody = document.getElementById('tableAssetsBody');
            if (combinedAssets.length === 0) {
                assetsTbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">
                            Belum ada aset yang Anda unggah. Klik <strong>+ Aset Baru</strong> untuk mulai mengunggah aset.
                        </td>
                    </tr>
                `;
            } else {
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
                    if (item.status === 'Menunggu') {
                        badgeHtml = '<span class="badge-status-pending" style="background:#fef3c7; color:#92400e;">Menunggu</span>';
                    } else if (item.status === 'Ditolak') {
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
            }

            const totalAsetCount = combinedAssets.length;
            const approvedCount = combinedAssets.filter(a => a.status === 'Disetujui').length;
            document.getElementById('statTotalAsset').innerText = totalAsetCount;
            document.getElementById('statAssetSub').innerText = approvedCount + ' Terpublikasi';

            // 3. TRANSAKSI PENJUALAN KHUSUS MILIK USER INI
            let sellerSales = [];
            const seenInvoices = new Set();

            // A. Ambil dari storage penjualan khusus kreator ini
            const cleanStoreKey = storeName.toLowerCase().replace(/[^a-z0-9]/g, '');
            const salesStorageKeys = [
                'indofx_seller_sales_' + currentUserId,
                'indofx_seller_sales_' + currentSeller,
                'indofx_seller_sales_' + cleanStoreKey,
                'indofx_seller_sales_' + storeName
            ];

            salesStorageKeys.forEach(k => {
                try {
                    const list = JSON.parse(localStorage.getItem(k)) || [];
                    list.forEach(s => {
                        const invKey = (s.invoice_no || '') + '_' + (s.asset_title || '');
                        if (invKey !== '_' && !seenInvoices.has(invKey)) {
                            seenInvoices.add(invKey);
                            sellerSales.push({
                                invoice_no: s.invoice_no,
                                asset_title: s.asset_title,
                                price: parseFloat(s.price) || 0,
                                date: s.date || 'Hari ini'
                            });
                        }
                    });
                } catch (e) {}
            });

            // B. Cross-check transaksi pembeli yang membeli aset milik kreator ini
            const myTitles = new Set(combinedAssets.map(a => a.title.trim().toLowerCase()));
            const myIds = new Set(combinedAssets.map(a => String(a.id)));

            if (myTitles.size > 0 || myIds.size > 0) {
                Object.keys(localStorage).forEach(key => {
                    if (key.startsWith('indofx_transactions_')) {
                        try {
                            const txList = JSON.parse(localStorage.getItem(key)) || [];
                            txList.forEach(tx => {
                                if (Array.isArray(tx.items)) {
                                    tx.items.forEach(it => {
                                        const matchAsset = myTitles.has((it.title || '').trim().toLowerCase()) || myIds.has(String(it.id));
                                        const invKey = (tx.invoice_no || '') + '_' + (it.title || '');
                                        if (matchAsset && !seenInvoices.has(invKey)) {
                                            seenInvoices.add(invKey);
                                            sellerSales.push({
                                                invoice_no: tx.invoice_no,
                                                asset_title: it.title,
                                                price: parseFloat(it.price) || 0,
                                                date: tx.short_date || tx.date || 'Hari ini'
                                            });
                                        }
                                    });
                                }
                            });
                        } catch (e) {}
                    }
                });
            }

            let totalRevenue = 0;
            sellerSales.forEach(s => totalRevenue += (parseFloat(s.price) || 0));

            document.getElementById('statSold').innerText = sellerSales.length + 'x';
            document.getElementById('statDownloads').innerText = sellerSales.length;
            document.getElementById('statRevenue').innerText = 'Rp' + totalRevenue.toLocaleString('id-ID');

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
                        <td colspan="4" class="text-center py-4 text-muted small">
                            Belum ada transaksi penjualan untuk aset Anda.
                        </td>
                    </tr>
                `;
            }
        });
    </script>
</body>

</html>