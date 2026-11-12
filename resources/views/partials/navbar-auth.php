<?php
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? '');
$userRole   = (string) ($_SESSION['role'] ?? 'buyer');
$isLoggedIn = !empty($_SESSION['user_id']);
$publicId   = (string) ($_SESSION['public_id'] ?? '');

// Deteksi status penjual dari backend
$isSeller   = ($userRole === 'seller' || !empty($_SESSION['is_seller']));
$storeName  = (string) ($_SESSION['store_name'] ?? $userName); // Pastikan backend menyimpan nama toko ke session

/**
 * Menghasilkan inisial nama otomatis
 * Jika penjual, ambil inisial toko. Jika pembeli, inisial nama.
 */
$initials = '';
$displayName = ($isSeller && $storeName !== '') ? $storeName : $userName;

if ($isLoggedIn && $displayName !== '') {
    $words = preg_split('/\s+/', trim($displayName)) ?: [];
    foreach (array_slice($words, 0, 2) as $word) {
        $initials .= mb_strtoupper(mb_substr($word, 0, 1));
    }
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
    <?php if ($isLoggedIn): ?>
    <div id="nav-pembeli" class="navbar-auth-group">
        
        <!-- Tombol Dinamis: Dashboard (Penjual) atau Jadi Penjual (Pembeli) -->
        <?php if ($isSeller): ?>
            <a href="/dashboard" class="btn btn-nav-seller d-none d-md-inline-flex">Dashboard</a>
        <?php else: ?>
            <a href="/seller/register" class="btn btn-nav-seller d-none d-md-inline-flex">Jadi Penjual</a>
        <?php endif; ?>

        <a href="/library" class="btn btn-nav-library d-none d-md-inline-flex">Library Saya</a>

        <a href="/cart" class="btn-nav-cart" title="Keranjang Belanja">
            <i class="bi bi-cart3"></i>
        </a>

        <!-- Avatar Lingkaran Profil Murni (Tanpa teks label nama agar sejajar rapi) -->
        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                title="<?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>">
                <span class="avatar-circle-inner"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <!-- Label nama (Sembunyi jika penjual agar lebih rapi) -->
                <span class="avatar-username-label <?= $isSeller ? 'd-none' : 'd-none d-lg-inline' ?>">
                    <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                </span>
            </button>

            <!-- Dropdown Menu Profil -->
            <div id="navbar-avatar-menu" class="navbar-avatar-menu" hidden>
                <a href="/creator/profile?id=<?= $publicId ?>" class="navbar-menu-item font-weight-bold">
                    <?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>
                </a>

                <!-- Tampilan Mobile (Menu tambahan) -->
                <div class="d-md-none border-bottom">
                    <?php if ($isSeller): ?>
                        <a href="/dashboard" class="navbar-menu-item text-primary fw-bold">Dashboard</a>
                    <?php else: ?>
                        <a href="/seller/register" class="navbar-menu-item text-primary fw-bold">Jadi Penjual</a>
                    <?php endif; ?>
                    <a href="/library" class="navbar-menu-item fw-bold">Library Saya</a>
                </div>

                <!-- Menu Edit Profile disembunyikan jika sudah jadi penjual (pakai dashboard) -->
                <?php if (!$isSeller): ?>
                    <a href="/dashboard/ubahprofile" class="navbar-menu-item">Edit Profile</a>
                <?php endif; ?>

                <a href="/invoice" class="navbar-menu-item">Histori Pembelian</a>
                <a href="/cart" class="navbar-menu-item">Keranjang</a>
                <a href="/#tentang" class="navbar-menu-item">Dukungan</a>
                <a href="/logout" class="navbar-menu-item text-danger">Keluar</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Script Handler Dropdown Profil -->
<script>
    document.addEventListener('click', function(e) {
        const avatarBtn = document.getElementById('navbar-avatar');
        const avatarMenu = document.getElementById('navbar-avatar-menu');
        if (!avatarBtn || !avatarMenu) return;

        if (avatarBtn.contains(e.target)) {
            e.preventDefault();
            e.stopPropagation();
            const isHidden = avatarMenu.hasAttribute('hidden');
            if (isHidden) {
                avatarMenu.removeAttribute('hidden');
                avatarBtn.setAttribute('aria-expanded', 'true');
            } else {
                avatarMenu.setAttribute('hidden', '');
                avatarBtn.setAttribute('aria-expanded', 'false');
            }
        } else if (!avatarMenu.contains(e.target)) {
            avatarMenu.setAttribute('hidden', '');
            avatarBtn.setAttribute('aria-expanded', 'false');
        }
    });
</script>