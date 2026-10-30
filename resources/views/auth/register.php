<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - IndoFX</title>

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
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#ffffff" stroke-width="1.8" stroke-linejoin="round" />
                    <!-- Sisi Atas Kubus (Top Face) -->
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#fffefe" stroke-width="1.5" stroke-linejoin="round" />
                    <!-- Garis Pemisah Sisi Tengah -->
                    <path d="M12 12V22" stroke="#fdfbfb" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#ffffff" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#f9f4f4" stroke-width="1.5" />
                    <!-- Detail Tutup Kardus / Aksen Dalam -->
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#ffffff" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text">IndoFX</span>
            </a>
        </div>
    </nav>

    <!-- Area Form Register Tengah Sesuai Mockup -->
    <main class="auth-wrapper">
        <div class="auth-card">
            <h2 class="auth-title mb-1">Daftar Akun Baru</h2>
            <p class="auth-subtitle mb-4">Akses ribuan aset Unity terbaik karya anak bangsa</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2" role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="/register" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label auth-label">Nama Lengkap</label>
                    <input type="text" class="form-control auth-input" id="name" name="name"
                        value="<?= htmlspecialchars((string) ($old['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label auth-label">Alamat Email</label>
                    <input type="email" class="form-control auth-input" id="email" name="email"
                        value="<?= htmlspecialchars((string) ($old['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label auth-label">Kata Sandi</label>
                    <input type="password" class="form-control auth-input" id="password" name="password" placeholder="Minimal 8 karakter" minlength="8" required>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-auth-submit">Daftar</button>
                </div>

                <p class="auth-footer-text mb-0">
                    Sudah punya akun? <a href="/login" class="auth-link fw-bold">Masuk</a>
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