/**
 * Pergantian tampilan navbar: guest vs pembeli.
 *
 * Alurnya sederhana:
 *   1. PHP menulis keadaan saat ini pada .navbar-auth[data-auth-state]
 *      ("guest" atau "pembeli").
 *   2. Saat halaman siap, syncNavbarAuth() membaca keadaan itu lalu
 *      memanggil showGuestNav() atau showPembeliNav().
 *   3. showGuestNav()/showPembeliNav() hanya menyembunyikan satu div
 *      dan menampilkan div lainnya.
 *
 * Kalau nanti keadaan berubah tanpa reload (mis. setelah login lewat AJAX),
 * cukup panggil salah satu fungsi ini dari kode Anda:
 *
 *   showPembeliNav();                      // tampilkan menu pembeli
 *   showGuestNav();                        // tampilkan tombol Masuk
 *   syncNavbarAuth('pembeli');             // set keadaan + tampilkan
 */

(function () {
    'use strict';

    var guestNav = document.getElementById('nav-guest');
    var pembeliNav = document.getElementById('nav-pembeli');

    /** Tampilkan tombol Masuk, sembunyikan menu pembeli. */
    function showGuestNav() {
        if (guestNav) {
            guestNav.hidden = false;
        }
        if (pembeliNav) {
            pembeliNav.hidden = true;
        }
    }

    /** Tampilkan menu pembeli, sembunyikan tombol Masuk. */
    function showPembeliNav() {
        if (guestNav) {
            guestNav.hidden = true;
        }
        if (pembeliNav) {
            pembeliNav.hidden = false;
        }
    }

    /**
     * Baca keadaan dari HTML lalu tampilkan div yang sesuai.
     * @param {string} [state] "guest" atau "pembeli"; kosong = baca dari atribut.
     */
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

    /** Buka/tutup menu kecil di avatar. */
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

    // Dipakai juga oleh skrip lain (mis. setelah login tanpa reload).
    window.showGuestNav = showGuestNav;
    window.showPembeliNav = showPembeliNav;
    window.syncNavbarAuth = syncNavbarAuth;
})();
