<?php

namespace Database\Factories;

use App\Models\PovertyRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PovertyRecord>
 */
class PovertyRecordFactory extends Factory
{
    protected $model = PovertyRecord::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['ACTIVE', 'EXPIRED', 'PENDING']),
            'income_range' => $this->faker->randomElement(['< 1jt', '1jt - 2jt', '2jt - 5jt']),
            'valid_from' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'valid_until' => $this->faker->dateTimeBetween('+1 month', '+2 years')->format('Y-m-d'),
            'source' => $this->faker->randomElement(['Data Desa', 'Hasil Survey', 'DTKS']),
        ];
    }
}
