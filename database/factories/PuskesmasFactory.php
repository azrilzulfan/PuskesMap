<?php

namespace Database\Factories;

use App\Enums\ServiceCategory;
use App\Models\Kecamatan;
use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends Factory<Puskesmas>
 */
class PuskesmasFactory extends Factory
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
            'kode_puskesmas' => 'P'.fake()->unique()->numerify('3271######'),
            'nama_puskesmas' => 'Puskesmas '.fake()->unique()->streetName(),
            'alamat' => fake()->streetAddress().', Kota Bogor',
            'x_long' => fake()->randomFloat(6, 106.76, 106.83),
            'y_lat' => fake()->randomFloat(6, -6.64, -6.55),
            'website_url' => 'https://dinkes.kotabogor.go.id',
            'kategori_layanan' => ServiceCategory::NonRawatInap,
            'jumlah_posyandu' => fake()->numberBetween(20, 50),
            'jumlah_sekolah' => fake()->numberBetween(15, 40),
            'skor_psw' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Puskesmas $puskesmas): void {
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::update(
                    'UPDATE puskesmas SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?',
                    [$puskesmas->x_long, $puskesmas->y_lat, $puskesmas->id],
                );
            }
        });
    }
}
