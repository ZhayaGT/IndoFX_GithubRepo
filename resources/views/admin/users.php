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
    <meta name="admin-csrf" content="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
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

    <!-- MODAL EDIT DATA PENGGUNA (READ-ONLY NAMA & EMAIL SESUAI FIGMA) -->
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
                            <label class="form-label-admin d-block">Nama Pengguna / Studio</label>
                            <div id="editUserNameDisplay" class="user-readonly-name">-</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-admin d-block">Alamat Email</label>
                            <div id="editUserEmailDisplay" class="user-readonly-email">-</div>
                        </div>
                        <div class="row g-2 mb-1">
                            <div class="col-6">
                                <label for="editUserRole" class="form-label-admin">Peran Pengguna</label>
                                <select id="editUserRole" class="form-select admin-select">
                                    <option value="Penjual">Penjual</option>
                                    <option value="Pembeli">Pembeli</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="editUserStatus" class="form-label-admin">Status Akun</label>
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
    <script src="/assets/js/admin-api.js"></script>

    <!-- ENGINE PENGGABUNGAN DATA PENJUAL + AKUN PGADMIN -->
    <script>
        // 1. DATA PENJUAL & STUDIO SEBELUMNYA LENGKAP
        let users = [];
        let modalEditUserInstance = null;

        // Paginasi: 5 akun per tabel
        const itemsPerPage = 5;
        let currentPage = 1;

        // 3. FUNGSI PENGGABUNGAN DATA (MERGE PENJUAL + USER PGADMIN)
        async function loadUsersData() {
            try {
                const userList = (await AdminApi.data()).users;
                users = userList;
                const total = userList.length;
                const sellers = userList.filter(u => u.role === 'Penjual').length;
                const buyers = userList.filter(u => u.role === 'Pembeli').length;
                document.getElementById('statTotalUsers').textContent = total;
                document.getElementById('statUsersSub').textContent = `${sellers} Penjual | ${buyers} Pembeli`;
                document.getElementById('statSellers').textContent = sellers;
                document.getElementById('statBuyers').textContent = buyers;
                document.getElementById('statBlocked').textContent = userList.filter(u => u.status === 'Diblokir').length;
                renderUsersTable();
            } catch (error) { AdminApi.showError(error); }
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
                const isAdminAccount = String(u.role || '').trim().toLowerCase() === 'admin';
                const editButton = isAdminAccount ?
                    `<button type="button" class="btn-edit-user is-disabled" disabled aria-disabled="true" title="Akun administrator tidak dapat diedit">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>` :
                    `<button type="button" class="btn-edit-user" data-edit-user-id="${esc(u.id)}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>`;

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
                            ${editButton}
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

        // 5. BUKA MODAL EDIT USER (NAMA & EMAIL READ-ONLY)
        function openEditUserModal(userId) {
            const user = users.find(u => String(u.id) === String(userId));
            if (!user || user.role === 'Admin') return;

            // Akun administrator tidak boleh diubah dari halaman ini.
            if (String(user.role || '').trim().toLowerCase() === 'admin') {
                return;
            }

            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserNameDisplay').textContent = user.name;
            document.getElementById('editUserEmailDisplay').textContent = user.email;
            document.getElementById('editUserRole').value = (user.role === 'Penjual') ? 'Penjual' : 'Pembeli';
            document.getElementById('editUserStatus').value = user.status;

            modalEditUserInstance.show();
        }

        // 6. SIMPAN EDIT USER (HANYA PERAN & STATUS AKUN)
        async function saveUserEdit(e) {
            try {
                e.preventDefault();
                await AdminApi.save('user.update', {
                    id: document.getElementById('editUserId').value,
                    role: document.getElementById('editUserRole').value === 'Penjual' ? 'seller' : 'buyer',
                    status: document.getElementById('editUserStatus').value === 'Aktif' ? 'active' : 'blocked'
                });
                modalEditUserInstance.hide();
                await loadUsersData();
            } catch (error) { AdminApi.showError(error); }
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