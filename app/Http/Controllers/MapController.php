<?php

namespace App\Http\Controllers;

use App\Services\SpatialAnalysisService;
use App\Support\MapPayloadPresenter;
use Illuminate\Contracts\View\View;

class MapController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
    ) {}

    public function index(): View
    {
        $facilities = $this->spatialAnalysis->getFilteredFacilities();
        $equity = $this->spatialAnalysis->getEquityAnalysis();

        $puskesmas = MapPayloadPresenter::presentFacilities($facilities);
        $districtStats = MapPayloadPresenter::presentDistrictStats($equity['districts']);
        $citySummary = MapPayloadPresenter::buildCitySummary($puskesmas, $districtStats);
        $districts = array_merge(['Semua'], array_column($districtStats, 'name'));

        return view('peta', [
            'puskesmas' => $puskesmas,
            'districtStats' => $districtStats,
            'citySummary' => $citySummary,
            'districts' => $districts,
        ]);
    }
}
