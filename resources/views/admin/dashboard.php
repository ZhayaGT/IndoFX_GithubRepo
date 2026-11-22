<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = (string) ($_SESSION['name'] ?? 'Admin IndoFX');
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="admin-csrf" content="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
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
                        <div class="admin-stat-sub" id="statRevenueSub">Transaksi berhasil</div>
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

    <!-- 3. MODAL DETAIL ASET PADA ANTREAN MODERASI -->
    <div class="modal fade" id="modalReviewAsset" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 580px;">
            <div class="modal-content modal-review-card">
                <div class="modal-review-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="seller-header-icon-box">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="modal-category-title mb-0">Detail Aset</h5>
                    </div>
                    <button type="button" class="btn-seller-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div class="modal-review-body">
                    <input type="hidden" id="reviewAssetId">

                    <div class="mb-3">
                        <span class="seller-label">Judul Aset</span>
                        <h5 class="fw-bold text-dark mb-0" id="reviewAssetTitle">-</h5>
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

                    <div class="mb-2">
                        <span class="seller-label">Deskripsi Aset</span>
                        <p class="mb-0 text-secondary small" id="reviewAssetDescription" style="line-height: 1.6;">-</p>
                    </div>
                </div>
                <div class="modal-category-footer">
                    <a href="/admin/assets" class="btn-modal-submit" id="reviewDetailLink">
                        <i class="bi bi-box-arrow-up-right"></i> Detail Aset
                    </a>
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

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <!-- Koneksi data admin -->
    <script src="/assets/js/admin-api.js"></script>

    <!-- ENGINE DATA DINAMIS ADMIN INDOFX -->
    <script>
        // Data Basis Pengguna Awal
        let allModerationAssets = [];
        let modalReviewInstance = null;

        // 1. SINKRONISASI DATA DINAMIS PENUH (TANPA NILAI HARDCODED)
        async function syncAdminDashboardData() {
            try {
                const data = await AdminApi.data();
                allModerationAssets = data.assets.filter(a => a.status === 'Menunggu');
                document.getElementById('statTotalUsers').textContent = data.users.length;
                document.getElementById('statUserBreakdown').textContent = `${data.users.filter(u => u.role === 'Penjual').length} Penjual | ${data.users.filter(u => u.role === 'Pembeli').length} Pembeli`;
                document.getElementById('statPendingModeration').textContent = allModerationAssets.length + ' Aset';
                document.getElementById('statPublishedAssets').textContent = data.assets.filter(a => a.status === 'Published').length;
                const paid = data.transactions.filter(t => t.status === 'Settlement');
                document.getElementById('statTotalRevenue').textContent = 'Rp ' + paid.reduce((sum, t) => sum + t.total_bayar, 0).toLocaleString('id-ID');
                document.getElementById('statRevenueSub').textContent = paid.length + ' Transaksi Berhasil';
                renderModerationTable();
            } catch (error) { AdminApi.showError(error); }
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

                const isPublished = AdminApi.status(item.status) === 'Published';
                const isRejected = AdminApi.status(item.status) === 'Ditolak';

                let statusBadge = `<span class="badge-status-review">Pending Review</span>`;
                if (isPublished) {
                    statusBadge = `<span class="badge-status-published">Published</span>`;
                } else if (isRejected) {
                    statusBadge = `<span class="badge-status-blocked">Ditolak</span>`;
                }

                const actionBtn = isPublished ?
                    `
                        <a href="/asset/detail?id=${encodeURIComponent(item.public_id)}&title=${encodeURIComponent(item.title)}&category=${encodeURIComponent(item.category || '')}&creator=${encodeURIComponent(item.studio || '')}&price=${encodeURIComponent(item.price_num || 0)}&pipeline=${encodeURIComponent(item.pipeline || '')}" class="btn-admin-public">
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
                        <button type="button" class="btn-admin-detail" data-review-id="${esc(item.id)}">
                            <i class="bi bi-file-earmark-text"></i> Detail
                        </button>
                    `;

                return `
                    <tr>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                <span class="fw-bold text-dark text-nowrap">${esc(item.title)}</span>
                                <span class="badge-pipe-admin ${pipeClass}">${esc(pipe)}</span>
                            </div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${esc(item.file_info || 'File: package.zip')}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${esc(item.studio || 'Studio Kreator')}</td>
                        <td class="text-dark cell-nowrap">${esc(item.unity_ver || 'Unity 2022.3 LTS')}</td>
                        <td class="fw-bold text-dark cell-nowrap">${esc(item.price || 'Gratis')}</td>
                        <td class="cell-nowrap">
                            <div class="text-dark">${esc(item.created_at || 'Hari ini')}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">${esc(item.time || 'WIB')}</div>
                        </td>
                        <td class="cell-nowrap">${statusBadge}</td>
                        <td class="text-end pe-3 cell-nowrap">${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }

        // 3. BUKA MODAL DETAIL ASET (HANYA INFORMASI)
        function openReviewModal(assetId) {
            const item = allModerationAssets.find(a => String(a.id) === String(assetId));
            if (!item) return;

            document.getElementById('reviewAssetId').value = item.id;
            document.getElementById('reviewAssetTitle').innerText = item.title;
            document.getElementById('reviewStudioName').innerText = item.studio || 'Studio Kreator';
            document.getElementById('reviewPriceText').innerText = item.price || 'Gratis';
            document.getElementById('reviewAssetDescription').innerText =
                (item.description && String(item.description).trim() !== '') ?
                String(item.description).trim() :
                'Penjual belum mencantumkan deskripsi aset.';

            const detailLink = document.getElementById('reviewDetailLink');
            if (detailLink) {
                detailLink.href = '/admin/assets/review?id=' + encodeURIComponent(item.id);
            }

            modalReviewInstance.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalReviewInstance = new bootstrap.Modal(document.getElementById('modalReviewAsset'));
            syncAdminDashboardData();

            // Delegasi tombol Detail pada tabel antrean
            document.addEventListener('click', function(event) {
                const detailBtn = event.target.closest('[data-review-id]');
                if (!detailBtn) return;
                openReviewModal(detailBtn.getAttribute('data-review-id'));
            });

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