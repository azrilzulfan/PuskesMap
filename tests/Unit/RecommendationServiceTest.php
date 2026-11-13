<?php

use App\Enums\CrowdStatus;
use App\Enums\MautScenario;
use App\Enums\NakesCategory;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Services\RecommendationService;
use Tests\TestCase;

uses(TestCase::class);

function makeInMemoryFacility(
    int $id,
    string $name,
    float $distanceMeters,
    bool $igd24Jam,
    string $jamBuka,
    string $jamTutup,
    float $nakesPct,
    float $crowdPct,
): Puskesmas {
    $facility = new Puskesmas([
        'kode_puskesmas' => sprintf('P3271%04d', $id),
        'nama_puskesmas' => $name,
    ]);
    $facility->id = $id;
    $facility->setAttribute('distance_meters', $distanceMeters);

    $facility->setRelation('operasional', new Operasional([
        'status_igd_24jam' => $igd24Jam,
        'jam_buka' => $jamBuka,
        'jam_tutup' => $jamTutup,
        'rawat_inap' => false,
    ]));

    $facility->setRelation('tenagaMedis', new TenagaMedis([
        'persentase_kelengkapan' => $nakesPct,
        'kategori_jumlah_nakes' => $nakesPct >= 100.0 ? NakesCategory::Lengkap : NakesCategory::KurangLengkap,
    ]));

    $facility->setRelation('latestKunjungan', new KunjunganPasien([
        'persentase_keramaian' => $crowdPct,
        'status_kepadatan' => CrowdStatus::fromPercentage($crowdPct),
    ]));

    return $facility;
}

test('darurat scenario prioritizes igd 24 jam facilities over non igd even when closer', function () {
    $service = app(RecommendationService::class);
    $nonIgdClose = makeInMemoryFacility(1, 'Puskesmas Dekat Non-IGD', 300.0, false, '07:30:00', '15:00:00', 100.0, 20.0);
    $igdFarther = makeInMemoryFacility(2, 'Puskesmas Jauh IGD', 1800.0, true, '00:00:00', '23:59:59', 88.89, 50.0);
    $igdMid = makeInMemoryFacility(3, 'Puskesmas Sedang IGD', 900.0, true, '00:00:00', '23:59:59', 100.0, 40.0);

    $ranked = $service->rankFacilities(collect([$nonIgdClose, $igdFarther, $igdMid]), MautScenario::Darurat, 3);

    expect($ranked)->toHaveCount(2);
    expect($ranked->first()['facility']->nama_puskesmas)->toBe('Puskesmas Sedang IGD');
    expect($ranked->pluck('facility.nama_puskesmas')->all())->not->toContain('Puskesmas Dekat Non-IGD');
});

test('maut normalizes identical bounds without division by zero and respects distance tie breaker', function () {
    $service = app(RecommendationService::class);
    $far = makeInMemoryFacility(1, 'Puskesmas Jauh', 2000.0, true, '00:00:00', '23:59:59', 100.0, 50.0);
    $near = makeInMemoryFacility(2, 'Puskesmas Dekat', 500.0, true, '00:00:00', '23:59:59', 100.0, 50.0);

    $ranked = $service->rankFacilities(collect([$far, $near]), MautScenario::Reguler, 3);

    expect($ranked)->toHaveCount(2);
    expect($ranked->first()['facility']->nama_puskesmas)->toBe('Puskesmas Dekat');
    expect($ranked->first()['score'])->toBe(1.0);
    expect($ranked->last()['score'])->toBe(0.8);
});

test('reguler and pemda scenarios include all complete candidates and rank by exact weighted utility', function () {
    $service = app(RecommendationService::class);
    $lowNakesLongHours = makeInMemoryFacility(1, 'Puskesmas A', 1000.0, false, '07:00:00', '19:00:00', 55.56, 80.0);
    $fullNakesLowCrowd = makeInMemoryFacility(2, 'Puskesmas B', 1000.0, false, '08:00:00', '14:00:00', 100.0, 20.0);

    $pemdaRanked = $service->rankFacilities(collect([$lowNakesLongHours, $fullNakesLowCrowd]), MautScenario::Pemda, 3);
    $regulerRanked = $service->rankFacilities(collect([$lowNakesLongHours, $fullNakesLowCrowd]), MautScenario::Reguler, 3);

    expect($pemdaRanked->first()['facility']->nama_puskesmas)->toBe('Puskesmas B');
    expect($pemdaRanked->first()['score'])->toBe(0.7);
    expect($regulerRanked->first()['facility']->nama_puskesmas)->toBe('Puskesmas B');
    expect($regulerRanked->first()['score'])->toBe(0.6);
});

test('igd 24 jam facility still evaluates regular polyclinic durationHours without duplicating igd weight', function () {
    $op = new Operasional([
        'status_igd_24jam' => true,
        'jam_buka' => '07:30:00',
        'jam_tutup' => '14:00:00',
    ]);

    expect($op->durationHours())->toBe(6.5);
});
