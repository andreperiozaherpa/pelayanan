<?php

namespace Database\Factories;

use App\Models\MppService;
use App\Models\MppServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MppServiceRequest>
 */
class MppServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mpp_service_id' => MppService::factory(),
            'front_office_user_id' => User::factory(),
            'submitted_form_data' => [
                'nama_pemohon' => [
                    'label' => 'Nama Pemohon',
                    'type' => 'text',
                    'value' => fake()->name(),
                ],
            ],
            'status' => MppServiceRequest::STATUS_PENDING,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => MppServiceRequest::STATUS_PENDING]);
    }

    public function processed(): static
    {
        return $this->state(['status' => MppServiceRequest::STATUS_PROCESSED]);
    }

    public function completed(): static
    {
        return $this->state(['status' => MppServiceRequest::STATUS_COMPLETED]);
    }

    public function rejected(): static
    {
        return $this->state(['status' => MppServiceRequest::STATUS_REJECTED]);
    }
}
