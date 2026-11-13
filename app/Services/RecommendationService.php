<?php

namespace App\Services;

use App\Enums\MautScenario;
use App\Models\Puskesmas;
use Illuminate\Support\Collection;

class RecommendationService
{
    /**
     * Filter candidates that possess complete data required for MAUT multi-criteria scoring.
     *
     * @param  Collection<int, Puskesmas>  $facilities
     * @return Collection<int, Puskesmas>
     */
    public function filterEligible(Collection $facilities, MautScenario $scenario): Collection
    {
        $eligible = $facilities->filter(
            fn (Puskesmas $facility): bool => $facility->operasional !== null && $facility->tenagaMedis !== null,
        );

        if ($scenario === MautScenario::Darurat) {
            $withIgd = $eligible->filter(
                fn (Puskesmas $facility): bool => (bool) $facility->operasional?->status_igd_24jam,
            );

            if ($withIgd->isNotEmpty()) {
                return $withIgd->values();
            }
        }

        return $eligible->values();
    }

    /**
     * Normalize 5 MAUT criteria (Min-Max) and compute weighted utility scores S_i = sum(w_k * N_ik).
     *
     * @param  Collection<int, Puskesmas>  $candidates
     * @return Collection<int, array{
     *     facility: Puskesmas,
     *     score: float,
     *     distance_meters: float,
     *     distance_km: float,
     *     raw: array{distance_km: float, igd: float, hours: float, nakes: float, crowd: float},
     *     utilities: array{distance: float, igd: float, hours: float, nakes: float, crowd: float},
     *     weights: array{distance: float, igd: float, hours: float, nakes: float, crowd: float}
     * }>
     */
    public function calculateScore(Collection $candidates, MautScenario $scenario): Collection
    {
        if ($candidates->isEmpty()) {
            return collect();
        }

        $weights = $scenario->weights();

        $rawItems = $candidates->map(function (Puskesmas $facility): array {
            $distanceMeters = (float) ($facility->getAttribute('distance_meters') ?? 0.0);
            $distanceKm = $distanceMeters / 1000.0;
            $operasional = $facility->operasional;
            $tenagaMedis = $facility->tenagaMedis;
            $latestKunjungan = $facility->latestKunjungan;

            return [
                'facility' => $facility,
                'distance_meters' => round($distanceMeters, 1),
                'distance_km' => round($distanceKm, 2),
                'raw' => [
                    'distance_km' => $distanceKm,
                    'igd' => $operasional?->status_igd_24jam ? 1.0 : 0.0,
                    'hours' => $operasional?->durationHours() ?? 0.0,
                    'nakes' => (float) ($tenagaMedis?->persentase_kelengkapan ?? 0.0),
                    'crowd' => (float) ($latestKunjungan?->persentase_keramaian ?? 0.0),
                ],
            ];
        });

        $bounds = [
            'distance_km' => $this->extractBounds($rawItems, 'distance_km'),
            'hours' => $this->extractBounds($rawItems, 'hours'),
            'nakes' => $this->extractBounds($rawItems, 'nakes'),
            'crowd' => $this->extractBounds($rawItems, 'crowd'),
        ];

        return $rawItems->map(function (array $item) use ($bounds, $weights): array {
            $raw = $item['raw'];

            $uDistance = $this->normalizeCost($raw['distance_km'], $bounds['distance_km']);
            $uIgd = $raw['igd'];
            $uHours = $this->normalizeBenefit($raw['hours'], $bounds['hours']);
            $uNakes = $this->normalizeBenefit($raw['nakes'], $bounds['nakes']);
            $uCrowd = $this->normalizeCost($raw['crowd'], $bounds['crowd']);

            $score = ($weights['distance'] * $uDistance)
                + ($weights['igd'] * $uIgd)
                + ($weights['hours'] * $uHours)
                + ($weights['nakes'] * $uNakes)
                + ($weights['crowd'] * $uCrowd);

            return [
                'facility' => $item['facility'],
                'score' => round($score, 4),
                'distance_meters' => $item['distance_meters'],
                'distance_km' => $item['distance_km'],
                'raw' => [
                    'distance_km' => round($raw['distance_km'], 3),
                    'igd' => $raw['igd'],
                    'hours' => round($raw['hours'], 2),
                    'nakes' => round($raw['nakes'], 2),
                    'crowd' => round($raw['crowd'], 2),
                ],
                'utilities' => [
                    'distance' => round($uDistance, 4),
                    'igd' => round($uIgd, 4),
                    'hours' => round($uHours, 4),
                    'nakes' => round($uNakes, 4),
                    'crowd' => round($uCrowd, 4),
                ],
                'weights' => $weights,
            ];
        })->values();
    }

    /**
     * Rank eligible candidates by descending MAUT score with shortest distance as tie-breaker.
     *
     * @param  Collection<int, Puskesmas>  $facilities
     * @return Collection<int, array<string, mixed>>
     */
    public function rankFacilities(Collection $facilities, MautScenario $scenario, ?int $topN = null): Collection
    {
        $limit = $topN ?? (int) config('sdss.maut.top_n', 3);
        $eligible = $this->filterEligible($facilities, $scenario);
        $scored = $this->calculateScore($eligible, $scenario);

        return $scored
            ->sort(function (array $a, array $b): int {
                $scoreCmp = $b['score'] <=> $a['score'];
                if ($scoreCmp !== 0) {
                    return $scoreCmp;
                }

                return $a['distance_meters'] <=> $b['distance_meters'];
            })
            ->take($limit)
            ->values();
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $items
     * @return array{min: float, max: float}
     */
    private function extractBounds(Collection $items, string $rawKey): array
    {
        $values = $items->map(fn (array $item): float => (float) $item['raw'][$rawKey]);

        return [
            'min' => (float) $values->min(),
            'max' => (float) $values->max(),
        ];
    }

    /**
     * @param  array{min: float, max: float}  $bounds
     */
    private function normalizeBenefit(float $value, array $bounds): float
    {
        $range = $bounds['max'] - $bounds['min'];
        if (abs($range) < 1e-9) {
            return 1.0;
        }

        return ($value - $bounds['min']) / $range;
    }

    /**
     * @param  array{min: float, max: float}  $bounds
     */
    private function normalizeCost(float $value, array $bounds): float
    {
        $range = $bounds['max'] - $bounds['min'];
        if (abs($range) < 1e-9) {
            return 1.0;
        }

        return ($bounds['max'] - $value) / $range;
    }
}
