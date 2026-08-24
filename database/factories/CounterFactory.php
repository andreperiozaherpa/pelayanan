<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\Gerai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Counter>
 */
class CounterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gerai_id' => Gerai::factory(),
            'code' => strtoupper(fake()->unique()->lexify('??')),
            'name' => fake()->unique()->words(2, true),
            'location' => fake()->optional()->sentence(),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}
