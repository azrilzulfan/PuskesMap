<?php

namespace Database\Seeders;

use App\Models\JangkauanJalan;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Services\WorkloadService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class DatasetBogorSeeder extends Seeder
{
    public function __construct(
        private readonly WorkloadService $workloadService,
    ) {}

    /**
     * Import canonical geospatial & operational datasets from docs/Dataset/*.csv.
     */
    public function run(): void
    {
        $isPgsql = DB::connection()->getDriverName() === 'pgsql';

        DB::transaction(function () use ($isPgsql): void {
            $kecamatanMap = $this->seedKecamatan($isPgsql);
            $puskesmasMap = $this->seedPuskesmas($kecamatanMap, $isPgsql);
            $this->seedOperasional($puskesmasMap);
            $this->seedTenagaMedis($puskesmasMap);
            $this->seedKunjunganPasien($puskesmasMap);
            $this->seedJangkauanJalan($kecamatanMap, $isPgsql);
        });

        $this->workloadService->recalculateAndPersistScores();
    }

    /**
     * @return array<string, int>
     */
    private function seedKecamatan(bool $isPgsql): array
    {
        $rows = $this->readCsv('kecamatan.csv');
        $map = [];

        foreach ($rows as $row) {
            $kecamatan = Kecamatan::query()->updateOrCreate(
                ['kode_kecamatan' => $row['kode_kecamatan']],
                [
                    'nama_kecamatan' => $row['nama_kecamatan'],
                    'jumlah_penduduk' => (int) $row['jumlah_penduduk'],
                    'luas_wilayah_km2' => (float) $row['luas_wilayah_km2'],
                ],
            );

            if ($isPgsql && $row['geom_wkt'] !== '') {
                DB::update(
                    'UPDATE kecamatan SET geom = ST_GeomFromText(?, 4326) WHERE id = ?',
                    [$row['geom_wkt'], $kecamatan->id],
                );
            }

            $map[$row['kode_kecamatan']] = $kecamatan->id;
        }

        return $map;
    }

    /**
     * @param  array<string, int>  $kecamatanMap
     * @return array<string, int>
     */
    private function seedPuskesmas(array $kecamatanMap, bool $isPgsql): array
    {
        $rows = $this->readCsv('puskesmas.csv');
        $map = [];

        foreach ($rows as $row) {
            $kecamatanId = $kecamatanMap[$row['kode_kecamatan']] ?? null;
            if ($kecamatanId === null) {
                throw new RuntimeException("Unknown kode_kecamatan {$row['kode_kecamatan']} in puskesmas.csv");
            }

            $xLong = (float) $row['x_long'];
            $yLat = (float) $row['y_lat'];

            $puskesmas = Puskesmas::query()->updateOrCreate(
                ['kode_puskesmas' => $row['kode_puskesmas']],
                [
                    'kecamatan_id' => $kecamatanId,
                    'nama_puskesmas' => $row['nama_puskesmas'],
                    'alamat' => $row['alamat'],
                    'x_long' => $xLong,
                    'y_lat' => $yLat,
                    'website_url' => $row['website_url'] !== '' ? $row['website_url'] : null,
                    'kategori_layanan' => $row['kategori_layanan'] ?: 'Non Rawat Inap',
                    'jumlah_posyandu' => (int) $row['jumlah_posyandu'],
                    'jumlah_sekolah' => (int) $row['jumlah_sekolah'],
                    'skor_psw' => $row['skor_psw'] !== '' ? (float) $row['skor_psw'] : null,
                ],
            );

            if ($isPgsql) {
                if ($row['geom_wkt'] !== '') {
                    DB::update(
                        'UPDATE puskesmas SET geom = ST_GeomFromText(?, 4326) WHERE id = ?',
                        [$row['geom_wkt'], $puskesmas->id],
                    );
                } else {
                    DB::update(
                        'UPDATE puskesmas SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?',
                        [$xLong, $yLat, $puskesmas->id],
                    );
                }
            }

            $map[$row['kode_puskesmas']] = $puskesmas->id;
        }

        return $map;
    }

    /**
     * @param  array<string, int>  $puskesmasMap
     */
    private function seedOperasional(array $puskesmasMap): void
    {
        foreach ($this->readCsv('operasional.csv') as $row) {
            $puskesmasId = $puskesmasMap[$row['kode_puskesmas']] ?? null;
            if ($puskesmasId === null) {
                throw new RuntimeException("Unknown kode_puskesmas {$row['kode_puskesmas']} in operasional.csv");
            }

            Operasional::query()->updateOrCreate(
                ['puskesmas_id' => $puskesmasId],
                [
                    'status_igd_24jam' => filter_var($row['status_igd_24jam'], FILTER_VALIDATE_BOOLEAN),
                    'jam_buka' => $row['jam_buka'],
                    'jam_tutup' => $row['jam_tutup'],
                    'kontak_darurat' => $row['kontak_darurat'] !== '' ? $row['kontak_darurat'] : null,
                    'rawat_inap' => filter_var($row['rawat_inap'], FILTER_VALIDATE_BOOLEAN),
                ],
            );
        }
    }

    /**
     * @param  array<string, int>  $puskesmasMap
     */
    private function seedTenagaMedis(array $puskesmasMap): void
    {
        foreach ($this->readCsv('tenaga_medis.csv') as $row) {
            $puskesmasId = $puskesmasMap[$row['kode_puskesmas']] ?? null;
            if ($puskesmasId === null) {
                throw new RuntimeException("Unknown kode_puskesmas {$row['kode_puskesmas']} in tenaga_medis.csv");
            }

            TenagaMedis::query()->updateOrCreate(
                ['puskesmas_id' => $puskesmasId],
                [
                    'dokter_umum' => (int) $row['dokter_umum'],
                    'dokter_gigi' => (int) $row['dokter_gigi'],
                    'bidan' => (int) $row['bidan'],
                    'perawat' => (int) $row['perawat'],
                    'farmasi' => (int) $row['farmasi'],
                    'gizi' => (int) $row['gizi'],
                    'kesling' => (int) $row['kesling'],
                    'kesmas' => (int) $row['kesmas'],
                    'atlm' => (int) $row['atlm'],
                    'persentase_kelengkapan' => (float) $row['persentase_kelengkapan'],
                    'kategori_jumlah_nakes' => $row['kategori_jumlah_nakes'],
                ],
            );
        }
    }

    /**
     * @param  array<string, int>  $puskesmasMap
     */
    private function seedKunjunganPasien(array $puskesmasMap): void
    {
        foreach ($this->readCsv('kunjungan_pasien.csv') as $row) {
            $puskesmasId = $puskesmasMap[$row['kode_puskesmas']] ?? null;
            if ($puskesmasId === null) {
                throw new RuntimeException("Unknown kode_puskesmas {$row['kode_puskesmas']} in kunjungan_pasien.csv");
            }

            KunjunganPasien::query()->updateOrCreate(
                [
                    'puskesmas_id' => $puskesmasId,
                    'tahun' => (int) $row['tahun'],
                    'bulan' => (int) $row['bulan'],
                ],
                [
                    'total_pasien_bulanan' => (int) $row['total_pasien_bulanan'],
                    'kapasitas_maksimal_bulanan' => (int) $row['kapasitas_maksimal_bulanan'],
                    'persentase_keramaian' => (float) $row['persentase_keramaian'],
                    'status_kepadatan' => $row['status_kepadatan'],
                ],
            );
        }
    }

    /**
     * @param  array<string, int>  $kecamatanMap
     */
    private function seedJangkauanJalan(array $kecamatanMap, bool $isPgsql): void
    {
        foreach ($this->readCsv('jangkauan_jalan.csv') as $row) {
            $kecamatanId = $kecamatanMap[$row['kode_kecamatan']] ?? null;
            if ($kecamatanId === null) {
                throw new RuntimeException("Unknown kode_kecamatan {$row['kode_kecamatan']} in jangkauan_jalan.csv");
            }

            $zone = JangkauanJalan::query()->updateOrCreate(
                [
                    'kecamatan_id' => $kecamatanId,
                    'kategori_jangkauan' => $row['kategori_jangkauan'],
                    'jangkauan_faskes' => (int) $row['jangkauan_faskes'],
                    'jangkauan_jalan' => (int) $row['jangkauan_jalan'],
                ],
                [],
            );

            if ($isPgsql && $row['geom_wkt'] !== '') {
                DB::update(
                    'UPDATE jangkauan_jalan SET geom = ST_GeomFromText(?, 4326) WHERE id = ?',
                    [$row['geom_wkt'], $zone->id],
                );
            }
        }
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function readCsv(string $filename): array
    {
        $path = base_path('docs/Dataset/'.$filename);

        if (! is_file($path)) {
            throw new RuntimeException("Dataset file not found: {$path}");
        }

        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Unable to open dataset file: {$path}");
        }

        $headers = fgetcsv($handle);
        if (! is_array($headers)) {
            fclose($handle);
            throw new RuntimeException("Dataset CSV header missing in {$path}");
        }

        // Strip UTF-8 BOM from first header cell if present.
        $headers[0] = preg_replace('/^\x{FEFF}/u', '', (string) $headers[0]) ?? (string) $headers[0];

        $records = [];
        while (($data = fgetcsv($handle)) !== false) {
            if ($data === [null] || count($data) !== count($headers)) {
                continue;
            }

            $records[] = array_combine($headers, $data);
        }

        fclose($handle);

        return $records;
    }
}
