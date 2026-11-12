<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = 'Super Admin IndoFX';
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Platform IndoFX - Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheet Admin Terpisah -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin Sesuai Figma -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <!-- Brand Logo Kiri -->
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/admin/dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold fs-6">IndoFX</span>
            </a>

            <!-- Profil Admin Kanan & Dropdown Menu Figma (Hanya Logout) -->
            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Layout Utama -->
    <div class="admin-layout-container">

        <!-- Sidebar Kiri Sesuai Figma image_45fee9 -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link active">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge" id="sidebarModerationBadge">0</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge" id="sidebarSellerReqBadge">0</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Utama Kanan -->
        <main class="admin-main-content">
            <h1 class="admin-page-title">Ringkasan Platform IndoFX</h1>
            <p class="admin-page-sub">Pemantauan statistik operasional platform dan antrean moderasi aset.</p>

            <!-- 4 Kartu Ringkasan Murni Dinamis Sesuai Data Riil -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Pengguna Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Pengguna</div>
                        <div class="admin-stat-val" id="statTotalUsers">0</div>
                        <div class="admin-stat-sub" id="statUserBreakdown">0 Penjual | 0 Pembeli</div>
                    </div>
                </div>

                <!-- Card 2: Antrean Moderasi Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Antrean Moderasi</div>
                        <div class="admin-stat-val val-orange" id="statPendingModeration">0 Aset</div>
                        <div class="admin-stat-sub">Menunggu Peninjauan</div>
                    </div>
                </div>

                <!-- Card 3: Aset Terpublikasi Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Aset Terpublikasi</div>
                        <div class="admin-stat-val val-green" id="statPublishedAssets">0</div>
                        <div class="admin-stat-sub">Tampil di Katalog</div>
                    </div>
                </div>

                <!-- Card 4: Total Omset Platform Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Omset Platform</div>
                        <div class="admin-stat-val" id="statTotalRevenue">Rp 0</div>
                        <div class="admin-stat-sub" id="statRevenueSub">Simulasi Midtrans</div>
                    </div>
                </div>
            </div>

            <!-- Header Seksi Tabel Antrean -->
            <div class="admin-section-header">
                <h5 class="admin-section-title">Antrean Moderasi Aset Terbaru</h5>
                <a href="/admin/assets" class="admin-section-link">
                    Lihat Semua Antrean Moderasi &rarr;
                </a>
            </div>

            <!-- Tabel Antrean Moderasi Dinamis -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 220px;">Aset &amp; File</th>
                                <th scope="col" class="cell-nowrap">Penjual / Studio</th>
                                <th scope="col" class="cell-nowrap">Versi Unity</th>
                                <th scope="col" class="cell-nowrap">Harga</th>
                                <th scope="col" class="cell-nowrap">Tanggal Masuk &darr;</th>
                                <th scope="col" class="cell-nowrap">Status Moderasi</th>
                                <th scope="col" class="text-end pe-3 cell-nowrap">Aksi Peninjauan</th>
                            </tr>
                        </thead>
                        <tbody id="adminModerationTableBody">
                            <!-- Diisi secara dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. MODAL PENINJAUAN MODERASI ASET (AKSI DINAMIS ADMIN) -->
    <div class="modal fade" id="modalReviewAsset" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
            <div class="modal-content modal-review-card">
                <div class="modal-review-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="seller-header-icon-box">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="modal-category-title mb-0">Peninjauan Moderasi Aset</h5>
                    </div>
                    <button type="button" class="btn-seller-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-review-body">
                    <input type="hidden" id="reviewAssetId">

                    <div class="mb-3">
                        <span class="seller-label">Judul Aset</span>
                        <h5 class="fw-bold text-dark mb-1" id="reviewAssetTitle">-</h5>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge-pipe-admin badge-pipe-hdrp" id="reviewPipelineBadge">HDRP</span>
                            <span class="text-muted small" id="reviewCategoryText">Shader</span>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="modal-review-meta-box">
                                <span class="seller-label">Penjual / Studio</span>
                                <div class="fw-bold text-dark" id="reviewStudioName">-</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="modal-review-meta-box">
                                <span class="seller-label">Harga Tertera</span>
                                <div class="fw-bold text-dark" id="reviewPriceText">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="seller-label">Berkas Paket Aset</span>
                        <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-file-earmark-zip text-danger fs-5"></i>
                                <span class="small fw-semibold text-dark" id="reviewFileName">asset_package.zip</span>
                            </div>
                            <span class="badge bg-secondary" id="reviewFileSize">32 MB</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="seller-label mb-1" for="reviewRejectReason">Catatan Keputusan (Wajib jika menolak):</label>
                        <textarea id="reviewRejectReason" class="seller-textarea" style="height: 75px;" placeholder="Tuliskan catatan perbaikan untuk kreator jika aset ditolak..."></textarea>
                    </div>
                </div>
                <div class="modal-category-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-confirm-delete" onclick="handleModerationReject()">
                        <i class="bi bi-x-lg"></i> Tolak Aset
                    </button>
                    <button type="button" class="btn-modal-submit" onclick="handleModerationApprove()">
                        <i class="bi bi-check2"></i> Setujui &amp; Publikasikan
                    </button>
                </div>
            </div>
        </div>
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

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <!-- ENGINE DATA DINAMIS ADMIN INDOFX -->
    <script>
        // Data Basis Pengguna Awal
        const defaultUsers = [{
                id: 1,
                name: 'CyberNet Studio',
                email: 'contact@cybernet.id',
                role: 'Penjual',
                status: 'Aktif'
            },
            {
                id: 2,
                name: 'Nusantara Devs',
                email: 'dev@nusantara.id',
                role: 'Penjual',
                status: 'Aktif'
            },
            {
                id: 3,
                name: 'Arga Studio',
                email: 'support@argastudio.com',
                role: 'Penjual',
                status: 'Aktif'
            },
            {
                id: 4,
                name: 'Rian_Devs Studio',
                email: 'rian.prasetya@gmail.com',
                role: 'Pembeli',
                status: 'Aktif'
            },
            {
                id: 5,
                name: 'Super Administrator',
                email: 'admin@indofx.id',
                role: 'Admin',
                status: 'Aktif'
            }
        ];

        // Data Basis Antrean Moderasi Awal
        const defaultModerationQueue = [{
                id: 'mod-1',
                title: 'Sci-Fi Holo UI Shader Package',
                pipeline: 'HDRP',
                category: 'Shader',
                file_info: 'File: scifi_holo_v1.zip (32 MB)',
                studio: 'CyberNet Studio',
                unity_ver: 'Unity 2022.3 LTS',
                price: 'Rp150.000',
                price_num: 150000,
                created_at: '15 Okt 2026',
                time: '09:15 WIB',
                status: 'Pending Review'
            },
            {
                id: 'mod-2',
                title: 'Procedural Stylized Grass System',
                pipeline: 'URP',
                category: 'VFX Partikel',
                file_info: 'File: stylized_grass_v2.unitypackage (14 MB)',
                studio: 'Nusantara Devs',
                unity_ver: 'Unity 2021.3 LTS',
                price: 'Gratis',
                price_num: 0,
                created_at: '16 Okt 2026',
                time: '14:20 WIB',
                status: 'Pending Review'
            },
            {
                id: 'mod-3',
                title: 'Volumetric Rain & Storm URP',
                pipeline: 'URP',
                category: 'VFX Partikel',
                file_info: 'File: volumetric_rain_v1.2.unitypackage (48 MB)',
                studio: 'Arga Studio',
                unity_ver: 'Unity 2021.3 LTS',
                price: 'Rp150.000',
                price_num: 150000,
                created_at: '14 Okt 2026',
                time: '11:00 WIB',
                status: 'Published'
            }
        ];

        let allModerationAssets = [];
        let modalReviewInstance = null;

        // 1. SINKRONISASI DATA DINAMIS PENUH (TANPA NILAI HARDCODED)
        function syncAdminDashboardData() {
            // ==========================================
            // A. TOTAL PENGGUNA DINAMIS (MURNI DATA RIIL)
            // ==========================================
            let userList = [];
            try {
                userList = JSON.parse(localStorage.getItem('indofx_admin_users')) || [];
            } catch (e) {
                userList = [];
            }

            if (userList.length === 0) {
                userList = [...defaultUsers];
                localStorage.setItem('indofx_admin_users', JSON.stringify(userList));
            }

            // Pindai juga jika ada pendaftaran user baru dari auth web
            ['indofx_users', 'indofx_registered_users', 'users'].forEach(key => {
                try {
                    const extra = JSON.parse(localStorage.getItem(key)) || [];
                    if (Array.isArray(extra)) {
                        extra.forEach(eu => {
                            if (eu.email && !userList.some(u => u.email === eu.email)) {
                                userList.push({
                                    id: eu.id || Date.now(),
                                    name: eu.name || 'Pengguna Baru',
                                    email: eu.email,
                                    role: eu.role || 'Pembeli',
                                    status: 'Aktif',
                                    join_date: 'Hari ini',
                                    stats: '0 Aset'
                                });
                            }
                        });
                    }
                } catch (e) {}
            });
            localStorage.setItem('indofx_admin_users', JSON.stringify(userList));

            // Hitung eksak tanpa angka buatan
            const sellerCount = userList.filter(u => u.role === 'Penjual').length;
            const buyerCount = userList.filter(u => u.role === 'Pembeli').length;
            const totalUserCount = userList.length;

            document.getElementById('statTotalUsers').innerText = totalUserCount.toLocaleString('id-ID');
            document.getElementById('statUserBreakdown').innerText = `${sellerCount} Penjual | ${buyerCount} Pembeli`;

            // ==========================================
            // B. ANTREAN MODERASI DINAMIS
            // ==========================================
            let queue = [];
            try {
                const storedQueue = localStorage.getItem('indofx_moderation_queue');
                if (storedQueue) {
                    queue = JSON.parse(storedQueue);
                } else {
                    queue = [...defaultModerationQueue];
                    localStorage.setItem('indofx_moderation_queue', JSON.stringify(queue));
                }
            } catch (e) {
                queue = [...defaultModerationQueue];
            }

            // Pindai aset yang baru diunggah kreator
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('indofx_seller_assets_')) {
                    try {
                        const sellerAssets = JSON.parse(localStorage.getItem(key)) || [];
                        sellerAssets.forEach(sa => {
                            const exists = queue.some(q => String(q.id) === String(sa.id) || q.title === sa.title);
                            if (!exists) {
                                queue.unshift({
                                    id: sa.id || ('asset-' + Date.now()),
                                    title: sa.title,
                                    pipeline: Array.isArray(sa.pipelines) ? (sa.pipelines[0] || 'Built-in') : (sa.pipelines || 'Built-in'),
                                    category: sa.category || 'VFX Partikel',
                                    file_info: 'File: ' + (sa.title.toLowerCase().replace(/[^a-z0-9]/g, '_')) + '.unitypackage (24 MB)',
                                    studio: sa.creator || sa.seller || 'Studio Kreator',
                                    unity_ver: 'Unity 2022.3 LTS',
                                    price: typeof sa.price === 'number' ? ('Rp' + sa.price.toLocaleString('id-ID')) : (sa.price || 'Gratis'),
                                    price_num: parseFloat(String(sa.price).replace(/[^0-9]/g, '')) || 0,
                                    created_at: sa.created_at || 'Hari ini',
                                    time: 'Baru saja',
                                    status: IndoFXStorage.normalizeStatus(sa.status),
                                    seller_storage_key: key,
                                    seller_user_id: sa.seller_user_id || key.replace('indofx_seller_assets_', '')
                                });
                            }
                        });
                    } catch (e) {}
                }
            });

            allModerationAssets = queue;
            localStorage.setItem('indofx_moderation_queue', JSON.stringify(allModerationAssets));

            // Hitung eksak aset pending
            const pendingList = allModerationAssets.filter(a => IndoFXStorage.normalizeStatus(a.status) === 'Menunggu');
            document.getElementById('statPendingModeration').innerText = pendingList.length + ' Aset';
            document.getElementById('sidebarModerationBadge').innerText = pendingList.length;

            // ==========================================
            // C. ASET TERPUBLIKASI DINAMIS (KATALOG RIIL)
            // ==========================================
            let publicCatalog = [];
            try {
                publicCatalog = JSON.parse(localStorage.getItem('indofx_public_catalog')) || [];
            } catch (e) {
                publicCatalog = [];
            }

            const publishedInQueue = allModerationAssets.filter(a => IndoFXStorage.normalizeStatus(a.status) === 'Published');

            // Himpunan ID unik yang terpublikasi
            const publishedAssetIds = new Set();
            publicCatalog.forEach(c => publishedAssetIds.add(String(c.id || c.title)));
            publishedInQueue.forEach(q => publishedAssetIds.add(String(q.id || q.title)));

            const totalPublishedCount = publishedAssetIds.size;
            document.getElementById('statPublishedAssets').innerText = totalPublishedCount.toLocaleString('id-ID');

            // ==========================================
            // D. TOTAL OMSET PLATFORM DINAMIS (TRANSAKSI RIIL)
            // ==========================================
            let totalGrossRevenue = 0;
            let successTrxCount = 0;

            let trxList = [];
            try {
                trxList = JSON.parse(localStorage.getItem('indofx_admin_transactions')) || [];
            } catch (e) {}

            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('indofx_transactions_') || key.startsWith('indofx_seller_sales_')) {
                    try {
                        const txs = JSON.parse(localStorage.getItem(key)) || [];
                        txs.forEach(t => {
                            const orderId = t.invoice_no || t.order_id;
                            if (orderId && !trxList.some(item => item.order_id === orderId)) {
                                trxList.push({
                                    order_id: orderId,
                                    total_bayar: parseFloat(t.subtotal || t.price || t.total) || 0,
                                    status: (t.status === 'Berhasil' || t.status === 'Settlement') ? 'Settlement' : t.status
                                });
                            }
                        });
                    } catch (e) {}
                }
            });

            // Akumulasi transaksi berstatus Settlement
            trxList.forEach(t => {
                if (t.status === 'Settlement' || t.status === 'Berhasil') {
                    totalGrossRevenue += (parseFloat(t.total_bayar) || 0);
                    successTrxCount++;
                }
            });

            if (totalGrossRevenue >= 1000000) {
                document.getElementById('statTotalRevenue').innerText = `Rp ${(totalGrossRevenue / 1000000).toFixed(2)}jt`;
            } else {
                document.getElementById('statTotalRevenue').innerText = `Rp ${totalGrossRevenue.toLocaleString('id-ID')}`;
            }
            document.getElementById('statRevenueSub').innerText = `${successTrxCount} Transaksi Berhasil`;

            // ==========================================
            // E. BADGE PENGAJUAN PENJUAL
            // ==========================================
            let sellerReqs = [];
            try {
                sellerReqs = JSON.parse(localStorage.getItem('indofx_seller_requests')) || [];
            } catch (e) {}
            const pendingReqCount = sellerReqs.filter(r => r.status === 'Menunggu').length;
            document.getElementById('sidebarSellerReqBadge').innerText = pendingReqCount;

            // Render tabel
            renderModerationTable();
        }

        // 2. RENDER TABEL ANTREAN TERBARU
        function renderModerationTable() {
            const tbody = document.getElementById('adminModerationTableBody');
            if (allModerationAssets.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">
                            Tidak ada antrean moderasi aset saat ini.
                        </td>
                    </tr>
                `;
                return;
            }

            const displayItems = allModerationAssets.slice(0, 5);

            tbody.innerHTML = displayItems.map(item => {
                const pipe = item.pipeline || 'URP';
                let pipeClass = 'badge-pipe-urp';
                if (pipe.toLowerCase().includes('hdrp')) pipeClass = 'badge-pipe-hdrp';
                if (pipe.toLowerCase().includes('built')) pipeClass = 'badge-pipe-builtin';

                const isPublished = IndoFXStorage.normalizeStatus(item.status) === 'Published';
                const isRejected = IndoFXStorage.normalizeStatus(item.status) === 'Ditolak';

                let statusBadge = `<span class="badge-status-review">Pending Review</span>`;
                if (isPublished) {
                    statusBadge = `<span class="badge-status-published">Published</span>`;
                } else if (isRejected) {
                    statusBadge = `<span class="badge-status-blocked">Ditolak</span>`;
                }

                const actionBtn = isPublished ?
                    `
                        <a href="/asset/detail?id=${encodeURIComponent(item.id)}&title=${encodeURIComponent(item.title)}&category=${encodeURIComponent(item.category || '')}&creator=${encodeURIComponent(item.studio || '')}&price=${encodeURIComponent(item.price_num || 0)}&pipeline=${encodeURIComponent(item.pipeline || '')}" class="btn-admin-public">
                            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                <rect x="2" y="2" width="12" height="12" rx="2.5" />
                                <line x1="5.5" y1="5.5" x2="10.5" y2="5.5" />
                                <line x1="5.5" y1="8" x2="10.5" y2="8" />
                                <line x1="5.5" y1="10.5" x2="8.5" y2="10.5" />
                            </svg>
                            <span>Halaman Publik</span>
                        </a>
                    ` :
                    `
                        <button type="button" class="btn-admin-detail" onclick="openReviewModal('${item.id}')">
                            <i class="bi bi-file-earmark-text"></i> Detail
                        </button>
                    `;

                return `
                    <tr>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                <span class="fw-bold text-dark text-nowrap">${item.title}</span>
                                <span class="badge-pipe-admin ${pipeClass}">${pipe}</span>
                            </div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${item.file_info || 'File: package.zip'}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${item.studio || 'Studio Kreator'}</td>
                        <td class="text-dark cell-nowrap">${item.unity_ver || 'Unity 2022.3 LTS'}</td>
                        <td class="fw-bold text-dark cell-nowrap">${item.price || 'Gratis'}</td>
                        <td class="cell-nowrap">
                            <div class="text-dark">${item.created_at || 'Hari ini'}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">${item.time || 'WIB'}</div>
                        </td>
                        <td class="cell-nowrap">${statusBadge}</td>
                        <td class="text-end pe-3 cell-nowrap">${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }

        // 3. AKSI BUKA MODAL DETAIL PENINJAUAN
        function openReviewModal(assetId) {
            const item = allModerationAssets.find(a => String(a.id) === String(assetId));
            if (!item) return;

            document.getElementById('reviewAssetId').value = item.id;
            document.getElementById('reviewAssetTitle').innerText = item.title;
            document.getElementById('reviewStudioName').innerText = item.studio || 'Studio Kreator';
            document.getElementById('reviewPriceText').innerText = item.price || 'Gratis';
            document.getElementById('reviewCategoryText').innerText = item.category || 'VFX Partikel';
            document.getElementById('reviewPipelineBadge').innerText = item.pipeline || 'URP';
            document.getElementById('reviewFileName').innerText = (item.title.toLowerCase().replace(/[^a-z0-9]/g, '_')) + '.unitypackage';
            document.getElementById('reviewRejectReason').value = '';

            modalReviewInstance.show();
        }

        // 4. AKSI SETUJUI & PUBLIKASIKAN ASET
        function handleModerationApprove() {
            const assetId = document.getElementById('reviewAssetId').value;
            let targetAsset = null;

            allModerationAssets = allModerationAssets.map(item => {
                if (String(item.id) === String(assetId)) {
                    item.status = 'Published';
                    targetAsset = item;
                }
                return item;
            });

            localStorage.setItem('indofx_moderation_queue', JSON.stringify(allModerationAssets));

            if (targetAsset) {
                let publicCatalog = [];
                try {
                    publicCatalog = JSON.parse(localStorage.getItem('indofx_public_catalog')) || [];
                } catch (e) {}
                publicCatalog = publicCatalog.filter(c => String(c.id) !== String(targetAsset.id));
                const rawPrice = (typeof targetAsset.price_num === 'number') ?
                    targetAsset.price_num :
                    (parseFloat(String(targetAsset.price).replace(/[^0-9]/g, '')) || 0);
                const pipelinesArray = Array.isArray(targetAsset.pipelines) && targetAsset.pipelines.length > 0 ?
                    targetAsset.pipelines : [targetAsset.pipeline || 'Built-in'];

                publicCatalog.unshift({
                    id: targetAsset.id,
                    title: targetAsset.title,
                    creator: targetAsset.studio || targetAsset.creator || '',
                    seller_user_id: targetAsset.seller_user_id || '',
                    seller_key: targetAsset.seller_storage_key || '',
                    price: rawPrice,
                    price_text: rawPrice === 0 ? 'Gratis' : ('Rp' + rawPrice.toLocaleString('id-ID')),
                    category: targetAsset.category || 'VFX Partikel',
                    pipelines: pipelinesArray,
                    unity_version: targetAsset.unity_ver || targetAsset.unity_version || '2022.3 LTS',
                    format: targetAsset.file_info && targetAsset.file_info.includes('.zip') ? '.zip' : '.unitypackage',
                    downloads: targetAsset.downloads || '0x Terjual',
                    image: targetAsset.image || 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
                    description: targetAsset.description || '',
                    how_to_use: targetAsset.how_to_use || '',
                    video_url: targetAsset.video_url || '',
                    published_at: new Date().toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    })
                });
                localStorage.setItem('indofx_public_catalog', JSON.stringify(publicCatalog));

                if (targetAsset.seller_storage_key) {
                    try {
                        let sAssets = JSON.parse(localStorage.getItem(targetAsset.seller_storage_key)) || [];
                        sAssets = sAssets.map(sa => {
                            if (String(sa.id) === String(targetAsset.id)) sa.status = 'Disetujui';
                            return sa;
                        });
                        localStorage.setItem(targetAsset.seller_storage_key, JSON.stringify(sAssets));
                    } catch (e) {}
                }
            }

            modalReviewInstance.hide();
            syncAdminDashboardData();
        }

        // 5. AKSI TOLAK ASET
        function handleModerationReject() {
            const assetId = document.getElementById('reviewAssetId').value;
            const reason = document.getElementById('reviewRejectReason').value.trim();

            if (!reason) {
                alert('Silakan tuliskan catatan perbaikan alasan penolakan untuk penjual.');
                document.getElementById('reviewRejectReason').focus();
                return;
            }

            allModerationAssets = allModerationAssets.map(item => {
                if (String(item.id) === String(assetId)) {
                    item.status = 'Ditolak';
                    item.reject_reason = reason;

                    if (item.seller_storage_key) {
                        try {
                            let sAssets = JSON.parse(localStorage.getItem(item.seller_storage_key)) || [];
                            sAssets = sAssets.map(sa => {
                                if (String(sa.id) === String(item.id)) {
                                    sa.status = 'Ditolak';
                                    sa.reject_reason = reason;
                                }
                                return sa;
                            });
                            localStorage.setItem(item.seller_storage_key, JSON.stringify(sAssets));
                        } catch (e) {}
                    }
                }
                return item;
            });

            localStorage.setItem('indofx_moderation_queue', JSON.stringify(allModerationAssets));
            modalReviewInstance.hide();
            syncAdminDashboardData();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalReviewInstance = new bootstrap.Modal(document.getElementById('modalReviewAsset'));
            syncAdminDashboardData();

            // Dropdown Profil Admin
            const btn = document.getElementById('adminDropdownBtn');
            const menu = document.getElementById('adminDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>