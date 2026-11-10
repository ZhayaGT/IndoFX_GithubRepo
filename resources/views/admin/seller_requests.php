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
    <title>Verifikasi Penjual - IndoFX</title>

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

        <!-- Sidebar Kiri Sesuai Figma image_1ed3ff -->
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
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link active">
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

            <!-- 4 Kartu Ringkasan Murni Dinamis Sesuai Data Akun Riil -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Pengguna Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Pengguna</div>
                        <div class="admin-stat-val" id="statTotalUsers">0</div>
                        <div class="admin-stat-sub text-muted" id="statUsersSub">0 Penjual | 0 Pembeli</div>
                    </div>
                </div>

                <!-- Card 2: Penjual / Studio Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Penjual / Studio</div>
                        <div class="admin-stat-val" id="statSellers">0</div>
                        <div class="admin-stat-sub text-muted">Kreator Terverifikasi</div>
                    </div>
                </div>

                <!-- Card 3: Pembeli Aktif Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pembeli Aktif</div>
                        <div class="admin-stat-val" id="statBuyers">0</div>
                        <div class="admin-stat-sub text-muted">User Publik</div>
                    </div>
                </div>

                <!-- Card 4: Pengguna Diblokir Riil -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Pengguna Diblokir</div>
                        <div class="admin-stat-val val-red" id="statBlocked">0</div>
                        <div class="admin-stat-sub text-muted">Pelanggaran Syarat</div>
                    </div>
                </div>
            </div>

            <!-- Bar Filter Sesuai Figma image_1ed3ff -->
            <div class="row g-2 align-items-center admin-filter-bar mb-3">
                <div class="col-12 col-md-8">
                    <div class="admin-search-wrap">
                        <i class="bi bi-search admin-search-icon"></i>
                        <input type="text" id="sellerSearchInput" class="form-control admin-search-input" placeholder="Cari nama, email, atau studio...">
                    </div>
                </div>
                <div class="col-12 col-md-4 text-end">
                    <!-- Dropdown Filter Pilihan Sesuai Desain Samping Figma -->
                    <select class="form-select admin-select d-inline-block w-auto" id="filterStatusSelect" style="min-width: 170px;">
                        <option value="all" selected>Semua Status</option>
                        <option value="Diterima">Diterima</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Ditolak">Ditolak</option>
                        <option value="Aktif">Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Pengajuan Penjual Sesuai Figma image_1ed3ff -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 28%;">Pengguna</th>
                                <th scope="col" style="width: 26%;">Email</th>
                                <th scope="col" style="width: 16%;">Status Akun</th>
                                <th scope="col" style="width: 18%;">Tanggal Bergabung</th>
                                <th scope="col" class="text-end pe-4" style="width: 12%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="sellerTableBody">
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. MODAL DETAIL PENGAJUAN PENJUAL (PRESISI FIGMA image_1e6ebb) -->
    <div class="modal fade" id="modalViewSeller" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 620px;">
            <div class="modal-content seller-modal-card">
                <!-- Header Modal -->
                <div class="seller-modal-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="seller-header-icon-box">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h5 class="seller-modal-title" id="sellerModalHeaderTitle">Detail Pengajuan Penjual</h5>
                    </div>
                    <button type="button" class="btn-seller-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Konten Body Sesuai Desain image_1e6ebb -->
                <div class="seller-modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="seller-section-title mb-0">Data Pendaftaran</h4>
                        <span id="detailStatusBadgeModal"></span>
                    </div>

                    <!-- Baris 1: Nama Lengkap & Alamat Email -->
                    <div class="seller-data-row">
                        <div class="row">
                            <div class="col-6">
                                <span class="seller-label">Nama Lengkap</span>
                                <p class="seller-val fw-bold" id="detailFullName">Rian Prasetya</p>
                            </div>
                            <div class="col-6">
                                <span class="seller-label">Alamat Email</span>
                                <p class="seller-val" id="detailEmail">rian.prasetya@gmail.com</p>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Alamat Domisili -->
                    <div class="seller-data-row">
                        <span class="seller-label">Alamat Domisili</span>
                        <p class="seller-val" id="detailAddress">
                            Jl. Melati No. 14, RT 03/RW 05, Kel. Lowokwaru, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141
                        </p>
                    </div>

                    <!-- Baris 3: Alasan -->
                    <div class="seller-data-row">
                        <span class="seller-label">Alasan</span>
                        <p class="seller-val" id="detailReason">
                            Saya sudah lama membuat shader dan tekstur untuk kebutuhan pribadi. Saya ingin mulai menjualnya di IndoFX agar bisa menjangkau lebih banyak pengembang Unity lokal.
                        </p>
                    </div>

                    <!-- Baris 4: Lampiran File -->
                    <div class="seller-data-row">
                        <span class="seller-label">Lampiran</span>
                        <div class="d-flex align-items-start gap-2 mt-1">
                            <i class="bi bi-file-earmark text-danger fs-5 lh-1"></i>
                            <div>
                                <a href="javascript:void(0)" class="seller-attachment-link" id="detailAttachmentName">
                                    rain_shader_pack.unitypackage
                                </a>
                                <span class="seller-attachment-size" id="detailAttachmentSize">18,4 MB</span>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 5: Deskripsi Aset -->
                    <div class="seller-data-row">
                        <span class="seller-label">Deskripsi Aset</span>
                        <p class="seller-val" id="detailAssetDesc">
                            Kumpulan shader stylized untuk pipeline URP beserta material contoh dan demo scene.
                        </p>
                    </div>

                    <!-- Baris 6: URL Portofolio & Tanggal Pengajuan -->
                    <div class="seller-data-row">
                        <div class="row">
                            <div class="col-6">
                                <span class="seller-label">URL Portofolio</span>
                                <p class="seller-val" id="detailPortfolio">artstation.com/riandevs</p>
                            </div>
                            <div class="col-6">
                                <span class="seller-label">Tanggal Pengajuan</span>
                                <p class="seller-val" id="detailSubmissionDate">08 Nov 2026</p>
                            </div>
                        </div>
                    </div>

                    <!-- Wadah Khusus Alasan Ditolak (Tampil Saat Status Ditolak) -->
                    <div class="seller-rejected-box" id="boxRejectedReason" style="display: none;">
                        <div class="seller-rejected-title">
                            <i class="bi bi-x-circle-fill"></i> Alasan Penolakan oleh Administrator:
                        </div>
                        <p class="seller-rejected-text" id="displayRejectedReasonText">-</p>
                    </div>

                    <!-- Baris 7: Input Catatan Admin (Tampil Hanya Saat Status Menunggu) -->
                    <div class="mt-3" id="boxAdminNoteInput">
                        <label class="seller-label mb-2" for="sellerAdminNote">Catatan Admin</label>
                        <textarea id="sellerAdminNote" class="seller-textarea" placeholder="Wajib diisi jika pengajuan ditolak..."></textarea>

                        <div class="seller-reject-alert">
                            <i class="bi bi-info-circle"></i>
                            <span>Catatan alasan wajib diisi sebelum menolak pengajuan.</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Tombol Keputusan (Dinamis: Setujui/Tolak untuk Menunggu, Tutup untuk Ditolak) -->
                <div class="seller-modal-footer" id="modalSellerFooter">
                    <!-- Diisi dinamis oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Footer Sesuai Desain Figma -->
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

    <!-- ENGINE PENGAJUAN PENJUAL DINAMIS -->
    <script>
        // Data Awal Cadangan (Figma accurate)
        const fallbackSellerRequests = [{
                id: 'REQ-1',
                name: 'CyberNet Studio',
                full_name: 'David Surya (CyberNet)',
                email: 'contact@cybernet.id',
                address: 'Jl. Sudirman No. 88, Menteng, Jakarta Pusat 10310',
                reason: 'Kami studio 3D asset indie yang ingin memasarkan pack environment futuristik Unity ke komunitas game developer lokal.',
                attachment: 'cyber_holo_pack_v1.unitypackage',
                attachment_size: '32,1 MB',
                asset_desc: 'Asset UI holografis interaktif siap pakai untuk pipeline HDRP dan Built-in.',
                portfolio: 'artstation.com/cybernetstudio',
                submission_date: '10 Jan 2026',
                status: 'Aktif',
                join_date: '10 Jan 2026',
                reject_reason: '',
                inits: 'CN',
                bg: '#FCE8DC',
                color: '#ED6B1D'
            },
            {
                id: 'REQ-2',
                name: 'Nusantara Devs',
                full_name: 'Budi Santoso',
                email: 'contact@cybernet.id',
                address: 'Jl. Pemuda No. 12, Surabaya, Jawa Timur 60271',
                reason: 'Ingin berkontribusi menyediakan rumput prosedural dan shader vegetasi bergaya stylized untuk pengembang game Unity lokal.',
                attachment: 'stylized_grass_pack.unitypackage',
                attachment_size: '14,0 MB',
                asset_desc: 'Sistem rumput interaktif URP dengan LOD dan wind gust shader.',
                portfolio: 'artstation.com/nusantaradevs',
                submission_date: '04 Feb 2026',
                status: 'Menunggu',
                join_date: '04 Feb 2026',
                reject_reason: '',
                inits: 'ND',
                bg: '#FEEFD8',
                color: '#B76E00'
            },
            {
                id: 'REQ-3',
                name: 'Rian_Devs Studio',
                full_name: 'Rian Prasetya',
                email: 'rian.prasetya@gmail.com',
                address: 'Jl. Melati No. 14, RT 03/RW 05, Kel. Lowokwaru, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141',
                reason: 'Saya sudah lama membuat shader dan tekstur untuk kebutuhan pribadi. Saya ingin mulai menjualnya di IndoFX agar bisa menjangkau lebih banyak pengembang Unity lokal.',
                attachment: 'rain_shader_pack.unitypackage',
                attachment_size: '18,4 MB',
                asset_desc: 'Kumpulan shader stylized untuk pipeline URP beserta material contoh dan demo scene.',
                portfolio: 'artstation.com/riandevs',
                submission_date: '08 Nov 2026',
                status: 'Diterima',
                join_date: '18 Mar 2026',
                reject_reason: '',
                inits: 'RD',
                bg: '#FCE4D6',
                color: '#C55A11'
            },
            {
                id: 'REQ-4',
                name: 'Super Administrator',
                full_name: 'IndoFX Root Admin',
                email: 'contact@cybernet.id',
                address: 'Graha IndoFX Lt. 3, Malang, Jawa Timur',
                reason: 'Akun pengujian verifikasi sistem marketplace internal.',
                attachment: 'system_core_sample.unitypackage',
                attachment_size: '5,2 MB',
                asset_desc: 'Asset template demonstrasi sistem.',
                portfolio: 'indofx.id/admin',
                submission_date: '01 Jan 2026',
                status: 'Diterima',
                join_date: '01 Jan 2026',
                reject_reason: '',
                inits: 'AD',
                bg: '#FADBD8',
                color: '#C0392B'
            },
            {
                id: 'REQ-5',
                name: 'Fake Asset Spammer',
                full_name: 'Unknown User',
                email: 'contact@cybernet.id',
                address: 'Alamat anonim',
                reason: 'Spamming konten tidak berlisensi.',
                attachment: 'corrupted_archive.zip',
                attachment_size: '1,1 MB',
                asset_desc: 'Berkas tanpa dokumentasi lengkap dan melanggar hak cipta.',
                portfolio: 'none',
                submission_date: '12 Mei 2026',
                status: 'Ditolak',
                join_date: '12 Mei 2026',
                reject_reason: 'Lampiran berkas rusak dan melanggar ketentuan lisensi hak cipta aset IndoFX.',
                inits: 'FX',
                bg: '#F5CBA7',
                color: '#B9770E'
            }
        ];

        let sellerRequests = [];
        let modalViewInstance = null;
        let selectedSellerRequestId = null;

        // 1. SINKRONISASI DATA DINAMIS DARI LOCALSTORAGE
        function loadSellerRequestsData() {
            try {
                const stored = localStorage.getItem('indofx_seller_requests');
                if (stored) {
                    sellerRequests = JSON.parse(stored);
                } else {
                    sellerRequests = [...fallbackSellerRequests];
                    localStorage.setItem('indofx_seller_requests', JSON.stringify(sellerRequests));
                }
            } catch (e) {
                sellerRequests = [...fallbackSellerRequests];
            }

            // Hitung Badge 'Menunggu' untuk Sidebar Navigasi
            const pendingRequests = sellerRequests.filter(r => r.status === 'Menunggu');
            document.getElementById('sidebarSellerBadge').innerText = pendingRequests.length;

            // Hitung badge antrean moderasi aset dari storage moderasi
            try {
                const queue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                const pQueue = queue.filter(q => q.status === 'Pending' || q.status === 'Pending Review').length;
                document.getElementById('sidebarModerationBadge').innerText = pQueue;
            } catch (e) {}

            // Sinkronkan 4 Kartu Metrik Pengguna Murni Dinamis
            syncUsersMetricCards();

            // Render baris tabel
            renderSellerTable();
        }

        // 2. SINKRONISASI KARTU METRIK MURNI DINAMIS (TANPA FALLBACK STATIS 1.284)
        function syncUsersMetricCards() {
            let userList = [];
            try {
                userList = JSON.parse(localStorage.getItem('indofx_admin_users')) || [];
            } catch (e) {
                userList = [];
            }

            // Jika belum ada di localStorage, ambil dari default basis
            if (userList.length === 0) {
                userList = [{
                        id: 1,
                        name: 'CyberNet Studio',
                        role: 'Penjual',
                        status: 'Aktif'
                    },
                    {
                        id: 2,
                        name: 'Nusantara Devs',
                        role: 'Penjual',
                        status: 'Aktif'
                    },
                    {
                        id: 3,
                        name: 'Arga Studio',
                        role: 'Penjual',
                        status: 'Aktif'
                    },
                    {
                        id: 4,
                        name: 'Rian_Devs Studio',
                        role: 'Pembeli',
                        status: 'Aktif'
                    },
                    {
                        id: 5,
                        name: 'Super Administrator',
                        role: 'Admin',
                        status: 'Aktif'
                    }
                ];
                localStorage.setItem('indofx_admin_users', JSON.stringify(userList));
            }

            // Perhitungan murni matematis dari data riil
            const total = userList.length;
            const sellers = userList.filter(u => u.role === 'Penjual').length;
            const buyers = userList.filter(u => u.role === 'Pembeli').length;
            const blocked = userList.filter(u => u.status === 'Diblokir').length;

            document.getElementById('statTotalUsers').innerText = total.toLocaleString('id-ID');
            document.getElementById('statUsersSub').innerText = `${sellers} Penjual | ${buyers} Pembeli`;
            document.getElementById('statSellers').innerText = sellers;
            document.getElementById('statBuyers').innerText = buyers;
            document.getElementById('statBlocked').innerText = blocked;
        }

        // 3. RENDER TABEL DENGAN LOGIKA TOMBOL AKSI CERDAS
        function renderSellerTable() {
            const tbody = document.getElementById('sellerTableBody');
            const keyword = document.getElementById('sellerSearchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatusSelect').value;

            const filtered = sellerRequests.filter(item => {
                const matchKeyword = !keyword ||
                    (item.name && item.name.toLowerCase().includes(keyword)) ||
                    (item.full_name && item.full_name.toLowerCase().includes(keyword)) ||
                    (item.email && item.email.toLowerCase().includes(keyword));

                const matchStatus = statusFilter === 'all' || item.status === statusFilter;
                return matchKeyword && matchStatus;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">
                            Tidak ada pengajuan penjual yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
                let statusBadge = '';
                let actionColumnContent = '';

                if (item.status === 'Aktif' || item.status === 'Diterima') {
                    statusBadge = `<span class="badge-status-active">${item.status}</span>`;
                    // KETENTUAN 1: JIKA DITERIMA / AKTIF -> GANTI DENGAN CENTANG SAJA
                    actionColumnContent = `
                        <span class="btn-action-check" title="Pengajuan Telah Diterima">
                            <i class="bi bi-check-lg"></i>
                        </span>
                    `;
                } else if (item.status === 'Menunggu') {
                    statusBadge = `<span class="badge-status-waiting">Menunggu</span>`;
                    // KETENTUAN 2: JIKA MENUNGGU -> TOMBOL LIHAT UNTUK MEMVERIFIKASI
                    actionColumnContent = `
                        <button type="button" class="btn-action-view" onclick="openViewSellerModal('${item.id}')">
                            <i class="bi bi-eye"></i> Lihat
                        </button>
                    `;
                } else {
                    statusBadge = `<span class="badge-status-blocked">Ditolak</span>`;
                    // KETENTUAN 3: JIKA DITOLAK -> TOMBOL LIHAT BERISI DETAIL & ALASAN DITOLAK ADMIN
                    actionColumnContent = `
                        <button type="button" class="btn-action-view" onclick="openViewSellerModal('${item.id}')">
                            <i class="bi bi-eye"></i> Lihat
                        </button>
                    `;
                }

                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="user-avatar-tag" style="background-color: ${item.bg || '#FEEFD8'}; color: ${item.color || '#B76E00'};">
                                    ${item.inits || 'US'}
                                </span>
                                <span class="fw-bold text-dark">${item.name || item.full_name}</span>
                            </div>
                        </td>
                        <td class="text-muted">${item.email}</td>
                        <td>${statusBadge}</td>
                        <td class="text-muted">${item.join_date || item.submission_date}</td>
                        <td class="text-end pe-4">
                            ${actionColumnContent}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // 4. BUKA MODAL DETAIL PENGAJUAN (SESUAI STATUS: MENUNGGU vs DITOLAK)
        function openViewSellerModal(id) {
            const item = sellerRequests.find(s => String(s.id) === String(id));
            if (!item) return;

            selectedSellerRequestId = id;

            // Isi Data Utama
            document.getElementById('detailFullName').innerText = item.full_name || item.name;
            document.getElementById('detailEmail').innerText = item.email;
            document.getElementById('detailAddress').innerText = item.address || 'Alamat tidak dicantumkan';
            document.getElementById('detailReason').innerText = item.reason || 'Tidak ada alasan yang dicantumkan';
            document.getElementById('detailAttachmentName').innerText = item.attachment || 'portofolio_file.unitypackage';
            document.getElementById('detailAttachmentSize').innerText = item.attachment_size || '12,0 MB';
            document.getElementById('detailAssetDesc').innerText = item.asset_desc || 'Kumpulan aset buatan pengembang lokal.';
            document.getElementById('detailPortfolio').innerText = item.portfolio || '-';
            document.getElementById('detailSubmissionDate').innerText = item.submission_date || item.join_date;

            // Status Badge di Modal
            const badgeContainer = document.getElementById('detailStatusBadgeModal');
            if (item.status === 'Ditolak') {
                badgeContainer.innerHTML = `<span class="badge-status-blocked">Ditolak</span>`;
            } else if (item.status === 'Menunggu') {
                badgeContainer.innerHTML = `<span class="badge-status-waiting">Menunggu Verifikasi</span>`;
            } else {
                badgeContainer.innerHTML = `<span class="badge-status-active">${item.status}</span>`;
            }

            // KONDISI PENAMPILAN ALASAN DITOLAK vs FORM INPUT ADMIN
            const boxRejected = document.getElementById('boxRejectedReason');
            const boxNoteInput = document.getElementById('boxAdminNoteInput');
            const footerEl = document.getElementById('modalSellerFooter');

            if (item.status === 'Ditolak') {
                // Tampilkan Kotak Alasan Penolakan
                boxRejected.style.display = 'block';
                document.getElementById('displayRejectedReasonText').innerText = item.reject_reason || 'Alasan penolakan tidak dicantumkan oleh administrator.';

                // Sembunyikan Input Catatan
                boxNoteInput.style.display = 'none';

                // Footer Hanya Tombol Tutup
                footerEl.innerHTML = `
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
                `;
            } else {
                // Sembunyikan Kotak Alasan Penolakan
                boxRejected.style.display = 'none';

                // Tampilkan Input Catatan Admin untuk Menolak
                boxNoteInput.style.display = 'block';
                const noteEl = document.getElementById('sellerAdminNote');
                noteEl.value = '';
                noteEl.classList.remove('is-invalid-note');

                // Footer Tombol Keputusan (Tolak & Setujui)
                footerEl.innerHTML = `
                    <button type="button" class="btn-seller-reject" onclick="handleRejectSeller()">
                        <i class="bi bi-x-lg"></i> Tolak
                    </button>
                    <button type="button" class="btn-seller-approve" onclick="handleApproveSeller()">
                        <i class="bi bi-check2"></i> Setujui
                    </button>
                `;
            }

            modalViewInstance.show();
        }

        // 5. AKSI SETUJUI PENGAJUAN PENJUAL
        function handleApproveSeller() {
            if (!selectedSellerRequestId) return;

            let approvedUser = null;

            // A. Perbarui Status di Daftar Pengajuan Menjadi 'Diterima'
            sellerRequests = sellerRequests.map(s => {
                if (String(s.id) === String(selectedSellerRequestId)) {
                    s.status = 'Diterima';
                    s.reject_reason = '';
                    approvedUser = s;
                }
                return s;
            });
            localStorage.setItem('indofx_seller_requests', JSON.stringify(sellerRequests));

            // B. Sinkronkan ke 'indofx_admin_users' (Ubah Peran menjadi 'Penjual')
            if (approvedUser) {
                try {
                    let userList = JSON.parse(localStorage.getItem('indofx_admin_users')) || [];
                    const userIdx = userList.findIndex(u => u.email === approvedUser.email);

                    if (userIdx !== -1) {
                        userList[userIdx].role = 'Penjual';
                        userList[userIdx].status = 'Aktif';
                        userList[userIdx].verified = true;
                    } else {
                        userList.push({
                            id: Date.now(),
                            name: approvedUser.name || approvedUser.full_name,
                            verified: true,
                            email: approvedUser.email,
                            role: 'Penjual',
                            status: 'Aktif',
                            join_date: approvedUser.join_date || 'Hari ini',
                            stats: '0 Aset (Baru)'
                        });
                    }
                    localStorage.setItem('indofx_admin_users', JSON.stringify(userList));
                } catch (e) {}
            }

            modalViewInstance.hide();
            loadSellerRequestsData(); // Muat ulang tabel, tombol centang otomatis muncul!
        }

        // 6. AKSI TOLAK PENGAJUAN (WAJIB ISI CATATAN ALASAN SECARA DINAMIS)
        function handleRejectSeller() {
            if (!selectedSellerRequestId) return;

            const noteEl = document.getElementById('sellerAdminNote');
            const noteVal = noteEl.value.trim();

            if (!noteVal) {
                noteEl.classList.add('is-invalid-note');
                noteEl.focus();
                return;
            }

            sellerRequests = sellerRequests.map(s => {
                if (String(s.id) === String(selectedSellerRequestId)) {
                    s.status = 'Ditolak';
                    s.reject_reason = noteVal; // Simpan alasan penolakan secara dinamis
                }
                return s;
            });

            localStorage.setItem('indofx_seller_requests', JSON.stringify(sellerRequests));
            modalViewInstance.hide();
            loadSellerRequestsData();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalViewInstance = new bootstrap.Modal(document.getElementById('modalViewSeller'));

            // Inisialisasi data dinamis
            loadSellerRequestsData();

            document.getElementById('sellerSearchInput').addEventListener('input', renderSellerTable);
            document.getElementById('filterStatusSelect').addEventListener('change', renderSellerTable);

            // Hilangkan outline merah saat admin mulai mengetik
            document.getElementById('sellerAdminNote').addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid-note');
                }
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