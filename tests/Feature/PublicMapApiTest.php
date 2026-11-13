<?php

use App\Enums\NakesCategory;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;

test('public geojson api lists and filters puskesmas with related records', function () {
    $kecA = Kecamatan::factory()->withPolygon()->create(['nama_kecamatan' => 'Bogor Tengah']);
    $kecB = Kecamatan::factory()->withPolygon()->create(['nama_kecamatan' => 'Bogor Timur']);

    $igdFacility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecA->id,
        'nama_puskesmas' => 'Puskesmas Bogor Tengah',
        'x_long' => 106.7950,
        'y_lat' => -6.5950,
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $igdFacility->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $igdFacility->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $igdFacility->id, 'total_pasien_bulanan' => 2800]);

    $regularFacility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecB->id,
        'nama_puskesmas' => 'Puskesmas Bogor Timur',
        'x_long' => 106.8200,
        'y_lat' => -6.6120,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $regularFacility->id, 'status_igd_24jam' => false]);
    TenagaMedis::factory()->kurangLengkap()->create(['puskesmas_id' => $regularFacility->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $regularFacility->id, 'total_pasien_bulanan' => 4500]);

    $allResponse = $this->getJson(route('api.v1.puskesmas.index'));

    $allResponse->assertOk()
        ->assertJsonPath('type', 'FeatureCollection')
        ->assertJsonPath('count', 2)
        ->assertJsonStructure([
            'type',
            'count',
            'features' => [
                '*' => [
                    'type',
                    'geometry' => ['type', 'coordinates'],
                    'properties' => ['id', 'kode_puskesmas', 'nama_puskesmas', 'status_igd_24jam', 'persentase_kelengkapan_nakes'],
                ],
            ],
        ]);

    $filteredResponse = $this->getJson(route('api.v1.puskesmas.index', [
        'igd_24jam' => 1,
        'kelengkapan_nakes' => NakesCategory::Lengkap->value,
    ]));

    $filteredResponse->assertOk()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('features.0.properties.nama_puskesmas', 'Puskesmas Bogor Tengah');

    $this->getJson(route('api.v1.puskesmas.show', ['puskesmas' => $igdFacility->id]))
        ->assertOk()
        ->assertJsonPath('data.nama_puskesmas', 'Puskesmas Bogor Tengah')
        ->assertJsonPath('data.operasional.status_igd_24jam', true);

    $this->getJson(route('api.v1.puskesmas.show', ['puskesmas' => 999999]))
        ->assertNotFound();
});

test('nearest and radius spatial endpoints use geodesic postgis distance and buffer geometry', function () {
    $kec = Kecamatan::factory()->withPolygon()->create();

    $near = Puskesmas::factory()->create([
        'kecamatan_id' => $kec->id,
        'nama_puskesmas' => 'Puskesmas Terdekat',
        'x_long' => 106.7960,
        'y_lat' => -6.5960,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $near->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $near->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $near->id]);

    $far = Puskesmas::factory()->create([
        'kecamatan_id' => $kec->id,
        'nama_puskesmas' => 'Puskesmas Jauh',
        'x_long' => 106.8450,
        'y_lat' => -6.6450,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $far->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $far->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $far->id]);

    $nearestResponse = $this->getJson(route('api.v1.puskesmas.nearest', [
        'lat' => -6.5950,
        'lng' => 106.7950,
        'limit' => 2,
    ]));

    $nearestResponse->assertOk()
        ->assertJsonPath('count', 2)
        ->assertJsonPath('features.0.properties.nama_puskesmas', 'Puskesmas Terdekat');

    $radiusResponse = $this->getJson(route('api.v1.puskesmas.radius', [
        'lat' => -6.5950,
        'lng' => 106.7950,
        'radius' => 1000,
    ]));

    $radiusResponse->assertOk()
        ->assertJsonPath('count', 1)
        ->assertJsonPath('features.0.properties.nama_puskesmas', 'Puskesmas Terdekat')
        ->assertJsonPath('meta.buffer_geometry.type', 'Polygon');
});

test('recommendation endpoint enforces kota bogor polygon boundary and computes maut top 3', function () {
    $kec = Kecamatan::factory()->withPolygon()->create();

    $p1 = Puskesmas::factory()->create([
        'kecamatan_id' => $kec->id,
        'nama_puskesmas' => 'Puskesmas Darurat Utama',
        'x_long' => 106.7960,
        'y_lat' => -6.5960,
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $p1->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $p1->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $p1->id, 'total_pasien_bulanan' => 2000]);

    $p2 = Puskesmas::factory()->create([
        'kecamatan_id' => $kec->id,
        'nama_puskesmas' => 'Puskesmas Reguler Terdekat',
        'x_long' => 106.7952,
        'y_lat' => -6.5952,
    ]);
    Operasional::factory()->create(['puskesmas_id' => $p2->id, 'status_igd_24jam' => false]);
    TenagaMedis::factory()->create(['puskesmas_id' => $p2->id]);
    KunjunganPasien::factory()->create(['puskesmas_id' => $p2->id, 'total_pasien_bulanan' => 1800]);

    $outsideResponse = $this->postJson(route('api.v1.rekomendasi'), [
        'lat' => -6.2000,
        'lng' => 106.8166,
        'scenario' => 'darurat',
    ]);

    $outsideResponse->assertUnprocessable()
        ->assertJsonValidationErrors('lat');

    $validResponse = $this->postJson(route('api.v1.rekomendasi'), [
        'lat' => -6.5950,
        'lng' => 106.7950,
        'scenario' => 'darurat',
    ]);

    $validResponse->assertOk()
        ->assertJsonPath('scenario', 'darurat')
        ->assertJsonPath('recommendations.0.rank', 1)
        ->assertJsonPath('recommendations.0.facility.nama_puskesmas', 'Puskesmas Darurat Utama');

    $this->postJson(route('api.v1.rekomendasi'), [
        'lat' => -6.5950,
        'lng' => 106.7950,
        'scenario' => 'pemda',
    ])->assertForbidden();

    $dinkesUser = User::factory()->pemerintah()->create();

    $this->actingAs($dinkesUser)
        ->postJson(route('api.v1.rekomendasi'), [
            'lat' => -6.5950,
            'lng' => 106.7950,
            'scenario' => 'pemda',
        ])
        ->assertOk()
        ->assertJsonPath('scenario', 'pemda');
});
