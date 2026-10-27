# Panduan Integrasi Supabase

Dokumen ini untuk dua orang: **Jonathan** (front end) dan **Diaz** (back end).
Isinya hanya "kalau mau ubah X, sentuh berkas Y". Tidak ada kode di sini.

---

## Kondisi sekarang

| Hal | Keadaan |
|---|---|
| Database | PostgreSQL lokal, skema dari `database/Script marketplace_game_assets.sql` |
| Gambar | File JPG/PNG di `public/assets/img/catalog/`, path di DB relatif (`assets/img/catalog/01.jpg`) |
| Berkas unduhan | Hanya berupa catatan di tabel `asset_files` (belum ada file asli) |
| URL gambar | Dibentuk oleh `app/Helpers/Storage.php` |

Artinya: **Supabase belum dipakai sama sekali.** Semua masih lokal.

---

## Bagian 1 — Database ke Supabase

Kalau mau database pindah ke Supabase (Postgres milik Supabase):

### Yang perlu dilakukan

1. Buat project di Supabase, ambil **Connection string** (mode *Session pooler*).
2. Jalankan skrip skema ke database Supabase:
   ```bash
   psql "<connection-string-supabase>" -f "database/Script marketplace_game_assets.sql"
   ```
3. Ubah `.env` (bukan `.env.example`):
   ```
   DB_HOST=aws-0-<region>.pooler.supabase.com
   DB_PORT=6543
   DB_NAME=postgres
   DB_USER=postgres.<project-ref>
   DB_PASSWORD=<password-database>
   DB_SSLMODE=require
   ```

### Yang perlu diubah di kode

**Tidak ada.** `app/Core/Database.php` sudah membaca `DB_SSLMODE` dan memasukkannya ke DSN:

```php
$sslmode = Env::get('DB_SSLMODE', 'prefer');
$dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode={$sslmode}";
```

Semua query lewat `Database::getInstance()`, jadi cukup ubah `.env` saja.

### Catatan penting

- Skrip memakai `gen_random_uuid()` untuk kolom `public_id`. Fungsi itu bawaan PostgreSQL 13
  ke atas, dan Supabase memakai PostgreSQL 15+, jadi tidak perlu `CREATE EXTENSION` apa pun.
- Skrip memberi hak akses ke `current_user`, bukan nama role tertentu. Di Supabase,
  `current_user` adalah `postgres`, jadi skrip jalan tanpa diubah.
- Skrip memakai `IF NOT EXISTS` dan `ON CONFLICT DO NOTHING`, jadi aman dijalankan berulang.
- **Jangan** commit connection string atau password ke repo. Simpan hanya di `.env`.

---

## Bagian 2 — Gambar ke Supabase Storage

Kalau gambar dipindah dari `public/assets/img/` ke bucket Supabase.

### Yang perlu dilakukan

1. Buat bucket **public** di Supabase Storage, misal bernama `indofx`.
2. Upload semua file dari `public/assets/img/catalog/` ke bucket itu, dengan folder `catalog/`.
   Jadi `01.jpg` menjadi `catalog/01.jpg` di dalam bucket.
3. Isi `.env`:
   ```
   STORAGE_BASE_URL=https://<project-ref>.supabase.co/storage/v1/object/public/indofx
   ```

### Yang perlu diubah di kode

**Tidak ada.** `app/Helpers/Storage.php` sudah menangani ini.

Cara kerjanya:

| `STORAGE_BASE_URL` | Hasil URL untuk `assets/img/catalog/01.jpg` |
|---|---|
| kosong (default) | `/assets/img/catalog/01.jpg` (file lokal) |
| terisi | `https://<ref>.supabase.co/storage/v1/object/public/indofx/assets/img/catalog/01.jpg` |

Karena itu, saat upload ke Supabase, **pertahankan struktur folder** `assets/img/catalog/...`
supaya path di database tidak perlu diubah sama sekali.

### Kalau struktur folder di bucket berbeda

Kalau Anda upload tanpa folder `assets/img/`, misal langsung `catalog/01.jpg`, maka ubah
**path di database**, bukan kodenya:

```sql
UPDATE asset_images
SET image_path = replace(image_path, 'assets/img/', '');
```

Cukup satu query. `Storage::url()` akan menambahkan basis URL di depannya.

---

## Bagian 3 — Berkas unduhan (.unitypackage) ke Supabase Storage

Ini bagian yang **belum ada kodenya** — masih perlu dikerjakan Diaz.

### Kondisi sekarang

Tabel `asset_files` menyimpan `file_path`, `original_name`, `file_size`, tapi:
- Tidak ada file asli di disk
- Tidak ada fitur upload
- Tidak ada fitur download

### Yang perlu dibuat

1. **Halaman/aksi upload** untuk penjual (belum ada route-nya).
2. **Aksi download** yang memeriksa `entitlements` sebelum memberi file.

### Kalau nanti pakai Supabase Storage

Alurnya sama seperti gambar:
- Simpan `file_path` relatif di `asset_files` (mis. `files/warrior_hero.unitypackage`).
- Bentuk URL dengan `Storage::url()` yang sudah ada.
- Untuk file privat (hanya pembeli), gunakan **bucket private** + *signed URL*, bukan
  `STORAGE_BASE_URL`. Ini butuh method baru di `Storage.php`, misalnya:
  ```php
  Storage::signedUrl(string $path, int $detik = 300): string
  ```
  Method ini memanggil Supabase Storage API dengan service key. **Service key jangan
  pernah ditaruh di front end** — hanya di `.env` dan dipakai server-side.

---

## Ringkasan: apa yang disentuh kalau...

| Kalau mau... | Ubah ini | Kode berubah? |
|---|---|---|
| Pindah database ke Supabase | `.env` saja | **Tidak** |
| Pindah gambar ke Supabase Storage | `.env` (`STORAGE_BASE_URL`) | **Tidak** |
| Ganti struktur folder bucket | Query `UPDATE asset_images` | **Tidak** |
| Tambah fitur upload berkas | Route baru + controller + view | Ya, fitur baru |
| Tambah fitur download berkas | Route baru + controller | Ya, fitur baru |
| Ganti warna tag pipeline | `public/assets/css/style.css` | Ya, CSS saja |
| Ubah jumlah aset per halaman | `app/Controllers/CatalogController.php` (`PER_PAGE`) | Ya, 1 baris |

---

## Yang TIDAK boleh dilakukan

1. **Jangan commit `.env`.** Sudah masuk `.gitignore`, jangan dihapus.
2. **Jangan taruh service key Supabase di JavaScript** atau di view. Hanya di `.env`.
3. **Jangan hardcode URL Supabase** di view atau model. Selalu lewat `Storage::url()`.
4. **Jangan ubah `database/schema.sql`** — berkas itu sudah tidak dipakai. Yang aktif
   `database/Script marketplace_game_assets.sql`.

---

## Cara memastikan tidak ada yang rusak

Setelah mengubah `.env`, jalankan:

```bash
composer serve
```

Lalu buka beranda dan `/catalog/explore`. Kalau gambar tampil dan jumlah aset benar,
konfigurasi sudah tepat. Kalau gambar rusak, cek `STORAGE_BASE_URL` — biasanya ada
garis miring berlebih atau nama bucket salah.

Untuk memastikan tidak ada yang rusak secara kode:

```bash
composer lint
composer test
```
