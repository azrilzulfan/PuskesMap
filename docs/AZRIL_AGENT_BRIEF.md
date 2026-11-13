# AZRIL_AGENT_BRIEF.md — Instruksi Kerja Wajib untuk AI Agent Pendamping Azril

> **Catatan:** File ini adalah panduan operasional resmi bagi AI Agent yang bekerja bersama **Azril** di repositori `PuskesMap/`. Seluruh aturan di bawah ini disusun berdasarkan hasil konsolidasi **Team Lead (`Fajar`)** hingga commit `3ebbe53`.

---

## 1. Posisi Peran & Tata Kelola Tim

- Anda mendampingi **Azril** pada pengembangan modul **Backend & Engine SDSS** `PuskesMap/`.
- **Team Lead proyek ini adalah `Fajar`.** Seluruh keputusan akhir arsitektur, skema data 2025–2026, Design System (`<x-ui.*>`, `DESIGN_SPEC.md`, `UI_RULES.md`), serta persetujuan merge ke `main` berada di tangan Team Lead.
- Apabila terdapat pekerjaan lama di commit sebelumnya yang berbeda dengan spesifikasi di `docs/specs/`, **spec di `docs/specs/` dan aturan Team Lead adalah acuan yang menang**.

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
- `resources/css/app.css`, `resources/css/map.css`, `resources/js/app.js`
- `resources/views/peta.blade.php`, `resources/views/map/partials/*`, `resources/js/map/sdss-map.js`, `resources/views/analytics/*`, `resources/views/admin/*` — area UI ini sedang dalam agenda perombakan Team Lead (`T-02` & `T-03`) agar sesuai `DESIGN_SPEC.md` (OneMap, palet `#0A6663`, Lucide icons). **Jangan mengedit view/JS/CSS frontend SDSS secara paralel kecuali diminta eksplisit oleh Fajar.**

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
3. **RBAC & Keamanan:**
   - Skenario MAUT **`pemda`** (`Evaluasi Kebijakan Pemda`) dilarang untuk publik; `RecommendationRequest::authorize()` wajib mengembalikan `403` untuk tamu dan hanya mengizinkan role `pemerintah` / `admin` aktif.
4. **Optimasi Supabase & PostGIS:**
   - `config/database.php` (`pgsql`) menggunakan `search_path => env('DB_SEARCH_PATH', 'public,extensions')` untuk kompatibilitas Supabase Supavisor port `5432`.
   - Poligon statis wajib memakai `ST_AsGeoJSON(geom, 6)` + `Cache::rememberForever`.
   - Setiap mutasi data master/operasional/nakes/kunjungan di Admin wajib memanggil `SpatialAnalysisService::flushSpatialCache()`.
5. **Konfigurasi Lingkungan & Paket:**
   - `composer.json` sudah memasang `mallardduck/blade-lucide-icons` & `barryvdh/laravel-dompdf`, serta sengaja memakai `"optimize-autoloader": false` agar `composer dump-autoload` tidak macet di Windows NTFS lokal. Jangan kembalikan ke `true` pada `composer.json` (gunakan flag CLI `--optimize-autoloader` saat build produksi).
   - Di PowerShell Windows, gunakan `npm.cmd` bila `npm` terblokir kebijakan `.ps1`.
6. **Jika Suatu Saat Diminta Membuat UI oleh Team Lead:**
   - Wajib memakai layout `layouts/app.blade.php` atau `layouts/map.blade.php` (`data-alpine="bundled"`), komponen `<x-ui.*>`, dan ikon `<x-lucide-*>` / `lucide` JS.
   - Patuhi penuh `docs/specs/UI_RULES.md`: hanya Light Mode, nol emoji, nol `<svg>` manual, nol gradien dekoratif, nol `rgba`/`backdrop-blur` berlebihan.

---

## 5. Checklist Verifikasi Sebelum Push / Lapor Selesai

1. Jalankan `php artisan test` (sebutkan secara jujur bila dijalankan dengan SQLite *in-memory* vs PostgreSQL/PostGIS nyata).
2. Pastikan `git status --short` hanya mengubah file di area backend Azril (file tim Landing Page & file UI/Design System Team Lead tetap utuh).
3. Jangan pernah meng-commit `.env` atau kredensial database/Supabase.
4. Push ke branch `feat/be-*` dan laporkan ringkasan perubahan ke **Fajar (Team Lead)** untuk *review* sebelum masuk ke `main`.
