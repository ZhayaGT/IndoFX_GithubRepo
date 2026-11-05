<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
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

            <!-- Logo IndoFX Kiri -->
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
                <span class="avatar-circle" id="navDashAvatar">AS</span>
                <span class="fw-bold fs-7 tracking-wide" id="navDashStoreName">ARGA STUDIO</span>
            </a>

        </div>
    </nav>

    <!-- 2. Kontainer Utama: Sidebar Kiri & Konten Kanan -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI (PUTIH) -->
        <aside class="dashboard-sidebar">
            <ul class="nav flex-column sidebar-nav-list">
                <!-- 1. Dashboard Utama -> main.php -->
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link active">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </li>
                <!-- 2. Semua Aset Saya -> dashboard.php -->
                <li class="nav-item">
                    <a href="/dashboard/assets" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>Semua Aset Saya</span>
                    </a>
                </li>
                <!-- 3. Riwayat Transaksi -> transactions.php -->
                <li class="nav-item">
                    <a href="/dashboard/transactions" class="sidebar-link">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <!-- 4. Profil Penjual -> ubahprofile.php -->
                <li class="nav-item">
                    <a href="/dashboard/ubahprofile" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN KANAN (KREM HANGAT) -->
        <main class="dashboard-main-content">

            <!-- Header Judul Dinamis -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="dash-page-title mb-0">Dashboard Penjual</h2>
                <span class="fw-bold fs-6 text-dark" id="dashContentStoreName">Arga Studio</span>
            </div>

            <!-- 4 Summary Stat Cards Dinamis -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Aset -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Aset</span>
                        <div class="stat-card-value mb-1" id="statTotalAsset">0</div>
                        <span class="stat-card-sub text-muted" id="statAssetSub">0 Terpublikasi</span>
                    </div>
                </div>

                <!-- Card 2: Aset Terjual -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Aset Terjual</span>
                        <div class="stat-card-value mb-1" id="statSold">0x</div>
                        <span class="stat-card-sub text-muted">Transaksi Berhasil</span>
                    </div>
                </div>

                <!-- Card 3: Total Unduhan -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Unduhan</span>
                        <div class="stat-card-value mb-1" id="statDownloads">0</div>
                        <span class="stat-card-sub text-muted">Termasuk Aset Gratis</span>
                    </div>
                </div>

                <!-- Card 4: Total Pendapatan -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Pendapatan</span>
                        <div class="stat-card-value mb-1" id="statRevenue">Rp0</div>
                        <span class="stat-card-sub text-muted">Developer &amp; Penjual</span>
                    </div>
                </div>
            </div>

            <!-- Bagian 1: Tabel Aset Baru Dinamis -->
            <section class="mb-5">
                <h5 class="fw-bold text-dark mb-3 fs-6">Aset Baru</h5>

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
                            <tbody id="tableAssetsBody">
                                <!-- Diisi otomatis dari data pendaftaran di LocalStorage -->
                            </tbody>
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
                            <tbody id="tableTransactionsBody">
                                <!-- Otomatis bersih untuk penjual baru -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- 3. Footer Putih Konsisten -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="row g-4 justify-content-between mb-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <h5 class="footer-brand-title mb-2">IndoFX</h5>
                    <p class="footer-brand-desc m-0">
                        Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia[cite: 24].
                    </p>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Katalog</h6>
                            <a href="/#katalog" class="footer-link">Shader</a>
                            <a href="/#katalog" class="footer-link">VFX Partikel</a>
                            <a href="/#katalog" class="footer-link">Model 3D</a>
                        </div>
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Dukungan</h6>
                            <a href="#" class="footer-link">Dokumentasi</a>
                            <a href="#" class="footer-link">Hubungi Kami</a>
                            <a href="#" class="footer-link">Syarat &amp; Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang[cite: 24].</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia[cite: 24]</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Hidrasi Data Dinamis Sesuai Akun Penjual -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userKey = '<?= $userKey ?>';

            // 1. Ambil Profil Penjual Terdaftar (Berdasarkan userKey)
            const profileRaw = localStorage.getItem('indofx_seller_profile_' + userKey);
            let storeName = 'Arga Studio';

            if (profileRaw) {
                try {
                    const profile = JSON.parse(profileRaw);
                    if (profile.store_name) {
                        storeName = profile.store_name;
                    }
                } catch (e) {}
            }

            // Tampilkan Nama Toko di Judul Konten & Header Navbar
            const titleEl = document.getElementById('dashContentStoreName');
            const navStoreEl = document.getElementById('navDashStoreName');
            if (titleEl) titleEl.innerText = storeName;
            if (navStoreEl) navStoreEl.innerText = storeName.toUpperCase();

            // Hitung Inisial Avatar (misal: "Arga Studio" -> "AS")
            const words = storeName.trim().split(/\s+/);
            let inits = '';
            words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
            const avatarEl = document.getElementById('navDashAvatar');
            if (avatarEl) avatarEl.innerText = inits || 'AS';

            // 2. Ambil Aset Penjual yang Diunggah Saat Pendaftaran
            const assetsRaw = localStorage.getItem('indofx_seller_assets_' + userKey);
            let sellerAssets = [];

            if (assetsRaw) {
                try {
                    sellerAssets = JSON.parse(assetsRaw);
                } catch (e) {
                    sellerAssets = [];
                }
            }

            const assetsTbody = document.getElementById('tableAssetsBody');

            if (sellerAssets.length > 0) {
                // Tampilkan Aset Sampel yang Baru Didaftarkan (Status: Pending)
                assetsTbody.innerHTML = sellerAssets.map(item => `
                    <tr>
                        <td class="fw-bold text-dark item-asset-title">${item.title}</td>
                        <td class="text-muted small">${item.category || 'VFX Partikel'}</td>
                        <td class="fw-bold text-dark">${item.price || 'Rp150.000'}</td>
                        <td>
                            <span class="${item.status === 'Disetujui' ? 'badge-status-approved' : 'badge-status-pending'}">
                                ${item.status || 'Pending'}
                            </span>
                        </td>
                        <td class="text-muted small">${item.created_at || new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</td>
                        <td class="text-end pe-4">
                            <a href="/dashboard/dashboard" class="action-link me-2">Edit</a>
                            <span class="text-muted mx-1">|</span>
                            <a href="/asset/detail" class="action-link">Lihat</a>
                        </td>
                    </tr>
                `).join('');

                // Update Statistik Aset
                document.getElementById('statTotalAsset').innerText = sellerAssets.length;
                const approvedCount = sellerAssets.filter(a => a.status === 'Disetujui').length;
                document.getElementById('statAssetSub').innerText = approvedCount + ' Terpublikasi';
            } else {
                assetsTbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted small">
                            <i class="bi bi-box-seam fs-3 d-block mb-1 text-secondary"></i>
                            Belum ada aset yang diunggah. Klik <a href="/dashboard/dashboard" class="fw-bold text-primary">Semua Aset Saya</a> untuk mulai mengunggah.
                        </td>
                    </tr>
                `;
                document.getElementById('statTotalAsset').innerText = '0';
                document.getElementById('statAssetSub').innerText = '0 Terpublikasi';
            }

            // 3. Tabel Transaksi Penjualan Dikosongkan Bersih Bagi Penjual Baru
            const transTbody = document.getElementById('tableTransactionsBody');
            transTbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted small">
                        <i class="bi bi-receipt text-secondary fs-3 d-block mb-1"></i>
                        Belum ada transaksi penjualan terbaru.
                    </td>
                </tr>
            `;

            // Reset Angka Penjualan & Pendapatan
            document.getElementById('statSold').innerText = '0x';
            document.getElementById('statDownloads').innerText = '0';
            document.getElementById('statRevenue').innerText = 'Rp0';
        });
    </script>
</body>

</html>