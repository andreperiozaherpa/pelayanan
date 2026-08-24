<?php

namespace Database\Seeders;

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('slug', 'superadmin')->first();
        $foRole = Role::where('slug', 'petugasfrontoffice')->first();
        $roleGerai = Role::where('slug', 'gerai')->first();

        // 1. Web Users (Super Admin & Front Office)
        User::updateOrCreate(
            ['email' => 'admin@pelayanan.test'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'desa_id' => 1,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'fo@pelayanan.test'],
            [
                'name' => 'Front Office Staff',
                'password' => Hash::make('password'),
                'role_id' => $foRole->id,
                'desa_id' => 1,
                'email_verified_at' => now(),
            ]
        );

        // 1b. Akun login per role (Contoh)
        $desaSukamaju = Village::where('name', 'Desa Sukamaju')->first();
        $desaSukaraya = Village::where('name', 'Desa Sukaraya')->first();

        $opdKesehatan = Opd::where('code', '05')->first();
        $opdSosial = Opd::where('code', '09')->first();

        $accountUsers = [
            ['name' => 'Administrator Pusat', 'email' => 'admin@example.com', 'role' => Role::where('slug', 'superadmin')->first()],
            ['name' => 'Petugas Front Office', 'email' => 'fo@example.com', 'role' => Role::where('slug', 'petugasfrontoffice')->first()],
            ['name' => 'Operator Desa Sukamaju', 'email' => 'sukamaju@example.com', 'role' => Role::where('slug', 'operatordesa')->first(), 'desa_id' => $desaSukamaju?->id],
            ['name' => 'Operator Desa Sukaraya', 'email' => 'sukaraya@example.com', 'role' => Role::where('slug', 'operatordesa')->first(), 'desa_id' => $desaSukaraya?->id],
            ['name' => 'Operator Dinas Kesehatan', 'email' => 'dinkes@example.com', 'role' => Role::where('slug', 'operatoropd')->first(), 'opd_id' => $opdKesehatan?->id],
            ['name' => 'Operator Dinas Sosial', 'email' => 'dinsos@example.com', 'role' => Role::where('slug', 'operatoropd')->first(), 'opd_id' => $opdSosial?->id],
        ];

        foreach ($accountUsers as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => 'password',
                    'role_id' => $data['role']->id,
                    'desa_id' => $data['desa_id'] ?? null,
                    'opd_id' => $data['opd_id'] ?? null,
                    'email_verified_at' => now(),
                    'is_active' => true,
                ]
            );
        }

        // 2. Queue Users (FO & Gerai) - 1 loket = 1 user
        if (! $foRole || ! $roleGerai) {
            $this->command->warn('Role FO/Gerai belum tersedia, seeder queue user dilewati.');

            return;
        }

        $counters = Counter::where('is_active', true)->orderBy('code')->get()->keyBy('code');

        $users = [
            ['username' => 'fo1', 'email' => 'fo1@pelayanan.test', 'role' => $foRole, 'counter' => $counters->get('1')],
            ['username' => 'fo2', 'email' => 'fo2@pelayanan.test', 'role' => $foRole, 'counter' => $counters->get('2')],
            ['username' => 'gerai1', 'email' => 'gerai1@pelayanan.test', 'role' => $roleGerai, 'counter' => $counters->get('3')],
            ['username' => 'gerai2', 'email' => 'gerai2@pelayanan.test', 'role' => $roleGerai, 'counter' => $counters->get('4')],
            ['username' => 'gerai3', 'email' => 'gerai3@pelayanan.test', 'role' => $roleGerai, 'counter' => $counters->get('5')],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['username'],
                    'password' => 'lerd123',
                    'role_id' => $data['role']->id,
                    'is_active' => true,
                ]
            );

            if ($data['counter']) {
                CounterUser::updateOrCreate(
                    ['counter_id' => $data['counter']->id, 'user_id' => $user->id],
                    ['is_active' => true]
                );
            }
        }

        $this->command->info('Seeded '.count($users).' queue users (FO & Gerai).');
    }
}
