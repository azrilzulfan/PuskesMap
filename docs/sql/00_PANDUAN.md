# Paket Dataset Master PuskesMap (PostgreSQL 16 + PostGIS 3.4)

## Isi Folder
- `sql/` : `01_schema.sql` (8 tabel relasional PostGIS sesuai Kamus Data SAD Revisi 12 Bab V), `02_data.sql`, `03_data_jangkauan_kunjungan.sql`, `04_validasi.sql`, dan `05_hitung_psw.sql`. Jalankan secara berurutan.
- `csv/` : Dataset bersih untuk seeder Laravel (6 berkas: `kecamatan.csv`, `puskesmas.csv`, `operasional.csv`, `tenaga_medis.csv`, `kunjungan_pasien.csv`, dan `jangkauan_jalan.csv`). Kolom `kode_kecamatan` dan `kode_puskesmas` dipakai seeder untuk menautkan `kecamatan_id` serta `puskesmas_id`. Kolom `geom_wkt` sudah berurutan standar OGC `(longitude latitude)` untuk `ST_GeomFromText(geom_wkt, 4326)`.
- `geojson/` : `kecamatan.geojson`, `puskesmas.geojson`, dan `jalan_utama.geojson` (standar RFC 7946 `[longitude, latitude]`, 941 ruas jalan utama Kota Bogor berukuran 467 KB) untuk kanvas peta Leaflet.js.
- `seeder/` : `PuskesmapDatabaseSeeder.php` yang membaca keenam berkas `csv/` bersih dan menghitung `skor_psw` otomatis selaras dengan `05_hitung_psw.sql`.
- `provenance.csv` : Catatan asal-usul setiap kolom (sumber resmi, berkas mentah proyek, estimasi disagregasi, atau asumsi terukur).

## Cara Pakai
1. Jalankan melalui SQL murni: buat basis data kosong ber-PostGIS, lalu jalankan `01_schema.sql` sampai `05_hitung_psw.sql` secara berurutan. Periksa hasil `04_validasi.sql`: blok 1 menghasilkan `6 / 25 / 25 / 25 / 300 / 37`, sedangkan blok 2-6 dan 9-11 menghasilkan 0 baris anomali.
2. Jalankan melalui Laravel Seeder: pastikan skema migrasi 8 tabel sudah terbentuk dan variabel lingkungan kata sandi akun awal (`PUSKESMAP_ADMIN_PASSWORD` dan `PUSKESMAP_DINKES_PASSWORD`) telah diisi pada `.env` lokal sebelum menjalankan `PuskesmapDatabaseSeeder`.

## Dua Parameter yang Dapat Disesuaikan Satu Perintah
Tahun data kunjungan (mengacu pada Profil Kesehatan Kota Bogor Tahun 2025):
    UPDATE kunjungan_pasien SET tahun = 2025;
Kapasitas acuan bulanan (saat ini `850` pasien baru/bulan, terkalibrasi dari sebaran Tabel 5 Profil Kesehatan 2025 sehingga menghasilkan 4 Padat, 12 Sedang, 9 Rendah). Apabila Dinas Kesehatan menetapkan kapasitas resmi baru, misalnya `1000`:
    UPDATE kunjungan_pasien SET kapasitas_maksimal_bulanan = 1000,
      persentase_keramaian = round(100.0*total_pasien_bulanan/1000, 2),
      status_kepadatan = CASE WHEN 100.0*total_pasien_bulanan/1000 < 60 THEN 'Rendah' WHEN 100.0*total_pasien_bulanan/1000 <= 85 THEN 'Sedang' ELSE 'Padat' END;
Setelah memperbarui data kunjungan, jalankan kembali `05_hitung_psw.sql`.

## Batasan Dataset yang Perlu Diketahui
- Data kunjungan bulanan berasal dari angka tahunan **Kunjungan Pasien Baru Rawat Jalan Tahun 2025** per Puskesmas (Tabel 5 Profil Kesehatan Kota Bogor 2025, total kota 174.861 pasien baru) yang dirata-ratakan ke 12 bulan (`ROUND(tahunan / 12)`), karena sumber resmi pemerintah tidak merilis rincian per bulan.
- Jumlah unit Posyandu (total 1.000, Tabel 12) dan Sekolah binaan SD/SMP/SMA (total 707, Tabel 47) menggunakan data riil masing-masing Puskesmas dari Profil Kesehatan Kota Bogor Tahun 2025.
- Nomor kontak pada kolom `kontak_darurat` dan `website_url` (`https://dinkes.kotabogor.go.id/puskesmas-<slug>`) telah terisi lengkap untuk seluruh 25 Puskesmas dari REST API resmi Bogor Smart Health Dinas Kesehatan Kota Bogor.
- Kolom `jam_buka` dan `jam_tutup` merepresentasikan jadwal pelayanan poli reguler Senin–Kamis (`07:30:00` s/d `14:00:00`, atau `08:00:00` s/d `14:00:00` untuk Cipaku, Pulo Armyn, dan Kedung Badak), terpisah dari kesiagaan kegawatdaruratan pada `status_igd_24jam` agar tidak menduplikasi bobot MAUT.
- Poligon batas kecamatan berasal dari basis data GADM (dengan `jumlah_penduduk` 2025 total `1.156.543` jiwa sesuai Tabel 1 Profil Kesehatan 2025), sedangkan jejaring jalan utama (`jalan_utama.geojson`) memuat 941 ruas lima kelas jalan utama setelah dipotong tepat mengikuti batas Kota Bogor (sinkron dengan SAD Revisi 12 Bab VI).

## Ringkasan Validasi Geospasial
Seluruh geometri poligon valid pada SRID 4326, ke-25 titik puskesmas berada tepat di dalam kecamatannya masing-masing, 37 poligon zona jangkauan menutup wilayah kecamatan tanpa tumpang-tindih, akumulasi 12 bulan kunjungan bulanan per kecamatan cocok dengan pembulatan Tabel 5 Profil Kesehatan 2025 (`174.876` dari `174.861` tahunan), dan estimasi area di luar radius 3 km tercatat sekitar `11,8 km2` dari `111,8 km2`.
