-- Skema basis data spasial PuskesMap (8 tabel relasional sesuai Kamus Data SAD Bab V).
-- Dirancang untuk PostgreSQL 16 + PostGIS 3.4. Jika skema dibuat melalui migration Laravel, berkas ini tidak perlu dijalankan ulang.

-- Aktifkan ekstensi spasial PostGIS untuk mendukung tipe GEOMETRY dan fungsi kueri geodetik
CREATE EXTENSION IF NOT EXISTS postgis;

-- Tabel batas administrasi dan demografi enam kecamatan di Kota Bogor (Tabel 5.2)
CREATE TABLE kecamatan (
  id                BIGSERIAL PRIMARY KEY,
  kode_kecamatan    VARCHAR(20)  NOT NULL UNIQUE,
  nama_kecamatan    VARCHAR(100) NOT NULL,
  geom              GEOMETRY(MultiPolygon, 4326) NOT NULL,
  jumlah_penduduk   INTEGER NOT NULL DEFAULT 0,
  luas_wilayah_km2  DOUBLE PRECISION
);

-- Indeks spasial GiST pada poligon kecamatan untuk mempercepat validasi batas wilayah ST_Contains
CREATE INDEX idx_kecamatan_geom ON kecamatan USING GIST (geom);

-- Tabel entitas induk 25 puskesmas di Kota Bogor beserta atribut binaan UKM dan skor PSW-Index (Tabel 5.1)
CREATE TABLE puskesmas (
  id                BIGSERIAL PRIMARY KEY,
  kecamatan_id      BIGINT NOT NULL REFERENCES kecamatan(id) ON DELETE RESTRICT,
  kode_puskesmas    VARCHAR(50)  NOT NULL UNIQUE,
  nama_puskesmas    VARCHAR(150) NOT NULL,
  alamat            TEXT,
  geom              GEOMETRY(Point, 4326) NOT NULL,
  x_long            DOUBLE PRECISION NOT NULL,
  y_lat             DOUBLE PRECISION NOT NULL,
  website_url       VARCHAR(255),
  kategori_layanan  VARCHAR(50) NOT NULL DEFAULT 'Non Rawat Inap',
  jumlah_posyandu   INTEGER NOT NULL DEFAULT 0,
  jumlah_sekolah    INTEGER NOT NULL DEFAULT 0,
  skor_psw          DECIMAL(5,2),
  created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indeks spasial GiST geometri standar serta indeks fungsional geografi untuk kueri ST_DWithin dan ST_Distance (NFR-03)
CREATE INDEX idx_puskesmas_geom ON puskesmas USING GIST (geom);
CREATE INDEX idx_puskesmas_geog ON puskesmas USING GIST ((geom::geography));

-- Tabel jadwal layanan harian, kesiagaan UGD 24 jam, dan kontak resmi puskesmas dengan relasi 1:1 (Tabel 5.3)
CREATE TABLE operasional (
  id                BIGSERIAL PRIMARY KEY,
  puskesmas_id      BIGINT NOT NULL UNIQUE REFERENCES puskesmas(id) ON DELETE CASCADE,
  status_igd_24jam  BOOLEAN NOT NULL DEFAULT FALSE,
  jam_buka          TIME NOT NULL DEFAULT '07:30:00',
  jam_tutup         TIME NOT NULL DEFAULT '14:00:00',
  kontak_darurat    VARCHAR(50),
  rawat_inap        BOOLEAN NOT NULL DEFAULT FALSE
);

-- Tabel ketersediaan sembilan profesi tenaga kesehatan wajib sesuai Permenkes No. 43 Tahun 2019 dengan relasi 1:1 (Tabel 5.4)
CREATE TABLE tenaga_medis (
  id                      BIGSERIAL PRIMARY KEY,
  puskesmas_id            BIGINT NOT NULL UNIQUE REFERENCES puskesmas(id) ON DELETE CASCADE,
  dokter_umum             INTEGER NOT NULL DEFAULT 0,
  dokter_gigi             INTEGER NOT NULL DEFAULT 0,
  bidan                   INTEGER NOT NULL DEFAULT 0,
  perawat                 INTEGER NOT NULL DEFAULT 0,
  farmasi                 INTEGER NOT NULL DEFAULT 0,
  gizi                    INTEGER NOT NULL DEFAULT 0,
  kesling                 INTEGER NOT NULL DEFAULT 0,
  kesmas                  INTEGER NOT NULL DEFAULT 0,
  atlm                    INTEGER NOT NULL DEFAULT 0,
  persentase_kelengkapan  DOUBLE PRECISION NOT NULL DEFAULT 0.0,
  kategori_jumlah_nakes   VARCHAR(50) NOT NULL
);

-- Tabel rekapitulasi kunjungan pasien bulanan dan indikator tingkat keramaian puskesmas (Tabel 5.5)
CREATE TABLE kunjungan_pasien (
  id                          BIGSERIAL PRIMARY KEY,
  puskesmas_id                BIGINT NOT NULL REFERENCES puskesmas(id) ON DELETE CASCADE,
  bulan                       SMALLINT NOT NULL CHECK (bulan BETWEEN 1 AND 12),
  tahun                       SMALLINT NOT NULL,
  total_pasien_bulanan        INTEGER NOT NULL DEFAULT 0,
  kapasitas_maksimal_bulanan  INTEGER NOT NULL DEFAULT 850,
  persentase_keramaian        DOUBLE PRECISION NOT NULL DEFAULT 0.0,
  status_kepadatan            VARCHAR(20) NOT NULL DEFAULT 'Rendah',
  UNIQUE (puskesmas_id, tahun, bulan)
);

-- Tabel 37 zona poligon keterjangkauan fasilitas kesehatan dan koridor jalan utama per kecamatan (Tabel 5.6)
CREATE TABLE jangkauan_jalan (
  id                  BIGSERIAL PRIMARY KEY,
  kecamatan_id        BIGINT NOT NULL REFERENCES kecamatan(id) ON DELETE CASCADE,
  kategori_jangkauan  VARCHAR(50)  NOT NULL,
  jangkauan_faskes    VARCHAR(100) NOT NULL,
  jangkauan_jalan     VARCHAR(100) NOT NULL,
  geom                GEOMETRY(MultiPolygon, 4326) NOT NULL
);

-- Indeks spasial GiST pada poligon zona jangkauan jalan untuk mempercepat pemuatan peta tematik
CREATE INDEX idx_jangkauan_geom ON jangkauan_jalan USING GIST (geom);

-- Tabel akun pengguna internal beserta peran RBAC dan status keaktifan akun untuk penonaktifan aman (Tabel 5.7)
CREATE TABLE users (
  id                 BIGSERIAL PRIMARY KEY,
  name               VARCHAR(100) NOT NULL,
  email              VARCHAR(150) NOT NULL UNIQUE,
  password           VARCHAR(255) NOT NULL,
  role               VARCHAR(30)  NOT NULL DEFAULT 'pemerintah' CHECK (role IN ('admin', 'pemerintah')),
  is_active          BOOLEAN NOT NULL DEFAULT TRUE,
  email_verified_at  TIMESTAMP,
  remember_token     VARCHAR(100),
  created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel riwayat log audit untuk mencatat setiap mutasi data master oleh administrator (Tabel 5.8)
CREATE TABLE audit_log (
  id                   BIGSERIAL PRIMARY KEY,
  user_id              BIGINT NOT NULL REFERENCES users(id),
  aksi                 VARCHAR(50)  NOT NULL,
  tabel_target         VARCHAR(100) NOT NULL,
  target_id            BIGINT,
  deskripsi_perubahan  TEXT,
  ip_address           VARCHAR(45),
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
