# Panduan Kontribusi IndoFX

Aturan kolaborasi repositori IndoFX. Wajib dibaca sebelum membuat branch pertama.

## 1. Peran dan Kepemilikan Folder

Setiap orang memiliki **satu peran utama**. Untuk menghindari konflik merge, patuhi area kepemilikan berikut.

| Peran | Penanggung jawab | Folder utama yang dimiliki |
|---|---|---|
| Project Manager | Jonathan Emmanual Kristanto | `docs/`, `.github/`, `README.md`, `CHANGELOG.md` |
| UI/UX Designer | Hikmal Fadhillah Rasyid Andro Mulyawan | `design/`, `resources/css/`, `resources/views/**` (struktur & istilah) |
| Front End Developer | Naufal Haidar Azmi | `resources/views/**`, `resources/js/`, `public/assets/**` |
| Back End Developer | Diaz Prahardyan | `app/**`, `routes/`, `database/**`, `public/index.php`, `tests/**` |

Aturan:

1. Area milik peran lain **boleh dibaca**, tetapi perubahan harus lewat pull request dan persetujuan pemiliknya.
2. Perubahan yang menyentuh dua area sekaligus (misal `resources/views` + `app/Controllers`) dikerjakan bersama di satu branch, dengan pemilik area sebagai peninjau.
3. `database/schema.sql` dan `database/migrations/**` adalah area Back End. Perubahan skema **wajib diumumkan** ke grup sebelum digabungkan.
4. `app/Config/**` dan `.env.example` hanya boleh diubah Back End.

## 2. Strategi Branch

Branch tetap:

| Branch | Fungsi |
|---|---|
| `main` | Selalu stabil dan bisa dijalankan. Dilarang push langsung. |
| `develop` | Gabungan fitur yang sedang dikerjakan sebelum rilis. |
| `feat/*`, `fix/*`, `docs/*`, `chore/*` | Branch kerja sementara. |

Pola nama branch:

```
<tipe>/<peran>-<topik>
```

| Tipe | Kegunaan | Contoh |
|---|---|---|
| `feat` | Fitur baru | `feat/be-katalog-filter-pipeline` |
| `fix` | Perbaikan bug | `fix/fe-webhook-idempoten` |
| `docs` | Dokumentasi | `docs/pm-panduan-instalasi` |
| `chore` | Konfigurasi, aset, setup | `chore/be-setup-pdo-postgres` |
| `design` | Wireframe, prototipe, aset desain | `design/ux-wireframe-checkout` |

Aturan:

1. Satu branch untuk satu pekerjaan. Jangan campur dua fitur.
2. Selalu buat branch dari `develop` yang sudah diperbarui.
3. Gunakan huruf kecil dan tanda hubung. Tanpa spasi.
4. Hapus branch setelah pull request digabungkan.
5. Branch yang tidak aktif lebih dari 7 hari sebaiknya di-rebase atau ditutup.

## 3. Format Commit (Conventional Commits)

```
<tipe>(<lingkup>): <deskripsi singkat>
```

Tipe: `feat`, `fix`, `docs`, `refactor`, `test`, `chore`, `style`, `perf`.

Lingkup mengikuti peran/area: `fe`, `be`, `ux`, `pm`, `db`, `midtrans`, `invoice`, `katalog`.

Contoh:

```
feat(be): tambah endpoint filter render pipeline
fix(be): cegah entitlement ganda saat webhook berulang
feat(fe): integrasi Midtrans Snap pada halaman checkout
style(fe): rapikan kartu aset pada layar mobile
docs(pm): tambah langkah konfigurasi Midtrans di README
design(ux): tambah wireframe halaman invoice
chore(db): tambah migrasi tabel entitlements
```

Aturan:

1. Satu commit = satu perubahan logis. Hindari commit "update" tanpa keterangan.
2. Deskripsi dalam Bahasa Indonesia atau Inggris, tetapi konsisten.
3. Jangan commit kredensial, kata sandi, atau kunci API.
4. Jangan commit `vendor/`, `node_modules/`, atau berkas `.env`.

## 4. Alur Pull Request

1. Perbarui `develop` lokal: `git checkout develop && git pull`.
2. Buat branch: `git checkout -b feat/be-katalog-filter`.
3. Kerjakan perubahan dan uji secara lokal.
4. Push: `git push -u origin feat/be-katalog-filter`.
5. Buka pull request ke `develop` dengan mengisi template yang tersedia.
6. Tandai peninjau: pemilik area terkait (lihat CODEOWNERS).
7. Perbaiki temuan peninjauan pada branch yang sama.
8. Gabungkan setelah minimal **1 persetujuan** dan CI hijau.
9. Hapus branch setelah digabungkan.

Aturan tambahan:

1. PR harus kecil. Lebih dari ~400 baris perubahan sebaiknya dipecah.
2. Isi deskripsi PR: **tujuan, perubahan, cara menguji, screenshot** (untuk perubahan tampilan).
3. Jangan pernah menggabungkan PR milik sendiri tanpa peninjauan orang lain.
4. `main` hanya menerima PR dari `develop` pada saat milestone/rilis.

## 5. Konvensi Pesan Pull Request

Judul PR mengikuti format commit:

```
feat(be): endpoint katalog dengan filter pipeline
```

Deskripsi wajib memuat:

- **Tujuan** — masalah yang diselesaikan.
- **Perubahan** — daftar berkas/folder yang disentuh.
- **Cara menguji** — langkah manual atau perintah pengujian.
- **Dampak skema** — ada/tidak perubahan database dan migrasinya.
- **Bukti** — screenshot untuk perubahan UI.

## 6. Code Review

Peninjau wajib memeriksa:

- [ ] Perubahan sesuai tujuan yang dijelaskan.
- [ ] Tidak ada perubahan di luar ruang lingkup PR.
- [ ] Validasi input tersedia pada data dari pengguna.
- [ ] Pemeriksaan hak akses/role diterapkan pada aksi sensitif.
- [ ] Tidak ada rahasia tertulis di kode.
- [ ] Query PostgreSQL memakai **prepared statement PDO** (anti SQL injection).
- [ ] Output ke halaman di-escape (`htmlspecialchars`) untuk mencegah XSS.
- [ ] Token CSRF disertakan pada form POST.
- [ ] Penamaan variabel dan fungsi jelas.
- [ ] Tidak ada kode debug (`var_dump`, `print_r`) yang tertinggal.

Kategori komentar peninjauan:

| Label | Arti |
|---|---|
| `nit:` | Saran kecil, tidak memblokir |
| `saran:` | Perbaikan yang sebaiknya dilakukan |
| `wajib:` | Harus diperbaiki sebelum digabungkan |
| `tanya:` | Butuh klarifikasi |

## 7. Penamaan Issue dan Label

Judul issue: `[AREA] Deskripsi singkat` — contoh `[BE] Webhook Midtrans belum idempoten`.

Label standar:

| Label | Arti |
|---|---|
| `frontend` / `backend` / `ui-ux` / `pm` | Peran terkait |
| `priority:high` / `priority:medium` / `priority:low` | Prioritas |
| `type:feature` / `type:bug` / `type:docs` / `type:chore` | Jenis pekerjaan |
| `status:todo` / `status:in-progress` / `status:review` / `status:done` | Status |
| `blocked` | Menunggu pihak lain |
| `good-first-task` | Cocok untuk tugas ringan |

Aturan:

1. Satu issue = satu pekerjaan yang bisa diselesaikan dalam satu PR.
2. Setiap PR harus menautkan issue: tulis `Closes #12` di deskripsi.
3. Pindahkan kartu di GitHub Project saat status berubah.

## 8. Aturan Khusus Proyek

1. **Jangan menulis kredensial** Midtrans, kata sandi PostgreSQL, atau kunci lain di kode. Gunakan `.env`.
2. **Jangan mengubah `main` secara langsung.**
3. **Perubahan skema database** (`database/migrations/**`, `database/schema.sql`) harus dibahas di pertemuan mingguan sebelum digabungkan.
4. Jika dua anggota mengerjakan berkas yang sama, koordinasikan lebih dulu di grup.
5. Semua teks antarmuka wajib **Bahasa Indonesia** yang jelas dan konsisten.
6. Gaya kode PHP mengikuti PSR-12. Jalankan `composer lint` sebelum PR.
7. Sinkronkan `develop` ke branch sendiri minimal sekali sehari untuk menghindari konflik besar.

## 9. Perintah Harian

```bash
# Mulai kerja
git checkout develop && git pull
git checkout -b feat/be-nama-fitur

# Simpan pekerjaan
git add .
git commit -m "feat(be): deskripsi singkat"
git push -u origin feat/be-nama-fitur

# Sinkronisasi dengan develop
git checkout develop && git pull
git checkout feat/be-nama-fitur && git rebase develop
```
