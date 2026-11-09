<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = 'Super Admin IndoFX';
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Kategori Aset - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheet Admin -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/admin/dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold fs-6">IndoFX</span>
            </a>

            <!-- Profil Admin Kanan & Dropdown Menu Logout Sesuai Figma -->
            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Layout Utama -->
    <div class="admin-layout-container">

        <!-- Sidebar Kiri Sesuai Figma image_1ee300 -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link active">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Utama Kanan -->
        <main class="admin-main-content">
            <!-- Header Judul & Tombol + Tambah Kategori -->
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                <h1 class="admin-page-title mb-0">Pengelolaan Kategori Aset</h1>
                <button type="button" class="btn-category-add" onclick="openAddCategoryModal()">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </button>
            </div>
            <p class="admin-page-sub">Atur kategori pengelompokan aset Unity yang tampil di katalog IndoFX.</p>

            <!-- Tabel Daftar Kategori Sesuai Figma image_1ee300 -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 8%;">ID</th>
                                <th scope="col" style="width: 38%;">Ikon &amp; Kategori</th>
                                <th scope="col" style="width: 26%;">Slug URL</th>
                                <th scope="col" style="width: 14%;">Total Aset</th>
                                <th scope="col" class="text-end pe-4" style="width: 14%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. MODAL 1: TAMBAH KATEGORI BARU (Sesuai Figma image_1ee2de) -->
    <div class="modal fade" id="modalAddCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header">
                    <h5 class="modal-category-title">Tambah Kategori Baru</h5>
                </div>
                <form id="formAddCategory" onsubmit="saveNewCategory(event)">
                    <div class="modal-category-body">
                        <!-- Nama Kategori -->
                        <div class="mb-3">
                            <label class="form-label-admin">Nama Kategori *</label>
                            <input type="text" id="addCategoryName" class="form-control admin-search-input" style="padding-left: 12px;" placeholder="Contoh: Audio / SFX" required oninput="autoGenerateAddSlug(this.value)">
                        </div>

                        <!-- Slug URL -->
                        <div class="mb-3">
                            <label class="form-label-admin">Slug URL *</label>
                            <div class="input-group">
                                <span class="input-group-text input-slug-prefix">/kategori/</span>
                                <input type="text" id="addCategorySlug" class="form-control input-slug-field" placeholder="audio-sfx" required>
                            </div>
                        </div>

                        <!-- Ikon Bootstrap -->
                        <div class="mb-3">
                            <label class="form-label-admin">Ikon Bootstrap</label>
                            <select id="addCategoryIcon" class="form-select admin-select">
                                <option value="bi-tags">bi-tags (Tag Umum)</option>
                                <option value="bi-stars">bi-stars (VFX Partikel)</option>
                                <option value="bi-palette">bi-palette (Shader &amp; Warna)</option>
                                <option value="bi-code-slash">bi-code-slash (Script / Tool)</option>
                                <option value="bi-box">bi-box (3D Model)</option>
                                <option value="bi-music-note-beamed">bi-music-note-beamed (Audio)</option>
                            </select>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="mb-1">
                            <label class="form-label-admin">Deskripsi Singkat</label>
                            <textarea id="addCategoryDesc" class="form-control admin-search-input" style="padding-left: 12px; height: 90px; resize: none;" placeholder="Uraian singkat kategori..."></textarea>
                        </div>
                    </div>
                    <div class="modal-category-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="bi bi-check2"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. MODAL 2: EDIT KATEGORI (Sesuai Figma image_1ee2de) -->
    <div class="modal fade" id="modalEditCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header">
                    <h5 class="modal-category-title">Edit Kategori</h5>
                </div>
                <form id="formEditCategory" onsubmit="saveEditCategory(event)">
                    <input type="hidden" id="editCategoryId">
                    <div class="modal-category-body">
                        <!-- Nama Kategori -->
                        <div class="mb-3">
                            <label class="form-label-admin">Nama Kategori *</label>
                            <input type="text" id="editCategoryName" class="form-control admin-search-input" style="padding-left: 12px;" required>
                        </div>

                        <!-- Slug URL -->
                        <div class="mb-3">
                            <label class="form-label-admin">Slug URL *</label>
                            <div class="input-group">
                                <span class="input-group-text input-slug-prefix">/kategori/</span>
                                <input type="text" id="editCategorySlug" class="form-control input-slug-field" required>
                            </div>
                        </div>

                        <!-- Ikon Bootstrap -->
                        <div class="mb-3">
                            <label class="form-label-admin">Ikon Bootstrap</label>
                            <select id="editCategoryIcon" class="form-select admin-select">
                                <option value="bi-tags">bi-tags (Tag Umum)</option>
                                <option value="bi-stars">bi-stars (VFX Partikel)</option>
                                <option value="bi-palette">bi-palette (Shader &amp; Warna)</option>
                                <option value="bi-code-slash">bi-code-slash (Script / Tool)</option>
                                <option value="bi-box">bi-box (3D Model)</option>
                                <option value="bi-music-note-beamed">bi-music-note-beamed (Audio)</option>
                            </select>
                        </div>

                        <!-- Deskripsi Singkat -->
                        <div class="mb-1">
                            <label class="form-label-admin">Deskripsi Singkat</label>
                            <textarea id="editCategoryDesc" class="form-control admin-search-input" style="padding-left: 12px; height: 90px; resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="modal-category-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-modal-submit">
                            <i class="bi bi-check2"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 5. MODAL 3: WARNING HAPUS KATEGORI (Sesuai Figma image_1ee2de) -->
    <div class="modal fade" id="modalDeleteCategory" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content modal-category-card text-center p-4">
                <input type="hidden" id="deleteCategoryId">

                <!-- Ikon Peringatan Segitiga Merah Figma -->
                <div class="delete-warning-icon-wrap">
                    <svg width="48" height="42" viewBox="0 0 48 42" fill="none">
                        <path d="M24 2L46 39H2L24 2Z" fill="#C92A2A" stroke="#C92A2A" stroke-width="2" stroke-linejoin="round" />
                        <line x1="24" y1="14" x2="24" y2="25" stroke="#FFFFFF" stroke-width="3.5" stroke-linecap="round" />
                        <circle cx="24" cy="32" r="2.2" fill="#FFFFFF" />
                    </svg>
                </div>

                <h5 class="delete-warning-title">Hapus Kategori?</h5>
                <p class="delete-warning-desc">
                    Apakah Anda yakin ingin menghapus kategori '<strong id="deleteCategoryNameDisplay">Shader</strong>'?
                </p>

                <div class="delete-warning-info">
                    <i class="bi bi-info-circle"></i>
                    <span>Peringatan: tindakan ini tidak dapat dibatalkan.</span>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn-modal-cancel" style="border: 1.5px solid #2B2623;" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-confirm-delete" onclick="confirmDeleteCategory()">
                        <i class="bi bi-trash3"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Footer Sesuai Desain Figma -->
    <footer class="footer-admin">
        <div class="footer-admin-content">
            <h5 class="footer-admin-title">IndoFX</h5>
            <p class="footer-admin-desc">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
        </div>
        <div class="footer-admin-bottom">
            <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
            <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Data Awal Kategori Sesuai Figma image_1ee300
        const defaultCategories = [{
                id: 1,
                name: 'Shader',
                desc: 'Shader Graph & HLSL custom Code',
                slug: 'shader',
                icon: 'bi-palette',
                count: '128 Aset'
            },
            {
                id: 2,
                name: 'VFX Partikel',
                desc: 'Visual Effects & Particle Systems',
                slug: 'vfx-partikel',
                icon: 'bi-stars',
                count: '94 Aset'
            },
            {
                id: 3,
                name: 'Script / Tool',
                desc: 'Editor Extensions & System Scripts',
                slug: 'script-tool',
                icon: 'bi-code-slash',
                count: '65 Aset'
            },
            {
                id: 4,
                name: '3D Model',
                desc: 'Environment, Character & Prop 3D',
                slug: '3d-model',
                icon: 'bi-box',
                count: '25 Aset'
            }
        ];

        let categories = [];
        let modalAddInstance = null;
        let modalEditInstance = null;
        let modalDeleteInstance = null;

        function loadCategories() {
            try {
                const raw = localStorage.getItem('indofx_admin_categories');
                if (raw) {
                    categories = JSON.parse(raw);
                } else {
                    categories = [...defaultCategories];
                    localStorage.setItem('indofx_admin_categories', JSON.stringify(categories));
                }
            } catch (e) {
                categories = [...defaultCategories];
            }
            renderCategoryTable();
        }

        function renderCategoryTable() {
            const tbody = document.getElementById('categoryTableBody');
            if (categories.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted small">
                            Belum ada kategori yang ditambahkan.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = categories.map((cat, index) => {
                return `
                    <tr>
                        <td class="text-muted fw-bold">${index + 1}</td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 0.88rem;">${cat.name}</div>
                            <div class="text-muted" style="font-size: 0.74rem;">${cat.desc || '-'}</div>
                        </td>
                        <td style="color: #c05e19; font-weight: 500;">/kategori/${cat.slug}</td>
                        <td>
                            <span class="badge-category-count">${cat.count || '0 Aset'}</span>
                        </td>
                        <td class="text-end pe-4 text-nowrap">
                            <button type="button" class="btn-category-edit me-1" onclick="openEditCategoryModal(${cat.id})">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button type="button" class="btn-category-delete" onclick="openDeleteCategoryModal(${cat.id})">
                                <i class="bi bi-trash3"></i> Hapus
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // AKSI 1: Buka Modal Tambah
        function openAddCategoryModal() {
            document.getElementById('formAddCategory').reset();
            modalAddInstance.show();
        }

        function autoGenerateAddSlug(val) {
            const clean = val.toLowerCase().trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-');
            document.getElementById('addCategorySlug').value = clean;
        }

        function saveNewCategory(e) {
            e.preventDefault();
            const name = document.getElementById('addCategoryName').value.trim();
            const slug = document.getElementById('addCategorySlug').value.trim();
            const icon = document.getElementById('addCategoryIcon').value;
            const desc = document.getElementById('addCategoryDesc').value.trim();

            if (!name || !slug) return;

            const newId = Date.now();
            categories.push({
                id: newId,
                name: name,
                desc: desc,
                slug: slug,
                icon: icon,
                count: '0 Aset'
            });

            localStorage.setItem('indofx_admin_categories', JSON.stringify(categories));
            renderCategoryTable();
            modalAddInstance.hide();
        }

        // AKSI 2: Buka Modal Edit
        function openEditCategoryModal(id) {
            const cat = categories.find(c => Number(c.id) === Number(id));
            if (!cat) return;

            document.getElementById('editCategoryId').value = cat.id;
            document.getElementById('editCategoryName').value = cat.name;
            document.getElementById('editCategorySlug').value = cat.slug;
            document.getElementById('editCategoryIcon').value = cat.icon || 'bi-tags';
            document.getElementById('editCategoryDesc').value = cat.desc || '';

            modalEditInstance.show();
        }

        function saveEditCategory(e) {
            e.preventDefault();
            const id = Number(document.getElementById('editCategoryId').value);
            const name = document.getElementById('editCategoryName').value.trim();
            const slug = document.getElementById('editCategorySlug').value.trim();
            const icon = document.getElementById('editCategoryIcon').value;
            const desc = document.getElementById('editCategoryDesc').value.trim();

            categories = categories.map(cat => {
                if (Number(cat.id) === id) {
                    return {
                        ...cat,
                        name: name,
                        slug: slug,
                        icon: icon,
                        desc: desc
                    };
                }
                return cat;
            });

            localStorage.setItem('indofx_admin_categories', JSON.stringify(categories));
            renderCategoryTable();
            modalEditInstance.hide();
        }

        // AKSI 3: Buka Modal Hapus
        function openDeleteCategoryModal(id) {
            const cat = categories.find(c => Number(c.id) === Number(id));
            if (!cat) return;

            document.getElementById('deleteCategoryId').value = cat.id;
            document.getElementById('deleteCategoryNameDisplay').innerText = cat.name;

            modalDeleteInstance.show();
        }

        function confirmDeleteCategory() {
            const id = Number(document.getElementById('deleteCategoryId').value);
            categories = categories.filter(c => Number(c.id) !== id);

            localStorage.setItem('indofx_admin_categories', JSON.stringify(categories));
            renderCategoryTable();
            modalDeleteInstance.hide();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalAddInstance = new bootstrap.Modal(document.getElementById('modalAddCategory'));
            modalEditInstance = new bootstrap.Modal(document.getElementById('modalEditCategory'));
            modalDeleteInstance = new bootstrap.Modal(document.getElementById('modalDeleteCategory'));

            loadCategories();

            // Dropdown Profil Admin
            const btn = document.getElementById('adminDropdownBtn');
            const menu = document.getElementById('adminDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>