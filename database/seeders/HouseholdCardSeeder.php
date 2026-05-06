<?php

namespace Database\Seeders;

use App\Models\Citizen;
use App\Models\HouseholdCard;
use Illuminate\Database\Seeder;

class HouseholdCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 20 Household Cards
        HouseholdCard::factory()
            ->count(20)
            ->create()
            ->each(function ($kk) {
                // For each KK, create 2-5 family members (Citizens)
                $memberCount = rand(2, 5);

                Citizen::factory()
                    ->count($memberCount)
                    ->create([
                        'household_card_id' => $kk->no_kk,
                        'desa_id' => $kk->village_id,
                        'alamat_desa' => $kk->address.', RT '.$kk->rt.'/RW '.$kk->rw,
                    ]);

                // Update the first member to have the same name as the head_name (optional, but realistic)
                $firstMember = Citizen::where('household_card_id', $kk->no_kk)->first();
                if ($firstMember) {
                    $firstMember->update(['nama_lengkap' => $kk->head_name]);
                }
            });
    }
}
