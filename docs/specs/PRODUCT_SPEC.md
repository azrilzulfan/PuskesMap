# PRODUCT_SPEC.md

File ini adalah cetak biru produk yang menjelaskan **APA** yang dibuat dan **KENAPA** produk ini dibutuhkan (penggabungan ringkas dari BRD, PRD, Use Case, dan User Flow).
*(Untuk urusan tampilan visual lihat [`specs/design/DESIGN_SPEC.md`](./DESIGN_SPEC.md), dan untuk urusan teknis/database lihat [`specs/tech/TECH_SPEC.md`](./TECH_SPEC.md)).*

---

## Metadata

- Nama Produk: PuskesMap — Sistem Pendukung Keputusan Spasial (WebGIS) Pemetaan Layanan dan Prioritas Puskesmas Kota Bogor
- Status Spec: Disetujui
- Terakhir diperbarui: 2026-11-11
- File Bahan / Referensi:
  - `Software Requirements Specification (SRS) (revisi 12).pdf`
  - `Use_Case_PuskesMap_v2.png`
  - `scouting_data_puskesmas.md` (Rekapitulasi Hasil Scouting Data Resmi Dinkes Kota Bogor 2025–2026)

---

## 1. Ringkasan & Tujuan Produk

- **Masalah Utama:** Masyarakat Kota Bogor kesulitan menemukan Puskesmas terdekat yang paling sesuai dengan kondisi darurat atau reguler (karena informasi jam layanan, kesiagaan UGD 24 jam, tingkat kepadatan kunjungan, dan kelengkapan tenaga medis tersebar terpisah), sementara Dinas Kesehatan Kota Bogor membutuhkan alat bantu visual geospasial untuk mengevaluasi ketimpangan beban wilayah kerja dan kelengkapan SDM antar-Puskesmas.
- **Solusi Produk:** Aplikasi WebGIS berbasis *Spatial Decision Support System* (SDSS) yang memetakan seluruh 25 Puskesmas dan 6 Kecamatan di Kota Bogor secara interaktif, dilengkapi pencarian radius geodetik + perankingan *Top-3* berbasis metode **MAUT (*Multi-Attribute Utility Theory*)** untuk warga, serta analitik kewilayahan berbasis indeks komposit **PSW-Index (*Puskesmas Spatial Workload Index* / *Indeks Beban Spasial Wilayah*)** untuk Dinas Kesehatan.
- **Target Hasil:**
  - Warga dapat menemukan dan membandingkan 3 rekomendasi Puskesmas terbaik di sekitar titik lokasinya dalam waktu kurang dari 30 detik (waktu muat awal halaman peta `<= 2,0 detik` sesuai `NFR-01`, antarmuka responsif `360px` s/d `1920px` sesuai `NFR-02`, dan respon kueri spasial radius/jarak `< 1,5 detik` sesuai `NFR-03`).
  - Staf Dinas Kesehatan dapat mengidentifikasi Puskesmas berbeban tinggi, wilayah di luar jangkauan layanan radius `1,5 km` atau `3,0 km` (*blank spot* — `FR-09`), ketimpangan rasio penduduk (`1 : 30.000` — `FR-10`), dan kekurangan profesi tenaga kesehatan secara langsung di atas peta serta mengunduh laporannya dalam format CSV dan PDF (`FR-11`, `FR-21`).
- **Landasan Regulasi yang Diselaraskan:**
  - Mengacu pada standar rasio spasial-demografis dan ketenagaan **Permenkes No. 43 Tahun 2019 yang diperbarui dan dipertahankan dalam Permenkes No. 19 Tahun 2024 Pasal 10**, yaitu:
    1. Minimal 1 Puskesmas di setiap kecamatan dan rasio kecukupan **1 Puskesmas per 30.000 penduduk**.
    2. Standar **9 profesi tenaga kesehatan inti** (*Dokter Umum, Dokter Gigi, Perawat, Bidan, Tenaga Promosi Kesehatan/Kesmas, Tenaga Sanitasi Lingkungan/Kesling, Nutrisionis/Gizi, Tenaga Kefarmasian/Apoteker, dan Ahli Teknologi Laboratorium Medik/ATLM*). Berdasarkan data resmi Tabel 14, 16, dan 17 Profil Kesehatan Dinas Kesehatan Kota Bogor Tahun 2025, dua profesi tambahan baru pada Permenkes 19/2024 (*Psikolog Klinis* dan *Fisioterapis*) masih bernilai `0` di seluruh 25 Puskesmas Kota Bogor, sehingga 9 profesi inti digunakan sebagai tolok ukur operasional yang valid.

---

## 2. Daftar Pengguna & Peran (User Roles)

- **Masyarakat Umum (Publik — Tanpa Login):**
  - Menjelajahi peta interaktif 25 Puskesmas dan batas 6 Kecamatan di Kota Bogor (`FR-01`).
  - Mencari Puskesmas berdasarkan kecocokan nama fasilitas, alamat, atau nama kecamatan dengan jeda input otomatis (*debounce* `300 ms` — `FR-03`) serta memfilter berdasarkan wilayah kecamatan, kesiapan IGD 24 jam, status jam buka, dan kategori kelengkapan tenaga kesehatan (`FR-04`).
  - Menentukan titik lokasi melalui sensor geolokasi HTML5 (GPS perangkat) atau klik pin manual di peta (`FR-05`), memilih radius pencarian (`1 km`, `3 km`, atau `5 km` — `FR-06`), dan mendapatkan rekomendasi **Top-3 Puskesmas terbaik** menggunakan metode MAUT pada skenario **`Darurat`** atau **`Reguler`** disertai penyorotan otomatis pada peringkat pertama (`FR-07`, `FR-08`).
  - Melihat halaman/panel detail lengkap setiap Puskesmas: alamat lengkap, kecamatan, kontak darurat, jam pelayanan, kesiagaan IGD 24 jam, kategori layanan, beban binaan UKM (Posyandu & Sekolah), rincian ketersediaan 9 profesi tenaga kesehatan, indikator estimasi keramaian pasien bulanan, dan tombol tautan langsung ke situs resmi `kotabogor.go.id` (`FR-02`, `FR-12`, `FR-13`, `FR-22`).
- **Pemerintah / Staf Dinas Kesehatan Kota Bogor (Role `pemerintah` — Wajib Login via `FR-14`, `FR-15`):**
  - Memiliki seluruh akses Masyarakat Umum ditambah akses ke skenario bobot MAUT ke-3 yaitu **`Evaluasi Pemda`** (`FR-07`).
  - Mengaktifkan layer analitik spasial kewilayahan:
    1. **Peta Choropleth Rasio Penduduk (`FR-10`):** Evaluasi rasio Puskesmas per 30.000 penduduk di 6 kecamatan (*Ideal* `<= 1:30.000` vs *Kurang* `> 1:30.000`).
    2. **Buffer Cakupan Wilayah Layanan `1,5 km` atau `3,0 km` & Deteksi *Blank Spot* (`FR-09`):** Visualisasi cakupan geografis gabungan (`ST_Buffer` & `ST_Union`) dari 25 Puskesmas untuk memperlihatkan area permukiman di luar jangkauan layanan.
    3. **Layer Zona Jangkauan Jalan & Jaringan Jalan Utama (`FR-23`):** Visualisasi 37 poligon zona keterjangkauan faskes/jalan dan 941 ruas jalan utama Kota Bogor.
  - Membuka Dasbor Analitik Spasial (`FR-11`) & evaluasi **Indeks Beban Spasial Wilayah (`PSW-Index` — `FR-24`)** untuk melihat peringkat beban wilayah ke-25 Puskesmas dalam spektrum *Tinggi*, *Ideal*, dan *Rendah*.
  - Mengunduh tabel rekapitulasi profil, rasio layanan, dan skor beban spasial 25 Puskesmas dalam format **CSV** dan **PDF** siap cetak (`FR-21`).
- **Administrator Sistem (Role `admin` — Wajib Login via `FR-14`, `FR-15`):**
  - Memiliki seluruh akses Pemerintah ditambah pengelolaan data master (*CRUD*):
    1. Menambah, mengubah, atau menghapus data master profil Puskesmas (`FR-16`) beserta penentuan titik koordinat geodetik lewat pemilih titik interaktif di peta (`FR-17`) yang divalidasi dengan `ST_Contains` agar berada di dalam poligon resmi Kota Bogor/kecamatannya (`FR-20`, `NFR-07`).
    2. Memperbarui jumlah staf 9 profesi tenaga kesehatan, jadwal jam buka reguler, status IGD 24 jam, serta data kunjungan pasien bulanan yang otomatis memicu perhitungan ulang persentase keramaian dan skor `PSW-Index` (`FR-18`, `FR-22`, `FR-24`).
    3. Mengelola akun pengguna internal (`admin` dan `pemerintah`) dengan pengaturan status aktif (*soft-deactivation* `is_active = false`) serta memantau catatan riwayat mutasi data pada tabel `audit_log` berpaginasi (`FR-19`, `NFR-09`).

---

## 3. Cakupan Fitur (Scope)

### Fitur Utama (In-Scope / Wajib Ada)
1. **Modul Peta Interaktif & Eksplorasi Publik (`FR-01`, `FR-03`, `FR-04`):** Peta dasar Kota Bogor (dengan pilihan basemap *OSM Standar* dan *Grey Lite*), pin kustom pembeda Puskesmas Reguler vs UGD 24 Jam, pencarian *real-time* (*debounce* `300 ms`), dan filter multi-kriteria.
2. **Modul Pencarian Radius Geodetik & Rekomendasi Top-3 MAUT (`FR-05` s/d `FR-08`):** Penentuan titik pengguna (GPS/Pin Manual), filter jarak `ST_DWithin` (`1 km`, `3 km`, atau `5 km`), normalisasi Min-Max 5 kriteria, pemilihan skenario bobot, kartu peringkat `#1`, `#2`, `#3` dengan rincian kontribusi nilai, serta penyorotan otomatis Puskesmas peringkat `#1` di peta.
3. **Modul Detail Profil Puskesmas (`FR-02`, `FR-12`, `FR-13`, `FR-22`):** Ringkasan identitas, kontak resmi, jam layanan, komposisi 9 profesi Nakes (beserta persentase pemenuhan standar Permenkes 43/2019), beban binaan UKM (Posyandu & Sekolah 2025), indikator estimasi keramaian & grafik kunjungan bulanan 12 bulan, serta tautan resmi `https://dinkes.kotabogor.go.id/<slug>`.
4. **Modul Analitik Spasial & Beban Wilayah Dinkes (`FR-09` s/d `FR-11`, `FR-21`, `FR-23`, `FR-24`):** Layer tematik rasio penduduk kecamatan, buffer cakupan layanan `1,5 km` atau `3,0 km` (*blank spot*), 37 poligon jangkauan jalan, 941 ruas jalan utama, dasbor eksekutif & peringkat `PSW-Index`, serta ekspor laporan CSV dan PDF.
5. **Modul Manajemen Data Master, RBAC & Audit Log (`FR-14` s/d `FR-20`, `NFR-05` s/d `NFR-07`, `NFR-09`):** Autentikasi berbasis sesi (Bcrypt 12, CSRF, *timeout* 120 menit) untuk `pemerintah` dan `admin`, CRUD tervalidasi spasial (`ST_Contains`), rekalkulasi otomatis `skor_psw`, *soft-deactivation* akun (`is_active`), dan log audit mutasi data berpaginasi (`10`–`25` baris per halaman).

### Batasan (Out-of-Scope / Tidak Dibuat)
- Navigasi rute perjalanan belok-demi-belok (*turn-by-turn street routing*).
- Pendaftaran antrean pasien online atau reservasi jadwal dokter.
- Aplikasi mobile native (Android/iOS); akses perangkat bergerak dilayani melalui desain web responsif.
- Pengelolaan Rekam Medis Elektronik (RME) atau data klinis individu pasien.
- Sistem pembayaran atau klaim pembiayaan kesehatan digital.
- Prediksi wabah/epidemiologi berbasis kecerdasan buatan (*machine learning*).

---

## 4. Alur Kerja Pengguna (User Flow)

- **Alur 1 — Masyarakat Mencari Rekomendasi Puskesmas Terdekat (MAUT):**
  `Buka Peta Utama PuskesMap` -> `Buka tab "Rekomendasi Radius" di panel kiri` -> `Klik "Gunakan Lokasi Saya (GPS)" atau klik titik di peta (FR-05)` -> `Pilih radius (1 / 3 / 5 km — FR-06) & skenario ("Darurat" atau "Reguler" — FR-07)` -> `Sistem menampilkan lingkaran radius, daftar Top-3 Puskesmas terbaik, serta otomatis menggerakkan fokus peta (flyTo) dan menyorot penanda Puskesmas peringkat #1 (FR-08)` -> `Klik kartu peringkat #1/#2/#3 untuk melihat profil detail lengkap`
- **Alur 2 — Staf Dinkes Mengevaluasi Beban Wilayah & Mengunduh Laporan:**
  `Login sebagai Pemerintah (Dinkes — FR-14, FR-15)` -> `Buka Dasbor Analitik / mode "Beban Wilayah (PSW)" (FR-11, FR-24) atau nyalakan layer tematik di toolbar kanan (ganti basemap ke Grey Lite agar poligon kontras)` -> `Amati spektrum warna PSW (Tinggi / Ideal / Rendah), rasio kecamatan (FR-10), jaringan jalan (FR-23), dan area cakupan/blank spot radius 1,5 km atau 3,0 km (FR-09)` -> `Klik tombol "Unduh CSV" atau "Unduh PDF" (FR-21)` -> `Sistem mengunduh dokumen rekapitulasi resmi 25 Puskesmas`
- **Alur 3 — Administrator Memperbarui Data Master Puskesmas:**
  `Login sebagai Admin (FR-14, FR-15)` -> `Buka halaman Manajemen Data (FR-16)` -> `Pilih Puskesmas & ubah data (misal: jumlah tenaga medis, posyandu, kunjungan bulanan [FR-18], atau geser pin koordinat [FR-17])` -> `Klik "Simpan Perubahan"` -> `Sistem memvalidasi batas kecamatan (ST_Contains — FR-20), menghitung ulang skor PSW otomatis (FR-24), mencatat riwayat ke audit_log (FR-19), menghapus cache peta, dan menampilkan pesan berhasil`

---

## 5. Aturan & Logika Bisnis (Business Rules)

1. **Aturan Evaluasi Status "Sedang Buka" (`FR-04`):**
   - Dievaluasi terhadap waktu aktual zona `Asia/Jakarta` (WIB):
     - Jika `status_igd_24jam = true`: Puskesmas selalu berstatus **Buka (Siaga UGD 24 Jam)** setiap hari termasuk hari Minggu dan malam hari (disertai keterangan pada UI bahwa di luar jam poli reguler layanan yang aktif adalah UGD 24 jam).
     - Jika `status_igd_24jam = false`: Senin–Kamis mengikuti rentang `jam_buka` s/d `jam_tutup` pada database (`07:30–14:00` atau `08:00–14:00`), Jumat dievaluasi pada `07:30–14:30`, Sabtu pada `07:30–12:00`, dan hari Minggu otomatis berstatus **Tutup**.
2. **Aturan Perhitungan Rekomendasi Top-3 MAUT (`FR-07`, `FR-08` — Tabel 3.3 SRS Revisi 12):**
   - Kandidat hanya mencakup Puskesmas yang berada di dalam radius pilihan pengguna (`ST_DWithin` geodetik: `1 km`, `3 km`, atau `5 km`).
   - Lima kriteria yang dinormalisasi dengan Min-Max ke rentang `[0, 1]`:
     - $C_1$ **Jarak Spasial Geodetik (`km`)** (*Cost* — semakin dekat semakin tinggi): $\frac{\max(x) - x}{\max(x) - \min(x)}$
     - $C_2$ **Kesiapan Layanan IGD 24 Jam** (*Benefit* — `1` jika tersedia, `0` jika tidak)
     - $C_3$ **Durasi Jam Operasional Reguler** (*Benefit* — selisih jam tutup dan jam buka reguler): $\frac{x - \min(x)}{\max(x) - \min(x)}$
     - $C_4$ **Kelengkapan Sembilan Profesi Nakes** (*Benefit* — `persentase_kelengkapan`): $\frac{x - \min(x)}{\max(x) - \min(x)}$
     - $C_5$ **Tingkat Estimasi Keramaian Pasien** (*Cost* — `persentase_keramaian`, semakin lengang semakin tinggi): $\frac{\max(x) - x}{\max(x) - \min(x)}$
   - **Tiga Skenario Bobot ($W_1, W_2, W_3, W_4, W_5$ sesuai Tabel 3.3 SRS):**
     - **`Darurat` (Publik & Internal):** Jarak `0,40` | IGD 24 Jam `0,30` | Jam Operasional `0,15` | Kelengkapan Nakes `0,15` | Keramaian Pasien `0,00`.
     - **`Reguler` (Publik & Internal):** Jarak `0,20` | IGD 24 Jam `0,10` | Jam Operasional `0,30` | Kelengkapan Nakes `0,20` | Keramaian Pasien `0,20`.
     - **`Evaluasi Pemda` (Khusus Login `pemerintah` & `admin`):** Jarak `0,20` | IGD 24 Jam `0,15` | Jam Operasional `0,15` | Kelengkapan Nakes `0,30` | Keramaian Pasien `0,20`.
   - **Penanganan *Edge-Case* Nilai Seragam ($\max = \min$):** Untuk mencegah pembagian dengan nol (*division by zero*), apabila seluruh kandidat dalam radius memiliki nilai mentah yang sama persis pada suatu kriteria, nilai normalisasi ditetapkan `0,0` jika nilai mentahnya `0` pada kriteria *benefit*, atau `1,0` pada kondisi lainnya.
3. **Aturan Perhitungan Indeks Beban Spasial Wilayah (`PSW-Index` — `FR-11`, `FR-24`):**
   - Mengikuti rumus baku [`05_hitung_psw.sql`](../sql/05_hitung_psw.sql) dan `PuskesmapDatabaseSeeder.php`:
     $$\text{Skor PSW} = \text{round}(0,40 \cdot N_{\text{pop}} + 0,20 \cdot N_{\text{edu}} + 0,20 \cdot N_{\text{pos}} + 0,20 \cdot N_{\text{kur}}, 2)$$
     di mana $N_{\text{pop}}$ adalah penduduk kecamatan dibagi jumlah Puskesmas di kecamatan tersebut, $N_{\text{edu}}$ adalah `jumlah_sekolah` binaan (Tabel 47 Profil Kesehatan 2025), $N_{\text{pos}}$ adalah `jumlah_posyandu` aktif (Tabel 12 Profil Kesehatan 2025), dan $N_{\text{kur}}$ adalah rata-rata `total_pasien_bulanan` (Tabel 5 Profil Kesehatan 2025), masing-masing dinormalisasi Min-Max lintas 25 Puskesmas.
   - **Klasifikasi Spektrum Beban Kerja Spasial (Tabel 10.3 SAD):**
     - `>= 0,70`: **Tinggi** (membutuhkan prioritas intervensi/penambahan sumber daya)
     - `0,40 s/d < 0,70`: **Ideal**
     - `< 0,40`: **Rendah**
4. **Aturan Kapasitas & Kepadatan Kunjungan Pasien Baru (`kunjungan_pasien` — `FR-22`):**
   - Menggunakan angka resmi **Kunjungan Pasien Baru Rawat Jalan Tahun 2025** per Puskesmas (Tabel 5 Profil Kesehatan Kota Bogor 2025) yang dirata-ratakan ke 12 bulan (`tahunan / 12`), dengan `kapasitas_maksimal_bulanan` terkalibrasi sebesar **`850` pasien baru/bulan**.
   - Ambang `persentase_keramaian = round(100 * total_pasien_bulanan / kapasitas_maksimal_bulanan, 2)`:
     - `< 60%`: **Rendah** (9 Puskesmas)
     - `60% s/d 85%`: **Sedang** (12 Puskesmas)
     - `> 85%`: **Padat** (4 Puskesmas: Kayu Manis, Sempur, Warung Jambu, Semplak)
5. **Aturan Validasi Spasial & Integritas Audit (`FR-17`, `FR-19`, `FR-20`, `NFR-07`):**
   - Setiap penambahan atau pemindahan titik koordinat Puskesmas oleh Admin wajib lolos uji `ST_Contains(kecamatan.geom, puskesmas.geom)` agar titik tidak keluar dari batas poligon kecamatannya.
   - Akun pengguna internal tidak boleh dihapus permanen (`DELETE`) dari database; pencabutan akses wajib menggunakan `is_active = false` agar kunci tamu (`user_id`) pada tabel `audit_log` tetap terjaga utuh.
