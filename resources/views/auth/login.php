<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/login.css">
</head>

<body>

    <!-- Navbar Auth (Logo di Kiri Rata Sesuai Figma) -->
    <nav class="navbar navbar-orange">
        <div class="container-figma d-flex align-items-center">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <!-- Ikon Kubus Isometrik 3D IndoFX -->
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Bentuk Kubus Luar -->
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#e9e9e9" stroke-width="1.8" stroke-linejoin="round" />
                    <!-- Sisi Atas Kubus (Top Face) -->
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#e9e9e9" stroke-width="1.5" stroke-linejoin="round" />
                    <!-- Garis Pemisah Sisi Tengah -->
                    <path d="M12 12V22" stroke="#e9e9e9" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#e9e9e9" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#e9e9e9" stroke-width="1.5" />
                    <!-- Detail Tutup Kardus / Aksen Dalam -->
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#e9e9e9" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text">IndoFX</span>
            </a>
        </div>
    </nav>

    <!-- Area Form Login Tengah -->
    <main class="auth-wrapper">
        <div class="auth-card">
            <h2 class="auth-title mb-1 text-center">Masuk ke Akun Anda</h2>
            <p class="auth-subtitle text-center">Gunakan akun IndoFX Anda untuk mulai mendownload aset</p>

            <form action="/login" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label auth-label">Alamat Email</label>
                    <input type="email" class="form-control auth-input" id="email" name="email" placeholder="contoh@developer.id" required>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label for="password" class="form-label auth-label m-0">Kata Sandi</label>
                        <a href="#" class="auth-link">Lupa Kata Sandi?</a>
                    </div>
                    <input type="password" class="form-control auth-input" id="password" name="password" placeholder="••••••••" required>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-auth-submit">Masuk</button>
                </div>

                <p class="auth-footer-text mb-0">
                    Belum punya akun? <a href="/register" class="auth-link fw-bold">Daftar</a>
                </p>
            </form>
        </div>
    </main>

    <!-- Footer Konsisten -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>