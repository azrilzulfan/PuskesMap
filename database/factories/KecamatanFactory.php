<?php

namespace Database\Factories;

use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Kecamatan>
 */
class KecamatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_kecamatan' => (string) fake()->unique()->numberBetween(3271010, 3271999),
            'nama_kecamatan' => 'Kecamatan '.fake()->citySuffix(),
            'jumlah_penduduk' => fake()->numberBetween(100000, 250000),
            'luas_wilayah_km2' => fake()->randomFloat(2, 8.0, 35.0),
        ];
    }

    public function withPolygon(?string $wkt = null): static
    {
        $polygonWkt = $wkt ?? 'MULTIPOLYGON (((106.73 -6.66, 106.86 -6.66, 106.86 -6.52, 106.73 -6.52, 106.73 -6.66)))';

        return $this->afterCreating(function (Kecamatan $kecamatan) use ($polygonWkt): void {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::update(
                    'UPDATE kecamatan SET geom = ST_GeomFromText(?, 4326) WHERE id = ?',
                    [$polygonWkt, $kecamatan->id],
                );
            }
        });
    }
}
