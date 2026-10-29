/**
 * Interaksi halaman detail aset.
 *
 * Isi halaman dirender PHP (AssetController::detail), jadi berkas ini hanya
 * mengurus dua hal:
 *   1. Perpindahan tab Deskripsi / Changelog / Ulasan.
 *   2. Pemilihan gambar galeri lewat thumbnail.
 *
 * Tombol Beli dan Keranjang belum punya backend; klik hanya menampilkan
 * keterangan singkat supaya tombol tidak terasa mati.
 */

(function () {
    'use strict';

    function bindTabs() {
        var tabs = document.querySelector('[data-detail-tabs]');
        if (!tabs) { return; }

        var panels = document.querySelectorAll('[data-detail-panel]');

        tabs.addEventListener('click', function (event) {
            var button = event.target.closest('[data-detail-tab]');
            if (!button) { return; }

            var name = button.getAttribute('data-detail-tab');

            tabs.querySelectorAll('[data-detail-tab]').forEach(function (node) {
                var isActive = node === button;
                node.classList.toggle('active', isActive);
                node.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            panels.forEach(function (panel) {
                panel.hidden = panel.getAttribute('data-detail-panel') !== name;
            });
        });
    }

    function bindGallery() {
        var thumbs = document.querySelector('[data-detail-thumbs]');
        var main = document.querySelector('[data-detail-main-img]');
        if (!thumbs || !main) { return; }

        thumbs.addEventListener('click', function (event) {
            var button = event.target.closest('[data-detail-thumb]');
            if (!button) { return; }

            main.src = button.getAttribute('data-detail-thumb');

            thumbs.querySelectorAll('[data-detail-thumb]').forEach(function (node) {
                node.classList.toggle('is-active', node === button);
            });
        });
    }

    function bindActions() {
        var status = document.querySelector('[data-detail-status]');
        var buy = document.querySelector('[data-detail-buy]');
        var cart = document.querySelector('[data-detail-cart]');

        function announce(text) {
            if (status) { status.textContent = text; }
        }

        if (buy) {
            buy.addEventListener('click', function () {
                announce('Pembelian belum tersedia pada versi dummy ini.');
            });
        }

        if (cart) {
            cart.addEventListener('click', function () {
                announce('Keranjang belum tersedia pada versi dummy ini.');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindTabs();
        bindGallery();
        bindActions();
    });
})();