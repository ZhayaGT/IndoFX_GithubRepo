(function () {
    'use strict';

    var DATA_URL = '/assets/data/assets.json';
    var PAGE_SIZE = 6;

    /* Kelas warna tag untuk tiap render pipeline (lihat .badge-pipeline-* di style.css). */
    var PIPELINE_CLASSES = {
        'URP': 'badge-pipeline-urp',
        'HDRP': 'badge-pipeline-hdrp',
        'Built-in': 'badge-pipeline-builtin'
    };

    function pipelineBadgeClass(pipeline) {
        return PIPELINE_CLASSES[pipeline] || '';
    }

    function formatPrice(price) {
        return price === 0 ? 'Gratis' : 'Rp' + price.toLocaleString('id-ID');
    }

    function escapeHtml(text) {
        return String(text === null || text === undefined ? '' : text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Mengembalikan string HTML satu kartu, mengikuti markup yang sekarang ada.
    function createCard(item, colClass) {
        var col = colClass || 'col-12 col-sm-6 col-lg-3';

        return '<div class="' + col + '">' +
            '<a href="/asset/detail?slug=' + encodeURIComponent(item.slug) + '" class="text-decoration-none d-block h-100">' +
            '<div class="asset-card-figma h-100 d-flex flex-column">' +
            '<div class="asset-thumb-wrapper">' +
            '<img class="asset-thumb-img" src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.title) + '" loading="lazy">' +
            '</div>' +
            '<div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">' +
            '<div>' +
            '<span class="asset-studio">' + escapeHtml(item.seller_name) + '</span>' +
            '<h6 class="asset-name">' + escapeHtml(item.title) + '</h6>' +
            '<div class="d-flex gap-1 mb-2">' +
            '<span class="badge-pipeline ' + pipelineBadgeClass(item.render_pipeline) + '">' + escapeHtml(item.render_pipeline) + '</span>' +
            '</div>' +
            '</div>' +
            '<div class="d-flex justify-content-between align-items-center pt-1">' +
            '<span class="asset-price">' + formatPrice(item.price) + '</span>' +
            '<i class="bi bi-download asset-dl-icon"></i>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</a>' +
            '</div>';
    }

    // Mengambil JSON sekali, lalu memanggil callback dengan array item.
    function loadCatalog(callback) {
        fetch(DATA_URL)
            .then(function (res) {
                if (!res.ok) { throw new Error('HTTP ' + res.status); }
                return res.json();
            })
            .then(function (payload) { callback(null, payload.data || []); })
            .catch(function (err) { callback(err, []); });
    }

    function showMessage(container, text) {
        if (!container) { return; }
        container.innerHTML = '<div class="alert alert-light border text-center text-muted mb-0 py-4 w-100">' +
            escapeHtml(text) + '</div>';
    }

    function renderCards(container, items, colClass) {
        if (!container) { return; }
        if (!items.length) {
            showMessage(container, 'Belum ada aset yang cocok.');
            return;
        }
        container.innerHTML = items.map(function (item) {
            return createCard(item, colClass);
        }).join('');
    }

    function byCreatedDesc(a, b) {
        if (a.created_at === b.created_at) { return 0; }
        return a.created_at < b.created_at ? 1 : -1;
    }

    function byDownloadsDesc(a, b) {
        return b.download_count - a.download_count;
    }

    function renderLatest() {
        var container = document.querySelector('[data-catalog-list="latest"]');

        loadCatalog(function (err, items) {
            if (err) {
                showMessage(container, 'Gagal memuat katalog. Muat ulang halaman.');
                return;
            }
            renderCards(container, items.slice().sort(byCreatedDesc).slice(0, 4));
        });
    }

    function renderFree() {
        var container = document.querySelector('[data-catalog-list="free"]');

        loadCatalog(function (err, items) {
            if (err) {
                showMessage(container, 'Gagal memuat katalog. Muat ulang halaman.');
                return;
            }
            var free = items.filter(function (item) { return item.price === 0; });
            renderCards(container, free.sort(byDownloadsDesc).slice(0, 4));
        });
    }

    /* Nilai <option> pada .sort-select yang dikenal renderExplore(). */
    var SORT_VALUES = ['terbaru', 'populer', 'harga_rendah', 'harga_tinggi'];

    /**
     * Baca keadaan awal halaman dari query string.
     *
     * Sumbernya:
     *   /catalog/explore?sort=terbaru -> tautan "Selengkapnya" aset terbaru
     *   /catalog/explore?gratis=1     -> tautan "Selengkapnya" aset gratis
     *   /catalog/explore?q=rain       -> kotak pencarian navbar (form GET)
     */
    function readExploreParams() {
        var params = new URLSearchParams(window.location.search);
        var sort = params.get('sort');

        return {
            sort: SORT_VALUES.indexOf(sort) === -1 ? 'terbaru' : sort,
            gratis: params.get('gratis') === '1',
            query: (params.get('q') || '').trim()
        };
    }

    /* Pencarian sederhana di sisi klien: setiap kata pada kata kunci harus
       muncul sebagai awal kata pada judul, penjual, kategori, atau deskripsi
       singkat. Pencocokan per kata (bukan substring) mencegah "rain" ikut
       menemukan "Terrain". Nanti diganti query backend. */
    function matchesQuery(item, query) {
        if (!query) { return true; }

        var haystack = [item.title, item.seller_name, item.category, item.short_description]
            .join(' ')
            .toLowerCase();
        var words = haystack.split(/[^a-z0-9]+/).filter(Boolean);
        var tokens = query.toLowerCase().split(/\s+/).filter(Boolean);

        return tokens.every(function (token) {
            return words.some(function (word) { return word.indexOf(token) === 0; });
        });
    }

    function renderExplore() {
        var listEl = document.querySelector('[data-catalog-list="explore"]');
        var countEl = document.querySelector('[data-catalog-count]');
        var paginationEl = document.querySelector('[data-catalog-pagination]');
        var sortSelect = document.querySelector('.sort-select');
        var freeRadio = document.getElementById('price_free_radio');
        var resetLink = document.querySelector('.filter-reset-link');

        var initial = readExploreParams();
        var allItems = [];
        var currentSort = initial.sort;
        var freeOnly = initial.gratis;
        var currentQuery = initial.query;
        var currentPage = 1;
        var colClass = 'col-12 col-sm-6 col-md-4';

        /* Tulis keadaan filter ke query string tanpa memuat ulang halaman,
           supaya tautan bisa dibagikan dan tombol Back tidak rusak. */
        function syncUrl() {
            var params = new URLSearchParams(window.location.search);

            if (currentSort === 'terbaru') {
                params.delete('sort');
            } else {
                params.set('sort', currentSort);
            }

            if (freeOnly) {
                params.set('gratis', '1');
            } else {
                params.delete('gratis');
            }

            if (currentQuery) {
                params.set('q', currentQuery);
            } else {
                params.delete('q');
            }

            var query = params.toString();
            window.history.replaceState(null, '', window.location.pathname + (query ? '?' + query : ''));
        }

        function applySort(items, sort) {
            if (sort === 'terbaru') { return items.slice().sort(byCreatedDesc); }
            if (sort === 'populer') { return items.slice().sort(byDownloadsDesc); }
            if (sort === 'harga_rendah') {
                return items.slice().sort(function (a, b) { return a.price - b.price; });
            }
            if (sort === 'harga_tinggi') {
                return items.slice().sort(function (a, b) { return b.price - a.price; });
            }
            return items;
        }

        function buildPagination(totalPages) {
            if (!paginationEl) { return; }

            var html = '<a href="javascript:void(0)" data-page-prev class="page-nav-link ' +
                (currentPage === 1 ? 'text-muted' : 'text-dark fw-bold') + '">&laquo; Sebelumnya</a>';

            for (var page = 1; page <= totalPages; page++) {
                html += '<a href="javascript:void(0)" data-page="' + page + '" class="page-num-link' +
                    (page === currentPage ? ' active' : '') + '">' + page + '</a>';
            }

            html += '<a href="javascript:void(0)" data-page-next class="page-nav-link ' +
                (currentPage === totalPages ? 'text-muted' : 'text-dark fw-bold') + '">Selanjutnya &raquo;</a>';

            paginationEl.innerHTML = html;
        }

        function paint() {
            var visible = allItems.filter(function (item) {
                if (freeOnly && item.price !== 0) { return false; }
                return matchesQuery(item, currentQuery);
            });
            var sorted = applySort(visible, currentSort);
            var totalPages = Math.max(1, Math.ceil(sorted.length / PAGE_SIZE));

            if (currentPage > totalPages) { currentPage = totalPages; }
            if (currentPage < 1) { currentPage = 1; }

            if (countEl) { countEl.textContent = String(sorted.length); }

            if (!sorted.length) {
                showMessage(listEl, 'Belum ada aset yang cocok.');
                if (paginationEl) { paginationEl.innerHTML = ''; }
                return;
            }

            var start = (currentPage - 1) * PAGE_SIZE;
            renderCards(listEl, sorted.slice(start, start + PAGE_SIZE), colClass);
            buildPagination(totalPages);
        }

        if (paginationEl) {
            paginationEl.addEventListener('click', function (event) {
                var link = event.target.closest('a');
                if (!link || !paginationEl.contains(link)) { return; }
                event.preventDefault();

                var target = null;
                if (link.hasAttribute('data-page-prev')) {
                    target = currentPage - 1;
                } else if (link.hasAttribute('data-page-next')) {
                    target = currentPage + 1;
                } else if (link.hasAttribute('data-page')) {
                    target = parseInt(link.getAttribute('data-page'), 10);
                }

                if (target === null || isNaN(target) || target === currentPage) { return; }
                currentPage = target;
                paint();
            });
        }

        if (sortSelect) {
            sortSelect.value = currentSort;

            sortSelect.addEventListener('change', function () {
                currentSort = sortSelect.value;
                currentPage = 1;
                paint();
                syncUrl();
            });
        }

        if (freeRadio) {
            freeRadio.checked = freeOnly;

            freeRadio.addEventListener('change', function () {
                freeOnly = freeRadio.checked;
                currentPage = 1;
                paint();
                syncUrl();
            });
        }

        if (resetLink) {
            resetLink.addEventListener('click', function (event) {
                event.preventDefault();

                freeOnly = false;
                currentSort = 'terbaru';
                currentQuery = '';
                currentPage = 1;

                if (freeRadio) { freeRadio.checked = false; }
                if (sortSelect) { sortSelect.value = currentSort; }

                var searchInput = document.querySelector('.search-input-header');
                if (searchInput) { searchInput.value = ''; }

                paint();
                syncUrl();
            });
        }

        loadCatalog(function (err, items) {
            if (err) {
                showMessage(listEl, 'Gagal memuat katalog. Muat ulang halaman.');
                return;
            }
            allItems = items;
            paint();
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelector('[data-catalog-list="latest"]')) { renderLatest(); }
        if (document.querySelector('[data-catalog-list="free"]')) { renderFree(); }
        if (document.querySelector('[data-catalog-list="explore"]')) { renderExplore(); }
    });
})();
