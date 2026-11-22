<?php
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? '');
$userRole   = (string) ($_SESSION['role'] ?? 'buyer');
$isLoggedIn = !empty($_SESSION['user_id']);
$userKey    = $isLoggedIn ? (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? '') : '';
$userEmail  = $isLoggedIn ? (string) ($_SESSION['email'] ?? '') : '';

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
<style>
    .btn-nav-cart {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .global-cart-badge {
        position: absolute;
        top: -5px;
        right: -8px;
        background-color: #ffffff;
        color: #ED6B1D;
        border: 1.5px solid #1A1A1A;
        font-size: 0.66rem;
        font-weight: 800;
        line-height: 1;
        min-width: 18px;
        height: 18px;
        padding: 0 4px;
        border-radius: 999px;
        display: none;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
        pointer-events: none;
    }

    .global-cart-badge.show {
        display: inline-flex;
    }
</style>

<div class="navbar-auth"
    data-auth-state="<?= $isLoggedIn ? 'pembeli' : 'guest' ?>"
    data-user-key="<?= htmlspecialchars($userKey, ENT_QUOTES, 'UTF-8') ?>">

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

        <!-- Ikon Keranjang Belanja + Badge Dinamis -->
        <a href="/cart" class="btn-nav-cart" title="Keranjang Belanja">
            <i class="bi bi-cart3"></i>
            <span id="globalCartBadge" class="global-cart-badge">0</span>
        </a>

        <!-- Avatar Lingkaran Profil Murni -->
        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>">
                <span class="avatar-circle-inner" id="navAvatarCircle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
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

<!-- Script Sinkronisasi Status Penjual & Badge Keranjang Awal -->
<script>
    (function() {
        const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
        const userKey = <?= json_encode($userKey, JSON_UNESCAPED_UNICODE) ?>;
        const currentUserName = <?= json_encode($userName, JSON_UNESCAPED_UNICODE) ?>;
        const currentUserEmail = <?= json_encode($userEmail, JSON_UNESCAPED_UNICODE) ?>;

        if (!isLoggedIn || !userKey) {
            return;
        }

        // 1. Update Badge Keranjang Secepat Mungkin Tanpa Menunggu Defer JS
        try {
            const cartItems = JSON.parse(localStorage.getItem('indofx_cart_' + userKey)) || [];
            const badge = document.getElementById('globalCartBadge');
            if (badge && Array.isArray(cartItems) && cartItems.length > 0) {
                badge.textContent = cartItems.length > 99 ? '99+' : String(cartItems.length);
                badge.classList.add('show');
            }
        } catch (e) {}

        // 2. Sinkronisasi Profil Penjual (HANYA aktif jika sudah disetujui Admin)
        try {
            const sellerDataRaw = localStorage.getItem('indofx_seller_profile_' + userKey);
            const isSellerFlag = localStorage.getItem('indofx_is_seller_' + userKey);
            let parsedSeller = null;
            if (sellerDataRaw) {
                parsedSeller = JSON.parse(sellerDataRaw);
            }

            // Cek juga antrean pengajuan penjual jika Admin menyetujui via email/user_key
            let reqRecord = null;
            try {
                const reqs = JSON.parse(localStorage.getItem('indofx_seller_requests')) || [];
                reqRecord = reqs.find(r =>
                    (userKey && String(r.user_key) === String(userKey)) ||
                    (currentUserEmail && r.email && r.email.toLowerCase() === currentUserEmail.toLowerCase())
                ) || null;
            } catch (e) {}

            const isApprovedByAdmin =
                (parsedSeller && (parsedSeller.is_seller === true || parsedSeller.status === 'approved')) ||
                (isSellerFlag === 'true') ||
                (reqRecord && (reqRecord.status === 'Diterima' || reqRecord.status === 'Aktif'));

            const isPendingReview = !isApprovedByAdmin && (
                (parsedSeller && parsedSeller.status === 'pending') ||
                (reqRecord && reqRecord.status === 'Menunggu')
            );

            const btnSeller = document.getElementById('btnSellerNav');
            const mobileSellerLink = document.getElementById('mobileSellerLink');

            if (isApprovedByAdmin) {
                const storeName = (parsedSeller && parsedSeller.store_name) ?
                    parsedSeller.store_name :
                    ((reqRecord && (reqRecord.store_name || reqRecord.name)) || currentUserName || 'Penjual');

                if (btnSeller) {
                    btnSeller.innerText = 'Dashboard';
                    btnSeller.href = '/dashboard';
                }

                if (mobileSellerLink) {
                    mobileSellerLink.innerText = 'Dashboard';
                    mobileSellerLink.href = '/dashboard';
                }

                const avatarCircle = document.getElementById('navAvatarCircle');
                if (avatarCircle && storeName) {
                    const parts = storeName.trim().split(/\s+/);
                    let storeInits = '';
                    parts.slice(0, 2).forEach(p => storeInits += p.charAt(0).toUpperCase());
                    avatarCircle.innerText = storeInits || 'US';
                }

                const dropdownTitle = document.getElementById('dropdownSellerName');
                if (dropdownTitle && storeName) {
                    dropdownTitle.innerText = storeName;
                }
            } else if (isPendingReview) {
                // Tetap arahkan ke halaman pendaftaran penjual agar bisa melihat status menunggu verifikasi
                if (btnSeller) {
                    btnSeller.innerText = 'Jadi Penjual';
                    btnSeller.href = '/seller/register';
                    btnSeller.title = 'Pengajuan penjual Anda sedang ditinjau Admin';
                }
                if (mobileSellerLink) {
                    mobileSellerLink.innerText = 'Jadi Penjual';
                    mobileSellerLink.href = '/seller/register';
                }
            }
        } catch (e) {
            console.error('Error parsing seller profile:', e);
        }
    })();
</script>