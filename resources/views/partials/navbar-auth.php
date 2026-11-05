<?php
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? '');
$userRole   = (string) ($_SESSION['role'] ?? 'buyer');
$isLoggedIn = !empty($_SESSION['user_id']);
$userKey    = $isLoggedIn ? (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? '') : '';

/**
 * Menghasilkan inisial nama otomatis hanya jika login
 */
$initials = '';
if ($isLoggedIn && $userName !== '') {
    $words = preg_split('/\s+/', trim($userName)) ?: [];
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
    <div id="nav-pembeli" class="navbar-auth-group" <?= $isLoggedIn ? '' : 'hidden' ?>>
        <!-- Tombol Desktop: Mengarah ke /dashboard jika penjual -->
        <?php if ($userRole === 'seller' || !empty($_SESSION['is_seller'])): ?>
            <a href="/dashboard" id="btnSellerNav" class="btn btn-nav-seller d-none d-md-inline-flex">Dashboard</a>
        <?php else: ?>
            <a href="/seller/register" id="btnSellerNav" class="btn btn-nav-seller d-none d-md-inline-flex">Jadi Penjual</a>
        <?php endif; ?>

        <!-- Tombol Library Saya -->
        <a href="/library" class="btn btn-nav-library d-none d-md-inline-flex">Library Saya</a>

        <!-- Ikon Keranjang Belanja -->
        <a href="/cart" class="btn-nav-cart" title="Keranjang Belanja">
            <i class="bi bi-cart3"></i>
        </a>

        <!-- Avatar Lingkaran Profil -->
        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>">
                <span class="avatar-circle-inner" id="navAvatarCircle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="avatar-username-label d-none d-lg-inline" id="navUsernameLabel"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
            </button>

            <!-- Dropdown Menu Profil -->
            <div id="navbar-avatar-menu" class="navbar-avatar-menu" hidden>
                <a href="/creator/profile?me=1" class="navbar-menu-item font-weight-bold" id="dropdownSellerName">
                    <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                </a>

                <!-- Khusus Tampilan Layar HP -->
                <div class="d-md-none border-bottom" id="mobileSellerLinkWrap">
                    <?php if ($userRole === 'seller' || !empty($_SESSION['is_seller'])): ?>
                        <a href="/dashboard" class="navbar-menu-item text-primary fw-bold" id="mobileSellerLink">Dashboard</a>
                    <?php else: ?>
                        <a href="/seller/register" class="navbar-menu-item text-primary fw-bold" id="mobileSellerLink">Jadi Penjual</a>
                    <?php endif; ?>
                    <a href="/library" class="navbar-menu-item fw-bold">Library Saya</a>
                </div>

                <!-- Menu Edit Profile: Hanya muncul jika akun belum jadi penjual -->
                <a href="/dashboard/ubahprofile" class="navbar-menu-item" id="menuEditProfile">
                    Edit Profile
                </a>
                <a href="/invoice" class="navbar-menu-item">
                    Histori Pembelian
                </a>
                <a href="/cart" class="navbar-menu-item">
                    Keranjang
                </a>
                <a href="/#tentang" class="navbar-menu-item">
                    Dukungan
                </a>
                <a href="/logout" class="navbar-menu-item text-danger" id="btnLogoutLink">
                    Keluar
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Script Sinkronisasi Status Penjual & Handler Dropdown -->
<script>
    (function() {
        const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
        const userKey = '<?= $userKey ?>';

        // JIKA BELUM LOGIN: Hentikan eksekusi, biarkan mode tamu (Guest) aktif
        if (!isLoggedIn || !userKey) {
            return;
        }

        // HANYA ambil data milik userKey yang sedang aktif (bukan kunci global)
        const sellerDataRaw = localStorage.getItem('indofx_seller_profile_' + userKey);
        const isSellerFlag = localStorage.getItem('indofx_is_seller_' + userKey);

        if (sellerDataRaw || isSellerFlag === 'true') {
            try {
                const seller = sellerDataRaw ? JSON.parse(sellerDataRaw) : {};
                const storeName = seller.store_name || 'Arga Studio';

                // A. Ubah Tombol "Jadi Penjual" Menjadi "Dashboard"
                const btnSeller = document.getElementById('btnSellerNav');
                if (btnSeller) {
                    btnSeller.innerText = 'Dashboard';
                    btnSeller.href = '/dashboard';
                }

                const mobileSellerLink = document.getElementById('mobileSellerLink');
                if (mobileSellerLink) {
                    mobileSellerLink.innerText = 'Dashboard';
                    mobileSellerLink.href = '/dashboard';
                }

                // B. Sembunyikan 'Edit Profile' pembeli karena sudah ada di dashboard penjual
                const editProfileLink = document.getElementById('menuEditProfile');
                if (editProfileLink) {
                    editProfileLink.style.display = 'none';
                }

                // C. Ubah Inisial Avatar Menjadi Inisial Toko
                const avatarCircle = document.getElementById('navAvatarCircle');
                if (avatarCircle && storeName) {
                    const parts = storeName.trim().split(/\s+/);
                    let storeInits = '';
                    parts.slice(0, 2).forEach(p => storeInits += p.charAt(0).toUpperCase());
                    avatarCircle.innerText = storeInits || 'AS';
                }

                // D. Sembunyikan Teks Nama Panjang (Hanya tampilkan lingkaran avatar)
                const usernameLabel = document.getElementById('navUsernameLabel');
                if (usernameLabel) {
                    usernameLabel.classList.remove('d-lg-inline');
                    usernameLabel.classList.add('d-none');
                    usernameLabel.style.setProperty('display', 'none', 'important');
                }

                // E. Pasang Nama Toko di Urutan Teratas Dropdown Profil
                const dropdownTitle = document.getElementById('dropdownSellerName');
                if (dropdownTitle && storeName) {
                    dropdownTitle.innerText = storeName;
                }
            } catch (e) {
                console.error('Error parsing seller profile:', e);
            }
        }

        // Handler Dropdown Profil
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
    })();
</script>