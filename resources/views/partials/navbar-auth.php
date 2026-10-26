<?php

/**
 * Status pengguna pada navbar.
 * Menampilkan tombol Masuk atau nama pengguna beserta tombol keluar.
 */

use IndoFX\Core\Auth;

$currentUser = Auth::check() ? Auth::user() : null;
?>
<?php if ($currentUser === null): ?>
    <a href="/auth/login" class="btn btn-nav-login">Masuk</a>
<?php else: ?>
    <span class="navbar-user-name"><?= htmlspecialchars((string) $currentUser['name'], ENT_QUOTES, 'UTF-8') ?></span>
    <form action="/auth/logout" method="POST" class="m-0">
        <button type="submit" class="btn btn-nav-login">Keluar</button>
    </form>
<?php endif; ?>
