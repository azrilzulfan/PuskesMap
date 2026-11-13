<?php

use Illuminate\Support\Facades\Blade;

test('dataset referensi csv dan geojson tersedia lengkap dengan jumlah baris sesuai spesifikasi', function () {
    $csvCounts = [
        'kecamatan.csv' => 6,
        'puskesmas.csv' => 25,
        'operasional.csv' => 25,
        'tenaga_medis.csv' => 25,
        'kunjungan_pasien.csv' => 300,
        'jangkauan_jalan.csv' => 37,
    ];

    foreach ($csvCounts as $file => $expectedRows) {
        $path = database_path('seeders/csv/' . $file);
        expect(is_file($path))->toBeTrue();

        $handle = fopen($path, 'r');
        fgetcsv($handle, 0, ',', '"', ''); // header
        $count = 0;
        while (($row = fgetcsv($handle, 0, ',', '"', '')) !== false) {
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }
            $count++;
        }
        fclose($handle);

        expect($count)->toBe($expectedRows);
    }

    foreach (['jalan_utama.geojson', 'kecamatan.geojson', 'puskesmas.geojson'] as $geoFile) {
        $geoPath = public_path('geojson/' . $geoFile);
        expect(is_file($geoPath))->toBeTrue();
        $decoded = json_decode((string) file_get_contents($geoPath), true);
        expect($decoded)->toBeArray()->and($decoded['type'] ?? null)->toBe('FeatureCollection');
    }
});

test('komponen blade dasar sdss merender atribut aksesibilitas dan ikon lucide dengan benar', function () {
    $buttonHtml = Blade::render('<x-ui.button :loading="true" loading-text="Menyimpan...">Simpan</x-ui.button>');
    expect($buttonHtml)
        ->toContain('disabled')
        ->toContain('aria-busy="true"')
        ->toContain('Menyimpan...');

    $inputHtml = Blade::render('<x-ui.input name="email" label="Email" error="Email wajib diisi." :required="true" />');
    expect($inputHtml)
        ->toContain('aria-invalid="true"')
        ->toContain('aria-describedby="email-error"')
        ->toContain('role="alert"')
        ->toContain('Email wajib diisi.');

    $selectHtml = Blade::render('<x-ui.select name="radius" label="Radius" :options="[\'1000\' => \'1 km\']" selected="1000" />');
    expect($selectHtml)
        ->toContain('for="radius"')
        ->toContain('1 km');

    $badgeHtml = Blade::render('<x-ui.badge variant="success">Buka</x-ui.badge>');
    expect($badgeHtml)->toContain('Buka');

    $drawerHtml = Blade::render('<x-ui.drawer label="Panel Peta"><x-slot:header>PuskesMap</x-slot:header> Konten</x-ui.drawer>');
    expect($drawerHtml)
        ->toContain('aria-label="Panel Peta"')
        ->toContain('PuskesMap');

    $modalHtml = Blade::render('<x-ui.modal name="detail" title="Detail Puskesmas">Isi dialog</x-ui.modal>');
    expect($modalHtml)
        ->toContain('role="dialog"')
        ->toContain('aria-modal="true"')
        ->toContain('Detail Puskesmas');

    expect(config('sdss.capacity.default_monthly'))->toBe(850);
});
