<?php

namespace Database\Factories;

use App\Enums\NakesCategory;
use App\Models\Puskesmas;
use App\Models\TenagaMedis;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TenagaMedis>
 */
class TenagaMedisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'puskesmas_id' => Puskesmas::factory(),
            'dokter_umum' => 3,
            'dokter_gigi' => 1,
            'bidan' => 6,
            'perawat' => 6,
            'farmasi' => 2,
            'gizi' => 1,
            'kesling' => 1,
            'kesmas' => 1,
            'atlm' => 1,
            'persentase_kelengkapan' => 100.0,
            'kategori_jumlah_nakes' => NakesCategory::Lengkap,
        ];
    }

    public function kurangLengkap(): static
    {
        return $this->state(fn (array $attributes): array => [
            'gizi' => 0,
            'persentase_kelengkapan' => 88.89,
            'kategori_jumlah_nakes' => NakesCategory::KurangLengkap,
        ]);
    }
}
