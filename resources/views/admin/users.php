<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = (string) ($_SESSION['name'] ?? 'Super Admin IndoFX');
$adminInitials = 'AP';
$words = preg_split('/\s+/', trim($adminName)) ?: [];
if (count($words) >= 2) {
    $adminInitials = mb_strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
} elseif (!empty($words[0])) {
    $adminInitials = mb_strtoupper(mb_substr($words[0], 0, 2));
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Pengguna Platform - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Khusus Admin Murni Terpisah -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <!-- Brand Logo -->
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

    <!-- 2. Kontainer Utama -->
    <div class="admin-layout-container">

        <!-- Sidebar Kiri Admin Sesuai Figma image_45fee9 -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link active">
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

        <!-- Konten Kanan -->
        <main class="admin-main-content">
            <h1 class="admin-page-title">Pengelolaan Pengguna Platform</h1>
            <p class="admin-page-sub">Kelola akun penjual, pembeli, dan administrator terdaftar di IndoFX Marketplace.</p>

            <!-- 4 Kartu Ringkasan Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Pengguna</div>
                        <div class="admin-stat-val">1.284</div>
                        <div class="admin-stat-sub text-muted">+12 minggu ini</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Penjual / Studio</div>
                        <div class="admin-stat-val">84</div>
                        <div class="admin-stat-sub text-muted">Kreator Terverifikasi</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pembeli Aktif</div>
                        <div class="admin-stat-val">1.195</div>
                        <div class="admin-stat-sub text-muted">User Publik</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pengguna Diblokir</div>
                        <div class="admin-stat-val val-red">5</div>
                        <div class="admin-stat-sub text-muted">Pelanggaran Syarat</div>
                    </div>
                </div>
            </div>

            <!-- Bar Filter -->
            <div class="row g-2 align-items-center admin-filter-bar">
                <div class="col-12 col-md-6">
                    <div class="admin-search-wrap">
                        <i class="bi bi-search admin-search-icon"></i>
                        <input type="text" id="adminSearchInput" class="form-control admin-search-input" placeholder="Cari nama, email, atau studio...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterRoleSelect">
                        <option value="all" selected>Semua Peran</option>
                        <option value="Penjual">Penjual</option>
                        <option value="Pembeli">Pembeli</option>
                        <option value="Admin">Admin</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterStatusSelect">
                        <option value="all" selected>Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Diblokir">Diblokir</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Pengguna -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 26%;">Pengguna</th>
                                <th scope="col" style="width: 22%;">Email</th>
                                <th scope="col" style="width: 10%;">Peran</th>
                                <th scope="col" style="width: 11%;">Status Akun</th>
                                <th scope="col" style="width: 13%;">Tanggal Bergabung</th>
                                <th scope="col" style="width: 12%;">Total Transaksi / Aset</th>
                                <th scope="col" class="text-end pe-4" style="width: 6%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="adminUsersTbody">
                            <!-- Diisi secara dinamis oleh JavaScript -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const initialUsers = [{
                id: '1',
                name: 'CyberNet Studio',
                verified: true,
                email: 'contact@cybernet.id',
                role: 'Penjual',
                status: 'Aktif',
                join_date: '10 Jan 2026',
                stats: '12 Aset (Rp18.5jt)',
                badge_bg: '#FCE8DC',
                badge_color: '#ED6B1D',
                inits: 'CN'
            },
            {
                id: '2',
                name: 'Nusantara Devs',
                verified: true,
                email: 'contact@cybernet.id',
                role: 'Penjual',
                status: 'Aktif',
                join_date: '04 Feb 2026',
                stats: '8 Aset (Rp9.2jt)',
                badge_bg: '#FEEFD8',
                badge_color: '#B76E00',
                inits: 'ND'
            },
            {
                id: '3',
                name: 'Rian_Devs Studio',
                verified: false,
                email: 'contact@cybernet.id',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '18 Mar 2026',
                stats: '5 Pembelian (Rp750rb)',
                badge_bg: '#FCE4D6',
                badge_color: '#C55A11',
                inits: 'RD'
            },
            {
                id: '4',
                name: 'Super Administrator',
                verified: false,
                email: 'contact@cybernet.id',
                role: 'Admin',
                status: 'Aktif',
                join_date: '01 Jan 2026',
                stats: 'System Root',
                badge_bg: '#FADBD8',
                badge_color: '#C0392B',
                inits: 'AD'
            },
            {
                id: '5',
                name: 'Fake Asset Spammer',
                verified: false,
                email: 'contact@cybernet.id',
                role: 'Penjual',
                status: 'Diblokir',
                join_date: '12 Mei 2026',
                stats: '0 Aset (Spamming)',
                badge_bg: '#F5CBA7',
                badge_color: '#B9770E',
                inits: 'FX'
            }
        ];

        function renderUsersTable() {
            const tbody = document.getElementById('adminUsersTbody');
            const searchKeyword = document.getElementById('adminSearchInput').value.toLowerCase().trim();
            const roleFilter = document.getElementById('filterRoleSelect').value;
            const statusFilter = document.getElementById('filterStatusSelect').value;

            const filtered = initialUsers.filter(u => {
                const matchKeyword = !searchKeyword ||
                    u.name.toLowerCase().includes(searchKeyword) ||
                    u.email.toLowerCase().includes(searchKeyword);
                const matchRole = roleFilter === 'all' || u.role === roleFilter;
                const matchStatus = statusFilter === 'all' || u.status === statusFilter;
                return matchKeyword && matchRole && matchStatus;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">
                            Tidak ada pengguna yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(u => {
                const verifiedIcon = u.verified ? '<i class="bi bi-check-lg ms-1 text-dark"></i>' : '';
                const statusBadge = u.status === 'Aktif' ?
                    '<span class="badge-status-active">Aktif</span>' :
                    '<span class="badge-status-blocked">Diblokir</span>';

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="user-avatar-tag" style="background-color: ${u.badge_bg}; color: ${u.badge_color};">
                                    ${u.inits}
                                </span>
                                <div>
                                    <div class="user-name-title">
                                        <span>${u.name}</span>
                                        ${verifiedIcon}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.74rem;">${u.email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">${u.email}</td>
                        <td><span class="role-pill">${u.role}</span></td>
                        <td>${statusBadge}</td>
                        <td class="text-muted">${u.join_date}</td>
                        <td class="fw-bold text-dark">${u.stats}</td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn-edit-user">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        document.addEventListener('DOMContentLoaded', function() {
            renderUsersTable();
            document.getElementById('adminSearchInput').addEventListener('input', renderUsersTable);
            document.getElementById('filterRoleSelect').addEventListener('change', renderUsersTable);
            document.getElementById('filterStatusSelect').addEventListener('change', renderUsersTable);

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