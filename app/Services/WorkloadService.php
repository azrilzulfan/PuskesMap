<?php

namespace App\Services;

use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkloadService
{
    /**
     * Calculate PSW-Index (0.40 pop + 0.20 edu + 0.20 pos + 0.20 kur) across facilities.
     *
     * @return Collection<int, array{
     *     puskesmas_id: int,
     *     kode_puskesmas: string,
     *     nama_puskesmas: string,
     *     kecamatan_id: int,
     *     nama_kecamatan: string,
     *     x_long: float,
     *     y_lat: float,
     *     pop_per_facility: float,
     *     jumlah_sekolah: int,
     *     jumlah_posyandu: int,
     *     avg_visits: float,
     *     n_pop: float,
     *     n_edu: float,
     *     n_pos: float,
     *     n_kur: float,
     *     skor_psw: float,
     *     spektrum: string,
     *     color_hex: string
     * }>
     */
    public function calculatePSWIndex(?int $tahun = null): Collection
    {
        $visitAggregateQuery = DB::table('kunjungan_pasien')
            ->select('puskesmas_id', DB::raw('AVG(total_pasien_bulanan) AS avg_visits'))
            ->when($tahun !== null, fn ($query) => $query->where('tahun', $tahun))
            ->groupBy('puskesmas_id');

        $facilities = Puskesmas::query()
            ->join('kecamatan', 'kecamatan.id', '=', 'puskesmas.kecamatan_id')
            ->leftJoinSub($visitAggregateQuery, 'kp_avg', 'kp_avg.puskesmas_id', '=', 'puskesmas.id')
            ->select([
                'puskesmas.id',
                'puskesmas.kode_puskesmas',
                'puskesmas.nama_puskesmas',
                'puskesmas.kecamatan_id',
                'puskesmas.x_long',
                'puskesmas.y_lat',
                'puskesmas.jumlah_sekolah',
                'puskesmas.jumlah_posyandu',
                'kecamatan.nama_kecamatan',
                'kecamatan.jumlah_penduduk',
                DB::raw('COALESCE(kp_avg.avg_visits, 0) AS avg_visits'),
            ])
            ->get();

        if ($facilities->isEmpty()) {
            return collect();
        }

        $facilitiesPerDistrict = $facilities->countBy('kecamatan_id');

        $rawRows = $facilities->map(function ($row) use ($facilitiesPerDistrict): array {
            $districtCount = max(1, (int) $facilitiesPerDistrict->get($row->kecamatan_id, 1));

            return [
                'puskesmas_id' => (int) $row->id,
                'kode_puskesmas' => (string) $row->kode_puskesmas,
                'nama_puskesmas' => (string) $row->nama_puskesmas,
                'kecamatan_id' => (int) $row->kecamatan_id,
                'nama_kecamatan' => (string) $row->nama_kecamatan,
                'x_long' => (float) $row->x_long,
                'y_lat' => (float) $row->y_lat,
                'pop_per_facility' => ((float) $row->jumlah_penduduk) / $districtCount,
                'jumlah_sekolah' => (int) $row->jumlah_sekolah,
                'jumlah_posyandu' => (int) $row->jumlah_posyandu,
                'avg_visits' => (float) $row->avg_visits,
            ];
        });

        return $this->normalizeAndScore($rawRows);
    }

    public function classifyWorkloadSpectrum(float $score): WorkloadSpectrum
    {
        return WorkloadSpectrum::fromScore($score);
    }

    public function recalculateAndPersistScores(?int $tahun = null): int
    {
        $calculated = $this->calculatePSWIndex($tahun);

        if ($calculated->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($calculated): void {
            foreach ($calculated as $item) {
                Puskesmas::query()
                    ->whereKey($item['puskesmas_id'])
                    ->update(['skor_psw' => $item['skor_psw']]);
            }
        });

        return $calculated->count();
    }

    /**
     * Build GeoJSON workload spectrum payload and summary distribution for Dinkes analytics.
     *
     * @return array{
     *     type: string,
     *     features: array<int, array<string, mixed>>,
     *     summary: array{
     *         total: int,
     *         counts: array{Tinggi: int, Ideal: int, Rendah: int},
     *         average_score: float,
     *         tahun: int|null
     *     }
     * }
     */
    public function getWorkloadDistribution(?int $kecamatanId = null, ?int $tahun = 2025): array
    {
        $rows = $this->calculatePSWIndex($tahun)
            ->when(
                $kecamatanId !== null,
                fn (Collection $items): Collection => $items->where('kecamatan_id', $kecamatanId)->values(),
            );

        $counts = [
            WorkloadSpectrum::Tinggi->value => 0,
            WorkloadSpectrum::Ideal->value => 0,
            WorkloadSpectrum::Rendah->value => 0,
        ];

        $features = [];

        foreach ($rows as $row) {
            $counts[$row['spektrum']] = ($counts[$row['spektrum']] ?? 0) + 1;

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$row['x_long'], $row['y_lat']],
                ],
                'properties' => [
                    'id' => $row['puskesmas_id'],
                    'kode_puskesmas' => $row['kode_puskesmas'],
                    'nama_puskesmas' => $row['nama_puskesmas'],
                    'kecamatan_id' => $row['kecamatan_id'],
                    'nama_kecamatan' => $row['nama_kecamatan'],
                    'skor_psw' => $row['skor_psw'],
                    'spektrum' => $row['spektrum'],
                    'color' => $row['color_hex'],
                    'pop_per_facility' => round($row['pop_per_facility'], 2),
                    'jumlah_sekolah' => $row['jumlah_sekolah'],
                    'jumlah_posyandu' => $row['jumlah_posyandu'],
                    'avg_visits' => round($row['avg_visits'], 2),
                ],
            ];
        }

        $averageScore = $rows->isNotEmpty()
            ? round((float) $rows->avg('skor_psw'), 2)
            : 0.0;

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
            'summary' => [
                'total' => $rows->count(),
                'counts' => $counts,
                'average_score' => $averageScore,
                'tahun' => $tahun,
            ],
        ];
    }

    /**
     * @param  Collection<int, array{
     *     puskesmas_id: int,
     *     kode_puskesmas: string,
     *     nama_puskesmas: string,
     *     kecamatan_id: int,
     *     nama_kecamatan: string,
     *     x_long: float,
     *     y_lat: float,
     *     pop_per_facility: float,
     *     jumlah_sekolah: int,
     *     jumlah_posyandu: int,
     *     avg_visits: float
     * }>  $rawRows
     * @return Collection<int, array<string, mixed>>
     */
    private function normalizeAndScore(Collection $rawRows): Collection
    {
        $weights = config('sdss.psw.weights');
        $wPop = (float) ($weights['pop'] ?? 0.40);
        $wEdu = (float) ($weights['edu'] ?? 0.20);
        $wPos = (float) ($weights['pos'] ?? 0.20);
        $wKur = (float) ($weights['kur'] ?? 0.20);

        $popBounds = $this->minMax($rawRows, 'pop_per_facility');
        $eduBounds = $this->minMax($rawRows, 'jumlah_sekolah');
        $posBounds = $this->minMax($rawRows, 'jumlah_posyandu');
        $kurBounds = $this->minMax($rawRows, 'avg_visits');

        return $rawRows
            ->map(function (array $row) use ($popBounds, $eduBounds, $posBounds, $kurBounds, $wPop, $wEdu, $wPos, $wKur): array {
                $nPop = $this->minMaxNormalize((float) $row['pop_per_facility'], $popBounds);
                $nEdu = $this->minMaxNormalize((float) $row['jumlah_sekolah'], $eduBounds);
                $nPos = $this->minMaxNormalize((float) $row['jumlah_posyandu'], $posBounds);
                $nKur = $this->minMaxNormalize((float) $row['avg_visits'], $kurBounds);

                $score = round(($wPop * $nPop) + ($wEdu * $nEdu) + ($wPos * $nPos) + ($wKur * $nKur), 2);
                $spectrum = $this->classifyWorkloadSpectrum($score);

                return [
                    ...$row,
                    'n_pop' => round($nPop, 4),
                    'n_edu' => round($nEdu, 4),
                    'n_pos' => round($nPos, 4),
                    'n_kur' => round($nKur, 4),
                    'skor_psw' => $score,
                    'spektrum' => $spectrum->value,
                    'color_hex' => $spectrum->colorHex(),
                ];
            })
            ->sort(function (array $a, array $b): int {
                $scoreCmp = $b['skor_psw'] <=> $a['skor_psw'];
                if ($scoreCmp !== 0) {
                    return $scoreCmp;
                }

                return strcmp($a['kode_puskesmas'], $b['kode_puskesmas']);
            })
            ->values();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $rows
     * @return array{min: float, max: float}
     */
    private function minMax(Collection $rows, string $key): array
    {
        $values = $rows->pluck($key)->map(fn (mixed $value): float => (float) $value);

        return [
            'min' => (float) $values->min(),
            'max' => (float) $values->max(),
        ];
    }

    /**
     * @param  array{min: float, max: float}  $bounds
     */
    private function minMaxNormalize(float $value, array $bounds): float
    {
        $range = $bounds['max'] - $bounds['min'];

        if (abs($range) < 1e-9) {
            return 0.0;
        }

        return ($value - $bounds['min']) / $range;
    }
}
