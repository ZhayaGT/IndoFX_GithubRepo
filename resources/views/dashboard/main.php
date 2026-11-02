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

    <!-- Stylesheets -->
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

            <!-- Profil Kreator Kanan -->
            <div class="d-flex align-items-center gap-2 text-white">
                <span class="avatar-circle">AS</span>
                <span class="fw-bold fs-7 tracking-wide">ARGA STUDIO</span>
            </div>

        </div>
    </nav>

    <!-- 2. Kontainer Utama: Sidebar Kiri & Konten Kanan -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI (PUTIH) -->
        <aside class="dashboard-sidebar">
            <ul class="nav flex-column sidebar-nav-list">
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link active">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/assets" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>Semua Aset Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/transactions" class="sidebar-link">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="/dashboard/add-asset" class="sidebar-link">
                        <i class="bi bi-pencil-square"></i>
                        <span>Form Edit Aset</span>
                    </a> -->
                </li>
                <li class="nav-item">
                    <a href="/dashboard/profile" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN KANAN (KREM HANGAT) -->
        <main class="dashboard-main-content">

            <!-- Header Judul -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="dash-page-title mb-0">Dashboard Penjual</h2>
                <span class="fw-bold fs-6 text-dark">Arga Studio</span>
            </div>

            <!-- 4 Summary Stat Cards -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Aset -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Aset</span>
                        <div class="stat-card-value mb-1">4</div>
                        <span class="stat-card-sub text-muted">3 Terpublikasi</span>
                    </div>
                </div>

                <!-- Card 2: Aset Terjual -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Aset Terjual</span>
                        <div class="stat-card-value mb-1">38x</div>
                        <span class="stat-card-sub text-muted">Transaksi Berhasil</span>
                    </div>
                </div>

                <!-- Card 3: Total Unduhan -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Unduhan</span>
                        <div class="stat-card-value mb-1">142</div>
                        <span class="stat-card-sub text-muted">Termasuk Aset Gratis</span>
                    </div>
                </div>

                <!-- Card 4: Total Pendapatan -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stat-card p-3">
                        <span class="stat-card-title d-block mb-2">Total Pendapatan</span>
                        <div class="stat-card-value mb-1">Rp550.000</div>
                        <span class="stat-card-sub text-muted">Developer &amp; Penjual</span>
                    </div>
                </div>
            </div>

            <!-- Bagian 1: Tabel Aset Baru -->
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
                            <tbody>
                                <!-- Baris 1 -->
                                <tr>
                                    <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                    <td class="text-muted small">VFX Partikel</td>
                                    <td class="fw-bold text-dark">Rp150.000</td>
                                    <td>
                                        <span class="badge-status-approved">Disetujui</span>
                                    </td>
                                    <td class="text-muted small">15 Jan 2026</td>
                                    <td class="text-end pe-4">
                                        <a href="/dashboard/add-asset" class="action-link">Edit</a>
                                        <span class="text-muted mx-1">|</span>
                                        <a href="/asset/detail" class="action-link">Lihat</a>
                                    </td>
                                </tr>

                                <!-- Baris 2 -->
                                <tr>
                                    <td class="fw-bold text-dark item-asset-title">Sunda-style Bamboo Shader Helper</td>
                                    <td class="text-muted small">Shader</td>
                                    <td class="fw-bold text-dark">Rp120.000</td>
                                    <td>
                                        <span class="badge-status-approved">Disetujui</span>
                                    </td>
                                    <td class="text-muted small">10 Jan 2026</td>
                                    <td class="text-end pe-4">
                                        <a href="/dashboard/add-asset" class="action-link">Edit</a>
                                        <span class="text-muted mx-1">|</span>
                                        <a href="/asset/detail" class="action-link">Lihat</a>
                                    </td>
                                </tr>

                                <!-- Baris 3 -->
                                <tr>
                                    <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                    <td class="text-muted small">VFX Partikel</td>
                                    <td class="fw-bold text-dark">Rp150.000</td>
                                    <td>
                                        <span class="badge-status-pending">Pending</span>
                                    </td>
                                    <td class="text-muted small">15 Jan 2026</td>
                                    <td class="text-end pe-4">
                                        <a href="/dashboard/add-asset" class="action-link">Edit</a>
                                        <span class="text-muted mx-1">|</span>
                                        <a href="/asset/detail" class="action-link">Lihat</a>
                                    </td>
                                </tr>

                                <!-- Baris 4 -->
                                <tr>
                                    <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                    <td class="text-muted small">VFX Partikel</td>
                                    <td class="fw-bold text-dark">Rp150.000</td>
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <span class="badge-status-rejected">Ditolak</span>
                                            <i class="bi bi-info-circle text-danger small" title="Perlu perbaikan deskripsi aset"></i>
                                        </div>
                                    </td>
                                    <td class="text-muted small">15 Jan 2026</td>
                                    <td class="text-end pe-4">
                                        <a href="/dashboard/add-asset" class="action-link-danger">Revisi</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Bagian 2: Tabel Transaksi Penjualan Terbaru -->
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
                            <tbody>
                                <!-- Baris Transaksi 1 -->
                                <tr>
                                    <td>
                                        <a href="#" class="action-link fw-semibold">INV-20260115-0001</a>
                                    </td>
                                    <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                    <td class="text-muted small">15 Jan 2026</td>
                                    <td class="text-end pe-4 fw-bold text-success-income">Rp150.000</td>
                                </tr>

                                <!-- Baris Transaksi 2 -->
                                <tr>
                                    <td>
                                        <a href="#" class="action-link fw-semibold">INV-20260115-0001</a>
                                    </td>
                                    <td class="fw-bold text-dark item-asset-title">Sunda-style Bamboo Shader Helper</td>
                                    <td class="text-muted small">10 Jan 2026</td>
                                    <td class="text-end pe-4 fw-bold text-success-income">Rp120.000</td>
                                </tr>

                                <!-- Baris Transaksi 3 -->
                                <tr>
                                    <td>
                                        <a href="#" class="action-link fw-semibold">INV-20260115-0001</a>
                                    </td>
                                    <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                    <td class="text-muted small">15 Jan 2026</td>
                                    <td class="text-end pe-4 fw-bold text-success-income">Rp150.000</td>
                                </tr>
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
                        Portal distribusi dan marketplace aset CG & VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
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
                            <a href="#" class="footer-link">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>