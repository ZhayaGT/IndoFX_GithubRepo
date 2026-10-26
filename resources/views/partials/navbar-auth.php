<?php

/**
 * Status pengguna pada navbar.
 *
 * Dua keadaan dirender sekaligus lalu diatur oleh JavaScript
 * (public/assets/js/navbar-auth.js):
 *
 *   #nav-guest    -> pengunjung yang belum login  (tombol Masuk)
 *   #nav-pembeli  -> pengguna yang sudah login    (Jadi Penjual, Library Saya, avatar)
 *
 * Keadaan awal ditulis server pada data-auth-state dan atribut `hidden` sudah
 * dipasang di sisi server, jadi tampilan tetap benar walaupun JavaScript gagal
 * dimuat (tidak ada kedipan saat halaman dibuka).
 */

use IndoFX\Core\Auth;

$currentUser = Auth::check() ? Auth::user() : null;
$isLoggedIn = $currentUser !== null;

$userName = (string) ($currentUser['name'] ?? '');
$userEmail = (string) ($currentUser['email'] ?? '');

/**
 * Inisial nama untuk avatar: "Andi Pratama" -> "AP".
 */
$initials = '';
foreach (array_slice(preg_split('/\s+/', trim($userName)) ?: [], 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
?>
<div class="navbar-auth" data-auth-state="<?= $isLoggedIn ? 'pembeli' : 'guest' ?>">

    <!-- Keadaan 1: pengunjung (belum login) -->
    <div id="nav-guest" class="navbar-auth-group" <?= $isLoggedIn ? 'hidden' : '' ?>>
        <a href="/auth/login" class="btn btn-nav-login">Masuk</a>
    </div>

    <!-- Keadaan 2: pembeli (sudah login) -->
    <div id="nav-pembeli" class="navbar-auth-group" <?= $isLoggedIn ? '' : 'hidden' ?>>
        <a href="/dashboard" class="btn btn-nav-seller">Jadi Penjual</a>
        <a href="/dashboard/assets" class="btn btn-nav-library">Library Saya</a>

        <div class="navbar-avatar-wrap">
            <button type="button" id="navbar-avatar" class="navbar-avatar"
                    aria-haspopup="true" aria-expanded="false" aria-controls="navbar-avatar-menu"
                    title="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?>
            </button>

            <div id="navbar-avatar-menu" class="navbar-avatar-menu" hidden>
                <p class="navbar-avatar-name"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></p>
                <p class="navbar-avatar-email"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></p>
                <form action="/auth/logout" method="POST" class="m-0">
                    <button type="submit" class="navbar-avatar-logout">Keluar</button>
                </form>
            </div>
        </div>
    </div>

</div>
