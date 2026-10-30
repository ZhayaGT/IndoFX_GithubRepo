<?php

$userName = (string) ($_SESSION['name'] ?? 'Pengguna');
$userRole = (string) ($_SESSION['role'] ?? 'buyer');
$isLoggedIn = isset($_SESSION['user_id']);

/**
 * Menghasilkan inisial otomatis dari nama (contoh: "Andi Pratama" -> "AP")
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

    <!-- Keadaan 1: Belum login -->
    <div id="nav-guest" class="navbar-auth-group" <?= $isLoggedIn ? 'hidden' : '' ?>>
        <a href="/login" class="btn btn-nav-login">Masuk</a>
    </div>

    <!-- Keadaan 2: Sudah login -->
    <div id="nav-pembeli" class="navbar-auth-group" <?= $isLoggedIn ? '' : 'hidden' ?>>
        <?php if ($userRole === 'seller'): ?>
            <a href="/dashboard/add-asset" class="btn btn-nav-seller">Tambah Aset</a>
        <?php else: ?>
            <a href="/seller/register" class="btn btn-nav-seller">Jadi Penjual</a>
        <?php endif; ?>

        <a href="/library" class="btn btn-nav-library">Library Saya</a>

        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?>
            </button>

            <!-- Dropdown Menu -->
            <div id="navbar-avatar-menu" class="navbar-avatar-menu" hidden>
                <!-- Klik nama: menuju halaman etalase publik profil -->
                <a href="/creator/profile?me=1" class="navbar-menu-item font-weight-bold">
                    <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                </a>

                <!-- Klik Edit Profile: menuju halaman formulir ubah nama & bio -->
                <a href="/dashboard/ubah-profil" class="navbar-menu-item">
                    Edit Profile
                </a>

                <a href="/dashboard/transactions" class="navbar-menu-item">
                    Histori Pembelian
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