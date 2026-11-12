<?php

test('map page returns successful response for public users', function () {
    $response = $this->get(route('peta'));

    $response->assertOk();
    $response->assertSee('Peta Interaktif Puskesmas');
    $response->assertSee('Daftar Faskes');
    $response->assertSee('Rekomendasi');
    $response->assertSee('Rekomendasi Puskesmas');
    $response->assertSee('Urutan terbaik dari lokasi Anda saat ini');
    $response->assertSee('modal-faskes-detail');
    $response->assertSee('modal-nakes-grid');

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
