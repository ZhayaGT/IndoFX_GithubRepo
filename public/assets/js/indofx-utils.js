/**
 * Utilitas kecil untuk keamanan output di sisi klien.
 *
 * Dipakai sebelum menulis data dinamis ke innerHTML agar karakter HTML
 * pada judul aset, nama kreator, komentar ulasan, dan sejenisnya tidak
 * dieksekusi sebagai markup.
 */

(function (window) {
    'use strict';

    function escapeHtml(value) {
        return String(value === null || value === undefined ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    window.IndoFXUtils = {
        escapeHtml: escapeHtml
    };

    // Alias pendek agar pemakaian di template string tetap ringkas.
    window.esc = escapeHtml;
})(window);
