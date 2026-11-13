# TEAM_GUIDE.md — Panduan Kerja & Pembagian Area Repo PuskesMap

Dokumen ini adalah acuan resmi kolaborasi tim di dalam repositori `PuskesMap/` agar pekerjaan paralel tetap sinkron, bebas bentrok, dan patuh pada spesifikasi resmi.

---

## 1. Pembagian Kepemilikan Area & Batas Wewenang

### A. Tim Landing Page (`Kayla` & `Kaila`) — Area Terbatas
- **Cakupan file:**
  - `resources/views/welcome.blade.php`
  - `resources/views/components/bogor-map.blade.php`
  - `resources/views/components/layout.blade.php` & `resources/views/components/button.blade.php` (khusus beranda `/`)
  - `resources/js/landing/landing-page.js`
  - `resources/css/landing.css` dan blok `@theme` Landing Page di `resources/css/app.css`
  - `public/images/*`
- **Aturan:** Tidak mengubah modul inti SDSS, dataset, migrasi, API, maupun layout/komponen `<x-ui.*>`.

### B. Tim Inti SDSS (`Fajar` & `Azril`) — Pemegang Penuh Modul Inti
Hanya **Fajar** dan **Azril** yang memegang seluruh modul inti SDSS: dataset referensi & seeder, migrasi PostGIS, Eloquent models/enums, `app/Services/*`, `app/Http/Controllers/*`, `/api/v1/*`, `/peta`, `/puskesmas/{slug}`, `/login`, `/analitik/*`, dan `/admin/*`.

- **Fajar (*Team Lead*):**
  - Pemegang otoritas akhir arsitektur, kontrak data resmi 2025–2026, prioritas *UI/UX user-friendly first*, optimasi, *maintainability*, serta *final review* sebelum merge ke `main`.
  - Memegang desain sistem (`--accent: #0A6663`, `layouts/*`, `components/ui/*`), perombakan UI OneMap `/peta`, `/puskesmas/{slug}`, `/analitik`, dan `/admin` views, serta konfigurasi Supabase.
- **Azril (*Backend & Engine SDSS*):**
  - Menangani pengembangan dan pemeliharaan backend pendukung (migrasi, model, *service layer*, endpoint `/api/v1/*`, RBAC, controller CRUD, Pest tests).
  - Wajib mengacu penuh pada angka, skema, dan aturan di folder `docs/` serta arahan/koreksi Team Lead; tidak melakukan perombakan desain sistem atau menyentuh file milik tim Landing Page.

---

## 2. Sumber Kebenaran Resmi di Repo Ini (`docs/`)

Sebelum menulis kode atau mengubah logika SDSS, baca 4 dokumen spesifikasi berikut:
1. [Produk & Aturan Bisnis (`PRODUCT_SPEC.md`)](./specs/PRODUCT_SPEC.md)
2. [Arsitektur Teknis & Kontrak API (`TECH_SPEC.md`)](./specs/TECH_SPEC.md)
3. [Desain Visual & UX OneMap (`DESIGN_SPEC.md`)](./specs/DESIGN_SPEC.md)
4. [Aturan Wajib Aksesibilitas & UI (`UI_RULES.md`)](./specs/UI_RULES.md)
5. [Skema SQL, Validasi PostGIS, & PSW (`docs/sql/`)](./sql/00_PANDUAN.md)

---

## 3. Aturan Teknis & Data Baku 2025–2026 (Wajib Dipatuhi)

1. **Sumber Dataset Tunggal:**
   - CSV bersih berada di `database/seeders/csv/` (6 kecamatan penduduk `1.156.543`, 25 puskesmas, 25 operasional, 25 tenaga medis, 300 kunjungan tahun 2025, 37 zona jangkauan jalan).
   - GeoJSON statis berada di `public/geojson/`. Jangan membuat folder dataset duplikat lain.
2. **Angka & Jam Operasional Resmi:**
   - `kapasitas_maksimal_bulanan` default = **`850`** (bukan `5603`).
   - `operasional.jam_tutup` reguler default = **`14:00:00`** (bukan `15:00:00`).
   - Kota Bogor memiliki **`0` Puskesmas Rawat Inap** (`rawat_inap = false`); filter/badge Rawat Inap tidak ditampilkan di UI publik.
3. **Aturan Akses & Keamanan:**
   - Skenario MAUT **`pemda`** (`Evaluasi Kebijakan Pemda`) dilarang untuk publik dan hanya boleh diakses role `pemerintah` / `admin` terautentikasi (HTTP `403` untuk tamu).
   - Koneksi PostgreSQL/Supabase menggunakan `DB_SEARCH_PATH=public,extensions` dan Supavisor Session Mode port `5432`.
   - Poligon statis wajib memakai `ST_AsGeoJSON(geom, 6)` + `Cache::rememberForever` dan di-flush melalui `SpatialAnalysisService::flushSpatialCache()` setiap ada mutasi CRUD Admin.
4. **Standar UI/Frontend SDSS:**
   - Gunakan layout terisolasi `resources/views/layouts/` (`app.blade.php`, `map.blade.php`) dengan Alpine.js bundled (`data-alpine="bundled"`) dan komponen `<x-ui.*>` di `resources/views/components/ui/`.
   - Gunakan ikon resmi `mallardduck/blade-lucide-icons` (`<x-lucide-*>`) / `lucide` JS — dilarang membuat `<svg>` manual atau memakai emoji.
   - Wajib Light Mode, kontras WCAG AA/AAA (`--accent: #0A6663`), tanpa gradien dekoratif atau `rgba`/`backdrop-blur` berlebihan.
5. **Lingkungan Lokal Windows:**
   - Gunakan `npm.cmd` di PowerShell jika eksekusi `.ps1` diblokir.
   - `composer.json` memakai `"optimize-autoloader": false` agar `composer dump-autoload` tidak macet di Windows NTFS; untuk build produksi gunakan flag CLI `composer install --optimize-autoloader`.

---

## 4. Aturan Alur Git Tim

1. Selalu `git pull origin main` sebelum memulai pekerjaan baru.
2. Buat branch fitur sesuai area masing-masing (misal `feat/be-*`, `feat/ui-*`, `feat/landing-*`).
3. **Jangan push langsung ke `main`** tanpa *review* dari Team Lead (`Fajar`).
4. Jangan pernah meng-commit `.env` atau kredensial Supabase.
