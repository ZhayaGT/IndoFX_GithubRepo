<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Aset Saya - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
                    <a href="/dashboard/assets" class="sidebar-link active">
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

            <!-- Header Judul & Tombol Tambah -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0">
                    Semua Aset dari <span class="fw-normal">Arga Studio</span>
                </h2>
                <a href="/dashboard/add-asset" class="btn btn-add-new-asset">
                    + Aset Baru
                </a>
            </div>

            <!-- Bar Pencarian & Filter -->
            <div class="filter-bar-container mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control filter-input" placeholder="Cari Nama Aset...">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <select class="form-select filter-select">
                            <option selected>Semua Status Moderasi</option>
                            <option value="approved">Disetujui</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select">
                            <option selected>Semua Kategori</option>
                            <option value="vfx">VFX Partikel</option>
                            <option value="shader">Shader</option>
                            <option value="3d">Model 3D</option>
                        </select>
                    </div>
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Aset -->
            <div class="table-card-wrapper">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 28%;">Judul Aset &amp; Pipeline</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Status Moderasi</th>
                                <th scope="col">Terjual / Unduhan</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <th scope="col" class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Baris 1 -->
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark item-asset-title">Volumetric Rain &amp; Storm URP</div>
                                    <span class="badge-pipe badge-urp">URP</span>
                                </td>
                                <td class="text-muted small">VFX Partikel</td>
                                <td class="fw-bold text-dark">Rp150.000</td>
                                <td>
                                    <span class="badge-status-approved">Disetujui</span>
                                </td>
                                <td class="text-muted small">24x Terjual</td>
                                <td class="text-muted small">15 Jan 2026</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/dashboard/add-asset" class="action-link-edit">Edit</a>
                                    <span class="action-divider">|</span>
                                    <a href="/asset/detail" class="action-link-view">Lihat</a>
                                </td>
                            </tr>

                            <!-- Baris 2 -->
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark item-asset-title">Sunda-style Bamboo Shader Helper</div>
                                    <span class="badge-pipe badge-builtin">Built-in</span>
                                </td>
                                <td class="text-muted small">Shader</td>
                                <td class="fw-bold text-dark">Rp120.000</td>
                                <td>
                                    <span class="badge-status-approved">Disetujui</span>
                                </td>
                                <td class="text-muted small">14x Terjual</td>
                                <td class="text-muted small">10 Jan 2026</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/dashboard/add-asset" class="action-link-edit">Edit</a>
                                    <span class="action-divider">|</span>
                                    <a href="/asset/detail" class="action-link-view">Lihat</a>
                                </td>
                            </tr>

                            <!-- Baris 3 -->
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark item-asset-title">Low-Poly Indonesian Forest Trees</div>
                                    <div class="d-flex gap-1 mt-1">
                                        <span class="badge-pipe badge-urp">URP</span>
                                        <span class="badge-pipe badge-builtin">Built-in</span>
                                    </div>
                                </td>
                                <td class="text-muted small">Shader</td>
                                <td class="fw-bold text-dark">Gratis</td>
                                <td>
                                    <span class="badge-status-approved">Disetujui</span>
                                </td>
                                <td class="text-muted small">104x Unduhan</td>
                                <td class="text-muted small">10 Jan 2026</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/dashboard/add-asset" class="action-link-edit">Edit</a>
                                    <span class="action-divider">|</span>
                                    <a href="/asset/detail" class="action-link-view">Lihat</a>
                                </td>
                            </tr>

                            <!-- Baris 4 -->
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark item-asset-title">Sci-Fi Holo UI Shader Package</div>
                                    <span class="badge-pipe badge-hdrp">HDRP</span>
                                </td>
                                <td class="text-muted small">VFX Partikel</td>
                                <td class="fw-bold text-dark">Rp150.000</td>
                                <td>
                                    <span class="badge-status-pending">Pending</span>
                                </td>
                                <td class="text-muted small">-</td>
                                <td class="text-muted small">15 Jan 2026</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/dashboard/add-asset" class="action-link-edit">Edit</a>
                                </td>
                            </tr>

                            <!-- Baris 5 -->
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark item-asset-title">Realistic Explosion VFX Pack</div>
                                    <span class="badge-pipe badge-urp">URP</span>
                                </td>
                                <td class="text-muted small">VFX Partikel</td>
                                <td class="fw-bold text-dark">Rp150.000</td>
                                <td>
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <span class="badge-status-rejected">Ditolak</span>
                                        <i class="bi bi-info-circle text-danger small" title="Perlu perbaikan deskripsi aset"></i>
                                    </div>
                                </td>
                                <td class="text-muted small">-</td>
                                <td class="text-muted small">15 Jan 2026</td>
                                <td class="text-end pe-4 text-nowrap">
                                    <a href="/dashboard/add-asset" class="action-link-danger">Revisi</a>
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