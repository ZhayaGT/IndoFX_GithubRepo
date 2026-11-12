/**
 * Helper penyimpanan lokal IndoFX.
 *
 * Semua data penjual disimpan memakai kunci berbasis user_id agar konsisten
 * antar halaman:
 *
 *   indofx_seller_profile_<user_id>
 *   indofx_seller_assets_<user_id>
 *   indofx_seller_sales_<user_id>
 *   indofx_library_<user_id>
 *
 * Data lama yang masih memakai <username> atau <store_name> tetap dibaca
 * sebagai fallback, lalu disalin ke kunci baru tanpa menghapus data lama.
 */

(function (window) {
    'use strict';

    var ASSET_PREFIX = 'indofx_seller_assets_';
    var PROFILE_PREFIX = 'indofx_seller_profile_';
    var SALES_PREFIX = 'indofx_seller_sales_';
    var LIBRARY_PREFIX = 'indofx_library_';

    function normalizeStatus(status) {
        var value = String(status === null || status === undefined ? '' : status).trim().toLowerCase();

        if (value === 'pending' || value === 'pending review' || value === 'menunggu') {
            return 'Menunggu';
        }
        if (value === 'published' || value === 'disetujui' || value === 'approved') {
            return 'Published';
        }
        if (value === 'ditolak' || value === 'rejected') {
            return 'Ditolak';
        }

        return 'Menunggu';
    }

    function readJSON(key, fallback) {
        try {
            var raw = localStorage.getItem(key);
            if (!raw) {
                return fallback;
            }
            var parsed = JSON.parse(raw);
            return parsed === null || parsed === undefined ? fallback : parsed;
        } catch (e) {
            return fallback;
        }
    }

    function writeJSON(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (e) {}
    }

    function buildKeys(prefix, userId, legacyNames) {
        var keys = [];
        var seen = {};

        function add(value) {
            var key = String(value || '').trim();
            if (!key || seen[key]) {
                return;
            }
            seen[key] = true;
            keys.push(prefix + key);
        }

        add(userId);
        (legacyNames || []).forEach(add);

        return keys;
    }

    function sellerAssetsKey(userId) {
        return ASSET_PREFIX + String(userId || '');
    }

    function sellerProfileKey(userId) {
        return PROFILE_PREFIX + String(userId || '');
    }

    function sellerSalesKey(userId) {
        return SALES_PREFIX + String(userId || '');
    }

    function libraryKey(userId) {
        return LIBRARY_PREFIX + String(userId || '');
    }

    /**
     * Ambil seluruh aset milik satu penjual, gabungkan kunci baru dan lama,
     * lalu simpan hasil gabungan ke kunci baru.
     */
    function readSellerAssets(userId, legacyNames) {
        var keys = buildKeys(ASSET_PREFIX, userId, legacyNames);
        var merged = [];
        var seen = {};

        keys.forEach(function (key) {
            var items = readJSON(key, []);
            if (!Array.isArray(items)) {
                return;
            }
            items.forEach(function (item) {
                if (!item || typeof item !== 'object') {
                    return;
                }
                var identity = String(item.id || item.title || '');
                if (!identity || seen[identity]) {
                    return;
                }
                seen[identity] = true;

                if (userId && !item.seller_user_id) {
                    item = Object.assign({}, item, { seller_user_id: String(userId) });
                }

                merged.push(item);
            });
        });

        if (userId && merged.length > 0) {
            writeJSON(sellerAssetsKey(userId), merged);
        }

        return merged;
    }

    /**
     * Gabungkan profil penjual dari kunci baru dan lama. Kunci user_id
     * diutamakan sehingga nilai terbaru tidak tertimpa data lama.
     */
    function readSellerProfile(userId, legacyNames) {
        var keys = buildKeys(PROFILE_PREFIX, userId, legacyNames).reverse();
        var merged = {};

        keys.forEach(function (key) {
            var profile = readJSON(key, null);
            if (profile && typeof profile === 'object' && !Array.isArray(profile)) {
                Object.keys(profile).forEach(function (field) {
                    merged[field] = profile[field];
                });
            }
        });

        if (userId && Object.keys(merged).length > 0) {
            writeJSON(sellerProfileKey(userId), merged);
        }

        return merged;
    }

    function readSellerSales(userId, legacyNames) {
        var keys = buildKeys(SALES_PREFIX, userId, legacyNames);
        var merged = [];
        var seen = {};

        keys.forEach(function (key) {
            var sales = readJSON(key, []);
            if (!Array.isArray(sales)) {
                return;
            }
            sales.forEach(function (sale) {
                if (!sale || typeof sale !== 'object') {
                    return;
                }
                var identity = String(sale.invoice_no || '') + '|' + String(sale.asset_title || sale.asset_id || '');
                if (seen[identity]) {
                    return;
                }
                seen[identity] = true;
                merged.push(sale);
            });
        });

        if (userId && merged.length > 0) {
            writeJSON(sellerSalesKey(userId), merged);
        }

        return merged;
    }

    function readLibrary(userId, legacyNames) {
        var keys = buildKeys(LIBRARY_PREFIX, userId, legacyNames);
        var merged = [];
        var seen = {};

        keys.forEach(function (key) {
            var items = readJSON(key, []);
            if (!Array.isArray(items)) {
                return;
            }
            items.forEach(function (item) {
                if (!item || typeof item !== 'object') {
                    return;
                }
                var identity = String(item.id || item.title || '');
                if (!identity || seen[identity]) {
                    return;
                }
                seen[identity] = true;
                merged.push(item);
            });
        });

        if (userId && merged.length > 0) {
            writeJSON(libraryKey(userId), merged);
        }

        return merged;
    }

    function readPublicCatalog() {
        var catalog = readJSON('indofx_public_catalog', []);
        return Array.isArray(catalog) ? catalog : [];
    }

    /**
     * Cari profil penjual berdasarkan nama toko. Dipakai halaman profil
     * kreator publik yang hanya mengetahui nama dari URL.
     */
    function findSellerProfileByName(name) {
        var target = String(name || '').trim().toLowerCase();
        if (!target) {
            return null;
        }

        var profileKeys = Object.keys(localStorage).filter(function (key) {
            return key.indexOf(PROFILE_PREFIX) === 0;
        });

        for (var i = 0; i < profileKeys.length; i++) {
            var profile = readJSON(profileKeys[i], null);
            if (profile && String(profile.store_name || '').trim().toLowerCase() === target) {
                return profile;
            }
        }

        return null;
    }

    /**
     * Ambil aset publik milik satu kreator berdasarkan nama toko.
     */
    function findPublicAssetsByCreator(name) {
        var target = String(name || '').trim().toLowerCase();
        if (!target) {
            return [];
        }

        return readPublicCatalog().filter(function (item) {
            return String(item.creator || item.seller || '').trim().toLowerCase() === target;
        });
    }

    /**
     * Cari penjual pemilik sebuah aset berdasarkan id atau judul.
     *
     * Dipakai halaman checkout milik pembeli yang tidak mengetahui user_id
     * penjual. Mengembalikan null bila aset tidak ditemukan.
     */
    function findSellerByAsset(assetId, title) {
        var assetKeys = Object.keys(localStorage).filter(function (key) {
            return key.indexOf(ASSET_PREFIX) === 0;
        });
        var fallback = null;

        for (var i = 0; i < assetKeys.length; i++) {
            var key = assetKeys[i];
            var items = readJSON(key, []);
            if (!Array.isArray(items)) {
                continue;
            }

            for (var j = 0; j < items.length; j++) {
                var item = items[j] || {};
                var sameId = assetId !== undefined && assetId !== null && assetId !== '' &&
                    String(item.id) === String(assetId);
                var sameTitle = title && String(item.title || '').trim() === String(title).trim();

                if (sameId || sameTitle) {
                    var match = {
                        key: key,
                        userId: String(item.seller_user_id || key.slice(ASSET_PREFIX.length)),
                        storeName: String(item.creator || item.seller || ''),
                        item: item
                    };

                    // Utamakan aset yang sudah menyimpan seller_user_id.
                    if (item.seller_user_id) {
                        return match;
                    }
                    if (!fallback) {
                        fallback = match;
                    }
                }
            }
        }

        return fallback;
    }

    window.IndoFXStorage = {
        normalizeStatus: normalizeStatus,
        sellerAssetsKey: sellerAssetsKey,
        sellerProfileKey: sellerProfileKey,
        sellerSalesKey: sellerSalesKey,
        libraryKey: libraryKey,
        readSellerAssets: readSellerAssets,
        readSellerProfile: readSellerProfile,
        readSellerSales: readSellerSales,
        readLibrary: readLibrary,
        readPublicCatalog: readPublicCatalog,
        findSellerProfileByName: findSellerProfileByName,
        findPublicAssetsByCreator: findPublicAssetsByCreator,
        findSellerByAsset: findSellerByAsset
    };
})(window);
