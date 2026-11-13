# TECH_SPEC.md

File ini adalah cetak biru teknis yang menjelaskan **BAGAIMANA** produk dibangun di balik layar (penggabungan ringkas dari arsitektur HLD/LLD, Database ERD, API Spec, dan Test Plan).
*(Untuk urusan fitur & aturan bisnis lihat [`PRODUCT_SPEC.md`](./PRODUCT_SPEC.md), dan untuk urusan tampilan visual lihat [`DESIGN_SPEC.md`](./DESIGN_SPEC.md)).*

---

## Metadata

- Nama Produk: PuskesMap — Sistem Pendukung Keputusan Spasial (WebGIS) Puskesmas Kota Bogor
- Status Spec: Disetujui & Disinkronkan ke Implementasi Backend (`T-01`)
- Terakhir diperbarui: 2026-11-13
- File Bahan / Referensi:
  - [`00_PANDUAN.md`](../sql/00_PANDUAN.md), [`01_schema.sql`](../sql/01_schema.sql), [`04_validasi.sql`](../sql/04_validasi.sql), [`05_hitung_psw.sql`](../sql/05_hitung_psw.sql)
  - `DatasetBogorSeeder.php`, `UserSeeder.php`, & [`provenance.csv`](../sql/provenance.csv)
  - Folder [`database/seeders/csv/`](../../database/seeders/csv) & [`public/geojson/`](../../public/geojson)
  - Arsip historis referensi awal: `Software Architecture Document (SAD) (revisi 12).pdf`, `scouting_data_puskesmas.md`

---

## 1. Fondasi Teknologi (Tech Stack)

- **Tampilan (Frontend):**
  - **Template & Styling:** Laravel Blade Components + **Tailwind CSS v4** (melalui `@tailwindcss/vite` pada Vite 6+).
  - **Komponen & Interaktivitas Buatan Manusia (*Human-Crafted Libraries*):**
    - **Alpine.js 3.x** (`~15 KB`) untuk state manajemen ringan pada *contextual left drawer*, *tab switching*, *modal*, dan filter reaktif.
    - Pola komponen HTML/Tailwind teruji dari **Preline UI / Flowbite** yang dibungkus menjadi komponen Blade terstandar dan disesuaikan dengan variabel CSS [`UI_RULES.md`](./UI_RULES.md).
    - **Lucide Icons** (`mallardduck/blade-lucide-icons` untuk Blade dan paket resmi `lucide` / SVG resmi Lucide untuk pin `L.divIcon` Leaflet)—100% ikon garis *open-source* buatan desainer manusia, bukan gambar/stiker buatan AI.
  - **Mesin Peta & Grafik:** **Leaflet.js 1.9.4** (dengan 2 pilihan *tile layer* gratis tanpa API key: *OpenStreetMap Standard* dan *CartoDB Positron Grey Lite*) serta **Chart.js 4.x** untuk visualisasi tren kunjungan 12 bulan dan komposisi beban PSW.
- **Mesin Belakang (Backend):**
  - **Framework & Bahasa:** **Laravel 13** di atas **PHP 8.4** (syarat minimum PHP `>= 8.3`; lokal terverifikasi menjalankan `PHP 8.4.27`, `Composer 2.10.3`, `Node.js v24.22.1`, dan `npm.cmd 12.2.1`).
  - **Ekspor Dokumen (`FR-11`, `FR-21`):** **`barryvdh/laravel-dompdf`** untuk pembangkitan laporan PDF resmi siap cetak di sisi server, serta *streamed CSV response* native Laravel untuk ekspor CSV.
- **Penyimpanan Data (Database):**
  - **PostgreSQL 16/17 + Ekstensi Spasial PostGIS 3.4** yang di-*hosting* pada **Supabase Cloud**.
  - **Koneksi Supabase dari Laravel:** Wajib menggunakan **Supavisor Session Mode (Port `5432`)** pada host pooler regional (`aws-0-ap-southeast-1.pooler.supabase.com`) agar kompatibel penuh dengan jaringan IPv4 (baik di laptop lokal maupun VPS) serta mendukung *prepared statements* dan migrasi Laravel. Konfigurasi `config/database.php` pada koneksi `pgsql` menetapkan `'search_path' => 'public,extensions'` agar fungsi PostGIS (`ST_DWithin`, `ST_Distance`, `ST_Contains`, `ST_AsGeoJSON`, `ST_Buffer`, `ST_Union`, `ST_Difference`) terpanggil langsung.
- **Lingkungan Pengembangan & Deployment (Hosting):**
  - **Fase Pengembangan (Saat Ini):** Lokal di laptop (`PuskesMap/`) + GitHub Private Repository + database bersama di Supabase Cloud + **Cloudflare Quick Tunnel** (`cloudflared tunnel --url http://localhost:8000`) saat membutuhkan tautan HTTPS publik sementara untuk menguji Geolocation API (GPS) di smartphone atau *preview* tim (`Fajar` & `Azril`).
  - **Fase Produksi (H-7 Sebelum Presentasi):** **VPS Rumahweb KVM Paket M (1 vCPU, 2 GB RAM, 40 GB SSD, IPv4 Publik)** dijalankan dengan **Full Docker & Docker Compose via SSH (tanpa control panel)** berisi kontainer `nginx`, `php-fpm (PHP 8.4)`, dan `cloudflared` / SSL, ditambah *swapfile* 2 GB di level OS untuk mencegah *Out-of-Memory* (OOM) saat *build*, serta perintah terjadwal `Schedule::call(fn () => DB::select('SELECT 1'))->daily()` agar proyek Supabase Free Tier tidak mengalami *auto-pause*.

---

## 2. Pendekatan Pengerjaan (*Contract-Driven Frontend-First*) & Struktur Kode

- **Strategi *Contract-Driven Frontend-First*:**
  1. Seluruh antarmuka halaman, interaksi peta Leaflet ala OneMap Singapore, kalkulator radius MAUT, panel PSW, halaman detail, dan halaman admin dibangun terlebih dahulu di sisi *frontend* menggunakan **kontrak data JSON/Array yang 100% identik dengan nama kolom [`01_schema.sql`](../sql/01_schema.sql)** (bersumber langsung dari berkas CSV/GeoJSON 2025–2026 yang sudah bersih).
  2. Setelah tampilan dan interaksi *frontend* terverifikasi rapi, barulah *data provider* di Controller dihubungkan ke *Service Layer* PostGIS Supabase tanpa perlu mengubah struktur variabel di Blade maupun JavaScript.
- **Arsitektur *Service-Layer* yang Modular, *Maintainable*, & *Scalable* (Selaras SAD Tabel 2.1, Gambar 2.1, Tabel 3.1, & Tabel 3.3):**
  Logika bisnis dan perhitungan matematis/spasial **dilarang ditulis menumpuk di dalam Controller atau Blade** (`NFR-12`). Seluruh logika dipisahkan ke dalam kelas layanan (*Service*) sesuai penamaan resmi di `SAD (revisi 12).pdf`:

```text
PuskesMap/
├── app/
│   ├── Enums/                                  # 7 Backed Enum domain (UserRole, AuditAction, ServiceCategory, NakesCategory, CrowdStatus, WorkloadSpectrum, MautScenario)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── MapController.php               # Peta publik /peta & payload awal puskesmas
│   │   │   ├── AnalyticsController.php         # Dasbor analitik Dinkes (/analitik) & ekspor CSV/PDF (FR-11, FR-21)
│   │   │   ├── AuthController.php              # Login/logout sesi RBAC (pemerintah & admin — FR-14, FR-15)
│   │   │   ├── Api/V1/
│   │   │   │   ├── PuskesmasController.php     # API /api/v1/puskesmas*, /nearest, /radius, /{puskesmas}
│   │   │   │   ├── RecommendationController.php# API POST /api/v1/rekomendasi (MAUT Top-3)
│   │   │   │   └── AnalysisController.php      # API /api/v1/analisis/{coverage,equity,workload-spectrum,road-access}
│   │   │   └── Admin/
│   │   │       ├── PuskesmasController.php     # CRUD master puskesmas & validasi poligon kota (FR-16..17, FR-20)
│   │   │       ├── OperationalMetricsController.php # Pembaruan operasional, 9 nakes, & kunjungan (FR-18)
│   │   │       └── UserController.php          # Kelola akun internal (soft-deactivation) & audit log (FR-19)
│   │   ├── Middleware/
│   │   │   └── CheckRole.php                   # Verifikasi role ('admin'/'pemerintah'), is_active = true, & tolak akses dengan HTTP 403 (NFR-06)
│   │   └── Requests/                           # FormRequest terpisah (Auth, Api, Admin)
│   ├── Models/                                 # 8 Model Eloquent (Operasional::isOpenAt() & durationHours() menangani evaluasi WIB)
│   └── Services/
│       ├── RecommendationService.php           # Mesin MAUT 5 kriteria: filterEligible(), calculateScore(), rankFacilities()
│       ├── SpatialAnalysisService.php          # Kueri PostGIS & Cache GeoJSON: getNearestFacilities(), getFacilitiesInBuffer(), calculateServiceArea(), analyzeRoadAccessibility(), getEquityAnalysis(), flushSpatialCache()
│       ├── WorkloadService.php                 # Sentralisasi rumus PSW-Index: calculatePSWIndex(), classifyWorkloadSpectrum(), recalculateAndPersistScores(), getWorkloadDistribution()
│       ├── AuditLogService.php                 # Pencatatan mutasi data master otomatis ke tabel audit_log
│       └── ReportExportService.php             # Ekspor CSV stream & laporan PDF DomPDF (FR-21)
├── database/
│   ├── migrations/                             # 9 migrasi skema PostGIS + users role/is_active
│   └── seeders/
│       ├── DatasetBogorSeeder.php              # Seeder transaksional dari CSV resmi (memanggil WorkloadService)
│       ├── UserSeeder.php                      # Seeder akun awal admin & pemerintah (via env PUSKESMAP_*_PASSWORD)
│       └── csv/                                # 6 berkas CSV master hasil pemutakhiran resmi 2025–2026
├── public/
│   └── geojson/
│       └── jalan_utama.geojson                 # 941 ruas jalan utama OSM (lazy-loaded + HTTP cached)
├── routes/
│   ├── web.php                                 # Rute /, /peta, /login, /logout, /analitik*, /admin/*
│   ├── api_v1.php                              # Rute /api/v1/* (middleware web + throttle:api)
│   └── console.php                             # Penjadwalan harian supabase-keep-alive (SELECT 1)
└── resources/
    ├── css/                                    # app.css, landing.css, map.css (Tailwind v4)
    ├── js/                                     # app.js (Alpine.js), landing/landing-page.js, map/sdss-map.js
    └── views/
        ├── layouts/                            # Layout terisolasi SDSS (app.blade.php, map.blade.php)
        ├── components/ui/                      # Komponen Blade SDSS (<x-ui.button>, input, select, badge, drawer, modal)
        └── ...                                 # welcome.blade.php (Landing), peta.blade.php, auth/login.blade.php, analytics/, admin/, exports/
```

---

## 3. Struktur Penyimpanan Data (8 Tabel PostGIS Sesuai [`01_schema.sql`](../sql/01_schema.sql))

Struktur tabel relasional-spasial pada PostgreSQL/PostGIS (`SRID 4326` / WGS 84):

1. **`kecamatan` (6 baris):**
   - Kolom: `id`, `kode_kecamatan` (`UNIQUE`), `nama_kecamatan`, `geom` (`MultiPolygon, 4326` + indeks `GiST`), `jumlah_penduduk` (data resmi Tabel 1 Profil Kesehatan 2025: total `1.156.543` jiwa), `luas_wilayah_km2`.
2. **`puskesmas` (25 baris):**
   - Kolom: `id`, `kecamatan_id` (`FK -> kecamatan.id`), `kode_puskesmas` (`UNIQUE`), `nama_puskesmas`, `alamat`, `geom` (`Point, 4326` + indeks `GiST` pada `geom` dan `(geom::geography)`), `x_long`, `y_lat`, `website_url` (URL aktif `https://dinkes.kotabogor.go.id/<slug>`), `kategori_layanan` (`Non Rawat Inap`), `jumlah_posyandu` (Tabel 12 Profil Kesehatan 2025: total `1.000`), `jumlah_sekolah` (Tabel 47 Profil Kesehatan 2025: total `707`), `skor_psw` (`DECIMAL(5,2)`), `created_at`, `updated_at`.
3. **`operasional` (25 baris — Relasi 1:1 dengan `puskesmas`):**
   - Kolom: `id`, `puskesmas_id` (`UNIQUE FK`), `status_igd_24jam` (`BOOLEAN` — `true` pada 6 Puskesmas: Cipaku, Bogor Timur, Bogor Utara, Bogor Tengah, Pasir Mulya, Tanah Sareal), `jam_buka` (`TIME`), `jam_tutup` (`TIME` — menyimpan jam poli reguler Senin–Kamis `07:30–14:00` atau `08:00–14:00`), `kontak_darurat` (terisi lengkap 25/25 nomor resmi Dinkes), `rawat_inap` (`BOOLEAN` — seluruhnya `false`).
4. **`tenaga_medis` (25 baris — Relasi 1:1 dengan `puskesmas`):**
   - Kolom: `id`, `puskesmas_id` (`UNIQUE FK`), `dokter_umum`, `dokter_gigi`, `bidan`, `perawat`, `farmasi`, `gizi`, `kesling`, `kesmas`, `atlm`, `persentase_kelengkapan` (`DOUBLE PRECISION`), `kategori_jumlah_nakes` (`Lengkap` pada 19 Puskesmas, `Kurang Lengkap` pada 6 Puskesmas sesuai snapshot Dinkes 2 Jan 2026).
5. **`kunjungan_pasien` (300 baris — 25 Puskesmas x 12 Bulan Tahun 2025):**
   - Kolom: `id`, `puskesmas_id` (`FK`), `bulan` (`1..12`), `tahun` (`2025`), `total_pasien_bulanan` (pasien baru tahunan Tabel 5 Profil Kesehatan 2025 dibagi 12), `kapasitas_maksimal_bulanan` (`DEFAULT 850` pasien baru/bulan), `persentase_keramaian`, `status_kepadatan` (`Rendah`, `Sedang`, `Padat`). Constraint: `UNIQUE(puskesmas_id, tahun, bulan)`.
6. **`jangkauan_jalan` (37 baris — Tabel 5.6 SAD):**
   - Kolom: `id`, `kecamatan_id` (`FK`), `kategori_jangkauan` (`'Sangat Dekat'`, `'Dekat'`, `'Sedang'`, `'Jauh'`, atau `'Sangat Jauh'`), `jangkauan_faskes` (`'1000'`, `'2000'`, atau `'3000'` meter), `jangkauan_jalan` (`'200'`, `'500'`, atau `'1000'` meter), `geom` (`MultiPolygon, 4326` + indeks `GiST`).
7. **`users` (Akun Internal RBAC — Tabel 5.7 SAD):**
   - Kolom: `id`, `name`, `email` (`UNIQUE`), `password` (Bcrypt 12 rounds), `role` (`CHECK IN ('admin', 'pemerintah')`), `is_active` (`BOOLEAN DEFAULT TRUE`), `email_verified_at`, `remember_token`, `created_at`, `updated_at`.
8. **`audit_log` (Jejak Mutasi Data Master — Tabel 5.8 SAD):**
   - Kolom: `id`, `user_id` (`FK -> users.id`), `aksi` (`'INSERT'`, `'UPDATE'`, `'DELETE'`, atau `'DEACTIVATE'`), `tabel_target`, `target_id`, `deskripsi_perubahan`, `ip_address`, `created_at`.

---

## 4. Jalur Data, Endpoint API (Tabel 7.1 SAD), & Optimasi Cache Spasial

- **Rute Antarmuka Web Utama:**
  - `GET /` — Halaman Beranda / Landing Page PuskesMap (area tim Landing Page).
  - `GET /peta` (serta pengalihan `GET /map` -> `/peta`) — Kanvas Peta Utama WebGIS SDSS PuskesMap (Publik & Internal).
  - `GET /puskesmas/{kode_puskesmas}` *(rencana fase UI `T-02`)* — Halaman/panel detail lengkap Puskesmas (`FR-02`, `FR-12`, `FR-13`, `FR-22`).
  - `GET /analitik` & `GET /analitik/ekspor/{format}` (`csv|pdf`) — Khusus role `pemerintah` & `admin`: menampilkan Dasbor Analitik Spasial (`FR-11`), evaluasi `PSW-Index` (`FR-24`), rasio kecamatan (`FR-10`), jaringan jalan (`FR-23`), area cakupan/blank spot `1,5 km` atau `3,0 km` (`FR-09`), serta mengunduh laporan CSV/PDF (`FR-21`).
  - `GET|POST /login`, `POST /logout` (`FR-14`, `FR-15`), serta `Resource /admin/puskesmas` (tanpa `show`), `GET|PUT /admin/puskesmas/{puskesmas}/operasional`, `PUT /admin/puskesmas/{puskesmas}/tenaga-medis`, `GET|POST /admin/puskesmas/{puskesmas}/kunjungan`, `GET|POST|PUT /admin/users`, `PATCH /admin/users/{user}/deactivate`, dan `GET /admin/audit-log` (`FR-16` s/d `FR-20`, khusus role `admin`).
- **9 Endpoint REST API Internal Asinkron (`/api/v1/*`, middleware `web` + `throttle:api`):**
  1. `GET /api/v1/puskesmas` (Publik) — Parameter: `kecamatan_id`, `igd_24jam`, `jam_operasional`, `kelengkapan_nakes` -> Mengembalikan `GeoJSON FeatureCollection` lengkap beserta batas wilayah kecamatan ter-cache.
  2. `GET /api/v1/puskesmas/nearest` (Publik) — Parameter: `lat`, `lng`, `limit` (default `5`, maks `25`) -> Mengembalikan `GeoJSON FeatureCollection` Puskesmas terdekat terurut `ST_Distance`.
  3. `GET /api/v1/puskesmas/radius` (Publik) — Parameter: `lat`, `lng`, `radius` (integer `100–20000` meter) -> Mengembalikan `GeoJSON FeatureCollection` hasil penapisan `ST_DWithin` + `meta.buffer_geometry`.
  4. `GET /api/v1/puskesmas/{puskesmas}` (Publik, `{puskesmas}` numerik) — Mengembalikan detail lengkap 1 Puskesmas beserta operasional, 9 profesi Nakes, dan riwayat kunjungan 12 bulan.
  5. `POST /api/v1/rekomendasi` (Publik untuk `darurat|reguler`; khusus `pemerintah|admin` terautentikasi untuk `pemda` -> tamu mendapat `HTTP 403`) — Parameter: `lat`, `lng`, `scenario` (`darurat|reguler|pemda`), `radius` (opsional: `1000|3000|5000`) -> Menjalankan `RecommendationService::rankFacilities()` dan mengembalikan JSON Top-3 Rekomendasi MAUT beserta `radius_meters`.
  6. `GET /api/v1/analisis/coverage` (Pemerintah, Admin) — Parameter: `buffer_km` (`1.5` atau `3.0`) -> Menjalankan `ST_Union(ST_Buffer(geom::geography, radius))` dan mengembalikan `GeoJSON FeatureCollection` cakupan beserta kalkulasi luas *blank spot* ($\text{km}^2$) per kota & kecamatan.
  7. `GET /api/v1/analisis/equity` (Pemerintah, Admin) — Parameter: `kecamatan_id` (opsional) -> Mengembalikan `JSON Series Rasio Layanan` (`1 : 30.000` penduduk).
  8. `GET /api/v1/analisis/workload-spectrum` (Pemerintah, Admin) — Parameter: `kecamatan_id` (opsional), `tahun=2025` -> Mengembalikan `GeoJSON FeatureCollection` Spektrum `PSW-Index` dari `WorkloadService`.
  9. `GET /api/v1/analisis/road-access` (Pemerintah, Admin) — Parameter: `kecamatan_id` (opsional) -> Mengembalikan `GeoJSON FeatureCollection` 37 zona `jangkauan_jalan` yang di-cache.
- **Optimasi Performa & Penghematan Kuota *Egress* Supabase:**
  1. **Pembulatan Presisi Geometri & *Local Cache*:** Poligon `kecamatan`, `jangkauan_jalan`, dan area `coverage` diekspor menggunakan `ST_AsGeoJSON(geom, 6)` (presisi 6 desimal = ~11 cm) dan disimpan dengan `Cache::rememberForever(...)` pada kunci `sdss:kecamatan:boundaries`, `sdss:road_access:all`, `sdss:road_access:kec:{id}`, dan `sdss:coverage:{1.5|3.0}`.
  2. **Invalidasi Cache Otomatis (*Auto-Flush*):** Setiap kali Admin mengubah data `puskesmas`, `operasional`, `tenaga_medis`, atau `kunjungan_pasien` dalam transaksi database, sistem menjalankan `WorkloadService::recalculateAndPersistScores()` lalu `SpatialAnalysisService::flushSpatialCache()` agar peta selalu mutakhir tanpa membebani jaringan Supabase pada setiap kunjungan halaman.
  3. **Aset Statis *Lazy-Load*:** Berkas `public/geojson/jalan_utama.geojson` (`467 KB`) hanya di-*fetch* oleh browser ketika pengguna menyalakan *toggle* layer "Jaringan Jalan Utama".

---

## 5. Keamanan, Konfigurasi & Verifikasi

- **Nama Variabel Lingkungan (`.env` — Tanpa Kredensial Asli di Repo):**
  - Koneksi Database Supabase: `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT=5432`, `DB_DATABASE=postgres`, `DB_USERNAME`, `DB_PASSWORD`, `DB_SSLMODE=require`.
  - Sesi & Keamanan: `SESSION_DRIVER=database`, `SESSION_LIFETIME=120` (kedaluwarsa otomatis setelah 120 menit tanpa aktivitas sesuai `NFR-05`), `BCRYPT_ROUNDS=12`, serta tugas terjadwal `supabase-keep-alive` harian di `routes/console.php`.
  - Akun Seeder Awal: `PUSKESMAP_ADMIN_PASSWORD`, `PUSKESMAP_DINKES_PASSWORD`, `PUSKESMAP_CSV_DIR`.
- **Aturan Keamanan, RBAC & Paginasi (`NFR-05`, `NFR-06`, `NFR-07`, `NFR-09`):**
  - *Password hashing* wajib menggunakan Bcrypt 12 putaran (`Hash::make($password, ['rounds' => 12])` — `NFR-05`), disertai regenerasi ID sesi (`$request->session()->regenerate()`) sesaat setelah login berhasil dan penghapusan kuki sesi saat logout.
  - Middleware `CheckRole` memeriksa peran pengguna aktif (`admin` atau `pemerintah`) dan status `is_active = true` pada setiap rute dasbor analitik maupun panel pengelolaan master, serta menolak akses tidak sah dengan kode **HTTP 403 Forbidden** (`NFR-06`).
  - Penyajian tabel daftar master puskesmas, akun pengguna internal, dan catatan `audit_log` di halaman administrasi wajib dibatasi **`10` hingga `25` baris per halaman** melalui paginasi sisi peladen (`paginate(15)` — `NFR-09`).
  - Proteksi CSRF aktif pada seluruh form dan request mutasi (`POST/PUT/DELETE`), validasi tipe dan batas nilai melalui `FormRequest`, serta sanitasi teks WKT sebelum memanggil fungsi geometri PostGIS.
  - Sinkronisasi atomik koordinat: setiap mutasi titik Puskesmas wajib mengecek `ST_Contains(kecamatan.geom, ST_SetSRID(ST_MakePoint(x_long, y_lat), 4326))` sebelum menyimpan `x_long`, `y_lat`, dan `geom` secara bersamaan (`FR-20`, `NFR-07`).
- **Perintah Menjalankan & Verifikasi Proyek:**
  - Menjalankan server lokal (Windows PowerShell): `php artisan serve` dan `npm.cmd run dev`.
  - Menjalankan pengujian otomatis & kompilasi aset: `php artisan test` dan `npm.cmd run build`.
  - Menjalankan verifikasi integritas geospasial PostGIS: menjalankan kueri pemeriksaan pada [`04_validasi.sql`](../sql/04_validasi.sql) (memastikan jumlah baris `6 / 25 / 25 / 25 / 300 / 37` dan 0 baris anomali pada blok validasi geometri, `ST_Contains`, serta konsistensi kepadatan).
