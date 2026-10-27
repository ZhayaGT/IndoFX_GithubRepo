// Navbar guest vs pembeli, plus menu avatar.

(function () {
    'use strict';

    var guestNav = document.getElementById('nav-guest');
    var pembeliNav = document.getElementById('nav-pembeli');

    // Tampilkan tombol Masuk.
    function showGuestNav() {
        if (guestNav) {
            guestNav.hidden = false;
        }
        if (pembeliNav) {
            pembeliNav.hidden = true;
        }
    }

    // Tampilkan menu pembeli.
    function showPembeliNav() {
        if (guestNav) {
            guestNav.hidden = true;
        }
        if (pembeliNav) {
            pembeliNav.hidden = false;
        }
    }

    // Baca data-auth-state lalu tampilkan grup yang sesuai.
    function syncNavbarAuth(state) {
        var container = document.querySelector('.navbar-auth');

        if (typeof state === 'string' && container) {
            container.setAttribute('data-auth-state', state);
        }

        var current = container ? container.getAttribute('data-auth-state') : 'guest';

        if (current === 'pembeli') {
            showPembeliNav();
        } else {
            showGuestNav();
        }
    }

    // Menu kecil pada avatar.
    function initAvatarMenu() {
        var button = document.getElementById('navbar-avatar');
        var menu = document.getElementById('navbar-avatar-menu');

        if (!button || !menu) {
            return;
        }

        button.addEventListener('click', function (event) {
            event.stopPropagation();
            var isOpen = !menu.hidden;
            menu.hidden = isOpen;
            button.setAttribute('aria-expanded', String(!isOpen));
        });

        document.addEventListener('click', function () {
            menu.hidden = true;
            button.setAttribute('aria-expanded', 'false');
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                menu.hidden = true;
                button.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        syncNavbarAuth();
        initAvatarMenu();
    });

    // Diekspos untuk skrip lain.
    window.showGuestNav = showGuestNav;
    window.showPembeliNav = showPembeliNav;
    window.syncNavbarAuth = syncNavbarAuth;
})();
