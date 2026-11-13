-- 01_schema.sql : 8 tabel PuskesMap sesuai Kamus Data (SAD Bab III).
-- Dipakai untuk PostgreSQL 16 + PostGIS 3.4. Kalau memakai migration Laravel, file ini TIDAK perlu dijalankan.
CREATE EXTENSION IF NOT EXISTS postgis;

CREATE TABLE kecamatan (
  id                BIGSERIAL PRIMARY KEY,
  kode_kecamatan    VARCHAR(20)  NOT NULL UNIQUE,
  nama_kecamatan    VARCHAR(100) NOT NULL,
  geom              GEOMETRY(MultiPolygon, 4326) NOT NULL,
  jumlah_penduduk   INTEGER,
  luas_wilayah_km2  DOUBLE PRECISION
);
CREATE INDEX idx_kecamatan_geom ON kecamatan USING GIST (geom);

CREATE TABLE puskesmas (
  id                BIGSERIAL PRIMARY KEY,
  kecamatan_id      BIGINT NOT NULL REFERENCES kecamatan(id),
  kode_puskesmas    VARCHAR(50)  NOT NULL UNIQUE,
  nama_puskesmas    VARCHAR(150) NOT NULL,
  alamat            TEXT,
  geom              GEOMETRY(Point, 4326) NOT NULL,
  x_long            DOUBLE PRECISION,
  y_lat             DOUBLE PRECISION,
  website_url       VARCHAR(255),
  kategori_layanan  VARCHAR(50),
  jumlah_posyandu   INTEGER,
  jumlah_sekolah    INTEGER,
  skor_psw          DECIMAL(5,2)
);
CREATE INDEX idx_puskesmas_geom ON puskesmas USING GIST (geom);

CREATE TABLE operasional (
  id                BIGSERIAL PRIMARY KEY,
  puskesmas_id      BIGINT NOT NULL UNIQUE REFERENCES puskesmas(id),
  status_igd_24jam  BOOLEAN,
  jam_buka          TIME,
  jam_tutup         TIME,
  kontak_darurat    VARCHAR(50),
  rawat_inap        BOOLEAN
);

CREATE TABLE tenaga_medis (
  id                      BIGSERIAL PRIMARY KEY,
  puskesmas_id            BIGINT NOT NULL UNIQUE REFERENCES puskesmas(id),
  dokter_umum             INTEGER, dokter_gigi INTEGER, bidan INTEGER, perawat INTEGER,
  farmasi                 INTEGER, gizi INTEGER, kesling INTEGER, kesmas INTEGER, atlm INTEGER,
  persentase_kelengkapan  DOUBLE PRECISION,
  kategori_jumlah_nakes   VARCHAR(50)
);

CREATE TABLE kunjungan_pasien (
  id                          BIGSERIAL PRIMARY KEY,
  puskesmas_id                BIGINT NOT NULL REFERENCES puskesmas(id),
  bulan                       SMALLINT NOT NULL CHECK (bulan BETWEEN 1 AND 12),
  tahun                       SMALLINT NOT NULL,
  total_pasien_bulanan        INTEGER,
  kapasitas_maksimal_bulanan  INTEGER,
  persentase_keramaian        DOUBLE PRECISION,
  status_kepadatan            VARCHAR(20),
  UNIQUE (puskesmas_id, tahun, bulan)
);

CREATE TABLE jangkauan_jalan (
  id                  BIGSERIAL PRIMARY KEY,
  kecamatan_id        BIGINT NOT NULL REFERENCES kecamatan(id),
  kategori_jangkauan  VARCHAR(50),
  jangkauan_faskes    VARCHAR(100),
  jangkauan_jalan     VARCHAR(100),
  geom                GEOMETRY(MultiPolygon, 4326)
);
CREATE INDEX idx_jangkauan_geom ON jangkauan_jalan USING GIST (geom);

CREATE TABLE users (
  id                 BIGSERIAL PRIMARY KEY,
  name               VARCHAR(100) NOT NULL,
  email              VARCHAR(150) NOT NULL UNIQUE,
  password           VARCHAR(255) NOT NULL,           -- hash Bcrypt, bukan teks asli
  role               VARCHAR(30)  NOT NULL CHECK (role IN ('admin','pemerintah')),
  email_verified_at  TIMESTAMP,
  remember_token     VARCHAR(100)
);

CREATE TABLE audit_log (
  id                   BIGSERIAL PRIMARY KEY,
  user_id              BIGINT REFERENCES users(id),
  aksi                 VARCHAR(50),
  tabel_target         VARCHAR(100),
  target_id            BIGINT,
  deskripsi_perubahan  TEXT,
  ip_address           VARCHAR(45),
  created_at           TIMESTAMP DEFAULT now()
);
