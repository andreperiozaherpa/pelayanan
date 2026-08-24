<?php

namespace Database\Factories;

use App\Models\Gerai;
use App\Models\Opd;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Gerai>
 */
class GeraiFactory extends Factory
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
            'code' => strtoupper(Str::random(2)),
            'name' => 'Gerai '.$this->faker->company(),
            'location' => $this->faker->address(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
