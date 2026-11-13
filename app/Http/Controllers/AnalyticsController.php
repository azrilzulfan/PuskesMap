<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Puskesmas;
use App\Services\ReportExportService;
use App\Services\SpatialAnalysisService;
use App\Services\WorkloadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
        private readonly WorkloadService $workloadService,
        private readonly ReportExportService $reportExportService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->validateFilters($request);
        $kecamatanId = $filters['kecamatan_id'];
        $bufferKm = $filters['buffer_km'];
        $tahun = $filters['tahun'];

        $districts = Kecamatan::query()->orderBy('nama_kecamatan')->get();
        $coverage = $this->spatialAnalysis->calculateServiceArea($bufferKm);
        $equity = $this->spatialAnalysis->getEquityAnalysis($kecamatanId);
        $workload = $this->workloadService->getWorkloadDistribution($kecamatanId, $tahun);
        $roadAccess = $this->spatialAnalysis->analyzeRoadAccessibility($kecamatanId);

        $facilities = Puskesmas::query()
            ->with(['kecamatan', 'operasional', 'tenagaMedis'])
            ->withLatestKunjungan($tahun)
            ->when($kecamatanId !== null, fn ($query) => $query->where('kecamatan_id', $kecamatanId))
            ->orderByDesc('skor_psw')
            ->orderBy('kode_puskesmas')
            ->paginate((int) config('sdss.pagination.per_page', 15))
            ->withQueryString();

        return view('analytics.index', [
            'districts' => $districts,
            'selectedKecamatanId' => $kecamatanId,
            'bufferKm' => $bufferKm,
            'tahun' => $tahun,
            'coverage' => $coverage,
            'equity' => $equity,
            'workload' => $workload,
            'roadAccess' => $roadAccess,
            'facilities' => $facilities,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $filters = $this->validateFilters($request);

        return $this->reportExportService->streamCsv(
            $filters['kecamatan_id'],
            $filters['buffer_km'],
            $filters['tahun'],
        );
    }

    public function exportPdf(Request $request): Response
    {
        $filters = $this->validateFilters($request);

        return $this->reportExportService->downloadPdf(
            $filters['kecamatan_id'],
            $filters['buffer_km'],
            $filters['tahun'],
        );
    }

    /**
     * @return array{kecamatan_id: int|null, buffer_km: float, tahun: int}
     */
    private function validateFilters(Request $request): array
    {
        $validated = $request->validate([
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatan,id'],
            'buffer_km' => ['nullable', 'numeric', Rule::in([1.5, 3, 3.0])],
            'tahun' => ['nullable', 'integer', 'min:2020', 'max:2100'],
        ]);

        return [
            'kecamatan_id' => isset($validated['kecamatan_id']) ? (int) $validated['kecamatan_id'] : null,
            'buffer_km' => isset($validated['buffer_km']) ? (float) $validated['buffer_km'] : 1.5,
            'tahun' => isset($validated['tahun']) ? (int) $validated['tahun'] : 2025,
        ];
    }
}
