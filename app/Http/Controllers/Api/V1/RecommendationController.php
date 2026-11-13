<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\MautScenario;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RecommendationRequest;
use App\Http\Resources\PuskesmasSummaryResource;
use App\Services\RecommendationService;
use App\Services\SpatialAnalysisService;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
        private readonly RecommendationService $recommendationService,
    ) {}

    public function store(RecommendationRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $lat = (float) $validated['lat'];
        $lng = (float) $validated['lng'];
        $scenario = MautScenario::from((string) $validated['scenario']);

        $facilities = $this->spatialAnalysis->getNearestFacilities($lat, $lng, null);
        $ranked = $this->recommendationService->rankFacilities($facilities, $scenario);

        $recommendations = $ranked->values()->map(function (array $item, int $index) use ($request): array {
            return [
                'rank' => $index + 1,
                'score' => $item['score'],
                'distance_meters' => $item['distance_meters'],
                'distance_km' => $item['distance_km'],
                'raw' => $item['raw'],
                'utilities' => $item['utilities'],
                'weights' => $item['weights'],
                'facility' => (new PuskesmasSummaryResource($item['facility']))->toArray($request),
            ];
        })->all();

        return response()->json([
            'scenario' => $scenario->value,
            'scenario_label' => $scenario->label(),
            'weights' => $scenario->weights(),
            'reference_point' => [
                'lat' => $lat,
                'lng' => $lng,
            ],
            'total_evaluated' => $facilities->count(),
            'recommendations' => $recommendations,
        ]);
    }
}
