<?php

namespace App\Services;

use App\Enums\WorkloadSpectrum;
use App\Models\JangkauanJalan;
use App\Models\Kecamatan;
use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SpatialAnalysisService
{
    public function isWithinBogorBounds(float $lat, float $lng): bool
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return $lat >= -6.70 && $lat <= -6.50 && $lng >= 106.70 && $lng <= 106.88;
        }

        $row = DB::selectOne(
            'SELECT EXISTS(SELECT 1 FROM kecamatan WHERE geom IS NOT NULL AND ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))) AS within_bounds',
            [$lng, $lat],
        );

        return (bool) ($row->within_bounds ?? false);
    }

    public function findDistrictForPoint(float $lat, float $lng): ?Kecamatan
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return Kecamatan::query()->first();
        }

        return Kecamatan::query()
            ->whereRaw('geom IS NOT NULL AND ST_Contains(geom, ST_SetSRID(ST_MakePoint(?, ?), 4326))', [$lng, $lat])
            ->first();
    }

    /**
     * Filter puskesmas facilities with optional operational and staffing criteria.
     *
     * @param  array{
     *     kecamatan_id?: int|string|null,
     *     igd_24jam?: bool|string|int|null,
     *     jam_operasional?: string|null,
     *     kelengkapan_nakes?: string|null
     * }  $filters
     * @return Collection<int, Puskesmas>
     */
    public function getFilteredFacilities(array $filters = []): Collection
    {
        $query = $this->baseFacilityQuery($filters)
            ->orderBy('puskesmas.id');

        $facilities = $query->get();

        return $this->applyOperatingHoursFilter($facilities, $filters['jam_operasional'] ?? null);
    }

    /**
     * Retrieve puskesmas facilities sorted by exact geodesic distance (meters).
     *
     * @param  array{
     *     kecamatan_id?: int|string|null,
     *     igd_24jam?: bool|string|int|null,
     *     jam_operasional?: string|null,
     *     kelengkapan_nakes?: string|null
     * }  $filters
     * @return Collection<int, Puskesmas>
     */
    public function getNearestFacilities(float $lat, float $lng, ?int $limit = 5, array $filters = []): Collection
    {
        $query = $this->baseFacilityQuery($filters);

        if (DB::connection()->getDriverName() === 'pgsql') {
            $query->selectRaw(
                'ST_Distance(puskesmas.geom::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) AS distance_meters',
                [$lng, $lat],
            )->orderBy('distance_meters');
        } else {
            $query->orderBy('puskesmas.id');
        }

        $facilities = $query->get();

        if (DB::connection()->getDriverName() !== 'pgsql') {
            $facilities->each(function (Puskesmas $facility) use ($lat, $lng): void {
                $facility->setAttribute(
                    'distance_meters',
                    $this->haversineMeters($lat, $lng, $facility->y_lat, $facility->x_long),
                );
            });
            $facilities = $facilities->sortBy('distance_meters')->values();
        }

        $filtered = $this->applyOperatingHoursFilter($facilities, $filters['jam_operasional'] ?? null);

        if ($limit !== null) {
            return $filtered->take($limit)->values();
        }

        return $filtered->values();
    }

    /**
     * Find puskesmas facilities inside a geodesic radius buffer and return buffer polygon GeoJSON.
     *
     * @param  array{
     *     kecamatan_id?: int|string|null,
     *     igd_24jam?: bool|string|int|null,
     *     jam_operasional?: string|null,
     *     kelengkapan_nakes?: string|null
     * }  $filters
     * @return array{
     *     buffer_geometry: array<string, mixed>|null,
     *     radius_meters: float,
     *     center: array{lat: float, lng: float},
     *     facilities: Collection<int, Puskesmas>
     * }
     */
    public function getFacilitiesInBuffer(
        float $lat,
        float $lng,
        float $radiusMeters,
        array $filters = [],
    ): array {
        $query = $this->baseFacilityQuery($filters);
        $bufferGeometry = null;

        if (DB::connection()->getDriverName() === 'pgsql') {
            $query->selectRaw(
                'ST_Distance(puskesmas.geom::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography) AS distance_meters',
                [$lng, $lat],
            )->whereRaw(
                'ST_DWithin(puskesmas.geom::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)',
                [$lng, $lat, $radiusMeters],
            )->orderBy('distance_meters');

            $facilities = $this->applyOperatingHoursFilter(
                $query->get(),
                $filters['jam_operasional'] ?? null,
            );

            $bufferRow = DB::selectOne(
                'SELECT ST_AsGeoJSON(ST_Buffer(ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)::geometry) AS geojson',
                [$lng, $lat, $radiusMeters],
            );

            $bufferGeometry = $bufferRow && $bufferRow->geojson
                ? json_decode((string) $bufferRow->geojson, true)
                : null;
        } else {
            $all = $this->getNearestFacilities($lat, $lng, null, $filters);
            $facilities = $all
                ->filter(fn (Puskesmas $facility): bool => (float) $facility->getAttribute('distance_meters') <= $radiusMeters)
                ->values();
        }

        return [
            'buffer_geometry' => $bufferGeometry,
            'radius_meters' => $radiusMeters,
            'center' => ['lat' => $lat, 'lng' => $lng],
            'facilities' => $facilities,
        ];
    }

    /**
     * Calculate service area coverage & blankspot zones using PostGIS UTM 48S projection (EPSG:32748).
     *
     * @return array{
     *     type: string,
     *     buffer_km: float,
     *     summary: array{
     *         luas_kota_km2: float,
     *         luas_terlayani_km2: float,
     *         blankspot_km2: float,
     *         persentase_terlayani: float,
     *         persentase_blankspot: float
     *     },
     *     per_kecamatan: array<int, array{
     *         kecamatan_id: int,
     *         kode_kecamatan: string,
     *         nama_kecamatan: string,
     *         luas_wilayah_km2: float,
     *         luas_terlayani_km2: float,
     *         blankspot_km2: float,
     *         persentase_terlayani: float
     *     }>,
     *     features: array<int, array<string, mixed>>
     * }
     */
    public function calculateServiceArea(float $bufferKm = 1.5): array
    {
        $cacheKey = 'sdss:coverage:' . number_format($bufferKm, 1, '.', '');

        return Cache::rememberForever($cacheKey, function () use ($bufferKm): array {
            return $this->computeServiceArea($bufferKm);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function computeServiceArea(float $bufferKm): array
    {
        $radiusMeters = $bufferKm * 1000.0;

        if (DB::connection()->getDriverName() !== 'pgsql') {
            return [
                'type' => 'FeatureCollection',
                'buffer_km' => $bufferKm,
                'summary' => [
                    'luas_kota_km2' => 0.0,
                    'luas_terlayani_km2' => 0.0,
                    'blankspot_km2' => 0.0,
                    'persentase_terlayani' => 0.0,
                    'persentase_blankspot' => 0.0,
                ],
                'per_kecamatan' => [],
                'features' => [],
            ];
        }

        $cityRow = DB::selectOne(
            'WITH kota AS (
                SELECT ST_Union(ST_Transform(geom, 32748)) AS g FROM kecamatan WHERE geom IS NOT NULL
             ),
             cakupan AS (
                SELECT ST_Union(ST_Buffer(ST_Transform(geom, 32748), ?)) AS g FROM puskesmas WHERE geom IS NOT NULL
             ),
             terlayani AS (
                SELECT ST_Intersection(kota.g, cakupan.g) AS g FROM kota, cakupan
             ),
             blankspot AS (
                SELECT CASE
                    WHEN cakupan.g IS NULL THEN kota.g
                    ELSE ST_Difference(kota.g, cakupan.g)
                END AS g
                FROM kota, cakupan
             )
             SELECT
                ROUND((ST_Area(kota.g) / 1e6)::numeric, 2) AS luas_kota_km2,
                ROUND((COALESCE(ST_Area(terlayani.g), 0) / 1e6)::numeric, 2) AS luas_terlayani_km2,
                ROUND((COALESCE(ST_Area(blankspot.g), 0) / 1e6)::numeric, 2) AS blankspot_km2,
                ST_AsGeoJSON(ST_Transform(terlayani.g, 4326), 6) AS covered_geojson,
                ST_AsGeoJSON(ST_Transform(blankspot.g, 4326), 6) AS blankspot_geojson
             FROM kota, terlayani, blankspot',
            [$radiusMeters],
        );

        $districtRows = DB::select(
            'WITH cakupan AS (
                SELECT ST_Union(ST_Buffer(ST_Transform(geom, 32748), ?)) AS g FROM puskesmas WHERE geom IS NOT NULL
             )
             SELECT
                k.id AS kecamatan_id,
                k.kode_kecamatan,
                k.nama_kecamatan,
                ROUND((ST_Area(ST_Transform(k.geom, 32748)) / 1e6)::numeric, 2) AS luas_wilayah_km2,
                ROUND((COALESCE(ST_Area(ST_Intersection(ST_Transform(k.geom, 32748), cakupan.g)), 0) / 1e6)::numeric, 2) AS luas_terlayani_km2,
                ROUND((COALESCE(ST_Area(
                    CASE
                        WHEN cakupan.g IS NULL THEN ST_Transform(k.geom, 32748)
                        ELSE ST_Difference(ST_Transform(k.geom, 32748), cakupan.g)
                    END
                ), 0) / 1e6)::numeric, 2) AS blankspot_km2
             FROM kecamatan k
             LEFT JOIN cakupan ON TRUE
             WHERE k.geom IS NOT NULL
             ORDER BY k.id',
            [$radiusMeters],
        );

        $cityArea = (float) ($cityRow->luas_kota_km2 ?? 0.0);
        $coveredArea = (float) ($cityRow->luas_terlayani_km2 ?? 0.0);
        $blankspotArea = (float) ($cityRow->blankspot_km2 ?? 0.0);
        $coveragePct = $cityArea > 0 ? round(($coveredArea / $cityArea) * 100, 1) : 0.0;
        $blankspotPct = $cityArea > 0 ? round(100.0 - $coveragePct, 1) : 0.0;

        $perKecamatan = array_map(function (object $row): array {
            $area = (float) $row->luas_wilayah_km2;
            $covered = (float) $row->luas_terlayani_km2;

            return [
                'kecamatan_id' => (int) $row->kecamatan_id,
                'kode_kecamatan' => (string) $row->kode_kecamatan,
                'nama_kecamatan' => (string) $row->nama_kecamatan,
                'luas_wilayah_km2' => $area,
                'luas_terlayani_km2' => $covered,
                'blankspot_km2' => (float) $row->blankspot_km2,
                'persentase_terlayani' => $area > 0 ? round(($covered / $area) * 100, 1) : 0.0,
            ];
        }, $districtRows);

        $features = [];

        if (! empty($cityRow?->covered_geojson)) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode((string) $cityRow->covered_geojson, true),
                'properties' => [
                    'layer' => 'covered_zone',
                    'buffer_km' => $bufferKm,
                    'area_km2' => $coveredArea,
                ],
            ];
        }

        if (! empty($cityRow?->blankspot_geojson)) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode((string) $cityRow->blankspot_geojson, true),
                'properties' => [
                    'layer' => 'blankspot_zone',
                    'buffer_km' => $bufferKm,
                    'area_km2' => $blankspotArea,
                ],
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'buffer_km' => $bufferKm,
            'summary' => [
                'luas_kota_km2' => $cityArea,
                'luas_terlayani_km2' => $coveredArea,
                'blankspot_km2' => $blankspotArea,
                'persentase_terlayani' => $coveragePct,
                'persentase_blankspot' => $blankspotPct,
            ],
            'per_kecamatan' => $perKecamatan,
            'features' => $features,
        ];
    }

    /**
     * Retrieve road accessibility layers (jangkauan_jalan) and summary statistics.
     *
     * @return array{
     *     type: string,
     *     features: array<int, array<string, mixed>>,
     *     summary: array<int, array{kategori_jangkauan: string, total_zones: int, total_area_km2: float}>
     * }
     */
    public function analyzeRoadAccessibility(?int $kecamatanId = null): array
    {
        $cacheKey = $kecamatanId !== null
            ? "sdss:road_access:kec:{$kecamatanId}"
            : 'sdss:road_access:all';

        return Cache::rememberForever($cacheKey, function () use ($kecamatanId): array {
            return $this->computeRoadAccessibility($kecamatanId);
        });
    }

    /**
     * @return array{
     *     type: string,
     *     features: array<int, array<string, mixed>>,
     *     summary: array<int, array{kategori_jangkauan: string, total_zones: int, total_area_km2: float}>
     * }
     */
    private function computeRoadAccessibility(?int $kecamatanId = null): array
    {
        $query = JangkauanJalan::query()
            ->join('kecamatan', 'kecamatan.id', '=', 'jangkauan_jalan.kecamatan_id')
            ->select([
                'jangkauan_jalan.id',
                'jangkauan_jalan.kecamatan_id',
                'kecamatan.nama_kecamatan',
                'jangkauan_jalan.kategori_jangkauan',
                'jangkauan_jalan.jangkauan_faskes',
                'jangkauan_jalan.jangkauan_jalan',
            ])
            ->when($kecamatanId !== null, fn (Builder $q) => $q->where('jangkauan_jalan.kecamatan_id', $kecamatanId))
            ->orderBy('jangkauan_jalan.id');

        if (DB::connection()->getDriverName() === 'pgsql') {
            $query->selectRaw('ST_AsGeoJSON(jangkauan_jalan.geom, 6) AS geojson')
                ->selectRaw('ROUND((COALESCE(ST_Area(jangkauan_jalan.geom::geography), 0) / 1e6)::numeric, 3) AS area_km2');
        }

        $rows = $query->get();

        $features = [];
        $categoryTotals = [];

        foreach ($rows as $row) {
            $category = (string) $row->kategori_jangkauan;
            $areaKm2 = (float) ($row->area_km2 ?? 0.0);

            if (! isset($categoryTotals[$category])) {
                $categoryTotals[$category] = [
                    'kategori_jangkauan' => $category,
                    'total_zones' => 0,
                    'total_area_km2' => 0.0,
                ];
            }

            $categoryTotals[$category]['total_zones']++;
            $categoryTotals[$category]['total_area_km2'] = round(
                $categoryTotals[$category]['total_area_km2'] + $areaKm2,
                3,
            );

            $features[] = [
                'type' => 'Feature',
                'geometry' => ! empty($row->geojson) ? json_decode((string) $row->geojson, true) : null,
                'properties' => [
                    'id' => (int) $row->id,
                    'kecamatan_id' => (int) $row->kecamatan_id,
                    'nama_kecamatan' => (string) $row->nama_kecamatan,
                    'kategori_jangkauan' => $category,
                    'jangkauan_faskes' => (int) $row->jangkauan_faskes,
                    'jangkauan_jalan' => (int) $row->jangkauan_jalan,
                    'area_km2' => $areaKm2,
                ],
            ];
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features,
            'summary' => array_values($categoryTotals),
        ];
    }

    /**
     * Compute WHO / Kemenkes equity ratio analysis (1 Puskesmas : 30,000 inhabitants) per district.
     *
     * @return array{
     *     standard_population_per_facility: int,
     *     city_totals: array{
     *         population: int,
     *         puskesmas_count: int,
     *         target_count: int,
     *         deficit: int
     *     },
     *     districts: array<int, array{
     *         id: int,
     *         kode_kecamatan: string,
     *         nama_kecamatan: string,
     *         jumlah_penduduk: int,
     *         luas_wilayah_km2: float,
     *         puskesmas_count: int,
     *         target_count: int,
     *         deficit: int,
     *         ratio_status: string,
     *         population_per_puskesmas: int,
     *         avg_psw_score: float,
     *         psw_spectrum: string
     *     }>
     * }
     */
    public function getEquityAnalysis(?int $kecamatanId = null): array
    {
        $ratioStandard = max(1, (int) config('sdss.equity.population_per_facility', 30000));

        $districts = Kecamatan::query()
            ->when($kecamatanId !== null, fn (Builder $q) => $q->whereKey($kecamatanId))
            ->withCount('puskesmas')
            ->withAvg('puskesmas', 'skor_psw')
            ->orderBy('id')
            ->get();

        $totalPop = 0;
        $totalExisting = 0;
        $totalTarget = 0;
        $items = [];

        foreach ($districts as $district) {
            $population = (int) $district->jumlah_penduduk;
            $existing = (int) $district->puskesmas_count;
            $target = max(1, (int) round($population / $ratioStandard));
            $deficit = max(0, $target - $existing);
            $avgPsw = round((float) ($district->puskesmas_avg_skor_psw ?? 0.0), 2);

            $totalPop += $population;
            $totalExisting += $existing;
            $totalTarget += $target;

            $items[] = [
                'id' => (int) $district->id,
                'kode_kecamatan' => (string) $district->kode_kecamatan,
                'nama_kecamatan' => (string) $district->nama_kecamatan,
                'jumlah_penduduk' => $population,
                'luas_wilayah_km2' => (float) $district->luas_wilayah_km2,
                'puskesmas_count' => $existing,
                'target_count' => $target,
                'deficit' => $deficit,
                'ratio_status' => $deficit > 0 ? "Kurang {$deficit}" : 'Memenuhi',
                'population_per_puskesmas' => $existing > 0 ? (int) round($population / $existing) : $population,
                'avg_psw_score' => $avgPsw,
                'psw_spectrum' => WorkloadSpectrum::fromScore($avgPsw)->value,
            ];
        }

        return [
            'standard_population_per_facility' => $ratioStandard,
            'city_totals' => [
                'population' => $totalPop,
                'puskesmas_count' => $totalExisting,
                'target_count' => $totalTarget,
                'deficit' => max(0, $totalTarget - $totalExisting),
            ],
            'districts' => $items,
        ];
    }

    /**
     * Flush cached spatial GeoJSON payloads after master or boundary mutations.
     */
    public function flushSpatialCache(): void
    {
        foreach (config('sdss.coverage.allowed_buffers_km', [1.5, 3.0]) as $bufferKm) {
            Cache::forget('sdss:coverage:' . number_format((float) $bufferKm, 1, '.', ''));
        }

        Cache::forget('sdss:road_access:all');
        Cache::forget('sdss:kecamatan:boundaries');

        foreach (Kecamatan::query()->pluck('id') as $kecamatanId) {
            Cache::forget("sdss:road_access:kec:{$kecamatanId}");
        }
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Builder<Puskesmas>
     */
    private function baseFacilityQuery(array $filters): Builder
    {
        $query = Puskesmas::query()
            ->with(['kecamatan', 'operasional', 'tenagaMedis'])
            ->withLatestKunjungan();

        if (! empty($filters['kecamatan_id'])) {
            $query->where('puskesmas.kecamatan_id', (int) $filters['kecamatan_id']);
        }

        if (array_key_exists('igd_24jam', $filters) && $filters['igd_24jam'] !== null && $filters['igd_24jam'] !== '') {
            $igdRequired = filter_var($filters['igd_24jam'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($igdRequired !== null) {
                $query->whereHas('operasional', fn (Builder $q) => $q->where('status_igd_24jam', $igdRequired));
            }
        }

        if (! empty($filters['kelengkapan_nakes'])) {
            $category = (string) $filters['kelengkapan_nakes'];
            $query->whereHas('tenagaMedis', fn (Builder $q) => $q->where('kategori_jumlah_nakes', $category));
        }

        return $query;
    }

    /**
     * @param  Collection<int, Puskesmas>  $facilities
     * @return Collection<int, Puskesmas>
     */
    private function applyOperatingHoursFilter(Collection $facilities, ?string $mode): Collection
    {
        if (empty($mode)) {
            return $facilities;
        }

        return $facilities->filter(function (Puskesmas $facility) use ($mode): bool {
            $operasional = $facility->operasional;
            if (! $operasional) {
                return false;
            }

            return match ($mode) {
                'buka_sekarang' => $operasional->isOpenAt(),
                'pagi' => substr((string) $operasional->jam_buka, 0, 5) <= '08:00',
                'siang' => $operasional->status_igd_24jam || substr((string) $operasional->jam_tutup, 0, 5) >= '14:00',
                default => true,
            };
        })->values();
    }

    private function haversineMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return round(2 * $earthRadius * asin(min(1.0, sqrt($a))), 2);
    }
}
