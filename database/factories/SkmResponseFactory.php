<?php

namespace Database\Factories;

use App\Models\Opd;
use App\Models\SkmResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SkmResponse>
 */
class SkmResponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'opd_id' => Opd::factory(),
            'nama' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'umur' => $this->faker->numberBetween(17, 65),
            'pendidikan' => $this->faker->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2']),
            'pekerjaan' => $this->faker->randomElement(['PNS', 'Swasta', 'Wiraswasta', 'Pelajar/Mahasiswa', 'IRT']),
            'u1' => $this->faker->numberBetween(1, 4),
            'u2' => $this->faker->numberBetween(1, 4),
            'u3' => $this->faker->numberBetween(1, 4),
            'u4' => $this->faker->numberBetween(1, 4),
            'u5' => $this->faker->numberBetween(1, 4),
            'u6' => $this->faker->numberBetween(1, 4),
            'u7' => $this->faker->numberBetween(1, 4),
            'u8' => $this->faker->numberBetween(1, 4),
            'u9' => $this->faker->numberBetween(1, 4),
            'saran' => $this->faker->optional()->sentence(),
            'source' => SkmResponse::SOURCE_WEB,
        ];
    }
}
