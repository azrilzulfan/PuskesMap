<?php

namespace App\Support;

use App\Enums\CrowdStatus;
use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use Illuminate\Support\Collection;

class MapPayloadPresenter
{
    private const DEFAULT_CENTER_LAT = -6.597147;

    private const DEFAULT_CENTER_LNG = 106.806039;

    /**
     * Verified photo paths per facility code under public/images/puskesmas.
     *
     * @var array<string, string>
     */
    private const FACILITY_IMAGES = [
        'P3271010101' => 'images/puskesmas/1_Bogor Selatan/1.jpg',
        'P3271010205' => 'images/puskesmas/2_Mulyaharjo/2.jpg',
        'P3271010102' => 'images/puskesmas/3_Cipaku/3.jpg',
        'P3271010204' => 'images/puskesmas/4_Bondongan/4.jpg',
        'P3271010203' => 'images/puskesmas/5_Lawang Gintung/5.jpg',
        'P3271020101' => 'images/puskesmas/6_Bogor Timur/6.jpg',
        'P3271020202' => 'images/puskesmas/7_Pulo armyn/7.jpg',
        'P3271030202' => 'images/puskesmas/8_Bogor Utara/8.jpg',
        'P3271030203' => 'images/puskesmas/9_Warung Jambu/9.jpg',
        'P3271030101' => 'images/puskesmas/10_Tegal Gundil/10.jpg',
        'P3271040101' => 'images/puskesmas/11_Bogor Tengah/11.jpg',
        'P3271040205' => 'images/puskesmas/12_Merdeka/12.jpg',
        'P3271040203' => 'images/puskesmas/13_Gang Aut/13.jpg',
        'P3271040204' => 'images/puskesmas/14_Belong/14.jpg',
        'P3271040202' => 'images/puskesmas/15_Sempur/15.jpg',
        'P3271050204' => 'images/puskesmas/16_Pasir Mulya/16.jpg',
        'P3271050102' => 'images/puskesmas/17_Semplak/17.jpg',
        'P3271050203' => 'images/puskesmas/18_Pancasan/18.jpg',
        'P3271050205' => 'images/puskesmas/19_Sindang Barang/19.jpg',
        'P3271050201' => 'images/puskesmas/20_Gang kelor/20.jpg',
        'P3271060101' => 'images/puskesmas/21_Tanah sereal/21.jpg',
        'P3271060202' => 'images/puskesmas/22_Pondok rumput/22.jpg',
        'P3271060104' => 'images/puskesmas/23_Kedung Badak/23.jpg',
        'P3271060203' => 'images/puskesmas/24_Kayu Manis/24.jpg',
        'P3271060205' => 'images/puskesmas/25_Mekar Wangi/25.jpg',
    ];

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
        $centerLat = (float) config('sdss.spatial.bogor_center.lat', self::DEFAULT_CENTER_LAT);
        $centerLng = (float) config('sdss.spatial.bogor_center.lng', self::DEFAULT_CENTER_LNG);

        return $facilities->values()->map(function (Puskesmas $facility, int $index) use ($centerLat, $centerLng): array {
            $operasional = $facility->operasional;
            $tenagaMedis = $facility->tenagaMedis;
            $kunjungan = $facility->latestKunjungan;

            $lat = (float) $facility->y_lat;
            $lng = (float) $facility->x_long;
            $distKm = self::haversineKm($centerLat, $centerLng, $lat, $lng);

            $pswScore = round((float) ($facility->skor_psw ?? 0.0), 4);
            $crowdStatus = $kunjungan?->status_kepadatan ?? CrowdStatus::Sedang;
            $counts = ($tenagaMedis ?? new TenagaMedis)->professionCounts();

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
                'open_hour' => self::timeStringToDecimalHour($operasional?->jam_buka, 7.5),
                'close_hour' => self::timeStringToDecimalHour($operasional?->jam_tutup, 14.0),
                'is_open' => $operasional?->isOpenAt() ?? false,
                'igd' => (bool) ($operasional?->status_igd_24jam ?? false),
                'inap' => (bool) ($operasional?->rawat_inap ?? false),
                'bpjs' => true,
                'nakes_score' => round((float) ($tenagaMedis?->persentase_kelengkapan ?? 0.0), 1),
                'nakes_count' => array_sum($counts),
                'nakes_details' => $counts,
                'posyandu_count' => (int) $facility->jumlah_posyandu,
                'school_count' => (int) $facility->jumlah_sekolah,
                'monthly_visits' => (int) ($kunjungan?->total_pasien_bulanan ?? 0),
                'capacity' => (int) ($kunjungan?->kapasitas_maksimal_bulanan ?? config('sdss.capacity.default_monthly', 850)),
                'crowd' => $crowdStatus->toMapLabel(),
                'crowd_status' => $crowdStatus->value,
                'crowd_percentage' => round((float) ($kunjungan?->persentase_keramaian ?? 0.0), 1),
                'psw_index' => WorkloadSpectrum::fromScore($pswScore)->value,
                'psw_score' => $pswScore,
                'color' => self::PASTEL_COLORS[$index % count(self::PASTEL_COLORS)],
                'image' => self::FACILITY_IMAGES[$facility->kode_puskesmas] ?? null,
                'distance' => number_format($distKm, 1, ',', '.').' km',
                'official_url' => $facility->website_url ?? 'https://dinkes.kotabogor.go.id',
                'detail_url' => route('puskesmas.show', ['kode_puskesmas' => $facility->kode_puskesmas]),
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

    /**
     * Convert HH:MM[:SS] string into decimal hours for client-side schedule checks.
     */
    private static function timeStringToDecimalHour(?string $time, float $fallback): float
    {
        if ($time === null || ! preg_match('/^(\d{1,2}):(\d{2})/', $time, $matches)) {
            return $fallback;
        }

        return round(((int) $matches[1]) + (((int) $matches[2]) / 60), 4);
    }
}
