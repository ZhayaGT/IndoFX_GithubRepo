<?php
// Pastikan sesi sudah berjalan dan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? 'Pengguna');
$initials = '';
$words = preg_split('/\s+/', trim($userName)) ?: [];
foreach (array_slice($words, 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
if ($initials === '') {
    $initials = 'U';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Saya - IndoFX</title>

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #FDECE2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        /* Navigasi Header */
        .navbar-library {
            background-color: #ED6B1D;
            padding: 12px 0;
            position: relative;
        }

        .avatar-circle-header {
            width: 36px;
            height: 36px;
            background-color: #CFCFCF;
            color: #2D2D2D;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* Dropdown Profil */
        .profile-dropdown-wrapper {
            position: relative;
        }

        .btn-profile-trigger {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            cursor: pointer;
        }

        .profile-custom-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background-color: #ffffff;
            border: 1px solid #e0d0c5;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            z-index: 1050;
            display: none;
            padding: 6px 0;
        }

        .profile-custom-menu.show {
            display: block;
        }

        .profile-custom-item {
            display: block;
            padding: 9px 18px;
            color: #2b2129;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.15s ease;
        }

        .profile-custom-item:hover {
            background-color: #fdf5f0;
            color: #ed6b1d;
        }

        .profile-custom-item.fw-bold {
            border-bottom: 1px solid #f0e6df;
            margin-bottom: 4px;
            color: #1a1a1a;
        }

        /* Breadcrumb & Judul */
        .breadcrumb-text {
            font-size: 0.82rem;
            color: #4A3B32;
            margin-bottom: 12px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1A1A1A;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.88rem;
            color: #555555;
            margin-top: 4px;
        }

        /* Filter Kategori */
        .filter-link {
            text-decoration: none;
            color: #333333;
            font-size: 0.88rem;
            font-weight: 500;
            padding: 4px 8px;
            transition: color 0.15s ease;
        }

        .filter-link.active {
            font-weight: 800;
            color: #1A1A1A;
        }

        .filter-link:hover {
            color: #ED6B1D;
        }

        /* Kotak Kosong Bergaris Putus-putus Sesuai Figma */
        .empty-library-card {
            border: 1.5px dashed #4A3B32;
            border-radius: 8px;
            background-color: transparent;
            min-height: 480px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        .empty-icon {
            font-size: 2.2rem;
            color: #1A1A1A;
            line-height: 1;
            margin-bottom: 14px;
        }

        .empty-title {
            font-weight: 700;
            font-size: 1rem;
            color: #1A1A1A;
            margin-bottom: 4px;
        }

        .empty-description {
            font-size: 0.85rem;
            color: #333333;
            margin: 0;
        }

        .empty-description a {
            color: #1A1A1A;
            font-weight: 700;
            text-decoration: none;
        }

        .empty-description a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <!-- 1. Header Navbar -->
    <nav class="navbar navbar-library sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
            <!-- Brand Logo -->
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold">IndoFX</span>
            </a>

            <!-- Right Nav: Cart & Dropdown Profil -->
            <div class="d-flex align-items-center gap-3">
                <a href="/cart" class="text-white text-decoration-none d-flex align-items-center" title="Keranjang">
                    <i class="bi bi-cart3 fs-4"></i>
                </a>

                <!-- Dropdown Trigger -->
                <div class="profile-dropdown-wrapper">
                    <button type="button" class="btn-profile-trigger" id="profileDropdownBtn" aria-expanded="false">
                        <span class="avatar-circle-header"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="fw-bold fs-6"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
                    </button>

                    <!-- Menu Dropdown -->
                    <div class="profile-custom-menu" id="profileDropdownMenu">
                        <a href="/creator/profile?me=1" class="profile-custom-item fw-bold">
                            <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                        <a href="/dashboard/ubah-profil" class="profile-custom-item">
                            Edit Profile
                        </a>
                        <a href="/dashboard/transactions" class="profile-custom-item">
                            Histori Pembelian
                        </a>
                        <a href="/#tentang" class="profile-custom-item">
                            Dukungan
                        </a>
                        <a href="/logout" class="profile-custom-item text-danger">
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Konten Utama Library -->
    <main class="flex-grow-1 py-4">
        <div class="container-figma">
            <!-- Breadcrumb -->
            <div class="breadcrumb-text">
                <span>Beranda</span> &gt; <span>Antrean Moderasi</span>
            </div>

            <!-- Header Halaman & Filter -->
            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="page-title mb-0">Library Saya</h1>
                    <p class="page-subtitle mb-0">Semua aset visual Unity yang telah Anda peroleh</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <a href="?filter=semua" class="filter-link active">Semua</a>
                    <a href="?filter=berbayar" class="filter-link">Berbayar</a>
                    <a href="?filter=gratis" class="filter-link">Gratis</a>
                </div>
            </div>

            <!-- Kotak Kosong -->
            <div class="empty-library-card">
                <i class="bi bi-exclamation-circle empty-icon"></i>
                <h5 class="empty-title">Belum ada aset</h5>
                <p class="empty-description">
                    Tambah aset dari <a href="/">beranda</a> atau <a href="/catalog">katalog</a>
                </p>
            </div>
        </div>
    </main>

    <!-- 3. Footer Terpusat -->
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

    <!-- Script Toggle Dropdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdownMenu');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>