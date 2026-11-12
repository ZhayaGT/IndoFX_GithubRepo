# Rencana Perbaikan Bug Tampilan Tambahan

Status: **tertunda** — dikerjakan setelah Fase 2 (konsistensi UI/responsif) dan
Fase 3 (escaping/XSS) selesai.

Ranah: hanya `resources/views/**`, `public/assets/js/**`, `public/assets/css/**`.
Tidak menyentuh `app/`, `routes/`, `database/`, `tests/`.

---

## A. Admin

### A1. Modal detail antrean aset terbaru hanya menampilkan informasi
File: `resources/views/admin/dashboard.php`

- Modal `#modalReviewAsset` (`:173-241`):
  - Pertahankan judul aset, nama penjual, harga.
  - Tambah blok deskripsi (`reviewAssetDescription`).
  - Hapus badge pipeline/kategori, berkas paket, ukuran file, dan textarea alasan.
  - Footer diganti satu tombol `Detail Aset` yang mengarah ke
    `/admin/assets/review?id=<id>`.
- `openReviewModal()` (`:611`):
  - Isi deskripsi dari `item.description`.
  - Set `reviewDetailLink.href`.
  - Hapus pengisian field yang sudah dihapus.
- Saat memindai `indofx_seller_assets_*` (`:422`), sertakan `description`.
- `handleModerationApprove()` (`:628`) dan `handleModerationReject()` (`:696`)
  menjadi tidak terpakai; hapus agar tidak membingungkan.

### A2. Badge sidebar Kelola Kategori masih statis
File: `resources/views/admin/categories.php`

- Tambah `id` pada badge (`:76`, `:86`): `sidebarModerationBadge`,
  `sidebarSellerBadge`.
- Muat `/assets/js/indofx-storage.js` setelah Bootstrap (`:294`).
- Hitung jumlah `Menunggu` dari `indofx_moderation_queue` + aset penjual, dan
  jumlah pengajuan penjual berstatus `Menunggu`.
- Rekomendasi: tambah helper `countPendingModeration()` dan
  `countPendingSellerRequests()` di `indofx-storage.js`.

### A3. Tombol edit akun admin harus nonaktif
File: `resources/views/admin/users.php`

- Render tombol (`:628`): jika `role === 'Admin'`, tambahkan `disabled`,
  `aria-disabled="true"`, dan class `is-disabled`.
- Guard di `openEditUserModal()` (`:673`): return bila role admin.
- Style `.btn-edit-user.is-disabled` di `public/assets/css/admin.css`.

---

## B. Penjual

### B1. Tombol Edit di dashboard utama membuka form edit
File: `resources/views/dashboard/main.php:356`

Ubah `href="/dashboard/assets"` menjadi
`/dashboard/add-asset?edit_id=<id>`.

### B2. Form edit/revisi auto-fill
File: `resources/views/dashboard/add_asset.php`

- Lengkapi pengisian: judul, kategori, deskripsi, panduan, video, versi Unity,
  pipeline, dan harga (berbayar/gratis).
- Tampilkan pratinjau gambar lama dan nama berkas lama.
- Jika `edit_id` tidak ditemukan, tampilkan peringatan dan nonaktifkan submit.

### B3. Judul form dibedakan
- `pageFormTitle` menjadi "Edit Aset" saat mode edit.
- `document.title` ikut berubah.
- Pastikan semua tautan edit memakai `?edit_id=`.

### B4. Animasi loading pada tombol search dashboard
File: `resources/views/dashboard/dashboard.php`

- `#btnApplyFilter`: spinner + disable saat memproses, pulihkan setelah render.
- Debounce 250 ms pada `#filterKeyword`.
- Opsional: tampilkan jumlah hasil.

---

## C. Halaman Keranjang

File: `resources/views/cart/keranjang.php`

### C1. Responsif
- Media query 768px: kartu item wrap, thumbnail full width, tombol checkout
  full width, judul lebih kecil.
- Media query 480px: judul aset dan tombol hapus lebih ringkas.

### C2. Dropdown konsisten dengan navbar lain
- Ganti mekanisme `.show` menjadi atribut `hidden` + `aria-expanded`.
- Tambah handler `Escape`.
- Samakan token visual dengan `.navbar-avatar-menu`.
- Samakan daftar menu: Edit Profile → `/dashboard/ubahprofile`, tambah
  `Library Saya`.
- Sembunyikan teks nama pada trigger di layar ≤576px.

---

## D. Strategi branch (rekomendasi)

| PR | Branch | Isi |
|---|---|---|
| PR-A | `fix/fe-admin-ui-followup` | A1, A2, A3 |
| PR-B | `fix/fe-seller-ui-followup` | B1, B2, B3, B4 |
| PR-C | `fix/fe-cart-responsive` | C1, C2 |

## E. Verifikasi

1. `php -l` semua view yang berubah.
2. Parser inline `<script>` bebas error.
3. `node --check` untuk JS yang berubah.
4. `composer test` tetap hijau.
5. Smoke test HTTP 200 untuk halaman admin, penjual, dan keranjang.
6. Uji manual sesuai butir A/B/C.
