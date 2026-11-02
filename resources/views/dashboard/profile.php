<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Penjual - IndoFX</title>

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
                <span class="fw-bold fs-7 tracking-wide">Arga Studio</span>
            </div>

        </div>
    </nav>

    <!-- 2. Kontainer Utama: Sidebar Kiri & Konten Kanan -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI -->
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
                    <a href="/dashboard/profile" class="sidebar-link active">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <h2 class="dash-page-title mb-4">Profil Penjual</h2>

            <form action="/dashboard/profile" method="POST">

                <!-- CARD 1: INFORMASI PENJUAL -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Penjual</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="seller_name" class="form-label form-field-label">Nama Penjual*</label>
                            <input type="text" class="form-control form-field-input" id="seller_name" name="seller_name" value="ARGA STUDIO" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contact_email" class="form-label form-field-label">Email Kontak*</label>
                            <input type="email" class="form-control form-field-input" id="contact_email" name="contact_email" value="contact@argastudio.com" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label form-field-label">Bio</label>
                        <textarea class="form-control form-field-input" id="bio" name="bio" rows="3">Pengembang independen aset shader dan VFX berkualitas tinggi untuk Unity berbasis di Malang, Indonesia.</textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="portfolio_url" class="form-label form-field-label">Tautan Portofolio</label>
                            <input type="url" class="form-control form-field-input" id="portfolio_url" name="portfolio_url" value="https://www.artstation.com/argastudio">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="website_url" class="form-label form-field-label">Tautan Github/Website</label>
                            <input type="text" class="form-control form-field-input" id="website_url" name="website_url" value="contact@argastudio.com">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: PENCAIRAN DANA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pencairan Dana</h5>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="dana_number" class="form-label form-field-label">Nomor DANA</label>
                            <input type="text" class="form-control form-field-input" id="dana_number" name="dana_number" value="08123456789">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="dana_account_name" class="form-label form-field-label">Nama Akun DANA</label>
                            <input type="text" class="form-control form-field-input" id="dana_account_name" name="dana_account_name" value="Arga Studio">
                        </div>
                    </div>
                </div>

                <!-- TOMBOL AKSI: BATAL & SIMPAN PERUBAHAN -->
                <div class="d-flex justify-content-end align-items-center gap-3 mb-5">
                    <a href="/dashboard" class="btn btn-profile-cancel">Batal</a>
                    <button type="submit" class="btn btn-profile-save">Simpan Perubahan</button>
                </div>

            </form>

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