/**
 * Pergantian tampilan navbar: guest vs pembeli + sinkronisasi badge keranjang dinamis.
 */

(function () {
  "use strict";

  var guestNav = document.getElementById("nav-guest");
  var pembeliNav = document.getElementById("nav-pembeli");

  /** Ambil userKey aktif dari atribut data-user-key pada .navbar-auth */
  function getActiveUserKey() {
    var container = document.querySelector(".navbar-auth");
    return container
      ? (container.getAttribute("data-user-key") || "").trim()
      : "";
  }

  /** Perbarui angka kecil pada ikon keranjang di navbar sesuai user yang login */
  function updateGlobalCartBadge() {
    var badge = document.getElementById("globalCartBadge");
    if (!badge) return;

    var userKey = getActiveUserKey();
    if (!userKey) {
      badge.textContent = "0";
      badge.classList.remove("show");
      return;
    }

    var count = 0;
    try {
      var raw = localStorage.getItem("indofx_cart_" + userKey);
      var items = raw ? JSON.parse(raw) : [];
      if (Array.isArray(items)) {
        count = items.length;
      }
    } catch (e) {
      count = 0;
    }

    if (count > 0) {
      badge.textContent = count > 99 ? "99+" : String(count);
      badge.classList.add("show");
    } else {
      badge.textContent = "0";
      badge.classList.remove("show");
    }
  }

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
    updateGlobalCartBadge();
  }

  /**
   * Baca keadaan dari HTML lalu tampilkan div yang sesuai.
   * @param {string} [state] "guest" atau "pembeli"; kosong = baca dari atribut.
   */
  function syncNavbarAuth(state) {
    var container = document.querySelector(".navbar-auth");

    if (typeof state === "string" && container) {
      container.setAttribute("data-auth-state", state);
    }

    var current = container
      ? container.getAttribute("data-auth-state")
      : "guest";

    if (current === "pembeli") {
      showPembeliNav();
    } else {
      showGuestNav();
    }
  }

  /** Buka/tutup menu kecil di avatar (listener tunggal tanpa bentrok). */
  function initAvatarMenu() {
    var button = document.getElementById("navbar-avatar");
    var menu = document.getElementById("navbar-avatar-menu");

    if (!button || !menu) {
      return;
    }

    button.addEventListener("click", function (event) {
      event.preventDefault();
      event.stopPropagation();
      var isOpen = !menu.hidden;
      menu.hidden = isOpen;
      button.setAttribute("aria-expanded", String(!isOpen));
    });

    document.addEventListener("click", function (event) {
      if (!menu.contains(event.target) && !button.contains(event.target)) {
        menu.hidden = true;
        button.setAttribute("aria-expanded", "false");
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        menu.hidden = true;
        button.setAttribute("aria-expanded", "false");
      }
    });
  }

  /** Deteksi perubahan keranjang secara langsung di tab yang sama maupun antar-tab */
  function initCartStorageWatcher() {
    var origSetItem = localStorage.setItem;
    localStorage.setItem = function (key, value) {
      origSetItem.apply(this, arguments);
      if (typeof key === "string" && key.indexOf("indofx_cart_") === 0) {
        updateGlobalCartBadge();
      }
    };

    var origRemoveItem = localStorage.removeItem;
    localStorage.removeItem = function (key) {
      origRemoveItem.apply(this, arguments);
      if (typeof key === "string" && key.indexOf("indofx_cart_") === 0) {
        updateGlobalCartBadge();
      }
    };

    window.addEventListener("storage", function (e) {
      if (e.key && e.key.indexOf("indofx_cart_") === 0) {
        updateGlobalCartBadge();
      }
    });
  }

  initCartStorageWatcher();

  document.addEventListener("DOMContentLoaded", function () {
    syncNavbarAuth();
    initAvatarMenu();
    updateGlobalCartBadge();
  });

  window.showGuestNav = showGuestNav;
  window.showPembeliNav = showPembeliNav;
  window.syncNavbarAuth = syncNavbarAuth;
  window.updateGlobalCartBadge = updateGlobalCartBadge;
})();
