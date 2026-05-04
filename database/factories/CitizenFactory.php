<?php

namespace Database\Factories;

use App\Models\Citizen;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Citizen>
 */
class CitizenFactory extends Factory
{
    protected $model = Citizen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('################'),
            'nama_lengkap' => $this->faker->name(),
            'tgl_lahir' => $this->faker->date(),
            'alamat_desa' => 'Kp. '.$this->faker->lastName.' No. '.$this->faker->buildingNumber.', RT 01/RW 03',
            'kontak' => '08'.$this->faker->numerify('##########'),
            'desa_id' => Village::inRandomOrder()->first()->id ?? 1,
        ];
    }
}
