<?php

namespace Database\Factories;

use App\Models\JangkauanJalan;
use App\Models\Kecamatan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<JangkauanJalan>
 */
class JangkauanJalanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kecamatan_id' => Kecamatan::factory()->withPolygon(),
            'kategori_jangkauan' => 'Sangat Dekat',
            'jangkauan_faskes' => 1000,
            'jangkauan_jalan' => 200,
        ];
    }

    public function withPolygon(?string $wkt = null): static
    {
        $polygonWkt = $wkt ?? 'MULTIPOLYGON (((106.78 -6.61, 106.80 -6.61, 106.80 -6.59, 106.78 -6.59, 106.78 -6.61)))';

        return $this->afterCreating(function (JangkauanJalan $row) use ($polygonWkt): void {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::update(
                    'UPDATE jangkauan_jalan SET geom = ST_GeomFromText(?, 4326) WHERE id = ?',
                    [$polygonWkt, $row->id],
                );
            }
        });
    }
}
