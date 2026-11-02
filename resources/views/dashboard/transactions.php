<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Penjualan - IndoFX</title>

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
                    <a href="/dashboard" class="sidebar-link">
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
                    <a href="/dashboard/transactions" class="sidebar-link active">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="/dashboard/add-asset" class="sidebar-link">
                        <i class="bi bi-pencil-square"></i>
                        <span>Form Edit Aset</span>
                    </a>
                </li> -->
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
            <div class="mb-4">
                <h2 class="dash-page-title mb-1">Riwayat Transaksi Penjualan</h2>
                <p class="text-muted small m-0">Daftar Seluruh transaksi pembelian aset Anda oleh pembeli lokal.</p>
            </div>

            <!-- 3 Stat Cards dengan Garis Aksen Kiri -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Aset (Aksen Hijau) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-green p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Total Aset</span>
                        <div class="stat-card-value text-stat-green mb-1">Rp 4.560.000</div>
                        <span class="stat-card-sub text-muted">Sudah Termasuk potongan fee platform</span>
                    </div>
                </div>

                <!-- Card 2: Aset Terjual (Aksen Biru) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-blue p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Aset Terjual</span>
                        <div class="stat-card-value text-stat-blue mb-1">38 Transaksi</div>
                        <span class="stat-card-sub text-muted">Status: Midtrans Settlement</span>
                    </div>
                </div>

                <!-- Card 3: Total Unduhan (Aksen Kuning) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-yellow p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Total Unduhan</span>
                        <div class="stat-card-value text-stat-yellow mb-1">Rp 120.000</div>
                        <span class="stat-card-sub text-muted">Per Aset Berbayar</span>
                    </div>
                </div>
            </div>

            <!-- Bar Filter Transaksi -->
            <div class="filter-bar-container mb-4">
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control filter-input" placeholder="Cari No.Invoice atau Judul Aset.....">
                        </div>
                    </div>
                    <!-- Date Picker -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="input-group">
                            <input type="date" class="form-control filter-input" placeholder="mm/dd/yyyy">
                        </div>
                    </div>
                    <!-- Dropdown Metode Pembayaran -->
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select">
                            <option selected>Semua Metode Pembayaran</option>
                            <option value="qris">QRIS / GoPay</option>
                            <option value="bca">BCA Virtual Account</option>
                            <option value="mandiri">Mandiri Bill</option>
                            <option value="shopeepay">ShopeePay</option>
                        </select>
                    </div>
                    <!-- Tombol Cari -->
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi -->
            <div class="table-card-wrapper">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 17%;">No.Invoice</th>
                                <th scope="col" style="width: 25%;">Aset Terjual</th>
                                <th scope="col" style="width: 18%;">Metode Pembayaran</th>
                                <th scope="col" style="width: 18%;">Tanggal Transaksi</th>
                                <th scope="col" style="width: 10%;">Status</th>
                                <th scope="col">Total Pendapatan</th>
                                <th scope="col" class="text-center" style="width: 6%;">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris Transaksi 1 -->
                            <tr>
                                <td>
                                    <a href="#" class="action-link fw-semibold">INV-20261018-001</a>
                                </td>
                                <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                <td class="text-secondary small">QRIS/GoPay</td>
                                <td class="text-muted small">18 Okt 2026, 14:20</td>
                                <td>
                                    <span class="badge-status-approved">Settlement</span>
                                </td>
                                <td class="fw-bold text-success-income">Rp150.000</td>
                                <td class="text-center">
                                    <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                        <i class="bi bi-printer fs-5"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Baris Transaksi 2 -->
                            <tr>
                                <td>
                                    <a href="#" class="action-link fw-semibold">INV-20261017-009</a>
                                </td>
                                <td class="fw-bold text-dark item-asset-title">Sunda Style Bamboo Shader Helper</td>
                                <td class="text-secondary small">BCA Virtual Account</td>
                                <td class="text-muted small">17 Okt 2026, 09:12</td>
                                <td>
                                    <span class="badge-status-approved">Settlement</span>
                                </td>
                                <td class="fw-bold text-success-income">Rp120.000</td>
                                <td class="text-center">
                                    <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                        <i class="bi bi-printer fs-5"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Baris Transaksi 3 -->
                            <tr>
                                <td>
                                    <a href="#" class="action-link fw-semibold">INV-20261015-004</a>
                                </td>
                                <td class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</td>
                                <td class="text-secondary small">Mandiri Bill</td>
                                <td class="text-muted small">15 Okt 2026, 18:45</td>
                                <td>
                                    <span class="badge-status-approved">Settlement</span>
                                </td>
                                <td class="fw-bold text-success-income">Rp150.000</td>
                                <td class="text-center">
                                    <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                        <i class="bi bi-printer fs-5"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Baris Transaksi 4 -->
                            <tr>
                                <td>
                                    <a href="#" class="action-link fw-semibold">INV-20261012-002</a>
                                </td>
                                <td class="fw-bold text-dark item-asset-title">Sunda Style Bamboo Shader Helper</td>
                                <td class="text-secondary small">QRIS/ShopeePay</td>
                                <td class="text-muted small">18 Okt 2026, 11:05</td>
                                <td>
                                    <span class="badge-status-approved">Settlement</span>
                                </td>
                                <td class="fw-bold text-success-income">Rp120.000</td>
                                <td class="text-center">
                                    <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                        <i class="bi bi-printer fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

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