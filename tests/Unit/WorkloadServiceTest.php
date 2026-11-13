<?php

use App\Enums\WorkloadSpectrum;
use App\Services\WorkloadService;
use Tests\TestCase;

uses(TestCase::class);

test('workload service classifies psw score thresholds and hex colors per specification', function (float $score, WorkloadSpectrum $expectedSpectrum, string $expectedHex) {
    $service = app(WorkloadService::class);

    $spectrum = $service->classifyWorkloadSpectrum($score);

    expect($spectrum)->toBe($expectedSpectrum);
    expect($spectrum->colorHex())->toBe($expectedHex);
})->with([
    'rendah lower boundary' => [0.0, WorkloadSpectrum::Rendah, '#2563EB'],
    'rendah upper boundary' => [0.39, WorkloadSpectrum::Rendah, '#2563EB'],
    'ideal lower boundary' => [0.40, WorkloadSpectrum::Ideal, '#16A34A'],
    'ideal upper boundary' => [0.69, WorkloadSpectrum::Ideal, '#16A34A'],
    'tinggi lower boundary' => [0.70, WorkloadSpectrum::Tinggi, '#DC2626'],
    'tinggi max boundary' => [1.0, WorkloadSpectrum::Tinggi, '#DC2626'],
]);
