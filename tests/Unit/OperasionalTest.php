<?php

use App\Models\Operasional;
use Carbon\CarbonImmutable;
use Tests\TestCase;

uses(TestCase::class);

test('regular facility follows weekday, friday, saturday, and sunday closed rules in WIB', function () {
    $operasional = new Operasional([
        'status_igd_24jam' => false,
        'jam_buka' => '07:30:00',
        'jam_tutup' => '14:00:00',
    ]);

    $mondayOpen = CarbonImmutable::parse('2026-11-16 10:00:00', 'Asia/Jakarta');
    $mondayClosed = CarbonImmutable::parse('2026-11-16 14:15:00', 'Asia/Jakarta');
    $fridayOpen = CarbonImmutable::parse('2026-11-20 14:15:00', 'Asia/Jakarta');
    $fridayClosed = CarbonImmutable::parse('2026-11-20 14:40:00', 'Asia/Jakarta');
    $saturdayOpen = CarbonImmutable::parse('2026-11-21 11:30:00', 'Asia/Jakarta');
    $saturdayClosed = CarbonImmutable::parse('2026-11-21 12:30:00', 'Asia/Jakarta');
    $sunday = CarbonImmutable::parse('2026-11-22 10:00:00', 'Asia/Jakarta');

    expect($operasional->isOpenAt($mondayOpen))->toBeTrue()
        ->and($operasional->isOpenAt($mondayClosed))->toBeFalse()
        ->and($operasional->isOpenAt($fridayOpen))->toBeTrue()
        ->and($operasional->isOpenAt($fridayClosed))->toBeFalse()
        ->and($operasional->isOpenAt($saturdayOpen))->toBeTrue()
        ->and($operasional->isOpenAt($saturdayClosed))->toBeFalse()
        ->and($operasional->isOpenAt($sunday))->toBeFalse();
});

test('igd 24 jam facility remains open on sunday and outside regular polyclinic hours while durationHours tracks regular hours', function () {
    $operasional = new Operasional([
        'status_igd_24jam' => true,
        'jam_buka' => '08:00:00',
        'jam_tutup' => '14:00:00',
    ]);

    $sundayNight = CarbonImmutable::parse('2026-11-22 23:30:00', 'Asia/Jakarta');

    expect($operasional->isOpenAt($sundayNight))->toBeTrue()
        ->and($operasional->durationHours())->toBe(6.0);
});
