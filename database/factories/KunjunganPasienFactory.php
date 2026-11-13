<?php

namespace Database\Factories;

use App\Enums\CrowdStatus;
use App\Models\KunjunganPasien;
use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KunjunganPasien>
 */
class KunjunganPasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->numberBetween(300, 950);
        $capacity = (int) config('sdss.capacity.default_monthly', 850);
        $percentage = round(($total / $capacity) * 100, 1);

        return [
            'puskesmas_id' => Puskesmas::factory(),
            'bulan' => 12,
            'tahun' => 2025,
            'total_pasien_bulanan' => $total,
            'kapasitas_maksimal_bulanan' => $capacity,
            'persentase_keramaian' => $percentage,
            'status_kepadatan' => CrowdStatus::fromPercentage($percentage),
        ];
    }
}
