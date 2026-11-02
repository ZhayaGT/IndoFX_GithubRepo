<?php

$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userRole = (string) ($_SESSION['role'] ?? 'buyer');
$isLoggedIn = isset($_SESSION['user_id']);

/**
 * Menghasilkan inisial nama otomatis
 */
$initials = '';
$words = preg_split('/\s+/', trim($userName)) ?: [];
foreach (array_slice($words, 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
if ($initials === '') {
    $initials = 'U';
}
?>
<div class="navbar-auth" data-auth-state="<?= $isLoggedIn ? 'pembeli' : 'guest' ?>">

    <!-- Keadaan 1: Belum login (Guest) -->
    <div id="nav-guest" class="navbar-auth-group" <?= $isLoggedIn ? 'hidden' : '' ?>>
        <a href="/login" class="btn btn-nav-login">Masuk</a>
    </div>

    <!-- Keadaan 2: Sudah login (Pembeli / Penjual) -->
    <div id="nav-pembeli" class="navbar-auth-group" <?= $isLoggedIn ? '' : 'hidden' ?>>
        <!-- Tombol Desktop (Otomatis sembunyi di HP) -->
        <?php if ($userRole === 'seller'): ?>
            <a href="/dashboard/add-asset" class="btn btn-nav-seller d-none d-md-inline-flex">Tambah Aset</a>
        <?php else: ?>
            <a href="/seller/register" class="btn btn-nav-seller d-none d-md-inline-flex">Jadi Penjual</a>
        <?php endif; ?>

        <a href="/library" class="btn btn-nav-library d-none d-md-inline-flex">Library Saya</a>

        <!-- Ikon Keranjang Belanja -->
        <a href="/cart" class="btn-nav-cart" title="Keranjang Belanja">
            <i class="bi bi-cart3"></i>
        </a>

        <!-- Avatar & Dropdown -->
        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>">
                <span class="avatar-circle-inner"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="avatar-username-label d-none d-lg-inline"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
            </button>

            <!-- Dropdown Menu -->
            <div id="navbar-avatar-menu" class="navbar-avatar-menu" hidden>
                <a href="/creator/profile?me=1" class="navbar-menu-item font-weight-bold">
                    <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                </a>

                <!-- Khusus Tampilan Layar HP: Akses Jadi Penjual & Library tetap ada di dalam menu -->
                <div class="d-md-none border-bottom">
                    <?php if ($userRole === 'seller'): ?>
                        <a href="/dashboard/add-asset" class="navbar-menu-item text-primary fw-bold">Tambah Aset</a>
                    <?php else: ?>
                        <a href="/seller/register" class="navbar-menu-item text-primary fw-bold">Jadi Penjual</a>
                    <?php endif; ?>
                    <a href="/library" class="navbar-menu-item fw-bold">Library Saya</a>
                </div>

                <a href="/dashboard/ubah-profil" class="navbar-menu-item">
                    Edit Profile
                </a>
                <a href="/dashboard/transactions" class="navbar-menu-item">
                    Histori Pembelian
                </a>
                <a href="/cart" class="navbar-menu-item">
                    Keranjang
                </a>
                <a href="/#tentang" class="navbar-menu-item">
                    Dukungan
                </a>
                <a href="/logout" class="navbar-menu-item text-danger">
                    Keluar
                </a>
            </div>
        </div>
    </div>

</div>