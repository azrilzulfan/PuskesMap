-- 04_validasi.sql : pemeriksaan GIS setelah SEMUA data dimuat. Tiap blok menghasilkan satu tabel.
-- Yang BAGUS: blok 1 = 6 / 25 / 25 / 25 / 300 / 37 ; blok 2-6 dan 9-11 kosong (0 rows).

-- 1. Jumlah baris
SELECT (SELECT count(*) FROM kecamatan) AS kecamatan, (SELECT count(*) FROM puskesmas) AS puskesmas,
       (SELECT count(*) FROM operasional) AS operasional, (SELECT count(*) FROM tenaga_medis) AS tenaga_medis,
       (SELECT count(*) FROM kunjungan_pasien) AS kunjungan, (SELECT count(*) FROM jangkauan_jalan) AS jangkauan;

-- 2. Geometri valid dan SRID 4326 (kecamatan & jangkauan)
SELECT 'kecamatan' AS tabel, id FROM kecamatan WHERE NOT ST_IsValid(geom) OR ST_SRID(geom) <> 4326
UNION ALL SELECT 'jangkauan_jalan', id FROM jangkauan_jalan WHERE NOT ST_IsValid(geom) OR ST_SRID(geom) <> 4326;

-- 3. FR-20: setiap puskesmas berada di dalam kecamatannya
SELECT p.kode_puskesmas, p.nama_puskesmas FROM puskesmas p JOIN kecamatan k ON k.id = p.kecamatan_id
WHERE NOT ST_Contains(k.geom, p.geom);

-- 4. x_long/y_lat harus sama dengan geom
SELECT kode_puskesmas FROM puskesmas WHERE ABS(x_long - ST_X(geom)) > 1e-6 OR ABS(y_lat - ST_Y(geom)) > 1e-6;

-- 5. Kode kecamatan di kode puskesmas cocok dengan kecamatan hasil spasial
SELECT p.kode_puskesmas FROM puskesmas p JOIN kecamatan k ON k.id = p.kecamatan_id WHERE substr(p.kode_puskesmas, 2, 7) <> k.kode_kecamatan;

-- 6. Poligon jangkauan tidak boleh tumpang-tindih (toleransi 1 m2) dan tidak keluar dari kecamatannya
SELECT a.id, b.id AS bertabrakan_dengan FROM jangkauan_jalan a JOIN jangkauan_jalan b
  ON a.id < b.id AND a.kecamatan_id = b.kecamatan_id AND ST_Intersects(a.geom, b.geom)
WHERE ST_Area(ST_Intersection(a.geom, b.geom)::geography) > 1
UNION ALL
SELECT j.id, NULL FROM jangkauan_jalan j JOIN kecamatan k ON k.id = j.kecamatan_id
WHERE ST_Area(ST_Difference(j.geom, ST_Buffer(k.geom, 0.0005))::geography) > 1;

-- 7. Luas poligon dibanding luas resmi (selisih wajar < 5%), dan luas jangkauan harus = luas kecamatan (rasio 1.00)
SELECT k.nama_kecamatan, k.luas_wilayah_km2 AS luas_resmi,
       round((ST_Area(k.geom::geography)/1e6)::numeric, 2) AS luas_poligon,
       round((100*(ST_Area(k.geom::geography)/1e6 - k.luas_wilayah_km2)/k.luas_wilayah_km2)::numeric, 1) AS selisih_persen,
       round((sum(ST_Area(j.geom::geography))/ST_Area(k.geom::geography))::numeric, 3) AS rasio_jangkauan
FROM kecamatan k JOIN jangkauan_jalan j ON j.kecamatan_id = k.id GROUP BY k.id ORDER BY k.id;

-- 8. Alokasi posyandu/sekolah per kecamatan dan jumlah puskesmas
SELECT k.nama_kecamatan, count(p.id) AS jml_puskesmas, sum(p.jumlah_posyandu) AS posyandu, sum(p.jumlah_sekolah) AS sekolah
FROM kecamatan k JOIN puskesmas p ON p.kecamatan_id = k.id GROUP BY k.id ORDER BY k.id;

-- 9. Total kunjungan per kecamatan harus sama persis dengan JMLPSN di file JUMLAH_PASIEN_PUSKESMAS_PERWILAYAH.csv
SELECT k.kode_kecamatan, sum(kp.total_pasien_bulanan) AS hasil, r.jmlpsn
FROM kunjungan_pasien kp JOIN puskesmas p ON p.id = kp.puskesmas_id JOIN kecamatan k ON k.id = p.kecamatan_id
JOIN (VALUES ('3271010',233523),('3271020',134464),('3271030',172590),('3271040',187219),('3271050',285396),('3271060',243733)) r(kode, jmlpsn)
  ON r.kode = k.kode_kecamatan
GROUP BY k.kode_kecamatan, r.jmlpsn HAVING sum(kp.total_pasien_bulanan) <> r.jmlpsn;

-- 10. Setiap puskesmas harus punya tepat 12 bulan kunjungan
SELECT puskesmas_id, count(*) FROM kunjungan_pasien GROUP BY puskesmas_id HAVING count(*) <> 12;

-- 11. persentase_keramaian dan status_kepadatan harus konsisten dengan rumusnya
SELECT id FROM kunjungan_pasien
WHERE abs(persentase_keramaian - round((100.0*total_pasien_bulanan/kapasitas_maksimal_bulanan)::numeric, 2)) > 0.01
   OR status_kepadatan <> CASE WHEN persentase_keramaian < 60 THEN 'Rendah' WHEN persentase_keramaian <= 85 THEN 'Sedang' ELSE 'Padat' END;

-- 12. FR-06 contoh: puskesmas dalam radius 3 km dari titik (lng 106.7945, lat -6.5929)
SELECT nama_puskesmas, round(ST_Distance(geom::geography, ST_SetSRID(ST_MakePoint(106.7945,-6.5929),4326)::geography)::numeric) AS jarak_m
FROM puskesmas WHERE ST_DWithin(geom::geography, ST_SetSRID(ST_MakePoint(106.7945,-6.5929),4326)::geography, 3000) ORDER BY jarak_m;

-- 13. FR-09 blank spot: kota di luar buffer 3 km puskesmas (UTM 48S, EPSG:32748)
WITH kota AS (SELECT ST_Union(ST_Transform(geom, 32748)) AS g FROM kecamatan),
     cakupan AS (SELECT ST_Union(ST_Buffer(ST_Transform(geom, 32748), 3000)) AS g FROM puskesmas)
SELECT round((ST_Area(kota.g)/1e6)::numeric, 2) AS luas_kota_km2,
       round((ST_Area(ST_Difference(kota.g, cakupan.g))/1e6)::numeric, 2) AS blankspot_km2 FROM kota, cakupan;
