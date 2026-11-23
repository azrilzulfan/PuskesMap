# AZRIL_AGENT_BRIEF.md — Panduan Kolaborasi & Kontrak Data untuk AI Agent Pendamping Azril

> **Pesan Resmi dari Fajar (Team Lead):** Dokumen ini adalah acuan kolaborasi bagi AI Agent yang mendampingi **Azril** di repositori `PuskesMap/`. Kebijakan kolaborasi dibuat **fleksibel untuk eksplorasi fitur dan UI kreatif**, selama seluruh **kontrak data baku 2025–2026**, batas keamanan/RBAC, dan integritas repositori bersama tetap terjaga. Keputusan arsitektur akhir serta standarisasi UI final sebelum rilis berada di tangan **Fajar (`Team Lead`)**.

---

## 1. Posisi Peran & Kebebasan Eksplorasi Tim

- Anda mendampingi **Azril** pada pengembangan **Backend, Engine SDSS, dan eksplorasi fitur/UI pendukung** di `PuskesMap/`.
- **Kebijakan Eksplorasi UI:** Team Lead membebaskan anggota tim (termasuk Azril pada area `/analitik*`, `/admin/*`, komponen `dashboard-shell.blade.php`, dan `/peta` serta Tim Landing Page pada `/`) untuk berkreasi bebas terlebih dahulu secara visual/UX. Nantinya pada fase finalisasi (`T-02` / `T-03`), **Fajar (`Team Lead`)** yang akan meninjau, memilih, dan menstandarkan UI final sesuai `DESIGN_SPEC.md` dan `UI_RULES.md`.
- **Ketentuan Spec & Data:** Apabila terdapat pekerjaan lama di commit sebelumnya atau arsip PDF/SRS lama di `docs/archive/` yang berbeda dengan spesifikasi di `docs/specs/` dan `config/sdss.php`, **kontrak data aktif di `docs/specs/` dan `config/sdss.php` adalah acuan yang menang**.

---

## 2. Alur Kerja Git & Sebelum Menulis Kode

1. Selalu jalankan `git pull origin main` (atau `--rebase`) terlebih dahulu sebelum bekerja agar tidak menimpa progres rekan tim.
2. **Alur Branch Bebas & Aman:** Anda boleh bekerja di branch fitur (misal `feat/be-*` atau `feat/ui-*`) maupun langsung menyinkronkan ke `main` bila diperlukan agar tidak terkunci saat berkolaborasi—dengan syarat **dilarang `--force` push**, **dilarang meng-commit `.env` / kredensial**, dan pastikan perubahan tidak merusak halaman tim lain.
3. Baca ringkas sebelum mengubah logika SDSS:
   - `docs/TEAM_GUIDE.md`
   - `docs/specs/PRODUCT_SPEC.md`
   - `docs/specs/TECH_SPEC.md`
   - `docs/specs/DESIGN_SPEC.md`
   - `docs/specs/UI_RULES.md`
   - `docs/sql/00_PANDUAN.md`
4. **Catatan Arsip Historis:** Kedua berkas PDF Revisi 12 (`SAD` & `SRS`) di folder `docs/archive/` hanya berstatus **arsip laporan akademik awal**. Jangan memakai subdomain lama, angka lama (`5603`/`15:00:00`), rute rancangan awal, atau referensi Permenkes 43-only dari dokumen tersebut.

---

## 3. Koordinasi Area File (Menghindari Konflik Antar-Tim)

### A. Area Tim Landing Page (`Kayla`, `Kaila`, & `Dila`) — Koordinasi Sebelum Mengubah
- `resources/views/welcome.blade.php`, `resources/views/components/bogor-map.blade.php`, `resources/views/components/layout.blade.php`, `resources/views/components/button.blade.php`
- `resources/js/landing/landing-page.js`, `resources/css/landing.css`, serta `public/images/*`
- Jangan menimpa kreativitas tim Landing Page tanpa koordinasi; bila perlu menyinkronkan data dari controller/dataset, pastikan angkanya berasal dari CSV resmi (`database/seeders/csv/`).

### B. Area Fondasi Design System Team Lead (`Fajar`) — Acuan Standar Final UI
- `resources/views/layouts/*`, `resources/views/components/ui/*`, `resources/views/auth/login.blade.php`
- `resources/css/app.css`, `resources/css/map.css`, `resources/js/app.js`, `resources/js/map/sdss-map.js`
- Rute `/analitik`, `/analitik/peta`, `/admin/*`, `/peta`, serta rencana detail `/puskesmas/{kode_puskesmas}` (`T-02`) saat ini boleh dieksplorasi secara kreatif (termasuk `resources/views/components/dashboard-shell.blade.php`, `resources/views/analytics/map.blade.php`, dan `resources/views/admin/*`), dengan catatan tampilan final sebelum rilis akan di-review dan distandarkan kembali oleh **Fajar**.

### C. Area Fokus Utama Azril (Backend & Engine SDSS)
- `app/Services/*`, `app/Http/Controllers/*`, `app/Http/Requests/*`, `app/Http/Resources/*`, `app/Models/*`, `app/Enums/*`, `database/migrations/*`, `database/seeders/*`, `routes/*`, dan `tests/*` (pertahankan `tests/Feature/DesignSystemAndDatasetTest.php` sebagai penjaga kontrak).

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
7. **Acuan Standar UI Rilis Akhir (`DESIGN_SPEC.md` & `UI_RULES.md`):**
   - Pada masa eksplorasi kreatif saat ini, penggunaan komponen dashboard alternatif seperti `dashboard-shell.blade.php` diizinkan agar pengembangan UI cepat berkembang.
   - Untuk standarisasi akhir yang dipimpin **Fajar**, target rilis mengacu pada layout `layouts/app.blade.php` / `layouts/map.blade.php` (`data-alpine="bundled"`), komponen `<x-ui.*>`, ikon `<x-lucide-*>` / `lucide` JS, serta `docs/specs/UI_RULES.md` (Light Mode, nol emoji, kontras `--accent: #0A6663`).

---

## 5. Checklist Verifikasi Sebelum Push / Lapor Selesai

1. Jalankan `php artisan test` dan `npm.cmd run build`:
   - Pada `pgsql` + PostGIS (`puskesmap_test`), seluruh test suite (baseline saat ini `38` runtime tests) wajib lolos.
   - Bila Anda menjalankan sementara memakai `DB_CONNECTION=sqlite` dan `DB_DATABASE=:memory:`, akan ada **3 ketidakcocokan PostGIS-native yang memang diharapkan** (`ST_AsGeoJSON` `'MultiPolygon'`, `ST_Buffer` `'Polygon'`, dan `ST_AsText` — `35 passed, 2 failed, 1 error` dari `38` tests). Jangan mengubah query PostGIS atau `phpunit.xml` demi mengakali SQLite.
   - Catatan tambahan: `tests/Feature/MapPageTest.php` masih mengacu kontrak DOM/label legacy (`'Sepi'` dan struktur kartu/modal lama) hingga perombakan UI fase `T-02` oleh Fajar.
2. Jalankan `php vendor/bin/pint --dirty --test` agar format kode tetap rapi.
3. Jangan pernah meng-commit `.env` atau kredensial database/Supabase.
4. Setelah push (baik di branch fitur maupun sinkronisasi `main`), laporkan ringkasan file yang disentuh dan hasil test ke **Fajar (Team Lead)** agar koordinasi tim tetap transparan.
