# Paket Dataset PuskesMap (diuji di PostgreSQL 16 + PostGIS 3.4)

## Isi folder
- `sql/` : 01_schema (8 tabel), 02_data, 03_data_jangkauan_kunjungan, 04_validasi, 05_hitung_psw. Jalankan berurutan.
- `csv/` : isi yang sama untuk seeder Laravel (6 file). Kolom `kode_kecamatan` dan `kode_puskesmas` dipakai seeder untuk mencari `kecamatan_id` / `puskesmas_id`. Kolom `geom_wkt` berurutan (long lat): `ST_GeomFromText(geom_wkt, 4326)`.
- `geojson/` : kecamatan, puskesmas, jalan_utama (RFC 7946, urutan [long, lat]) untuk Leaflet.
- `provenance.csv` : asal setiap kolom (resmi / dari file kamu / estimasi / asumsi).

## Cara pakai
1. Buat database kosong dengan PostGIS. Jalankan 01 sampai 05 berurutan. Kalau pakai migration Laravel, lewati 01.
2. Cek hasil 04_validasi: baris pertama 6 / 25 / 25 / 25 / 300 / 37; blok 2-6 dan 9-11 harus kosong.

## Dua hal yang bisa kamu ubah dengan satu perintah
Tahun data kunjungan (file sumber tidak menyebut tahun, dipakai 2025):
    UPDATE kunjungan_pasien SET tahun = 2024;
Kapasitas acuan (sekarang 5603, beban bulanan tertinggi pada data). Kalau Dinkes memberi kapasitas resmi, misalnya 3000:
    UPDATE kunjungan_pasien SET kapasitas_maksimal_bulanan = 3000,
      persentase_keramaian = round(100.0*total_pasien_bulanan/3000, 2),
      status_kepadatan = CASE WHEN 100.0*total_pasien_bulanan/3000 < 60 THEN 'Rendah' WHEN 100.0*total_pasien_bulanan/3000 <= 85 THEN 'Sedang' ELSE 'Padat' END;
Setelah mengubah kunjungan, jalankan ulang 05_hitung_psw.sql.

## Batasan yang harus kamu ketahui
- Data kunjungan adalah ESTIMASI: file sumber hanya punya total per kecamatan, jadi semua puskesmas dalam satu kecamatan dan semua bulan bernilai sama. Pola musiman dan perbedaan antar puskesmas belum ada. Akibatnya skor PSW puskesmas satu kecamatan banyak yang sama.
- Posyandu dan sekolah per puskesmas juga estimasi (dibagi rata dari total resmi).
- Kontak darurat baru terisi 10 dari 25 dan berasal dari pihak ketiga; verifikasi sebelum tampil ke publik.
- Jam buka hanya satu jadwal (Senin-Kamis); jadwal Jumat dan Sabtu tidak muat di tabel.
- Batas kecamatan berasal dari GADM, bukan Satu Peta. Dokumenmu mensyaratkan data resmi (ARC-05).
- Jalan: dokumen menulis 7.457 ruas / 2,1 MB. Data aslinya hanya 958 ruas untuk lima kelas itu (941 setelah dipotong batas kota).

## Hasil uji yang sudah dijalankan
Semua skrip jalan dari database kosong tanpa error. Poligon valid, ke-25 titik berada di kecamatan yang benar, jangkauan menutup kecamatan tanpa tumpang-tindih, total kunjungan per kecamatan sama persis dengan file sumber, blank spot sekitar 11,8 km2 dari 111,8 km2.
