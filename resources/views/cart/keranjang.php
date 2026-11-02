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
    <title>Keranjang - IndoFX</title>

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #FDECE2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        /* Navbar */
        .navbar-cart {
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

        /* Header Judul Halaman */
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1A1A1A;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.88rem;
            color: #4A3B32;
            margin-top: 4px;
        }

        /* Kartu Item Keranjang */
        .cart-item-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(43, 33, 41, 0.04);
            margin-bottom: 16px;
        }

        .cart-item-thumb {
            width: 175px;
            min-width: 175px;
            height: 110px;
            object-fit: cover;
            border-right: 1.5px solid #2B2129;
            background-color: #3b2e39;
        }

        .badge-dibeli {
            color: #2ecc71;
            font-weight: 800;
            font-size: 0.78rem;
            letter-spacing: 0.5px;
        }

        .item-perolehan {
            font-size: 0.78rem;
            color: #555555;
        }

        .item-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1A1A1A;
            margin: 2px 0 4px 0;
        }

        .item-creator {
            font-size: 0.82rem;
            color: #4A3B32;
            margin-bottom: 0;
        }

        .badge-pipeline-tag {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            color: #ffffff;
            line-height: 1.2;
            display: inline-block;
        }

        .tag-hdrp {
            background-color: #503d6d;
        }

        .tag-urp {
            background-color: #a82828;
        }

        .tag-builtin {
            background-color: #0b72b9;
        }

        /* Checkbox & Tombol Aksi */
        .cart-checkbox {
            width: 18px;
            height: 18px;
            border: 1.5px solid #2B2129;
            border-radius: 3px;
            cursor: pointer;
        }

        .btn-cart-delete {
            background-color: #dc2626;
            color: #ffffff;
            font-size: 0.8rem;
            font-weight: 700;
            border: none;
            border-radius: 5px;
            padding: 6px 18px;
            transition: background-color 0.15s ease;
        }

        .btn-cart-delete:hover {
            background-color: #b91c1c;
            color: #ffffff;
        }

        .btn-checkout {
            background-color: #ED6B1D;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 10px 32px;
            transition: background-color 0.15s ease;
        }

        .btn-checkout:hover {
            background-color: #d95d12;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <!-- 1. Header Navbar Tanpa Icon Keranjang -->
    <nav class="navbar navbar-cart sticky-top">
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

            <!-- Sisi Kanan: Dropdown Akun Pengguna Dinamis -->
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
    </nav>

    <!-- 2. Konten Utama Keranjang -->
    <main class="flex-grow-1 py-4">
        <div class="container-figma">
            <h1 class="page-title mb-1">Keranjang</h1>
            <p class="page-subtitle mb-4">Semua aset visual Unity yang Anda Ingin Beli</p>

            <!-- Daftar Item Keranjang Sesuai Figma -->
            <div class="cart-items-wrapper mb-4">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="cart-item-card d-flex align-items-center">
                        <!-- Thumbnail Aset -->
                        <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=500&auto=format&fit=crop&q=60"
                            alt="Thumbnail Aset" class="cart-item-thumb">

                        <!-- Detail Aset -->
                        <div class="p-3 flex-grow-1 d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge-dibeli">DIBELI</span>
                                <span class="item-perolehan">Perolehan: 15 Jan 2026</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h3 class="item-title">Volumetric Rain &amp; Storm URP</h3>
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <span class="badge-pipeline-tag tag-hdrp">HDRP</span>
                                    <span class="badge-pipeline-tag tag-urp">URP</span>
                                    <span class="badge-pipeline-tag tag-builtin">Built-in</span>
                                </div>
                            </div>
                            <p class="item-creator">Oleh Arga Studio</p>
                        </div>

                        <!-- Checkbox & Tombol Hapus -->
                        <div class="d-flex align-items-center gap-3 pe-4">
                            <input type="checkbox" class="form-check-input cart-checkbox m-0" checked>
                            <button type="button" class="btn btn-cart-delete">Hapus</button>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Tombol Checkout di Kanan Bawah -->
            <div class="d-flex justify-content-end mb-5">
                <a href="/pembayaran" class="btn btn-checkout text-decoration-none">Checkout</a>
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