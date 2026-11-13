<?php

namespace Database\Factories;

use App\Models\Operasional;
use App\Models\Puskesmas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Operasional>
 */
class OperasionalFactory extends Factory
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
            'status_igd_24jam' => false,
            'jam_buka' => '07:30:00',
            'jam_tutup' => '14:00:00',
            'kontak_darurat' => '0251-8320000',
            'rawat_inap' => false,
        ];
    }

    public function igd24Jam(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status_igd_24jam' => true,
            'jam_buka' => '07:30:00',
            'jam_tutup' => '14:00:00',
        ]);
    }
}
