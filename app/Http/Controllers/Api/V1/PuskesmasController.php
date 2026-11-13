<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\NearestPuskesmasRequest;
use App\Http\Requests\Api\PuskesmasFilterRequest;
use App\Http\Requests\Api\RadiusPuskesmasRequest;
use App\Http\Resources\PuskesmasGeoJsonCollection;
use App\Http\Resources\PuskesmasSummaryResource;
use App\Models\Puskesmas;
use App\Services\SpatialAnalysisService;
use Illuminate\Http\JsonResponse;

class PuskesmasController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
    ) {}

    public function index(PuskesmasFilterRequest $request): JsonResponse
    {
        $facilities = $this->spatialAnalysis->getFilteredFacilities($request->validated());

        return response()->json(
            (new PuskesmasGeoJsonCollection($facilities))->toArray($request),
        );
    }

    public function show(int $id): JsonResponse
    {
        $facility = Puskesmas::query()
            ->with(['kecamatan', 'operasional', 'tenagaMedis'])
            ->withLatestKunjungan()
            ->findOrFail($id);

        return response()->json([
            'data' => (new PuskesmasSummaryResource($facility))->toArray(request()),
        ]);
    }

    public function nearest(NearestPuskesmasRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $lat = (float) $validated['lat'];
        $lng = (float) $validated['lng'];
        $limit = isset($validated['limit']) ? (int) $validated['limit'] : 5;

        $facilities = $this->spatialAnalysis->getNearestFacilities($lat, $lng, $limit, $validated);

        return response()->json(
            (new PuskesmasGeoJsonCollection($facilities, [
                'reference_point' => [
                    'lat' => $lat,
                    'lng' => $lng,
                ],
                'limit' => $limit,
            ]))->toArray($request),
        );
    }

    public function radius(RadiusPuskesmasRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $lat = (float) $validated['lat'];
        $lng = (float) $validated['lng'];
        $radiusMeters = (float) $validated['radius'];

        $result = $this->spatialAnalysis->getFacilitiesInBuffer($lat, $lng, $radiusMeters, $validated);

        return response()->json(
            (new PuskesmasGeoJsonCollection($result['facilities'], [
                'reference_point' => $result['center'],
                'radius_meters' => $result['radius_meters'],
                'buffer_geometry' => $result['buffer_geometry'],
            ]))->toArray($request),
        );
    }
}
