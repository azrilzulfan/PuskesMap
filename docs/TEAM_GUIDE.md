# TEAM_GUIDE.md — Panduan Kerja & Pembagian Area Repo PuskesMap

Dokumen ini adalah acuan resmi kolaborasi tim di dalam repositori `PuskesMap/` agar pekerjaan paralel tetap sinkron, bebas bentrok, dan patuh pada spesifikasi resmi.

---

## 1. Pembagian Kepemilikan Area & Batas Wewenang

### A. Tim Landing Page (`Kayla`, `Kaila`, & `Dila`) — Area Terbatas
- **Cakupan file:**
  - `resources/views/welcome.blade.php`
  - `resources/views/components/bogor-map.blade.php`
  - `resources/views/components/layout.blade.php` & `resources/views/components/button.blade.php` (khusus beranda `/`)
  - `resources/js/landing/landing-page.js`
  - `resources/css/landing.css` dan blok `@theme` Landing Page di `resources/css/app.css`
  - `public/images/*`
- **Aturan:**
  - Tidak mengubah modul inti SDSS, dataset, migrasi, API, maupun layout/komponen `<x-ui.*>`.
  - Seluruh data puskesmas yang ditampilkan di Landing Page (status `IGD 24 Jam` = hanya 6 puskesmas, `Rawat Inap` = 0 / tidak ditampilkan, jam tutup poli `14:00 WIB`, kelengkapan 9 nakes `100%` atau `89%`, serta kepadatan `Rendah|Sedang|Padat`) **wajib mengacu pada CSV resmi di `database/seeders/csv/`** — dilarang mengisi angka/status perkiraan manual.
  - Aset foto di `public/images/puskesmas/` wajib terkompres ringan, hanya memasukkan foto yang benar-benar direferensikan di UI, dan memakai penamaan huruf kecil tanpa spasi agar aman di server Linux.

### B. Tim Inti SDSS (`Fajar` & `Azril`) — Pemegang Penuh Modul Inti
Hanya **Fajar** dan **Azril** yang memegang seluruh modul inti SDSS: dataset referensi & seeder, migrasi PostGIS, Eloquent models/enums, `app/Services/*`, `app/Http/Controllers/*`, `/api/v1/*`, `/peta`, `/puskesmas/{kode_puskesmas}` *(rencana fase UI `T-02`, belum aktif di `routes/web.php`)*, `/login`, `/analitik`, `/analitik/peta`, dan `/admin/*`.

- **Fajar (*Team Lead*):**
  - Pemegang otoritas akhir arsitektur, kontrak data resmi 2025–2026, prioritas *UI/UX user-friendly first*, optimasi, *maintainability*, serta penentu & penstandardisasi **UI final sebelum rilis** (`--accent: #0A6663`, `layouts/*`, `components/ui/*`, `DESIGN_SPEC.md`, `UI_RULES.md`, dan konfigurasi Supabase).
- **Azril (*Backend, Engine SDSS, & Eksplorasi Fitur*):**
  - Menangani pengembangan dan pemeliharaan backend pendukung (migrasi, model, *service layer*, endpoint `/api/v1/*`, RBAC, controller CRUD, Pest tests), serta **dibebaskan mengeksplorasi rancangan fitur/UI kreatif** pada modul internal (`/analitik*`, `/admin/*`, komponen shell pendukung) selama kontrak data baku terjaga. Keputusan UI final pada fase rilis tetap berada di Team Lead.

---

## 2. Sumber Kebenaran Resmi di Repo Ini (`docs/`)

Sebelum menulis kode atau mengubah logika SDSS, baca dokumen spesifikasi aktif berikut:
1. [Produk & Aturan Bisnis (`PRODUCT_SPEC.md`)](./specs/PRODUCT_SPEC.md)
2. [Arsitektur Teknis & Kontrak API (`TECH_SPEC.md`)](./specs/TECH_SPEC.md)
3. [Desain Visual & UX OneMap (`DESIGN_SPEC.md`)](./specs/DESIGN_SPEC.md)
4. [Aturan Wajib Aksesibilitas & UI (`UI_RULES.md`)](./specs/UI_RULES.md)
5. [Skema SQL, Validasi PostGIS, & PSW (`docs/sql/`)](./sql/00_PANDUAN.md)

> **Catatan Arsip Historis:** Kedua berkas PDF Revisi 12 (`SAD` dan `SRS`) di folder `docs/archive/` merupakan **arsip laporan akademik awal** — jangan memakai subdomain lama, angka lama (`5603`/`15:00:00`), atau referensi Permenkes 43 tanpa pemutakhiran Permenkes 19/2024 dari arsip tersebut jika bertentangan dengan `docs/specs/` dan `config/sdss.php`.

---

## 3. Aturan Teknis & Data Baku 2025–2026 (Wajib Dipatuhi)

1. **Sumber Dataset Tunggal:**
   - CSV bersih berada di `database/seeders/csv/` (6 kecamatan penduduk `1.156.543`, 25 puskesmas, 25 operasional, 25 tenaga medis, 300 kunjungan tahun 2025, 37 zona jangkauan jalan).
   - GeoJSON statis berada di `public/geojson/`. Jangan membuat folder dataset duplikat lain.
2. **Angka & Jam Operasional Resmi:**
   - `kapasitas_maksimal_bulanan` default = **`850`** (bukan `5603`).
   - `operasional.jam_tutup` reguler default = **`14:00:00`** (bukan `15:00:00`).
   - Kota Bogor memiliki **`0` Puskesmas Rawat Inap** (`rawat_inap = false`); filter/badge Rawat Inap tidak ditampilkan di UI publik.
   - **Algoritma MAUT & Durasi Operasional:** `Operasional::durationHours()` selalu menghitung jam poli reguler (`jam_buka`–`jam_tutup`) meskipun `status_igd_24jam === true` agar kriteria `hours` tidak menggandakan bobot `igd`. `POST /api/v1/rekomendasi` mendukung filter `radius` opsional (`1000|3000|5000`).
3. **Aturan Akses, Optimasi Laravel, & Keamanan:**
   - Skenario MAUT **`pemda`** (`Evaluasi Kebijakan Pemda`) dilarang untuk publik dan hanya boleh diakses role `pemerintah` / `admin` terautentikasi (HTTP `403` untuk tamu).
   - Koneksi PostgreSQL/Supabase menggunakan `DB_SEARCH_PATH=public,extensions` dan Supavisor Session Mode port `5432`. Keep-alive harian (`supabase-keep-alive`) terjadwal di `routes/console.php`.
   - `AppServiceProvider` mengaktifkan `Model::preventLazyLoading(! app()->isProduction())` — seluruh query relasi wajib memakai *eager loading* (`with()` / `loadMissing()`).
   - Seluruh daftar admin & audit log wajib terpaginasi `10–25` baris (`paginate(15)` sesuai `NFR-09`).
   - Poligon statis wajib memakai `ST_AsGeoJSON(geom, 6)` + `Cache::rememberForever` dan di-flush melalui `SpatialAnalysisService::flushSpatialCache()` setiap ada mutasi CRUD Admin.
4. **Standar UI/Frontend SDSS & Pengujian:**
   - **Fase Eksplorasi Tim vs Target Standar Rilis:** Selama pengembangan paralel saat ini, tim dibebaskan bereksperimen secara kreatif di cabang tampilan masing-masing (misal eksplorasi Landing Page maupun Bento Dashboard internal `dashboard-shell.blade.php`), sementara penyelarasan akhir sebelum rilis akan dipimpin oleh **Fajar** mengacu penuh pada `layouts/` (`app.blade.php`, `map.blade.php`), `<x-ui.*>`, `<x-lucide-*>`, dan `UI_RULES.md` (`--accent: #0A6663`, Light Mode).
   - **Konfigurasi `phpunit.xml`:** Sengaja mengarah ke `DB_CONNECTION=pgsql` (`puskesmap_test`). **Dilarang mengubah `phpunit.xml` kembali ke `sqlite`.** Pengujian darurat dengan `DB_CONNECTION=sqlite` dan `DB_DATABASE=:memory:` akan menyisakan **3 ketidakcocokan PostGIS-native** (`ST_AsGeoJSON` `'MultiPolygon'`, `ST_Buffer` `'Polygon'`, dan `ST_AsText`) — `35 passed, 2 failed, 1 error` dari total baseline `38` runtime tests. Selain itu, `tests/Feature/MapPageTest.php` masih mengacu kontrak DOM/label legacy (`'Sepi'`) hingga perombakan UI `T-02`.
5. **Lingkungan Lokal Windows:**
   - Gunakan `npm.cmd` di PowerShell jika eksekusi `.ps1` diblokir.
   - `composer.json` memakai `"optimize-autoloader": false` agar `composer dump-autoload` tidak macet di Windows NTFS; untuk build produksi gunakan flag CLI `composer install --optimize-autoloader`.

---

## 4. Aturan Alur Git Tim

1. Selalu `git pull origin main` (atau `--rebase`) sebelum memulai atau mengirim perubahan baru agar pekerjaan antar-anggota tidak saling menimpa.
2. Disarankan memakai branch fitur (`feat/be-*`, `feat/ui-*`, `feat/landing-*`), namun tim tetap diperbolehkan berkolaborasi fleksibel di GitHub (termasuk sinkronisasi ke `main`) asal **tidak melakukan `--force` push** dan selalu menginformasikan perubahan besar ke Team Lead.
3. Jangan pernah meng-commit `.env` atau kredensial Supabase.
