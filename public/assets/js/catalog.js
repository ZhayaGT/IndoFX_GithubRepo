(function () {
  "use strict";

  var DATA_URL = "/assets/data/assets.json";
  var PAGE_SIZE = 6;

  /* Kelas warna tag untuk tiap render pipeline (lihat .badge-pipeline-* di style.css). */
  var PIPELINE_CLASSES = {
    URP: "badge-pipeline-urp",
    HDRP: "badge-pipeline-hdrp",
    "Built-in": "badge-pipeline-builtin",
    Builtin: "badge-pipeline-builtin",
  };

  function pipelineBadgeClass(pipeline) {
    if (!pipeline) return "";
    var pLower = String(pipeline).toLowerCase();
    if (pLower.includes("hdrp")) return PIPELINE_CLASSES.HDRP;
    if (pLower.includes("built")) return PIPELINE_CLASSES["Built-in"];
    return PIPELINE_CLASSES.URP;
  }

  function formatPrice(price) {
    return price === 0 ? "Gratis" : "Rp" + price.toLocaleString("id-ID");
  }

  function escapeHtml(text) {
    return String(text === null || text === undefined ? "" : text)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  // Mengembalikan string HTML satu kartu, mengikuti markup bawaan Figma Anda
  function createCard(item, colClass) {
    var col = colClass || "col-12 col-sm-6 col-lg-3";

    // Tangani pipeline baik jika berbentuk string maupun array
    var pipelines = Array.isArray(item.pipelines)
      ? item.pipelines
      : item.render_pipeline
        ? [item.render_pipeline]
        : ["URP"];
    var primaryPipeline = pipelines[0] || "URP";

    var pipelineBadgesHtml = pipelines
      .map(function (pipe) {
        return (
          '<span class="badge-pipeline ' +
          pipelineBadgeClass(pipe) +
          '">' +
          escapeHtml(pipe) +
          "</span>"
        );
      })
      .join(" ");

    // Thumbnail: Dukung file gambar nyata maupun SVG helper fallback
    var thumbHtml = "";
    if (
      item.image &&
      typeof item.image === "string" &&
      (item.image.includes("/") || item.image.startsWith("data:"))
    ) {
      thumbHtml =
        '<img class="asset-thumb-img" src="' +
        escapeHtml(item.image) +
        '" alt="' +
        escapeHtml(item.title) +
        '" loading="lazy">';
    } else {
      var artId =
        item.category === "Shader" ? "forest-mist-art" : "castle-sunset-art";
      thumbHtml =
        '<svg class="asset-thumb-img" viewBox="0 0 400 220" preserveAspectRatio="xMidYMid slice">' +
        '<use href="#' +
        artId +
        '" />' +
        "</svg>";
    }

    // Parameter dinamis agar halaman detail.php langsung mengenali identitas aset
    var detailUrl =
      "/asset/detail?slug=" +
      encodeURIComponent(item.slug || "") +
      "&id=" +
      encodeURIComponent(item.id || "") +
      "&title=" +
      encodeURIComponent(item.title || "") +
      "&category=" +
      encodeURIComponent(item.category || "") +
      "&creator=" +
      encodeURIComponent(item.seller_name || "") +
      "&price=" +
      encodeURIComponent(item.price !== undefined ? item.price : 0) +
      "&pipeline=" +
      encodeURIComponent(primaryPipeline);

    return (
      '<div class="' +
      col +
      '">' +
      '<a href="' +
      detailUrl +
      '" class="text-decoration-none d-block h-100">' +
      '<div class="asset-card-figma h-100 d-flex flex-column">' +
      '<div class="asset-thumb-wrapper">' +
      thumbHtml +
      "</div>" +
      '<div class="asset-card-body d-flex flex-column justify-content-between flex-grow-1">' +
      "<div>" +
      '<span class="asset-studio">' +
      escapeHtml(item.seller_name) +
      "</span>" +
      '<h6 class="asset-name">' +
      escapeHtml(item.title) +
      "</h6>" +
      '<div class="d-flex flex-wrap gap-1 mb-2">' +
      pipelineBadgesHtml +
      "</div>" +
      "</div>" +
      '<div class="d-flex justify-content-between align-items-center pt-1">' +
      '<span class="asset-price">' +
      formatPrice(item.price) +
      "</span>" +
      '<i class="bi bi-download asset-dl-icon"></i>' +
      "</div>" +
      "</div>" +
      "</div>" +
      "</a>" +
      "</div>"
    );
  }

  // Mengambil JSON bawaan lalu menggabungkannya dengan aset penjual yang disetujui dari LocalStorage
  function loadCatalog(callback) {
    // 1. Ambil aset tambahan penjual berstatus 'Disetujui' dari LocalStorage
    var approvedSellerAssets = [];
    try {
      var rawPublic = localStorage.getItem("indofx_public_catalog");
      if (rawPublic) {
        var parsed = JSON.parse(rawPublic);
        if (Array.isArray(parsed)) {
          approvedSellerAssets = parsed.map(function (item) {
            // Normalisasi harga menjadi angka integer
            var numericPrice = 0;
            if (typeof item.price === "number") {
              numericPrice = item.price;
            } else if (typeof item.price === "string") {
              var clean = item.price.replace(/[^0-9]/g, "");
              numericPrice = clean ? parseInt(clean, 10) : 0;
            }

            return {
              id: item.id || "asset-" + Date.now(),
              slug: item.slug || item.id || "asset-item",
              title: item.title || "Aset Tanpa Judul",
              seller_name:
                item.seller ||
                item.creator ||
                item.seller_name ||
                "Arga Studio",
              category: item.category || "VFX Partikel",
              short_description:
                item.description || item.short_description || "",
              price: numericPrice,
              render_pipeline: Array.isArray(item.pipelines)
                ? item.pipelines[0] || "URP"
                : item.render_pipeline || "URP",
              pipelines: Array.isArray(item.pipelines)
                ? item.pipelines
                : [item.render_pipeline || "URP"],
              image: item.image || "",
              download_count: parseInt(item.downloads, 10) || 0,
              created_at:
                item.created_at || new Date().toISOString().split("T")[0],
            };
          });
        }
      }
    } catch (e) {
      approvedSellerAssets = [];
    }

    // 2. Fetch aset statis awal dari file JSON
    fetch(DATA_URL)
      .then(function (res) {
        if (!res.ok) {
          throw new Error("HTTP " + res.status);
        }
        return res.json();
      })
      .then(function (payload) {
        var baseItems = payload.data || [];
        // Gabungkan: Aset penjual yang baru disetujui ditaruh di depan agar langsung terlihat
        var merged = approvedSellerAssets.concat(baseItems);
        callback(null, merged);
      })
      .catch(function (err) {
        // Fallback jika file JSON gagal dimuat: tetap tampilkan aset dari penjual
        if (approvedSellerAssets.length > 0) {
          callback(null, approvedSellerAssets);
        } else {
          callback(err, []);
        }
      });
  }

  function showMessage(container, text) {
    if (!container) {
      return;
    }
    container.innerHTML =
      '<div class="alert alert-light border text-center text-muted mb-0 py-4 w-100">' +
      escapeHtml(text) +
      "</div>";
  }

  function renderCards(container, items, colClass) {
    if (!container) {
      return;
    }
    if (!items.length) {
      showMessage(container, "Belum ada aset yang cocok.");
      return;
    }
    container.innerHTML = items
      .map(function (item) {
        return createCard(item, colClass);
      })
      .join("");
  }

  function byCreatedDesc(a, b) {
    if (a.created_at === b.created_at) {
      return 0;
    }
    return a.created_at < b.created_at ? 1 : -1;
  }

  function byDownloadsDesc(a, b) {
    return (b.download_count || 0) - (a.download_count || 0);
  }

  function renderLatest() {
    var container = document.querySelector('[data-catalog-list="latest"]');
    if (!container) return;

    loadCatalog(function (err, items) {
      if (err) {
        showMessage(container, "Gagal memuat katalog. Muat ulang halaman.");
        return;
      }
      renderCards(container, items.slice().sort(byCreatedDesc).slice(0, 4));
    });
  }

  function renderFree() {
    var container = document.querySelector('[data-catalog-list="free"]');
    if (!container) return;

    loadCatalog(function (err, items) {
      if (err) {
        showMessage(container, "Gagal memuat katalog. Muat ulang halaman.");
        return;
      }
      var free = items.filter(function (item) {
        return item.price === 0;
      });
      renderCards(container, free.sort(byDownloadsDesc).slice(0, 4));
    });
  }

  /* Nilai <option> pada .sort-select yang dikenal renderExplore(). */
  var SORT_VALUES = ["terbaru", "populer", "harga_rendah", "harga_tinggi"];

  /**
   * Baca keadaan awal halaman dari query string.
   */
  function readExploreParams() {
    var params = new URLSearchParams(window.location.search);
    var sort = params.get("sort");

    return {
      sort: SORT_VALUES.indexOf(sort) === -1 ? "terbaru" : sort,
      gratis: params.get("gratis") === "1",
      query: (params.get("q") || "").trim(),
    };
  }

  function matchesQuery(item, query) {
    if (!query) {
      return true;
    }

    var haystack = [
      item.title,
      item.seller_name,
      item.category,
      item.short_description,
    ]
      .join(" ")
      .toLowerCase();
    var words = haystack.split(/[^a-z0-9]+/).filter(Boolean);
    var tokens = query.toLowerCase().split(/\s+/).filter(Boolean);

    return tokens.every(function (token) {
      return words.some(function (word) {
        return word.indexOf(token) === 0;
      });
    });
  }

  function renderExplore() {
    var listEl = document.querySelector('[data-catalog-list="explore"]');
    if (!listEl) return;

    var countEl = document.querySelector("[data-catalog-count]");
    var paginationEl = document.querySelector("[data-catalog-pagination]");
    var sortSelect = document.querySelector(".sort-select");
    var freeRadio = document.getElementById("price_free_radio");
    var resetLink = document.querySelector(".filter-reset-link");

    // Filter tambahan (kategori, rentang harga, pipeline)
    var catShader = document.getElementById("cat_shader");
    var catParticle = document.getElementById("cat_particle");
    var pipeBuiltin = document.getElementById("pipe_builtin");
    var pipeUrp = document.getElementById("pipe_urp");
    var pipeHdrp = document.getElementById("pipe_hdrp");
    var minPriceInput = document.getElementById("minPrice");
    var maxPriceInput = document.getElementById("maxPrice");

    var initial = readExploreParams();
    var allItems = [];
    var currentSort = initial.sort;
    var freeOnly = initial.gratis;
    var currentQuery = initial.query;
    var currentPage = 1;
    var colClass = "col-12 col-sm-6 col-md-4";

    function syncUrl() {
      var params = new URLSearchParams(window.location.search);

      if (currentSort === "terbaru") {
        params.delete("sort");
      } else {
        params.set("sort", currentSort);
      }

      if (freeOnly) {
        params.set("gratis", "1");
      } else {
        params.delete("gratis");
      }

      if (currentQuery) {
        params.set("q", currentQuery);
      } else {
        params.delete("q");
      }

      var query = params.toString();
      window.history.replaceState(
        null,
        "",
        window.location.pathname + (query ? "?" + query : ""),
      );
    }

    function applySort(items, sort) {
      if (sort === "terbaru") {
        return items.slice().sort(byCreatedDesc);
      }
      if (sort === "populer") {
        return items.slice().sort(byDownloadsDesc);
      }
      if (sort === "harga_rendah") {
        return items.slice().sort(function (a, b) {
          return a.price - b.price;
        });
      }
      if (sort === "harga_tinggi") {
        return items.slice().sort(function (a, b) {
          return b.price - a.price;
        });
      }
      return items;
    }

    function buildPagination(totalPages) {
      if (!paginationEl) {
        return;
      }

      var html =
        '<a href="javascript:void(0)" data-page-prev class="page-nav-link ' +
        (currentPage === 1 ? "text-muted" : "text-dark fw-bold") +
        '">&laquo; Sebelumnya</a>';

      for (var page = 1; page <= totalPages; page++) {
        html +=
          '<a href="javascript:void(0)" data-page="' +
          page +
          '" class="page-num-link' +
          (page === currentPage ? " active" : "") +
          '">' +
          page +
          "</a>";
      }

      html +=
        '<a href="javascript:void(0)" data-page-next class="page-nav-link ' +
        (currentPage === totalPages ? "text-muted" : "text-dark fw-bold") +
        '">Selanjutnya &raquo;</a>';

      paginationEl.innerHTML = html;
    }

    function paint() {
      var minVal = minPriceInput ? parseFloat(minPriceInput.value) || 0 : 0;
      var maxVal = maxPriceInput
        ? parseFloat(maxPriceInput.value) || Infinity
        : Infinity;

      var visible = allItems.filter(function (item) {
        // Filter Radio Gratis
        if (freeOnly && item.price !== 0) {
          return false;
        }

        // Filter Rentang Harga (Min - Max)
        if (!freeOnly && (item.price < minVal || item.price > maxVal)) {
          return false;
        }

        // Filter Kategori Checkbox
        var checkShader = catShader && catShader.checked;
        var checkParticle = catParticle && catParticle.checked;
        if (checkShader || checkParticle) {
          var itemCat = (item.category || "").toLowerCase();
          var isShader = itemCat.includes("shader");
          var isParticle =
            itemCat.includes("partikel") ||
            itemCat.includes("vfx") ||
            itemCat.includes("particle");
          if (checkShader && !checkParticle && !isShader) return false;
          if (checkParticle && !checkShader && !isParticle) return false;
        }

        // Filter Render Pipeline Checkbox
        var chkBuiltin = pipeBuiltin && pipeBuiltin.checked;
        var chkUrp = pipeUrp && pipeUrp.checked;
        var chkHdrp = pipeHdrp && pipeHdrp.checked;
        if (chkBuiltin || chkUrp || chkHdrp) {
          var pipes = (
            Array.isArray(item.pipelines)
              ? item.pipelines
              : [item.render_pipeline]
          ).map(function (p) {
            return String(p).toLowerCase();
          });

          var match = false;
          if (
            chkBuiltin &&
            pipes.some(function (p) {
              return p.includes("built");
            })
          )
            match = true;
          if (
            chkUrp &&
            pipes.some(function (p) {
              return p.includes("urp");
            })
          )
            match = true;
          if (
            chkHdrp &&
            pipes.some(function (p) {
              return p.includes("hdrp");
            })
          )
            match = true;
          if (!match) return false;
        }

        // Filter Pencarian Teks
        return matchesQuery(item, currentQuery);
      });

      var sorted = applySort(visible, currentSort);
      var totalPages = Math.max(1, Math.ceil(sorted.length / PAGE_SIZE));

      if (currentPage > totalPages) {
        currentPage = totalPages;
      }
      if (currentPage < 1) {
        currentPage = 1;
      }

      if (countEl) {
        countEl.textContent = String(sorted.length);
      }

      if (!sorted.length) {
        showMessage(listEl, "Belum ada aset yang cocok.");
        if (paginationEl) {
          paginationEl.innerHTML = "";
        }
        return;
      }

      var start = (currentPage - 1) * PAGE_SIZE;
      renderCards(listEl, sorted.slice(start, start + PAGE_SIZE), colClass);
      buildPagination(totalPages);
    }

    if (paginationEl) {
      paginationEl.addEventListener("click", function (event) {
        var link = event.target.closest("a");
        if (!link || !paginationEl.contains(link)) {
          return;
        }
        event.preventDefault();

        var target = null;
        if (link.hasAttribute("data-page-prev")) {
          target = currentPage - 1;
        } else if (link.hasAttribute("data-page-next")) {
          target = currentPage + 1;
        } else if (link.hasAttribute("data-page")) {
          target = parseInt(link.getAttribute("data-page"), 10);
        }

        if (target === null || isNaN(target) || target === currentPage) {
          return;
        }
        currentPage = target;
        paint();
      });
    }

    if (sortSelect) {
      sortSelect.value = currentSort;

      sortSelect.addEventListener("change", function () {
        currentSort = sortSelect.value;
        currentPage = 1;
        paint();
        syncUrl();
      });
    }

    if (freeRadio) {
      freeRadio.checked = freeOnly;

      freeRadio.addEventListener("change", function () {
        freeOnly = freeRadio.checked;
        currentPage = 1;
        paint();
        syncUrl();
      });
    }

    // Pasang listener pada checkbox dan filter input samping
    [catShader, catParticle, pipeBuiltin, pipeUrp, pipeHdrp].forEach(
      function (el) {
        if (el) {
          el.addEventListener("change", function () {
            currentPage = 1;
            paint();
          });
        }
      },
    );

    [minPriceInput, maxPriceInput].forEach(function (el) {
      if (el) {
        el.addEventListener("input", function () {
          currentPage = 1;
          paint();
        });
      }
    });

    if (resetLink) {
      resetLink.addEventListener("click", function (event) {
        event.preventDefault();

        freeOnly = false;
        currentSort = "terbaru";
        currentQuery = "";
        currentPage = 1;

        if (freeRadio) freeRadio.checked = false;
        if (catShader) catShader.checked = false;
        if (catParticle) catParticle.checked = false;
        if (pipeBuiltin) pipeBuiltin.checked = false;
        if (pipeUrp) pipeUrp.checked = false;
        if (pipeHdrp) pipeHdrp.checked = false;
        if (minPriceInput) minPriceInput.value = "";
        if (maxPriceInput) maxPriceInput.value = "";

        if (sortSelect) {
          sortSelect.value = currentSort;
        }

        var searchInput = document.querySelector(".search-input-header");
        if (searchInput) {
          searchInput.value = "";
        }

        paint();
        syncUrl();
      });
    }

    loadCatalog(function (err, items) {
      if (err) {
        showMessage(listEl, "Gagal memuat katalog. Muat ulang halaman.");
        return;
      }
      allItems = items;
      paint();
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector('[data-catalog-list="latest"]')) {
      renderLatest();
    }
    if (document.querySelector('[data-catalog-list="free"]')) {
      renderFree();
    }
    if (document.querySelector('[data-catalog-list="explore"]')) {
      renderExplore();
    }
  });
})();
