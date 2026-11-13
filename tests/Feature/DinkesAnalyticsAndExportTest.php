<?php

use App\Models\JangkauanJalan;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;
use App\Services\WorkloadService;

test('dinkes dashboard and analysis apis return postgis coverage, equity, workload spectrum, and road access', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create([
        'nama_kecamatan' => 'Bogor Tengah',
        'jumlah_penduduk' => 120000,
    ]);

    $facilityA = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'nama_puskesmas' => 'Puskesmas A',
        'x_long' => 106.7950,
        'y_lat' => -6.5950,
        'jumlah_posyandu' => 45,
        'jumlah_sekolah' => 30,
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $facilityA->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facilityA->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2025, 'total_pasien_bulanan' => 4800]);

    $facilityB = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'nama_puskesmas' => 'Puskesmas B',
        'x_long' => 106.8200,
        'y_lat' => -6.6100,
        'jumlah_posyandu' => 15,
        'jumlah_sekolah' => 10,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $facilityB->id]);
    TenagaMedis::factory()->kurangLengkap()->create(['puskesmas_id' => $facilityB->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityB->id, 'tahun' => 2025, 'total_pasien_bulanan' => 1500]);

    JangkauanJalan::factory()->withPolygon()->create([
        'kecamatan_id' => $kecamatan->id,
        'kategori_jangkauan' => 'Sangat Dekat',
    ]);

    app(WorkloadService::class)->recalculateAndPersistScores(2025);

    $this->actingAs($dinkes)
        ->get(route('analitik.index'))
        ->assertOk()
        ->assertSee('Dasbor Analitik Spasial Dinas Kesehatan')
        ->assertSee('Bogor Tengah');

    $coverageResponse = $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.coverage', ['buffer_km' => 1.5]));

    $coverageResponse->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonPath('buffer_km', 1.5)
        ->assertJsonStructure([
            'summary' => ['luas_kota_km2', 'luas_terlayani_km2', 'blankspot_km2', 'persentase_terlayani', 'persentase_blankspot'],
            'per_kecamatan',
            'features',
        ]);

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.coverage', ['buffer_km' => 5.0]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('buffer_km');

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.equity'))
        ->assertOk()
        ->assertJsonPath('standard_population_per_facility', 30000)
        ->assertJsonPath('districts.0.puskesmas_count', 2)
        ->assertJsonPath('districts.0.target_count', 4)
        ->assertJsonPath('districts.0.deficit', 2)
        ->assertJsonPath('districts.0.ratio_status', 'Kurang 2');

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.workload'))
        ->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonPath('summary.total', 2);

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.road-access'))
        ->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonPath('summary.0.kategori_jangkauan', 'Sangat Dekat');
});

test('dinkes can export analytical reports as utf8 csv and pdf', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create(['nama_kecamatan' => 'Tanah Sareal']);

    $facility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710101',
        'nama_puskesmas' => 'Puskesmas Tanah Sareal',
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $facility->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facility->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facility->id, 'tahun' => 2025, 'total_pasien_bulanan' => 3600]);
    app(WorkloadService::class)->recalculateAndPersistScores(2025);

    $csvResponse = $this->actingAs($dinkes)
        ->get(route('analitik.export.csv', ['buffer_km' => 1.5, 'tahun' => 2025]));

    $csvResponse->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $csvBody = $csvResponse->streamedContent();
    expect(str_starts_with($csvBody, "\xEF\xBB\xBF"))->toBeTrue();
    expect($csvBody)->toContain('Kode Puskesmas')
        ->toContain('Puskesmas Tanah Sareal')
        ->toContain('Tanah Sareal');

    $pdfResponse = $this->actingAs($dinkes)
        ->get(route('analitik.export.pdf', ['buffer_km' => 3.0, 'tahun' => 2025]));

    $pdfResponse->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    expect(str_starts_with((string) $pdfResponse->getContent(), '%PDF'))->toBeTrue();
});
