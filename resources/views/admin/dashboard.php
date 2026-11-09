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

                <!-- Dropdown Menu Sesuai Figma image_45fee9 -->
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
            <h1 class="admin-page-title">Ringkasan Platform IndoFX</h1>
            <p class="admin-page-sub">Pemantauan statistik operasional platform dan antrean moderasi aset.</p>

            <!-- 4 Kartu Ringkasan Sesuai Figma image_45fee9 -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Pengguna -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Pengguna</div>
                        <div class="admin-stat-val">1.284</div>
                        <div class="admin-stat-sub">84 Penjual | 1.200 Pembeli</div>
                    </div>
                </div>

                <!-- Card 2: Antrean Moderasi -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Antrean Moderasi</div>
                        <div class="admin-stat-val val-orange">2 Aset</div>
                        <div class="admin-stat-sub">Menunggu Peninjauan</div>
                    </div>
                </div>

                <!-- Card 3: Aset Terpublikasi -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Aset Terpublikasi</div>
                        <div class="admin-stat-val val-green">312</div>
                        <div class="admin-stat-sub">Tampil di Katalog</div>
                    </div>
                </div>

                <!-- Card 4: Total Omset Platform Sesuai Figma -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Omset Platform</div>
                        <div class="admin-stat-val">Rp 48.25jt</div>
                        <div class="admin-stat-sub">Simulasi Midtrans</div>
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

            <!-- Tabel Antrean Moderasi Sesuai Figma -->
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
                        <tbody>
                            <!-- Baris 1: Sci-Fi Holo UI Package -->
                            <tr>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                        <span class="fw-bold text-dark text-nowrap">Sci-Fi Holo UI Shader Package</span>
                                        <span class="badge-pipe-admin badge-pipe-hdrp">HDRP</span>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.73rem;">File: scifi_holo_v1.zip (32 MB)</div>
                                </td>
                                <td class="fw-bold text-dark cell-nowrap">CyberNet Studio</td>
                                <td class="text-dark cell-nowrap">Unity 2022.3 LTS</td>
                                <td class="fw-bold text-dark cell-nowrap">Rp150.000</td>
                                <td class="cell-nowrap">
                                    <div class="text-dark">15 Okt 2026</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">09:15 WIB</div>
                                </td>
                                <td class="cell-nowrap"><span class="badge-status-review">Pending Review</span></td>
                                <td class="text-end pe-3 cell-nowrap">
                                    <a href="/admin/assets" class="btn-admin-detail">
                                        <i class="bi bi-file-earmark-text"></i> Detail
                                    </a>
                                </td>
                            </tr>

                            <!-- Baris 2: Procedural Stylized Grass System -->
                            <tr>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                        <span class="fw-bold text-dark text-nowrap">Procedural Stylized Grass System</span>
                                        <span class="badge-pipe-admin badge-pipe-urp">URP</span>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.73rem;">File: stylized_grass_v2.unitypackage (14 MB)</div>
                                </td>
                                <td class="fw-bold text-dark cell-nowrap">Nusantara Devs</td>
                                <td class="text-dark cell-nowrap">Unity 2021.3 LTS</td>
                                <td class="fw-bold text-dark cell-nowrap">Gratis</td>
                                <td class="cell-nowrap">
                                    <div class="text-dark">16 Okt 2026</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">14:20 WIB</div>
                                </td>
                                <td class="cell-nowrap"><span class="badge-status-review">Pending Review</span></td>
                                <td class="text-end pe-3 cell-nowrap">
                                    <a href="/admin/assets" class="btn-admin-detail">
                                        <i class="bi bi-file-earmark-text"></i> Detail
                                    </a>
                                </td>
                            </tr>

                            <!-- Baris 3: Volumetric Rain & Storm URP -->
                            <tr>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                        <span class="fw-bold text-dark text-nowrap">Volumetric Rain &amp; Storm URP</span>
                                        <span class="badge-pipe-admin badge-pipe-urp">URP</span>
                                    </div>
                                    <div class="text-muted small" style="font-size: 0.73rem;">File: volumetric_rain_v1.2.unitypackage (48 MB)</div>
                                </td>
                                <td class="fw-bold text-dark cell-nowrap">Arga Studio</td>
                                <td class="text-dark cell-nowrap">Unity 2021.3 LTS</td>
                                <td class="fw-bold text-dark cell-nowrap">Rp150.000</td>
                                <td class="cell-nowrap">
                                    <div class="text-dark">14 Okt 2026</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">11:00 WIB</div>
                                </td>
                                <td class="cell-nowrap"><span class="badge-status-published">Published</span></td>
                                <td class="text-end pe-3 cell-nowrap">
                                    <a href="/asset/detail?id=1&title=Volumetric+Rain+%26+Storm+URP" class="btn-admin-public">
                                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0">
                                            <rect x="2" y="2" width="12" height="12" rx="2.5" />
                                            <line x1="5.5" y1="5.5" x2="10.5" y2="5.5" />
                                            <line x1="5.5" y1="8" x2="10.5" y2="8" />
                                            <line x1="5.5" y1="10.5" x2="8.5" y2="10.5" />
                                        </svg>
                                        <span>Halaman Publik</span>
                                    </a>
                                </td>
                            </tr>
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

    <!-- Script Dropdown Profil Admin -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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