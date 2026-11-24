<?php

namespace App\Http\Controllers;

use App\Enums\CrowdStatus;
use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Services\SpatialAnalysisService;
use App\Support\MapPayloadPresenter;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
    ) {}

    public function landing(): View
    {
        $facilities = $this->spatialAnalysis->getFilteredFacilities();
        $puskesmas = MapPayloadPresenter::presentFacilities($facilities);

        return view('welcome', [
            'puskesmas' => $puskesmas,
            'bogorCenter' => config('sdss.spatial.bogor_center', [
                'lat' => -6.597147,
                'lng' => 106.806039,
            ]),
        ]);
    }

    public function index(Request $request): View
    {
        $facilities = $this->spatialAnalysis->getFilteredFacilities();
        $equity = $this->spatialAnalysis->getEquityAnalysis();

        $puskesmas = MapPayloadPresenter::presentFacilities($facilities);
        $districtStats = MapPayloadPresenter::presentDistrictStats($equity['districts']);
        $citySummary = MapPayloadPresenter::buildCitySummary($puskesmas, $districtStats);
        $districts = array_merge(['Semua'], array_column($districtStats, 'name'));
        $user = $request->user();
        $canUsePemda = $user !== null && $user->is_active && ($user->isAdmin() || $user->isPemerintah());

        return view('peta', [
            'puskesmas' => $puskesmas,
            'districtStats' => $districtStats,
            'citySummary' => $citySummary,
            'districts' => $districts,
            'canUsePemda' => $canUsePemda,
            'bogorCenter' => config('sdss.spatial.bogor_center', [
                'lat' => -6.597147,
                'lng' => 106.806039,
            ]),
        ]);
    }

    public function show(string $kode_puskesmas): View
    {
        $facility = Puskesmas::query()
            ->with([
                'kecamatan',
                'operasional',
                'tenagaMedis',
                'kunjunganPasien' => fn ($query) => $query
                    ->orderBy('tahun')
                    ->orderBy('bulan'),
            ])
            ->withLatestKunjungan()
            ->where('kode_puskesmas', $kode_puskesmas)
            ->firstOrFail();

        $operasional = $facility->operasional;
        $tenagaMedis = $facility->tenagaMedis;
        $latestKunjungan = $facility->latestKunjungan;
        $visitHistory = $facility->kunjunganPasien
            ->sortBy([
                ['tahun', 'asc'],
                ['bulan', 'asc'],
            ])
            ->slice(-12)
            ->values();

        $pswScore = round((float) ($facility->skor_psw ?? 0.0), 2);
        $crowdStatus = $latestKunjungan?->status_kepadatan ?? CrowdStatus::Sedang;
        $professionCounts = ($tenagaMedis ?? new TenagaMedis)->professionCounts();

        return view('puskesmas.show', [
            'facility' => $facility,
            'isOpenNow' => $operasional?->isOpenAt() ?? false,
            'hoursLabel' => $operasional?->formattedHoursLabel() ?? '07.30 – 14.00',
            'professionCounts' => $professionCounts,
            'totalNakes' => array_sum($professionCounts),
            'nakesPct' => round((float) ($tenagaMedis?->persentase_kelengkapan ?? 0.0), 1),
            'nakesLabel' => $tenagaMedis?->kategori_jumlah_nakes?->value ?? 'Kurang Lengkap',
            'latestKunjungan' => $latestKunjungan,
            'crowdStatus' => $crowdStatus->value,
            'pswScore' => $pswScore,
            'pswSpectrum' => WorkloadSpectrum::fromScore($pswScore)->value,
            'visitHistory' => $visitHistory,
        ]);
    }
}
