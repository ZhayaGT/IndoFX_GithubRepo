<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil Pembeli - IndoFX</title>

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #fdece2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .profile-edit-card {
            background-color: #ffffff;
            border: 1.5px solid var(--ifx-border-dark, #3b2314);
            border-radius: 8px;
            padding: 32px 36px;
            box-shadow: 0 4px 14px rgba(59, 35, 20, 0.04);
        }

        .profile-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--ifx-brown-dark, #3b2314);
            letter-spacing: -0.5px;
        }

        .profile-form-label {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--ifx-brown-dark, #3b2314);
            margin-bottom: 8px;
            display: block;
        }

        .profile-form-input {
            border: 1.5px solid #6e5a4f;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 0.88rem;
            color: var(--ifx-brown-dark, #3b2314);
            width: 100%;
        }

        .profile-form-input:focus {
            outline: none;
            border-color: var(--ifx-orange, #ed6b1d);
            box-shadow: 0 0 0 3px rgba(237, 107, 29, 0.15);
        }

        .btn-profile-save {
            background-color: var(--ifx-orange, #ed6b1d);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 10px 38px;
            transition: all 0.2s ease;
        }

        .btn-profile-save:hover {
            background-color: var(--ifx-orange-hover, #d95d12);
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    <!-- 1. Header Navbar Oranye -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
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

            <div class="d-flex align-items-center gap-2">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- 2. Konten Formulir Ubah Profil -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma" style="max-width: 900px;">
            <h1 class="profile-page-title mb-4">Kelola Profil Pembeli</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success py-2 px-3 mb-4 rounded-3 small">
                    Profil berhasil diperbarui dan tersimpan permanen!
                </div>
            <?php endif; ?>

            <form action="/dashboard/ubah-profil" method="POST">
                <div class="profile-edit-card mb-4">
                    <!-- Input Nama -->
                    <div class="mb-4">
                        <label class="profile-form-label" for="name">Nama Pengguna</label>
                        <input type="text" class="profile-form-input" id="name" name="name"
                            value="<?= htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    </div>

                    <!-- Input Bio -->
                    <div>
                        <label class="profile-form-label" for="bio">Bio</label>
                        <textarea class="profile-form-input" id="bio" name="bio" rows="6"><?= htmlspecialchars($_SESSION['bio'] ?? 'Hanya game developer pemula :v', ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-profile-save">Simpan</button>
                </div>
            </form>
        </div>
    </main>

    <!-- 3. Footer Sederhana Terpusat -->
    <footer class="footer-creator-center py-5 mt-auto">
        <div class="container text-center">
            <h5 class="fw-bold mb-2 text-dark">IndoFX</h5>
            <p class="text-muted small mb-3 mx-auto" style="max-width: 540px;">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
            <div class="d-flex justify-content-between align-items-center text-muted small pt-3 border-top flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
</body>

</html>