<?php

use App\Enums\CrowdStatus;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use App\Models\User;

test('map page returns successful response for public users', function () {
    $response = $this->get(route('peta'));

    $response->assertOk();
    $response->assertSee('Peta Interaktif Puskesmas');
    $response->assertSee('Daftar Faskes');
    $response->assertSee('Rekomendasi');
    $response->assertSee('Rekomendasi Puskesmas');
    $response->assertSee('Terbaik dari titik acuan');
    $response->assertSee('modal-faskes-detail');
    $response->assertSee('modal-nakes-grid');
    $response->assertSee('filter-active-badge');
    $response->assertSee('ref-precision-status');
    $response->assertSee('ref-precision-coords');
    $response->assertSee('btn-geo-radius');
    $response->assertSee('id="map-floating-card"', false);
    $response->assertDontSee('hidden sm:block absolute inset-x-3', false);
    $response->assertSee('id="left-filter-panel"', false);
    $response->assertSee('-translate-x-full lg:translate-x-0 lg:hidden', false);
    $response->assertSee('id="map-sidebar"', false);
    $response->assertSee('translate-x-full lg:translate-x-0 lg:hidden', false);

    // Pastikan fitur analitik internal Dinkes tidak tampil di halaman publik
    $response->assertDontSee('DASBOR ANALITIK SPASIAL DINAS KESEHATAN');
    $response->assertDontSee('Beban Wilayah (PSW)');
    $response->assertDontSee('panel-dinkes');
    $response->assertDontSee('tab-nav-dinkes');
});

test('legacy map route redirects to peta route', function () {
    $response = $this->get('/map');

    $response->assertRedirect(route('peta'));
});

test('map page loads database facilities and translates crowd status labels for frontend js', function () {
    $kecamatan = Kecamatan::factory()->withPolygon()->create([
        'nama_kecamatan' => 'Bogor Barat',
        'jumlah_penduduk' => 238420,
    ]);

    $facility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P32710001',
        'nama_puskesmas' => 'Puskesmas Uji Peta',
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $facility->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facility->id]);
    KunjunganPasien::factory()->create([
        'puskesmas_id' => $facility->id,
        'total_pasien_bulanan' => 425,
        'kapasitas_maksimal_bulanan' => 850,
        'persentase_keramaian' => 50.0,
        'status_kepadatan' => CrowdStatus::Rendah,
    ]);

    $response = $this->get(route('peta'));

    $response->assertOk()
        ->assertSee('Puskesmas Uji Peta')
        ->assertSee('"Dokter":3', false)
        ->assertSee('"Farmasi":2', false)
        ->assertSee('"crowd":"Sepi"', false);
});

test('public map page hides pemda scenario and rawat inap filter while authenticated dinkes sees pemda', function () {
    $kecamatan = Kecamatan::factory()->create(['nama_kecamatan' => 'Bogor Tengah']);

    $igdFacility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P3271040101',
    ]);
    Operasional::factory()->igd24Jam()->create(['puskesmas_id' => $igdFacility->id]);
    TenagaMedis::factory()->create(['puskesmas_id' => $igdFacility->id]);

    $guestResponse = $this->get(route('peta'));
    $guestResponse->assertOk()
        ->assertSee('IGD 24 Jam (1)')
        ->assertSee('id="btn-basemap-osm"', false)
        ->assertSee('id="btn-basemap-grey"', false)
        ->assertDontSee('Rawat Inap')
        ->assertDontSee('value="pemda"', false)
        ->assertDontSee('data-quick-scenario="pemda"', false);

    $dinkesUser = User::factory()->pemerintah()->create();
    $this->actingAs($dinkesUser)
        ->get(route('peta'))
        ->assertOk()
        ->assertSee('value="pemda"', false)
        ->assertSee('data-quick-scenario="pemda"', false);
});

test('puskesmas detail page displays complete profile, 9 nakes, ukm metrics, and 12-month visit trend', function () {
    $kecamatan = Kecamatan::factory()->create(['nama_kecamatan' => 'Tanah Sareal']);

    $facility = Puskesmas::factory()->create([
        'kecamatan_id' => $kecamatan->id,
        'kode_puskesmas' => 'P3271060101',
        'nama_puskesmas' => 'Puskesmas Tanah Sareal',
        'website_url' => 'https://dinkes.kotabogor.go.id/puskesmas-tanah-sareal',
        'jumlah_posyandu' => 30,
        'jumlah_sekolah' => 18,
        'skor_psw' => 0.31,
    ]);
    Operasional::factory()->igd24Jam()->create([
        'puskesmas_id' => $facility->id,
        'kontak_darurat' => '0251-8312345',
    ]);
    TenagaMedis::factory()->create(['puskesmas_id' => $facility->id]);

    for ($month = 1; $month <= 12; $month++) {
        KunjunganPasien::factory()->create([
            'puskesmas_id' => $facility->id,
            'tahun' => 2025,
            'bulan' => $month,
            'total_pasien_bulanan' => 500 + $month,
        ]);
    }

    $response = $this->get(route('puskesmas.show', ['kode_puskesmas' => 'P3271060101']));

    $response->assertOk()
        ->assertSee('Puskesmas Tanah Sareal')
        ->assertSee('P3271060101')
        ->assertSee('Kec. Tanah Sareal')
        ->assertSee('0251-8312345')
        ->assertSee('https://dinkes.kotabogor.go.id/puskesmas-tanah-sareal', false)
        ->assertSee('30')
        ->assertSee('18')
        ->assertSee('id="visitTrendChart"', false)
        ->assertSee('id="visit-trend-data"', false);

    $this->get('/puskesmas/TIDAK_ADA')->assertNotFound();
});
