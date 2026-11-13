-- 05_hitung_psw.sql : mengisi puskesmas.skor_psw (FR-24, Permenkes 43/2019). Jalankan setelah kunjungan_pasien terisi.
-- Bobot: penduduk 0.40, sekolah 0.20, posyandu 0.20, kunjungan 0.20 (min-max per dimensi).
-- B'pop = penduduk kecamatan dibagi jumlah puskesmas di kecamatan itu (penyederhanaan).
UPDATE puskesmas SET skor_psw = x.skor FROM (
  WITH dasar AS (
    SELECT p.id, k.jumlah_penduduk::float / count(*) OVER (PARTITION BY p.kecamatan_id) AS pop,
           p.jumlah_sekolah::float AS edu, p.jumlah_posyandu::float AS pos,
           (SELECT avg(total_pasien_bulanan) FROM kunjungan_pasien kp WHERE kp.puskesmas_id = p.id)::float AS kur
    FROM puskesmas p JOIN kecamatan k ON k.id = p.kecamatan_id),
  norm AS (
    SELECT id,
      (pop-min(pop) OVER())/NULLIF(max(pop) OVER()-min(pop) OVER(),0) AS n_pop,
      (edu-min(edu) OVER())/NULLIF(max(edu) OVER()-min(edu) OVER(),0) AS n_edu,
      (pos-min(pos) OVER())/NULLIF(max(pos) OVER()-min(pos) OVER(),0) AS n_pos,
      (kur-min(kur) OVER())/NULLIF(max(kur) OVER()-min(kur) OVER(),0) AS n_kur FROM dasar)
  SELECT id, round((0.40*n_pop + 0.20*n_edu + 0.20*n_pos + 0.20*n_kur)::numeric, 2) AS skor FROM norm
) x WHERE puskesmas.id = x.id;

-- Spektrum: >= 0.70 Tinggi (#DC2626) | 0.40 s/d < 0.70 Ideal (#16A34A) | < 0.40 Rendah (#2563EB)
SELECT nama_puskesmas, skor_psw,
  CASE WHEN skor_psw >= 0.70 THEN 'Tinggi' WHEN skor_psw >= 0.40 THEN 'Ideal' ELSE 'Rendah' END AS spektrum
FROM puskesmas ORDER BY skor_psw DESC;
