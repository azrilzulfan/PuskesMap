<?php

use App\Enums\CrowdStatus;
use App\Models\Kecamatan;
use App\Models\KunjunganPasien;
use App\Models\Operasional;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;

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
        'total_pasien_bulanan' => 1500,
        'kapasitas_maksimal_bulanan' => 5603,
        'persentase_keramaian' => 26.77,
        'status_kepadatan' => CrowdStatus::Rendah,
    ]);

    $response = $this->get(route('peta'));

    $response->assertOk()
        ->assertSee('Puskesmas Uji Peta')
        ->assertSee('"crowd":"Sepi"', false);
});
