<?php

namespace Database\Factories;

use App\Models\HouseholdCard;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HouseholdCard>
 */
class HouseholdCardFactory extends Factory
{
    protected $model = HouseholdCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_kk' => $this->faker->unique()->numerify('################'),
            'head_name' => $this->faker->name('male'),
            'address' => 'Jl. '.$this->faker->streetName.' No. '.$this->faker->buildingNumber,
            'rt' => $this->faker->numerify('0##'),
            'rw' => $this->faker->numerify('0##'),
            'village_id' => Village::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
