<?php

use App\Models\JangkauanJalan;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;
use App\Services\SpatialAnalysisService;
use App\Services\WorkloadService;
use Illuminate\Support\Facades\Cache;

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
        ->assertSee('Bogor Tengah')
        ->assertSee(route('analitik.peta'))
        ->assertDontSee('href="'.route('peta').'"', false);

    $this->actingAs($dinkes)
        ->get(route('analitik.peta'))
        ->assertOk()
        ->assertSee('Peta Spasial Internal')
        ->assertSee('Simulasi MAUT Internal')
        ->assertSee(route('analitik.index'))
        ->assertDontSee('href="'.route('peta').'"', false);

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
        ->getJson('/api/v1/analisis/coverage?buffer_km=3.0')
        ->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonPath('buffer_km', 3);

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

test('equity analysis rounds up target facilities and csv export respects requested year psw scores', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create([
        'nama_kecamatan' => 'Bogor Selatan',
        'jumlah_penduduk' => 100000,
    ]);

    $facilityA = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710101',
        'nama_puskesmas' => 'Puskesmas Tahun A',
        'jumlah_posyandu' => 10,
        'jumlah_sekolah' => 10,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $facilityA->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facilityA->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2025, 'total_pasien_bulanan' => 800]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2026, 'total_pasien_bulanan' => 100]);

    $facilityB = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710102',
        'nama_puskesmas' => 'Puskesmas Tahun B',
        'jumlah_posyandu' => 10,
        'jumlah_sekolah' => 10,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $facilityB->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facilityB->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityB->id, 'tahun' => 2025, 'total_pasien_bulanan' => 200]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityB->id, 'tahun' => 2026, 'total_pasien_bulanan' => 800]);

    app(WorkloadService::class)->recalculateAndPersistScores(2026);

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.equity'))
        ->assertOk()
        ->assertJsonPath('districts.0.target_count', 4)
        ->assertJsonPath('districts.0.deficit', 2);

    $csv2025 = $this->actingAs($dinkes)
        ->get(route('analitik.export.csv', ['tahun' => 2025]))
        ->assertOk()
        ->streamedContent();

    $lines = array_values(array_filter(explode("\n", trim(preg_replace('/^\x{EF}\x{BB}\x{BF}/u', '', $csv2025) ?? ''))));
    $firstRow = str_getcsv($lines[1], ',', '"', '');

    expect($firstRow[0])->toBe('P32710101')
        ->and((float) $firstRow[15])->toBe(0.2);

    $body2025 = $this->actingAs($dinkes)
        ->get(route('analitik.index', ['tahun' => 2025]))
        ->assertOk()
        ->assertSee('0.20')
        ->getContent();

    expect(strpos($body2025, 'Puskesmas Tahun A'))->toBeLessThan(strpos($body2025, 'Puskesmas Tahun B'));

    $body2026 = $this->actingAs($dinkes)
        ->get(route('analitik.index', ['tahun' => 2026]))
        ->assertOk()
        ->getContent();

    expect(strpos($body2026, 'Puskesmas Tahun B'))->toBeLessThan(strpos($body2026, 'Puskesmas Tahun A'));

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.equity', ['tahun' => 2025]))
        ->assertOk()
        ->assertJsonPath('districts.0.avg_psw_score', 0.1);
});

test('analytics index sorts all matching facilities by requested year before paginating', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $kecamatan = Kecamatan::factory()->withPolygon()->create();

    for ($i = 1; $i <= 15; $i++) {
        $facility = Puskesmas::factory()->create([
            'kecamatan_id' => $kecamatan->id,
            'kode_puskesmas' => sprintf('P327199%02d', $i),
            'nama_puskesmas' => sprintf('Puskesmas Reguler %02d', $i),
            'jumlah_posyandu' => 50,
            'jumlah_sekolah' => 50,
        ]);
        Operasional::factory()->create(['puskesmas_id' => $facility->id]);
        TenagaMedis::factory()->create(['puskesmas_id' => $facility->id]);
        KunjunganPasien::factory()->create(['puskesmas_id' => $facility->id, 'tahun' => 2025, 'total_pasien_bulanan' => 200]);
        KunjunganPasien::factory()->create(['puskesmas_id' => $facility->id, 'tahun' => 2026, 'total_pasien_bulanan' => 800]);
    }

    $top2025Facility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32719999',
        'nama_puskesmas' => 'Puskesmas Prioritas 2025',
        'jumlah_posyandu' => 50,
        'jumlah_sekolah' => 50,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $top2025Facility->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $top2025Facility->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $top2025Facility->id, 'tahun' => 2025, 'total_pasien_bulanan' => 850]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $top2025Facility->id, 'tahun' => 2026, 'total_pasien_bulanan' => 100]);

    app(WorkloadService::class)->recalculateAndPersistScores(2026);

    $this->actingAs($dinkes)
        ->get(route('analitik.index', ['tahun' => 2025]))
        ->assertOk()
        ->assertSee('Puskesmas Prioritas 2025');
});

test('workload service defaults to each facility latest year without blending multiple years', function () {
    $kecamatan = Kecamatan::factory()->withPolygon()->create(['jumlah_penduduk' => 100000]);

    $facilityA = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710901',
        'jumlah_posyandu' => 20,
        'jumlah_sekolah' => 20,
    ]);
    $facilityB = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710902',
        'jumlah_posyandu' => 20,
        'jumlah_sekolah' => 20,
    ]);

    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2025, 'total_pasien_bulanan' => 800]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2026, 'total_pasien_bulanan' => 200]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityB->id, 'tahun' => 2025, 'total_pasien_bulanan' => 500]);

    $scores = app(WorkloadService::class)->calculatePSWIndex(null)->keyBy('puskesmas_id');

    expect((float) $scores[$facilityA->id]['avg_visits'])->toBe(200.0)
        ->and((float) $scores[$facilityB->id]['avg_visits'])->toBe(500.0);
});

test('analytics index, internal map, pdf view, rbac, and spatial cache invalidation work accurately per district and year', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $admin = User::factory()->admin()->create();

    $kecamatanCentral = Kecamatan::factory()->withPolygon()->create([
        'kode_kecamatan' => '3271040',
        'nama_kecamatan' => 'Bogor Tengah',
        'jumlah_penduduk' => 100000,
    ]);
    $kecamatanEast = Kecamatan::factory()->create([
        'kode_kecamatan' => '3271020',
        'nama_kecamatan' => 'Bogor Timur',
        'jumlah_penduduk' => 90000,
        'geom' => 'MULTIPOLYGON(((106.820 -6.620, 106.860 -6.620, 106.860 -6.600, 106.820 -6.600, 106.820 -6.620)))',
    ]);

    $facilityA = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatanCentral->id,
        'nama_puskesmas' => 'Puskesmas Tengah Uji',
        'x_long' => 106.7950,
        'y_lat' => -6.5950,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $facilityA->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facilityA->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $facilityA->id, 'tahun' => 2026, 'total_pasien_bulanan' => 600]);

    JangkauanJalan::factory()->withPolygon()->create(['kecamatan_id' => $kecamatanCentral->id]);
    app(WorkloadService::class)->recalculateAndPersistScores(2026);

    $service = app(SpatialAnalysisService::class);
    $fullCoverage = $service->calculateServiceArea(1.5);
    $expectedDistrictCov = collect($fullCoverage['per_kecamatan'])->firstWhere('kecamatan_id', $kecamatanCentral->id);

    $this->get(route('analitik.export.csv'))->assertRedirect(route('login'));
    $this->get(route('analitik.export.pdf'))->assertRedirect(route('login'));
    $this->getJson(route('api.v1.analisis.workload'))->assertUnauthorized();
    $this->getJson(route('api.v1.analisis.road-access'))->assertUnauthorized();

    $this->actingAs($dinkes)
        ->get(route('analitik.index', ['kecamatan_id' => $kecamatanCentral->id, 'tahun' => 2026]))
        ->assertOk()
        ->assertViewHas('coverageScope', fn (array $scope): bool => $scope['is_filtered'] === true
            && $scope['scope_label'] === 'Kec. Bogor Tengah'
            && $scope['persentase_terlayani'] === (float) $expectedDistrictCov['persentase_terlayani']
            && $scope['blankspot_km2'] === (float) $expectedDistrictCov['blankspot_km2'])
        ->assertSee('Cakupan Kec. Bogor Tengah')
        ->assertDontSee('Puskesmas di Bogor Timur');

    $this->actingAs($dinkes)
        ->get('/analitik?kecamatan_id='.$kecamatanCentral->id.'&buffer_km=3.0&tahun=2026')
        ->assertOk()
        ->assertSee('Cakupan Kec. Bogor Tengah')
        ->assertSee('Terlayani (3 km)');

    $this->actingAs($dinkes)
        ->get('/analitik/peta?kecamatan_id='.$kecamatanCentral->id.'&buffer_km=3.0&tahun=2026')
        ->assertOk()
        ->assertSee('const activeYear = 2026;', false)
        ->assertSee('const hasExplicitBuffer = true;', false)
        ->assertSee("'?tahun=' + encodeURIComponent(activeYear)", false);

    $this->actingAs($dinkes)
        ->getJson(route('api.v1.analisis.road-access', ['kecamatan_id' => $kecamatanEast->id]))
        ->assertOk()
        ->assertJsonCount(0, 'features');

    $this->actingAs($admin)
        ->get(route('analitik.export.pdf', ['kecamatan_id' => $kecamatanCentral->id, 'buffer_km' => 1.5, 'tahun' => 2026]))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');

    $this->actingAs($dinkes)->getJson(route('api.v1.analisis.coverage', ['buffer_km' => 1.5]))->assertOk();
    $this->actingAs($dinkes)->getJson(route('api.v1.analisis.road-access'))->assertOk();
    expect(Cache::has('sdss:coverage:1.5'))->toBeTrue()
        ->and(Cache::has('sdss:road_access:all'))->toBeTrue();

    $this->actingAs($admin)->put(route('admin.operasional.update', $facilityA), [
        'status_igd_24jam' => '1',
        'jam_buka' => '07:30:00',
        'jam_tutup' => '14:00:00',
        'kontak_darurat' => '0251-8111222',
    ])->assertRedirect(route('admin.operasional.edit', $facilityA));

    expect(Cache::has('sdss:coverage:1.5'))->toBeFalse()
        ->and(Cache::has('sdss:road_access:all'))->toBeFalse();
});
