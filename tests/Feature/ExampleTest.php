<?php

use Database\Seeders\DatasetBogorSeeder;

test('the application returns a successful response when database is empty', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Belum ada data puskesmas terdata saat ini.')
        ->assertSee('id="landing-puskesmas-data"', false);
});

test('landing page loads all 25 facilities from database with detail links and bogor center', function () {
    $this->seed(DatasetBogorSeeder::class);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Lihat Detail')
        ->assertSee(route('puskesmas.show', ['kode_puskesmas' => 'P3271060101'], false), false)
        ->assertSee('https://dinkes.kotabogor.go.id/puskesmas-tanah-sareal', false)
        ->assertSee('88,9%')
        ->assertDontSee('images/igd24jam.png', false)
        ->assertDontSee('images/buka-sekarang.png', false)
        ->assertDontSee('Lihat di Peta');

    $html = $response->getContent();
    preg_match('/<script id="landing-puskesmas-data" type="application\/json">(.*?)<\/script>/s', $html, $matches);
    expect($matches[1] ?? null)->not->toBeNull();

    $payload = json_decode((string) $matches[1], true);
    expect($payload)->toBeArray()
        ->and($payload['facilities'] ?? [])->toHaveCount(25)
        ->and($payload['center']['lat'] ?? null)->toBe(config('sdss.spatial.bogor_center.lat'))
        ->and($payload['center']['lng'] ?? null)->toBe(config('sdss.spatial.bogor_center.lng'));

    foreach ($payload['facilities'] as $facility) {
        expect($facility['image'] ?? null)->not->toBeNull()
            ->and(is_file(public_path($facility['image'])))->toBeTrue();
    }
});
