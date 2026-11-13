<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CoverageAnalysisRequest;
use App\Http\Requests\Api\EquityAnalysisRequest;
use App\Services\SpatialAnalysisService;
use App\Services\WorkloadService;
use Illuminate\Http\JsonResponse;

class AnalysisController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
        private readonly WorkloadService $workloadService,
    ) {}

    public function coverage(CoverageAnalysisRequest $request): JsonResponse
    {
        $bufferKm = (float) ($request->validated('buffer_km') ?? 1.5);

        return response()->json(
            $this->spatialAnalysis->calculateServiceArea($bufferKm),
        );
    }

    public function equity(EquityAnalysisRequest $request): JsonResponse
    {
        $kecamatanId = $request->validated('kecamatan_id');

        return response()->json(
            $this->spatialAnalysis->getEquityAnalysis($kecamatanId !== null ? (int) $kecamatanId : null),
        );
    }

    public function workloadSpectrum(EquityAnalysisRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $kecamatanId = isset($validated['kecamatan_id']) ? (int) $validated['kecamatan_id'] : null;
        $tahun = isset($validated['tahun']) ? (int) $validated['tahun'] : 2025;

        return response()->json(
            $this->workloadService->getWorkloadDistribution($kecamatanId, $tahun),
        );
    }

    public function roadAccess(EquityAnalysisRequest $request): JsonResponse
    {
        $kecamatanId = $request->validated('kecamatan_id');

        return response()->json(
            $this->spatialAnalysis->analyzeRoadAccessibility($kecamatanId !== null ? (int) $kecamatanId : null),
        );
    }
}
