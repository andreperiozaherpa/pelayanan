<?php

use App\Models\Counter;
use App\Models\Gerai;
use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);

    $this->superAdmin = User::factory()->create([
        'role_id' => Role::where('slug', 'superadmin')->first()->id,
    ]);

    $this->opd = Opd::create([
        'code' => '11',
        'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
    ]);
});

test('daftar loket di urutkan berdasarkan kode loket', function () {
    $cCodes = ['3', '1', '2'];
    foreach ($cCodes as $code) {
        $gerai = Gerai::create([
            'opd_id' => $this->opd->id,
            'code' => $code,
            'name' => 'Gerai '.$code,
            'is_active' => true,
        ]);

        Counter::create([
            'gerai_id' => $gerai->id,
            'code' => $code,
            'name' => 'Loket '.$code,
            'is_active' => true,
        ]);
    }

    $response = $this->actingAs($this->superAdmin)
        ->get(route('counters.index'))
        ->assertStatus(200);

    $html = $response->getContent();

    preg_match_all('/py-1 bg-primary-acorn\/10[^>]*>\s*([0-9]+)\s*<\/span>/', $html, $matches);
    $codes = $matches[1];

    expect($codes)->toBe(['1', '2', '3']);
});
