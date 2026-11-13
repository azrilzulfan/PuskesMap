# AZRIL_AGENT_BRIEF.md — Instruksi Kerja Wajib untuk AI Agent Pendamping Azril

> **Pesan Resmi dari Fajar (Team Lead):** Dokumen ini disusun dan diinstruksikan langsung oleh **Fajar (`Team Lead`)** sebagai panduan wajib bagi AI Agent yang mendampingi **Azril** di repositori `PuskesMap/`. Seluruh aturan di bawah ini merupakan acuan arsitektur, kontrak data, dan pembagian area kerja yang sudah dikonsolidasikan di branch `main` (termasuk sinkronisasi pasca audit `T-01`).

---

## 1. Posisi Peran & Tata Kelola Tim

- Anda mendampingi **Azril** pada pengembangan modul **Backend & Engine SDSS** `PuskesMap/`.
- **Team Lead proyek ini adalah `Fajar`.** Seluruh keputusan akhir arsitektur, skema data 2025–2026, Design System (`<x-ui.*>`, `DESIGN_SPEC.md`, `UI_RULES.md`), serta persetujuan merge ke `main` berada di tangan **Fajar**.
- Apabila terdapat pekerjaan lama di commit sebelumnya atau arsip PDF/SRS lama yang berbeda dengan spesifikasi di `docs/specs/` dan `config/sdss.php`, **spec aktif di `docs/specs/` dan arahan Team Lead (`Fajar`) adalah acuan yang menang**.

---

## 2. Prosedur Wajib Sebelum Menulis Kode

1. Pastikan berada di branch terbaru dari `origin/main` (`git pull origin main`).
2. Buat branch fitur baru untuk pekerjaan backend Azril (misal `feat/be-<nama-fitur>`). **Dilarang push langsung ke `main`.**
3. Baca berurutan sebelum merencanakan perubahan apa pun:
   - `docs/TEAM_GUIDE.md`
   - `docs/specs/PRODUCT_SPEC.md`
   - `docs/specs/TECH_SPEC.md`
   - `docs/specs/DESIGN_SPEC.md`
   - `docs/specs/UI_RULES.md`
   - `docs/sql/00_PANDUAN.md`
4. **Catatan Arsip Historis:** `docs/SRS.md` dan 2 berkas PDF Revisi 12 (`SAD` dan `SRS`) di folder `docs/` hanya berstatus **arsip historis**. Jangan memakai subdomain lama, angka lama (`5603`/`15:00:00`), atau referensi Permenkes 43-only dari dokumen tersebut.

---

## 3. Batas Kepemilikan File (DILARANG DISENTUH Tanpa Izin Team Lead)

### A. Area Tim Landing Page (`Kayla` & `Kaila`) — Dilarang Disentuh
- `resources/views/welcome.blade.php`
- `resources/views/components/bogor-map.blade.php`
- `resources/views/components/layout.blade.php` & `resources/views/components/button.blade.php`
- `resources/js/landing/landing-page.js`
- `resources/css/landing.css` serta token `@theme` Landing Page di `resources/css/app.css`
- `public/images/*`

### B. Area Design System & Perombakan UI Team Lead (`Fajar`) — Jangan Diubah Paralel
- `resources/views/layouts/*` (`app.blade.php`, `map.blade.php`)
- `resources/views/components/ui/*` (`button`, `input`, `select`, `badge`, `drawer`, `modal`)
- `resources/views/auth/login.blade.php`
- `resources/css/app.css`, `resources/css/map.css`, `resources/js/app.js`
- `resources/views/peta.blade.php`, `resources/views/map/partials/*`, `resources/js/map/sdss-map.js`, `resources/views/analytics/*`, `resources/views/admin/*`, serta rute detail `/puskesmas/{kode_puskesmas}` (rencana fase UI `T-02`) — area UI ini berada di bawah kendali **Fajar (Team Lead)** agar sesuai `DESIGN_SPEC.md` (OneMap, palet `#0A6663`, Lucide icons). **Jangan mengedit view/JS/CSS frontend SDSS secara paralel kecuali diminta eksplisit oleh Fajar.**

### C. Area Fokus Kerja Azril (Backend & Engine SDSS)
- `app/Services/*`, `app/Http/Controllers/*`, `app/Http/Requests/*`, `app/Http/Resources/*`, `app/Models/*`, `app/Enums/*`, `database/migrations/*`, `database/seeders/*`, `routes/api_v1.php`, dan `tests/*` (kecuali `tests/Feature/DesignSystemAndDatasetTest.php` milik fondasi Team Lead).

---

## 4. Kontrak Data & Aturan Teknis Baku (Hasil Konsolidasi)

Jangan mengembalikan angka atau pola lama berikut:
1. **Dataset Tunggal 2025–2026:**
   - Sumber CSV resmi hanya di `database/seeders/csv/` (`DatasetBogorSeeder` sudah diarahkan ke sini). **Jangan membuat ulang folder `docs/Dataset/`.**
2. **Kapasitas & Jam Operasional Resmi:**
   - `kapasitas_maksimal_bulanan` default = **`850`** (bukan `5603`).
   - `operasional.jam_tutup` reguler default = **`14:00:00`** (bukan `15:00:00`).
   - Total Puskesmas Rawat Inap di Kota Bogor = **`0`** (`rawat_inap = false`); jangan menambahkan filter/label `Rawat Inap` di respons UI publik maupun validasi baru.
3. **Bobot MAUT, Jam Poli vs IGD, & PSW-Index (`config/sdss.php`):**
   - `Operasional::durationHours()` **selalu menghitung jam poli reguler** (`jam_buka`–`jam_tutup`) termasuk untuk Puskesmas IGD 24 jam agar kriteria `hours` tidak menggandakan bobot `igd`.
   - Bobot MAUT resmi: `darurat` (`distance 0.40, igd 0.30, hours 0.15, nakes 0.15, crowd 0.00`), `reguler` (`hours 0.30, distance 0.20, nakes 0.20, crowd 0.20, igd 0.10`), `pemda` (`nakes 0.30, distance 0.20, crowd 0.20, igd 0.15, hours 0.15`). `POST /api/v1/rekomendasi` menerima `radius` opsional (`1000|3000|5000`).
   - Rumus PSW-Index: `0.40 pop + 0.20 edu + 0.20 pos + 0.20 kur` dengan ambang `Tinggi >= 0.70`, `Ideal >= 0.40`, `Rendah < 0.40`.
4. **RBAC, Keamanan, & Optimasi Laravel (`AppServiceProvider` & `routes/console.php`):**
   - Skenario MAUT **`pemda`** (`Evaluasi Kebijakan Pemda`) dilarang untuk publik; `RecommendationRequest::authorize()` wajib mengembalikan `403` untuk tamu dan hanya mengizinkan role `pemerintah` / `admin` aktif.
   - `UserSeeder` membaca `PUSKESMAP_ADMIN_PASSWORD` dan `PUSKESMAP_DINKES_PASSWORD` dari `.env` (jangan mengembalikan sandi hardcoded saja tanpa `env()`).
   - `AppServiceProvider` mengaktifkan `Model::preventLazyLoading(! app()->isProduction())` — seluruh query relasi wajib memakai *eager loading* agar bebas `LazyLoadingViolationException`.
   - Seluruh daftar tabel Admin & Audit Log wajib menerapkan paginasi server-side `10–25` baris (`paginate(15)` sesuai `NFR-09`).
   - `routes/console.php` menjalankan tugas terjadwal harian `supabase-keep-alive` (`SELECT 1`). Jangan hapus penjadwalan ini.
5. **Optimasi Supabase & PostGIS:**
   - `config/database.php` (`pgsql`) menggunakan `search_path => env('DB_SEARCH_PATH', 'public,extensions')` untuk kompatibilitas Supabase Supavisor port `5432`.
   - Poligon statis wajib memakai `ST_AsGeoJSON(geom, 6)` + `Cache::rememberForever`.
   - Setiap mutasi data master/operasional/nakes/kunjungan di Admin wajib memanggil `SpatialAnalysisService::flushSpatialCache()`.
6. **Konfigurasi Lingkungan & Paket:**
   - `composer.json` sudah memasang `mallardduck/blade-lucide-icons` & `barryvdh/laravel-dompdf`, serta sengaja memakai `"optimize-autoloader": false` agar `composer dump-autoload` tidak macet di Windows NTFS lokal. Jangan kembalikan ke `true` pada `composer.json` (gunakan flag CLI `--optimize-autoloader` saat build produksi).
   - **JANGAN MENGUBAH `phpunit.xml`** (`DB_CONNECTION=pgsql`, `DB_DATABASE=puskesmap_test`) kembali ke `sqlite`.
   - Di PowerShell Windows, gunakan `npm.cmd` bila `npm` terblokir kebijakan `.ps1`.
7. **Jika Suatu Saat Diminta Membuat UI oleh Team Lead (`Fajar`):**
   - Wajib memakai layout `layouts/app.blade.php` atau `layouts/map.blade.php` (`data-alpine="bundled"`), komponen `<x-ui.*>`, dan ikon `<x-lucide-*>` / `lucide` JS.
   - Patuhi penuh `docs/specs/UI_RULES.md`: hanya Light Mode, nol emoji, nol `<svg>` manual, nol gradien dekoratif, nol `rgba`/`backdrop-blur` berlebihan.

---

## 5. Checklist Verifikasi Sebelum Push / Lapor Selesai

1. Jalankan `php artisan test`:
   - Pada `pgsql` + PostGIS (`puskesmap_test`), seluruh test backend wajib lolos.
   - Bila Anda menjalankan sementara memakai `DB_CONNECTION=sqlite DB_DATABASE=:memory:`, akan ada **2 kegagalan PostGIS-native yang memang diharapkan** (`ST_Buffer` & `ST_AsText`). Jangan mengubah query PostGIS atau `phpunit.xml` demi mengakali SQLite.
   - Catatan tambahan: `tests/Feature/MapPageTest.php` masih mengacu kontrak DOM/label legacy (`5603`, `'Sepi'`) dan baru akan diperbarui oleh Fajar pada fase `T-02`.
2. Pastikan `git status --short` hanya mengubah file di area backend Azril (file tim Landing Page & file UI/Design System Team Lead tetap utuh).
3. Jangan pernah meng-commit `.env` atau kredensial database/Supabase.
4. Push ke branch `feat/be-*` dan laporkan ringkasan perubahan ke **Fajar (Team Lead)** untuk *review* sebelum masuk ke `main`.
