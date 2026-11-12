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
    <title>Daftar Antrean Moderasi Aset - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheet Admin -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
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

            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Layout Utama -->
    <div class="admin-layout-container">
        <!-- Sidebar Kiri -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link active">
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
                    <span class="admin-sidebar-badge" id="sidebarSellerBadge">0</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Utama Kanan -->
        <main class="admin-main-content">
            <h1 class="admin-page-title">Daftar Antrean Moderasi Aset</h1>
            <p class="admin-page-sub">Klik tombol Detail pada aset untuk membuka subpage peninjauan dan pengambilan keputusan moderasi.</p>

            <!-- Bar Filter -->
            <div class="row g-2 align-items-center admin-filter-bar mb-3">
                <div class="col-12 col-md-6">
                    <div class="admin-search-wrap">
                        <i class="bi bi-search admin-search-icon"></i>
                        <input type="text" id="adminFilterAssetInput" class="form-control admin-search-input" placeholder="Cari Nama Aset...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterModerationStatus">
                        <option value="all" selected>Semua Status Moderasi</option>
                        <option value="Menunggu">Menunggu Peninjauan</option>
                        <option value="Published">Published</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterModerationCategory">
                        <option value="all" selected>Semua Kategori</option>
                        <option value="Shader">Shader</option>
                        <option value="VFX Partikel">VFX Partikel</option>
                        <option value="Model 3D">Model 3D</option>
                        <option value="Script / Tool">Script / Tool</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Antrean Moderasi -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="min-width: 230px;">Aset &amp; File</th>
                                <th scope="col" class="cell-nowrap">Penjual / Studio</th>
                                <th scope="col" class="cell-nowrap">Versi Unity</th>
                                <th scope="col" class="cell-nowrap">Harga</th>
                                <th scope="col" class="cell-nowrap">Tanggal Masuk &darr;</th>
                                <th scope="col" class="cell-nowrap">Status Moderasi</th>
                                <th scope="col" class="text-end pe-3 cell-nowrap">Aksi Peninjauan</th>
                            </tr>
                        </thead>
                        <tbody id="moderationTableBody">
                            <!-- Diisi secara dinamis oleh JavaScript -->
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

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <!-- ENGINE TABEL MODERASI ASET (MENGARAH KE SUBPAGE PENINJAUAN) -->
    <script>
        const initialQueue = [{
                id: 'mod-1',
                title: 'Sci-Fi Holo UI Shader Package',
                pipeline: 'HDRP',
                category: 'Shader',
                file_info: 'File: scifi_holo_v1.zip (32 MB)',
                studio: 'CyberNet Studio',
                unity_ver: 'Unity 2022.3 LTS',
                price: 'Rp150.000',
                price_num: 150000,
                date: '15 Okt 2026',
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
                date: '16 Okt 2026',
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
                date: '14 Okt 2026',
                time: '11:00 WIB',
                status: 'Published'
            }
        ];

        let moderationAssets = [];

        function loadModerationData() {
            try {
                const stored = localStorage.getItem('indofx_moderation_queue');
                if (stored) {
                    moderationAssets = JSON.parse(stored);
                } else {
                    moderationAssets = [...initialQueue];
                    localStorage.setItem('indofx_moderation_queue', JSON.stringify(moderationAssets));
                }
            } catch (e) {
                moderationAssets = [...initialQueue];
            }

            // Pindai aset dari penjual yang baru diunggah
            Object.keys(localStorage).forEach(key => {
                if (key.startsWith('indofx_seller_assets_')) {
                    try {
                        const sAssets = JSON.parse(localStorage.getItem(key)) || [];
                        sAssets.forEach(sa => {
                            const exists = moderationAssets.some(m => String(m.id) === String(sa.id) || m.title === sa.title);
                            if (!exists) {
                                moderationAssets.unshift({
                                    id: sa.id || ('asset-' + Date.now()),
                                    title: sa.title,
                                    pipeline: Array.isArray(sa.pipelines) ? (sa.pipelines[0] || 'Built-in') : (sa.pipelines || 'Built-in'),
                                    category: sa.category || 'VFX Partikel',
                                    file_info: 'File: ' + sa.title.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.unitypackage (24 MB)',
                                    studio: sa.creator || sa.seller || 'Studio Kreator',
                                    unity_ver: 'Unity 2022.3 LTS',
                                    price: typeof sa.price === 'number' ? ('Rp' + sa.price.toLocaleString('id-ID')) : (sa.price || 'Gratis'),
                                    price_num: parseFloat(String(sa.price).replace(/[^0-9]/g, '')) || 0,
                                    date: sa.created_at || sa.date || 'Hari ini',
                                    time: 'Baru saja',
                                    status: IndoFXStorage.normalizeStatus(sa.status),
                                    seller_key: key,
                                    seller_user_id: sa.seller_user_id || key.replace('indofx_seller_assets_', '')
                                });
                            }
                        });
                    } catch (e) {}
                }
            });

            localStorage.setItem('indofx_moderation_queue', JSON.stringify(moderationAssets));

            // Perbarui badge sidebar
            const pendingCount = moderationAssets.filter(a => IndoFXStorage.normalizeStatus(a.status) === 'Menunggu').length;
            document.getElementById('sidebarModerationBadge').innerText = pendingCount;

            renderModerationTable();
        }

        function renderModerationTable() {
            const tbody = document.getElementById('moderationTableBody');
            const keyword = document.getElementById('adminFilterAssetInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterModerationStatus').value;
            const categoryFilter = document.getElementById('filterModerationCategory').value;

            const filtered = moderationAssets.filter(item => {
                const matchKeyword = !keyword ||
                    item.title.toLowerCase().includes(keyword) ||
                    item.studio.toLowerCase().includes(keyword);

                const itemStatusNormalized = IndoFXStorage.normalizeStatus(item.status);
                const matchStatus = statusFilter === 'all' || itemStatusNormalized === statusFilter;
                const matchCategory = categoryFilter === 'all' || item.category === categoryFilter;

                return matchKeyword && matchStatus && matchCategory;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">
                            Tidak ada antrean moderasi yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
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

                // Tombol Detail langsung membuka SUBPAGE REVIEW FIGMA (bukan modal popup)
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
                        <a href="/admin/assets/review?id=${encodeURIComponent(item.id)}" class="btn-admin-detail">
                            <i class="bi bi-file-earmark-text"></i> Detail
                        </a>
                    `;

                const displayDate = item.date || item.created_at || '15 Okt 2026';
                const displayTime = item.time || 'WIB';

                return `
                    <tr>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                <span class="fw-bold text-dark text-nowrap">${item.title}</span>
                                <span class="badge-pipe-admin ${pipeClass}">${pipe}</span>
                            </div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${item.file_info}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${item.studio}</td>
                        <td class="text-dark cell-nowrap">${item.unity_ver || 'Unity 2022.3 LTS'}</td>
                        <td class="fw-bold text-dark cell-nowrap">${item.price}</td>
                        <td class="cell-nowrap">
                            <div class="text-dark">${displayDate}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">${displayTime}</div>
                        </td>
                        <td class="cell-nowrap">${statusBadge}</td>
                        <td class="text-end pe-3 cell-nowrap">${actionBtn}</td>
                    </tr>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadModerationData();

            document.getElementById('adminFilterAssetInput').addEventListener('input', renderModerationTable);
            document.getElementById('filterModerationStatus').addEventListener('change', renderModerationTable);
            document.getElementById('filterModerationCategory').addEventListener('change', renderModerationTable);

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