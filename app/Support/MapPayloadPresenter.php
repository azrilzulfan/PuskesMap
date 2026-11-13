<?php

namespace App\Support;

use App\Enums\CrowdStatus;
use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use Illuminate\Support\Collection;

class MapPayloadPresenter
{
    private const DEFAULT_CENTER_LAT = -6.586372;

    private const DEFAULT_CENTER_LNG = 106.7706;

    /**
     * @var array<int, string>
     */
    private const PASTEL_COLORS = [
        'bg-card-mint',
        'bg-card-peach',
        'bg-card-green',
        'bg-card-salmon',
        'bg-card-blue',
    ];

    /**
     * Transform Eloquent facilities into the frontend WebGIS JSON & Blade payload shape.
     *
     * @param  Collection<int, Puskesmas>  $facilities
     * @return array<int, array<string, mixed>>
     */
    public static function presentFacilities(Collection $facilities): array
    {
        return $facilities->values()->map(function (Puskesmas $facility, int $index): array {
            $operasional = $facility->operasional;
            $tenagaMedis = $facility->tenagaMedis;
            $kunjungan = $facility->latestKunjungan;

            $lat = (float) $facility->y_lat;
            $lng = (float) $facility->x_long;
            $distKm = self::haversineKm(self::DEFAULT_CENTER_LAT, self::DEFAULT_CENTER_LNG, $lat, $lng);

            $pswScore = round((float) ($facility->skor_psw ?? 0.0), 4);
            $crowdStatus = $kunjungan?->status_kepadatan ?? CrowdStatus::Sedang;
            $counts = $tenagaMedis?->professionCounts() ?? [];

            return [
                'id' => $facility->id,
                'code' => $facility->kode_puskesmas,
                'name' => $facility->nama_puskesmas,
                'district' => $facility->kecamatan?->nama_kecamatan ?? '-',
                'address' => $facility->alamat,
                'phone' => $operasional?->kontak_darurat ?? '(0251) 8332943',
                'lat' => $lat,
                'lng' => $lng,
                'hours' => $operasional?->formattedHoursLabel() ?? 'Buka 07.30 – 14.00',
                'hours_duration' => $operasional?->durationHours() ?? 6.5,
                'is_open' => $operasional?->isOpenAt() ?? true,
                'igd' => (bool) ($operasional?->status_igd_24jam ?? false),
                'inap' => (bool) ($operasional?->rawat_inap ?? false),
                'bpjs' => true,
                'nakes_score' => round((float) ($tenagaMedis?->persentase_kelengkapan ?? 0.0), 1),
                'nakes_count' => array_sum($counts),
                'nakes_details' => [
                    'Dokter' => $counts['dokter_umum'] ?? 0,
                    'Dokter Gigi' => $counts['dokter_gigi'] ?? 0,
                    'Perawat' => $counts['perawat'] ?? 0,
                    'Bidan' => $counts['bidan'] ?? 0,
                    'Tenaga Kesmas' => $counts['kesmas'] ?? 0,
                    'Tenaga Kesling' => $counts['kesling'] ?? 0,
                    'ATLM' => $counts['atlm'] ?? 0,
                    'Tenaga Gizi' => $counts['gizi'] ?? 0,
                    'Farmasi' => $counts['farmasi'] ?? 0,
                ],
                'posyandu_count' => (int) $facility->jumlah_posyandu,
                'school_count' => (int) $facility->jumlah_sekolah,
                'monthly_visits' => (int) ($kunjungan?->total_pasien_bulanan ?? 0),
                'capacity' => (int) ($kunjungan?->kapasitas_maksimal_bulanan ?? config('sdss.capacity.default_monthly', 850)),
                'crowd' => $crowdStatus->toMapLabel(),
                'psw_index' => WorkloadSpectrum::fromScore($pswScore)->value,
                'psw_score' => $pswScore,
                'color' => self::PASTEL_COLORS[$index % count(self::PASTEL_COLORS)],
                'distance' => number_format($distKm, 1, ',', '.').' km',
                'official_url' => $facility->website_url ?? 'https://kotabogor.go.id',
            ];
        })->all();
    }

    /**
     * Transform district equity analysis results into legacy districtStats view format.
     *
     * @param  array<int, array<string, mixed>>  $districts
     * @return array<int, array<string, mixed>>
     */
    public static function presentDistrictStats(array $districts): array
    {
        return array_map(static function (array $district): array {
            return [
                'name' => $district['nama_kecamatan'],
                'population' => $district['jumlah_penduduk'],
                'puskesmas_count' => $district['puskesmas_count'],
                'target_count' => $district['target_count'],
                'ratio_status' => $district['ratio_status'],
                'psw_avg' => $district['psw_spectrum'],
            ];
        }, $districts);
    }

    /**
     * Compute aggregate city metrics compatible with map page summary cards.
     *
     * @param  array<int, array<string, mixed>>  $puskesmasPayload
     * @param  array<int, array<string, mixed>>  $districtStats
     * @return array<string, int|float>
     */
    public static function buildCitySummary(array $puskesmasPayload, array $districtStats): array
    {
        $count = count($puskesmasPayload);

        return [
            'total_puskesmas' => $count,
            'total_igd' => count(array_filter($puskesmasPayload, static fn (array $p): bool => (bool) $p['igd'])),
            'total_inap' => count(array_filter($puskesmasPayload, static fn (array $p): bool => (bool) $p['inap'])),
            'avg_nakes_compliance' => $count > 0
                ? round(array_sum(array_column($puskesmasPayload, 'nakes_score')) / $count, 1)
                : 0.0,
            'total_posyandu' => array_sum(array_column($puskesmasPayload, 'posyandu_count')),
            'total_school' => array_sum(array_column($puskesmasPayload, 'school_count')),
            'total_population' => array_sum(array_column($districtStats, 'population')),
        ];
    }

    private static function haversineKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusKm = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * (sin($dLng / 2) ** 2);

        return 2 * $earthRadiusKm * atan2(sqrt($a), sqrt(1 - $a));
    }
}
