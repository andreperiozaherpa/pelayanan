<?php

use App\Models\Opd;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
    Role::create(['name' => 'OperatorOpd', 'slug' => 'operatoropd']);
    Role::create(['name' => 'OperatorDesa', 'slug' => 'operatordesa']);
});

test('user dapat dikaitkan dengan opd', function () {
    $opd = Opd::create(['name' => 'Dinas Kesehatan', 'code' => '05']);
    $role = Role::where('slug', 'operatoropd')->first();

    $user = User::factory()->create([
        'role_id' => $role->id,
        'opd_id' => $opd->id,
    ]);

    expect($user->opd)->not->toBeNull()
        ->and($user->opd->name)->toBe('Dinas Kesehatan');
});

test('opd memiliki relasi hasMany ke users', function () {
    $opd = Opd::create(['name' => 'Dinas Pendidikan', 'code' => '04']);
    $role = Role::where('slug', 'operatoropd')->first();

    User::factory()->count(3)->create([
        'role_id' => $role->id,
        'opd_id' => $opd->id,
    ]);

    expect($opd->users)->toHaveCount(3);
});

test('opd_id bersifat nullable pada user', function () {
    $role = Role::where('slug', 'superadmin')->first();

    $user = User::factory()->create([
        'role_id' => $role->id,
        'opd_id' => null,
    ]);

    expect($user->opd)->toBeNull()
        ->and($user->opd_id)->toBeNull();
});

test('isOperatorOpd mengembalikan true untuk role operatoropd', function () {
    $role = Role::where('slug', 'operatoropd')->first();

    $user = User::factory()->create(['role_id' => $role->id]);

    expect($user->isOperatorOpd())->toBeTrue();
});

test('isOperatorOpd mengembalikan false untuk role lain', function () {
    $role = Role::where('slug', 'superadmin')->first();

    $user = User::factory()->create(['role_id' => $role->id]);

    expect($user->isOperatorOpd())->toBeFalse();
});
