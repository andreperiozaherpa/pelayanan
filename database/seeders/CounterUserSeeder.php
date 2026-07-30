<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class CounterUserSeeder extends Seeder
{
    public function run(): void
    {
        $foUser = User::whereHas('role', fn ($q) => $q->where('slug', 'petugasfrontoffice'))->first();
        if (! $foUser) {
            $this->command->warn('Tidak ada user FO, seeder CounterUser dilewati.');

            return;
        }

        $counters = Counter::where('is_active', true)->get();

        foreach ($counters as $counter) {
            CounterUser::create([
                'counter_id' => $counter->id,
                'user_id' => $foUser->id,
                'is_active' => true,
            ]);
        }

        $this->command->info('Seeded '.$counters->count().' counter-user assignments.');
    }
}
