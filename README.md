# IndoFX

Platform marketplace & komunitas aset digital Unity CG dan VFX untuk pengembang game, 3D/VFX artist, dan mahasiswa di Indonesia.

**Tech stack:** PHP native (PDO) · Bootstrap 5 · PostgreSQL · JavaScript · Midtrans Snap

---

## Struktur Folder

```
IndoFX/
├── .github/                     # Konfigurasi kolaborasi GitHub
│   ├── workflows/ci.yml         # CI: lint PHP + pengujian
│   ├── ISSUE_TEMPLATE/          # Template bug, fitur, tugas
│   ├── PULL_REQUEST_TEMPLATE.md # Checklist wajib saat membuka PR
│   └── CODEOWNERS               # Pemilik folder per peran
│
├── app/                         # [BACK END] Logika aplikasi
│   ├── Config/                  # app.php, database.php, midtrans.php, mail.php
│   ├── Core/                    # Router, Request, Database, Session, Auth, Csrf, Validator, View
│   ├── Controllers/             # Auth, Asset, Checkout, Payment, Invoice, Download, Admin
│   ├── Models/                  # User, Asset, Transaction, Entitlement, Review
│   ├── Services/                # MidtransService, MailService, InvoiceService, StorageService
│   ├── Middleware/              # Auth, Role, Csrf
│   └── Helpers/                 # format.php, markdown.php
│
├── routes/
│   └── web.php                  # [BACK END] Peta URL aplikasi
│
├── resources/                   # Sumber tampilan
│   ├── views/                   # [FRONT END] Template PHP
│   │   ├── layouts/             # Kerangka halaman + navbar/footer Bootstrap
│   │   ├── partials/            # Komponen berulang: kartu aset, badge pipeline, pagination
│   │   ├── auth/                # Masuk, daftar, lupa sandi
│   │   ├── catalog/             # Beranda, katalog, pencarian, filter
│   │   ├── asset/               # Detail aset, preview media, ulasan
│   │   ├── checkout/            # Keranjang, checkout, tombol Midtrans Snap
│   │   ├── invoice/             # Invoice ramah cetak (@media print)
│   │   ├── dashboard/           # Dashboard pembeli & penjual
│   │   ├── admin/               # Panel admin
│   │   ├── profile/             # Profil & pengaturan akun
│   │   └── errors/              # 404, 403, 500
│   ├── css/                     # [UI/UX] SCSS/CSS sumber sebelum dikompilasi
│   └── js/                      # [FRONT END] JS sumber (modular)
│
├── public/                      # Satu-satunya folder yang diakses web
│   ├── index.php                # Front controller
│   ├── .htaccess                # Rewrite + header keamanan
│   └── assets/                  # [FRONT END] Aset siap saji
│       ├── css/                 # Hasil kompilasi
│       ├── js/                  # Hasil kompilasi
│       ├── img/
│       └── vendor/              # Bootstrap, ikon (via Composer/npm)
│
├── database/                    # [BACK END]
│   ├── schema.sql               # Skema lengkap PostgreSQL
│   ├── migrations/              # Perubahan skema bernomor
│   └── seeders/                 # Data awal & data uji
│
├── storage/                     # Runtime, tidak di-commit
│   ├── logs/
│   ├── cache/
│   ├── invoices/                # Arsip invoice
│   └── uploads/tmp/             # Unggahan sementara sebelum ke Google Drive
│
├── tests/
│   ├── Feature/                 # Uji alur: checkout, webhook, unduhan
│   └── Unit/                    # Uji fungsi: validasi, format harga
│
├── design/                      # [UI/UX]
│   ├── wireframe/               # Wireframe tiap halaman
│   ├── prototype/               # Prototipe interaktif
│   └── design-system/           # Palet warna, tipografi, komponen
│
├── docs/                        # [PM] Dokumentasi proyek
│   ├── arsitektur.md
│   ├── erd.md
│   ├── api.md
│   ├── setup.md
│   └── panduan-kontribusi.md
│
├── .env.example                 # Contoh variabel lingkungan
├── .gitignore
├── composer.json
├── CONTRIBUTING.md              # Aturan kolaborasi GitHub
└── README.md
```

---

## Pembagian Peran

Setiap anggota memiliki satu peran utama dan **folder yang menjadi tanggung jawabnya**. Ini mencegah dua orang mengedit berkas yang sama.

| Peran | Penanggung jawab | Folder utama |
|---|---|---|
| Project Manager | Jonathan Emmanual Kristanto | `docs/`, `.github/`, `README.md` |
| UI/UX Designer | Hikmal Fadhillah Rasyid Andro Mulyawan | `design/`, `resources/css/` |
| UI/UX Designer | Dhafir Tsabit | `design/`, `resources/css/` |
| Front End Developer | Naufal Haidar Azmi | `resources/views/`, `resources/js/`, `public/assets/` |
| Back End Developer | Diaz Prahardyan | `app/`, `routes/`, `database/`, `tests/` |

Area milik peran lain boleh dibaca, tetapi perubahan harus melalui pull request dan persetujuan pemiliknya.

### Kontributor

| Nama | Akun GitHub | Kontribusi |
|---|---|---|
| Jonathan Emmanual Kristanto | [@ZhayaGT](https://github.com/ZhayaGT) | Project manager; navbar auth, katalog beranda dan eksplorasi, detail aset, pencarian, integrasi branch |
| Diaz Prahardyan | [@Dzy-hue](https://github.com/Dzy-hue) | Back end: PDO wrapper, router, autentikasi login/register/logout, skema `database/Script marketplace_game_assets.sql` (tabel, seed, view `vw_katalog_publik`, trigger entitlement, fungsi royalti), `HomeController` |
| Naufal Haidar Azmi | [@naufal2323](https://github.com/naufal2323) | Front end: halaman login dan register, eksplorasi katalog, styling |
| Hikmal Fadhillah Rasyid Andro Mulyawan | - | UI/UX design |
| Dhafir Tsabit | - | UI/UX design |

Riwayat kontribusi per orang bisa dilihat dengan `git log --author="<nama>"`.

---

## Aturan Kolaborasi GitHub (Ringkasan)

Aturan lengkap ada di **[CONTRIBUTING.md](CONTRIBUTING.md)**.

### Branch

| Branch | Fungsi |
|---|---|
| `main` | Stabil, siap demo. Dilarang push langsung. |
| `develop` | Gabungan fitur sebelum rilis. |
| `feat/*`, `fix/*`, `docs/*`, `chore/*`, `design/*` | Branch kerja sementara. |

Pola: `<tipe>/<peran>-<topik>` → contoh `feat/be-filter-pipeline`, `design/ux-wireframe-checkout`.

### Commit

Conventional Commits: `<tipe>(<lingkup>): <deskripsi>`

```
feat(be): tambah endpoint filter render pipeline
feat(fe): integrasi Midtrans Snap pada checkout
fix(be): cegah entitlement ganda saat webhook berulang
docs(pm): tambah langkah konfigurasi Midtrans
design(ux): tambah wireframe halaman invoice
```

### Alur Pull Request

```
develop ──► feat/be-filter-pipeline ──► PR ke develop ──► review 1 orang ──► merge
```

1. Buat branch dari `develop` terbaru.
2. Kerjakan dan uji lokal.
3. Buka PR ke `develop`, isi template lengkap, tautkan issue (`Closes #12`).
4. Minta review pemilik area (lihat `CODEOWNERS`).
5. Merge setelah **1 persetujuan** dan CI hijau.
6. Hapus branch.

### Aturan Wajib

1. Jangan pernah menulis kredensial di kode. Gunakan `.env`.
2. Jangan mengubah `main` secara langsung.
3. Perubahan skema database harus diumumkan sebelum digabungkan.
4. Query wajib memakai **prepared statement PDO**; output di-escape dengan `htmlspecialchars`.
5. Satu PR = satu pekerjaan. PR besar dipecah.
6. Semua teks antarmuka berbahasa Indonesia.

---

## Menyiapkan Lingkungan Lokal

```bash
# 1. Clone
git clone https://github.com/OWNER/IndoFX.git && cd IndoFX

# 2. Dependensi PHP
composer install

# 3. Variabel lingkungan
cp .env.example .env
# isi DB_PASSWORD, MIDTRANS_*, MAIL_*, dll.

# 4. Siapkan database PostgreSQL (skema + data contoh)
createdb indofx
psql -d indofx -f "database/Script marketplace_game_assets.sql"

# 5. Jalankan server pengembangan
composer serve          # http://localhost:8000

# 6. Perintah lain
composer lint           # cek gaya kode PSR-12
composer lint:fix       # perbaiki otomatis
composer test           # jalankan PHPUnit (butuh role PostgreSQL dengan hak CREATEDB)
```

Skrip `database/Script marketplace_game_assets.sql` membuat seluruh tabel, mengisi 5 kategori,
5 pengguna, 11 aset contoh, berkas dan gambar aset, transaksi, serta view dan trigger pelaporan.
Skrip aman dijalankan berulang (`IF NOT EXISTS` + `ON CONFLICT DO NOTHING`).

Akun contoh: `admin@assetforge.io` / `admin12345`, `budi.studio@gmail.com` / `seller12345`,
`dimas.dev@indiegame.com` / `buyer12345`.

### Gambar dan penyimpanan berkas

Gambar contoh disimpan di `public/assets/img/` sebagai JPG/PNG. Path di database bersifat
relatif (mis. `assets/img/catalog/01.jpg`) dan diubah menjadi URL oleh
`IndoFX\Helpers\Storage`. Bila nanti memakai Supabase Storage, cukup isi
`STORAGE_BASE_URL` di `.env` dengan URL bucket publik; tidak ada kode lain yang perlu diubah.

---

## Alur Kerja Harian

```bash
git checkout develop && git pull
git checkout -b feat/be-nama-fitur
# ...kerjakan...
git add .
git commit -m "feat(be): deskripsi singkat"
git push -u origin feat/be-nama-fitur
# buka Pull Request ke develop
```
