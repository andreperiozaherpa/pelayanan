<?php

namespace Database\Factories;

use App\Models\Counter;
use App\Models\MppService;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Queue>
 */
class QueueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => 'T-'.str_pad((string) random_int(1, 999), 3, '0', STR_PAD_LEFT),
            'service_id' => MppService::factory(),
            'status' => Queue::STATUS_WAITING_FO,
        ];
    }

    public function waitingFo(): static
    {
        return $this->state(['status' => Queue::STATUS_WAITING_FO]);
    }

    public function callingFo(?int $petugasId = null): static
    {
        return $this->state(fn () => [
            'status' => Queue::STATUS_CALLING_FO,
            'fo_petugas_id' => $petugasId ?? User::factory(),
            'called_at' => now(),
        ]);
    }

    public function waitingGerai(Counter $counter): static
    {
        return $this->state(fn () => [
            'status' => Queue::STATUS_WAITING_GERAI,
            'counter_id' => $counter->id,
            'counter_name' => $counter->name,
        ]);
    }

    public function callingGerai(Counter $counter, ?int $petugasId = null): static
    {
        return $this->state(fn () => [
            'status' => Queue::STATUS_CALLING_GERAI,
            'counter_id' => $counter->id,
            'counter_name' => $counter->name,
            'gerai_petugas_id' => $petugasId ?? User::factory(),
            'called_at' => now(),
        ]);
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status' => Queue::STATUS_DONE,
            'done_at' => now(),
        ]);
    }

    public function rejected(string $alasan = 'Berkas tidak lengkap'): static
    {
        return $this->state(fn () => [
            'status' => Queue::STATUS_REJECTED,
            'alasan_reject' => $alasan,
        ]);
    }
}
