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

    <!-- Stylesheet Admin Terpisah -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
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

            <!-- Profil Admin Kanan & Dropdown Menu Logout Sesuai Figma -->
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

        <!-- Sidebar Kiri Sesuai Figma image_1eeb36 -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link active">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge">2</span>
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

            <!-- Bar Filter Sesuai Figma image_1eeb36 -->
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
                        <option value="Pending Review">Pending Review</option>
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
                        <option value="Tekstur & Material">Tekstur &amp; Material</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Antrean Moderasi Sesuai Figma image_1eeb36 -->
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
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. Footer Sesuai Desain Figma (Gambar ke-3) -->
    <footer class="footer-admin">
        <div class="footer-admin-content">
            <h5 class="footer-admin-title">IndoFX</h5>
            <p class="footer-admin-desc">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
        </div>
        <div class="footer-admin-bottom">
            <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
            <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Data Aset Moderasi sesuai Figma image_1eeb36
        const moderationItems = [{
                id: 'mod-1',
                title: 'Sci-Fi Holo UI Shader Package',
                pipeline: 'HDRP',
                pipeline_class: 'badge-pipe-hdrp',
                category: 'Shader',
                file_info: 'File: scifi_holo_v1.zip (32 MB)',
                studio: 'CyberNet Studio',
                unity_ver: 'Unity 2022.3 LTS',
                price: 'Rp150.000',
                date: '15 Okt 2026',
                time: '09:15 WIB',
                status: 'Pending Review',
                is_public: false
            },
            {
                id: 'mod-2',
                title: 'Sci-Fi Holo UI Shader Package',
                pipeline: 'HDRP',
                pipeline_class: 'badge-pipe-hdrp',
                category: 'Shader',
                file_info: 'File: scifi_holo_v1.zip (32 MB)',
                studio: 'CyberNet Studio',
                unity_ver: 'Unity 2022.3 LTS',
                price: 'Rp150.000',
                date: '15 Okt 2026',
                time: '09:15 WIB',
                status: 'Pending Review',
                is_public: false
            },
            {
                id: 'mod-3',
                title: 'Procedural Stylized Grass System',
                pipeline: 'URP',
                pipeline_class: 'badge-pipe-urp',
                category: 'VFX Partikel',
                file_info: 'File: stylized_grass_v2.unitypackage (14 MB)',
                studio: 'Nusantara Devs',
                unity_ver: 'Unity 2021.3 LTS',
                price: 'Gratis',
                date: '16 Okt 2026',
                time: '14:20 WIB',
                status: 'Pending Review',
                is_public: false
            },
            {
                id: 'mod-4',
                title: 'Procedural Stylized Grass System',
                pipeline: 'URP',
                pipeline_class: 'badge-pipe-urp',
                category: 'VFX Partikel',
                file_info: 'File: stylized_grass_v2.unitypackage (14 MB)',
                studio: 'Nusantara Devs',
                unity_ver: 'Unity 2021.3 LTS',
                price: 'Gratis',
                date: '16 Okt 2026',
                time: '14:20 WIB',
                status: 'Pending Review',
                is_public: false
            },
            {
                id: 'mod-5',
                title: 'Volumetric Rain & Storm URP',
                pipeline: 'URP',
                pipeline_class: 'badge-pipe-urp',
                category: 'VFX Partikel',
                file_info: 'File: volumetric_rain_v1.2.unitypackage (48 MB)',
                studio: 'Arga Studio',
                unity_ver: 'Unity 2021.3 LTS',
                price: 'Rp150.000',
                date: '14 Okt 2026',
                time: '11:00 WIB',
                status: 'Published',
                is_public: true
            }
        ];

        function renderModerationTable() {
            const tbody = document.getElementById('moderationTableBody');
            const keyword = document.getElementById('adminFilterAssetInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterModerationStatus').value;
            const categoryFilter = document.getElementById('filterModerationCategory').value;

            const filtered = moderationItems.filter(item => {
                const matchKeyword = !keyword ||
                    item.title.toLowerCase().includes(keyword) ||
                    item.studio.toLowerCase().includes(keyword);
                const matchStatus = statusFilter === 'all' || item.status === statusFilter;
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
                const statusBadge = item.status === 'Published' ?
                    `<span class="badge-status-published">Published</span>` :
                    `<span class="badge-status-review">Pending Review</span>`;

                const actionButton = item.is_public ?
                    `
                        <a href="/asset/detail?id=1&title=${encodeURIComponent(item.title)}" class="btn-admin-public">
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

                return `
                    <tr>
                        <td>
                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                <span class="fw-bold text-dark text-nowrap">${item.title}</span>
                                <span class="badge-pipe-admin ${item.pipeline_class}">${item.pipeline}</span>
                            </div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${item.file_info}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${item.studio}</td>
                        <td class="text-dark cell-nowrap">${item.unity_ver}</td>
                        <td class="fw-bold text-dark cell-nowrap">${item.price}</td>
                        <td class="cell-nowrap">
                            <div class="text-dark">${item.date}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">${item.time}</div>
                        </td>
                        <td class="cell-nowrap">${statusBadge}</td>
                        <td class="text-end pe-3 cell-nowrap">${actionButton}</td>
                    </tr>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderModerationTable();

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