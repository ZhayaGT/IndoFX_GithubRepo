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

// =========================================================================
// 1. QUERY DATA PENGGUNA RIIL DARI POSTGRESQL (JIKA KONEKSI AKTIF)
// =========================================================================
$serverPgUsers = [];

$dbHost = 'localhost';
$dbPort = '5432';
$dbName = 'indofx';
$dbUser = 'postgres';
$dbPass = 'postgres';

try {
    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName};";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $stmt = $pdo->query("SELECT * FROM users");
    $dbRows = $stmt->fetchAll();

    foreach ($dbRows as $row) {
        $name = $row['name'] ?? 'User Tanpa Nama';
        $email = $row['email'] ?? '-';

        $role = $row['role'] ?? null;
        if (!$role) {
            if (stripos($email, 'admin') !== false || stripos($name, 'administrator') !== false) {
                $role = 'Admin';
            } elseif (stripos($name, 'jonathan') !== false || stripos($email, 'jonathan') !== false) {
                $role = 'Penjual';
            } else {
                $role = 'Pembeli';
            }
        }

        $status = $row['status'] ?? 'Aktif';
        $joinDate = isset($row['created_at'])
            ? date('d M Y', strtotime($row['created_at']))
            : '08 Nov 2026';

        $nameParts = preg_split('/\s+/', trim($name));
        $inits = '';
        foreach (array_slice($nameParts, 0, 2) as $p) {
            $inits .= mb_strtoupper(mb_substr($p, 0, 1));
        }
        if (empty($inits)) $inits = 'US';

        $serverPgUsers[] = [
            'id' => (string)$row['id'],
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'status' => $status,
            'join_date' => $joinDate,
            'stats' => $role === 'Admin' ? 'System Root' : ($role === 'Penjual' ? '1 Aset (Terverifikasi)' : '0 Transaksi'),
            'inits' => $inits,
            'badge_bg' => '#FCE8DC',
            'badge_color' => '#ED6B1D',
            'verified' => ($role === 'Penjual')
        ];
    }
} catch (PDOException $e) {
    $serverPgUsers = [];
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

            <!-- Profil Admin Kanan & Dropdown Menu -->
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
        <!-- Sidebar Kiri Admin -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge" id="sidebarModerationBadge">0</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link active">
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
            <h1 class="admin-page-title">Pengelolaan Pengguna Platform</h1>
            <p class="admin-page-sub">Kelola akun penjual, pembeli, dan administrator terdaftar di IndoFX Marketplace.</p>

            <!-- 4 Kartu Ringkasan Statistik Riil (Gabungan Penjual + Pengguna Login) -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Pengguna</div>
                        <div class="admin-stat-val" id="statTotalUsers">0</div>
                        <div class="admin-stat-sub text-muted" id="statUsersSub">0 Penjual | 0 Pembeli</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Penjual / Studio</div>
                        <div class="admin-stat-val" id="statSellers">0</div>
                        <div class="admin-stat-sub text-muted">Kreator Terverifikasi</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pembeli Aktif</div>
                        <div class="admin-stat-val" id="statBuyers">0</div>
                        <div class="admin-stat-sub text-muted">User Publik</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pengguna Diblokir</div>
                        <div class="admin-stat-val val-red" id="statBlocked">0</div>
                        <div class="admin-stat-sub text-muted">Pelanggaran Syarat</div>
                    </div>
                </div>
            </div>

            <!-- Bar Filter -->
            <div class="row g-2 align-items-center admin-filter-bar mb-3">
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

            <!-- Tabel Pengguna Berpaginasi 5 Data per Halaman -->
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
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer Navigasi Paginasi Dinamis -->
                <div class="admin-pagination-bar" id="userPaginationBar">
                    <span id="paginationInfoText">Menampilkan 0 dari 0 pengguna</span>
                    <div class="admin-pagination-btns" id="paginationBtnsContainer">
                        <!-- Tombol Halaman Digenerate Dinamis -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL EDIT DATA PENGGUNA -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header">
                    <h5 class="modal-category-title">Edit Data Pengguna</h5>
                </div>
                <form id="formEditUser" onsubmit="saveUserEdit(event)">
                    <input type="hidden" id="editUserId">
                    <div class="modal-category-body">
                        <div class="mb-3">
                            <label class="form-label-admin">Nama Pengguna / Studio *</label>
                            <input type="text" id="editUserName" class="form-control admin-search-input" style="padding-left: 12px;" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin">Alamat Email *</label>
                            <input type="email" id="editUserEmail" class="form-control admin-search-input" style="padding-left: 12px;" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label-admin">Peran Pengguna</label>
                                <select id="editUserRole" class="form-select admin-select">
                                    <option value="Penjual">Penjual</option>
                                    <option value="Pembeli">Pembeli</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label-admin">Status Akun</label>
                                <select id="editUserStatus" class="form-select admin-select">
                                    <option value="Aktif">Aktif</option>
                                    <option value="Diblokir">Diblokir</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-category-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="bi bi-check2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <!-- ENGINE PENGGABUNGAN DATA PENJUAL + AKUN PGADMIN -->
    <script>
        // 1. DATA PENJUAL & STUDIO SEBELUMNYA LENGKAP
        const dummySellers = [{
                id: 'seller-1',
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
                id: 'seller-2',
                name: 'Nusantara Devs',
                verified: true,
                email: 'dev@nusantara.id',
                role: 'Penjual',
                status: 'Aktif',
                join_date: '04 Feb 2026',
                stats: '8 Aset (Rp9.2jt)',
                badge_bg: '#FEEFD8',
                badge_color: '#B76E00',
                inits: 'ND'
            },
            {
                id: 'seller-3',
                name: 'Rian_Devs Studio',
                verified: true,
                email: 'rian.prasetya@gmail.com',
                role: 'Penjual',
                status: 'Aktif',
                join_date: '18 Mar 2026',
                stats: '5 Pembelian (Rp750rb)',
                badge_bg: '#FCE4D6',
                badge_color: '#C55A11',
                inits: 'RD'
            },
            {
                id: 'seller-4',
                name: 'Fake Asset Spammer',
                verified: false,
                email: 'spammer@temp.mail',
                role: 'Penjual',
                status: 'Diblokir',
                join_date: '12 Mei 2026',
                stats: '0 Aset (Spamming)',
                badge_bg: '#F5CBA7',
                badge_color: '#B9770E',
                inits: 'FX'
            }
        ];

        // 2. DATA USER DARI PGADMIN / DATABASE SESUAI GAMBAR
        const rawPgUsers = <?= json_encode($serverPgUsers, JSON_UNESCAPED_UNICODE) ?>;

        const fallbackPgUsers = [{
                id: '188f0dc9-7a97-4d5e-b8b8-4c363d76d',
                name: 'naufalhaidarazmi',
                email: 'naufal@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '08 Nov 2026',
                stats: '0 Transaksi',
                inits: 'NH'
            },
            {
                id: '2a83aad1-9d50-47c2-bc21-853152d36',
                name: 'naufal haidar azmi',
                email: 'naufal889@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '08 Nov 2026',
                stats: '0 Transaksi',
                inits: 'NH'
            },
            {
                id: '5a3f3057-338a-48e3-b6b6-dc6f47162',
                name: 'naufalhaidar',
                email: 'naufal2@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '08 Nov 2026',
                stats: '0 Transaksi',
                inits: 'NH'
            },
            {
                id: '5e400795-9bd0-4269-81a5-94ca4136d',
                name: 'Administrator',
                email: 'admin@gmail.com',
                role: 'Admin',
                status: 'Aktif',
                join_date: '01 Jan 2026',
                stats: 'System Root',
                inits: 'AD'
            },
            {
                id: '650f1c63-c0d7-451a-8f2b-88996cc568',
                name: 'andrapratama',
                email: 'andripratama@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '09 Nov 2026',
                stats: '0 Transaksi',
                inits: 'AP'
            },
            {
                id: '6979575c-a095-4f37-8f73-4a4be8b00',
                name: 'naufalhaidar',
                email: 'naufaloo@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '09 Nov 2026',
                stats: '0 Transaksi',
                inits: 'NH'
            },
            {
                id: '9dd09047-5d03-40f5-9781-54fab88be',
                name: 'jonathan',
                email: 'jonathan@gmail.com',
                role: 'Penjual',
                status: 'Aktif',
                join_date: '09 Nov 2026',
                stats: '1 Aset (Terverifikasi)',
                inits: 'JO',
                verified: true
            },
            {
                id: 'f4837456-17ed-45ed-8e22-6b6bcafd4e',
                name: 'lena',
                email: 'lena@gmail.com',
                role: 'Pembeli',
                status: 'Aktif',
                join_date: '10 Nov 2026',
                stats: '0 Transaksi',
                inits: 'LE'
            }
        ];

        let users = [];
        let modalEditUserInstance = null;

        // Paginasi: 5 akun per tabel
        const itemsPerPage = 5;
        let currentPage = 1;

        // 3. FUNGSI PENGGABUNGAN DATA (MERGE PENJUAL + USER PGADMIN)
        function loadUsersData() {
            let combined = [];

            // A. Masukkan seluruh data penjual dummy terlebih dahulu
            dummySellers.forEach(ds => {
                combined.push({
                    ...ds
                });
            });

            // B. Tentukan sumber data pgAdmin (dari query PHP atau fallback riil)
            const activePgList = (rawPgUsers && rawPgUsers.length > 0) ? rawPgUsers : fallbackPgUsers;

            // C. Masukkan akun dari pgAdmin tanpa duplikasi email
            activePgList.forEach(pu => {
                const alreadyExists = combined.some(u => u.email.toLowerCase() === pu.email.toLowerCase());
                if (!alreadyExists) {
                    combined.push({
                        id: pu.id,
                        name: pu.name,
                        email: pu.email,
                        role: pu.role || 'Pembeli',
                        status: pu.status || 'Aktif',
                        join_date: pu.join_date || 'Hari ini',
                        stats: pu.stats || '0 Transaksi',
                        inits: pu.inits || 'US',
                        badge_bg: pu.badge_bg || '#FCE8DC',
                        badge_color: pu.badge_color || '#ED6B1D',
                        verified: pu.verified || (pu.role === 'Penjual')
                    });
                }
            });

            // D. Cek apakah ada modifikasi status/role yang pernah disimpan di localStorage
            try {
                const savedEdits = JSON.parse(localStorage.getItem('indofx_admin_user_overrides')) || {};
                combined = combined.map(u => {
                    if (savedEdits[u.id] || savedEdits[u.email]) {
                        return {
                            ...u,
                            ...(savedEdits[u.id] || savedEdits[u.email])
                        };
                    }
                    return u;
                });
            } catch (e) {}

            users = combined;
            localStorage.setItem('indofx_admin_users', JSON.stringify(users));

            // ==========================================
            // HITUNG 4 METRIK MURNI DARI KESELURUHAN DATA
            // ==========================================
            const total = users.length;
            const sellers = users.filter(u => u.role === 'Penjual').length;
            const buyers = users.filter(u => u.role === 'Pembeli').length;
            const blocked = users.filter(u => u.status === 'Diblokir').length;

            document.getElementById('statTotalUsers').innerText = total;
            document.getElementById('statUsersSub').innerText = `${sellers} Penjual | ${buyers} Pembeli`;
            document.getElementById('statSellers').innerText = sellers;
            document.getElementById('statBuyers').innerText = buyers;
            document.getElementById('statBlocked').innerText = blocked;

            // Update badge sidebar
            try {
                const queue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                const pCount = queue.filter(q => q.status === 'Pending' || q.status === 'Pending Review').length;
                document.getElementById('sidebarModerationBadge').innerText = pCount;
            } catch (e) {}

            try {
                const sReqs = JSON.parse(localStorage.getItem('indofx_seller_requests')) || [];
                const pReqs = sReqs.filter(r => r.status === 'Menunggu').length;
                document.getElementById('sidebarSellerBadge').innerText = pReqs;
            } catch (e) {}

            renderUsersTable();
        }

        // 4. RENDER TABEL & PAGINASI 5 AKUN PER TABEL
        function renderUsersTable() {
            const tbody = document.getElementById('adminUsersTbody');
            const searchKeyword = document.getElementById('adminSearchInput').value.toLowerCase().trim();
            const roleFilter = document.getElementById('filterRoleSelect').value;
            const statusFilter = document.getElementById('filterStatusSelect').value;

            const filtered = users.filter(u => {
                const matchKeyword = !searchKeyword ||
                    (u.name && u.name.toLowerCase().includes(searchKeyword)) ||
                    (u.email && u.email.toLowerCase().includes(searchKeyword));

                const matchRole = roleFilter === 'all' || u.role === roleFilter;
                const matchStatus = statusFilter === 'all' || u.status === statusFilter;

                return matchKeyword && matchRole && matchStatus;
            });

            const totalItems = filtered.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage) || 1;

            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            if (totalItems === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">
                            Tidak ada pengguna yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                `;
                document.getElementById('paginationInfoText').innerText = 'Menampilkan 0 dari 0 pengguna';
                document.getElementById('paginationBtnsContainer').innerHTML = '';
                return;
            }

            // Tepat 5 akun per halaman aktif
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, totalItems);
            const pageData = filtered.slice(startIndex, endIndex);

            tbody.innerHTML = pageData.map(u => {
                const verifiedIcon = u.verified ? '<i class="bi bi-check-lg ms-1 text-dark"></i>' : '';
                const statusBadge = u.status === 'Aktif' ?
                    '<span class="badge-status-active">Aktif</span>' :
                    '<span class="badge-status-blocked">Diblokir</span>';

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="user-avatar-tag" style="background-color: ${esc(u.badge_bg || '#FCE8DC')}; color: ${esc(u.badge_color || '#ED6B1D')};">
                                    ${esc(u.inits || 'US')}
                                </span>
                                <div>
                                    <div class="fw-bold text-dark">
                                        <span>${esc(u.name)}</span>
                                        ${verifiedIcon}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.74rem;">${esc(u.email)}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted">${esc(u.email)}</td>
                        <td><span class="role-pill">${esc(u.role)}</span></td>
                        <td>${statusBadge}</td>
                        <td class="text-muted">${esc(u.join_date)}</td>
                        <td class="fw-bold text-dark">${esc(u.stats || '-')}</td>
                        <td class="text-end pe-4">
                            <button type="button" class="btn-edit-user" data-edit-user-id="${esc(u.id)}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');

            document.getElementById('paginationInfoText').innerText = `Menampilkan ${startIndex + 1}-${endIndex} dari ${totalItems} pengguna`;
            renderPaginationControls(totalPages);
        }

        function renderPaginationControls(totalPages) {
            const container = document.getElementById('paginationBtnsContainer');
            let btnsHtml = '';

            btnsHtml += `
                <button type="button" class="btn-page-admin" ${currentPage === 1 ? 'disabled' : ''} onclick="goToPage(${currentPage - 1})">
                    <i class="bi bi-chevron-left"></i>
                </button>
            `;

            for (let i = 1; i <= totalPages; i++) {
                btnsHtml += `
                    <button type="button" class="btn-page-admin ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">
                        ${i}
                    </button>
                `;
            }

            btnsHtml += `
                <button type="button" class="btn-page-admin ${currentPage === totalPages ? 'disabled' : ''}" onclick="goToPage(${currentPage + 1})">
                    <i class="bi bi-chevron-right"></i>
                </button>
            `;

            container.innerHTML = btnsHtml;
        }

        function goToPage(page) {
            currentPage = page;
            renderUsersTable();
        }

        // 5. BUKA MODAL EDIT USER
        function openEditUserModal(userId) {
            const user = users.find(u => String(u.id) === String(userId));
            if (!user) return;

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserEmail').value = user.email;
            document.getElementById('editUserRole').value = user.role;
            document.getElementById('editUserStatus').value = user.status;

            modalEditUserInstance.show();
        }

        // 6. SIMPAN EDIT USER
        function saveUserEdit(e) {
            e.preventDefault();
            const id = document.getElementById('editUserId').value;
            const name = document.getElementById('editUserName').value.trim();
            const email = document.getElementById('editUserEmail').value.trim();
            const role = document.getElementById('editUserRole').value;
            const status = document.getElementById('editUserStatus').value;

            let overrides = {};
            try {
                overrides = JSON.parse(localStorage.getItem('indofx_admin_user_overrides')) || {};
            } catch (e) {}

            const words = name.split(/\s+/);
            let inits = '';
            words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());

            overrides[id] = {
                name: name,
                email: email,
                role: role,
                status: status,
                inits: inits || 'US',
                verified: (role === 'Penjual')
            };

            localStorage.setItem('indofx_admin_user_overrides', JSON.stringify(overrides));
            modalEditUserInstance.hide();
            loadUsersData();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalEditUserInstance = new bootstrap.Modal(document.getElementById('modalEditUser'));

            // Delegasi tombol Edit pengguna
            document.addEventListener('click', function(event) {
                const editBtn = event.target.closest('[data-edit-user-id]');
                if (!editBtn) return;
                openEditUserModal(editBtn.getAttribute('data-edit-user-id'));
            });

            // Muat data gabungan
            loadUsersData();

            document.getElementById('adminSearchInput').addEventListener('input', function() {
                currentPage = 1;
                renderUsersTable();
            });

            document.getElementById('filterRoleSelect').addEventListener('change', function() {
                currentPage = 1;
                renderUsersTable();
            });

            document.getElementById('filterStatusSelect').addEventListener('change', function() {
                currentPage = 1;
                renderUsersTable();
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