<?php

use App\Models\Counter;
use App\Models\CounterUser;
use App\Models\Gerai;
use App\Models\Opd;
use App\Models\Permission;
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

function geraiLoket(Opd $opd, string $code): Counter
{
    $gerai = Gerai::create([
        'opd_id' => $opd->id,
        'code' => $code,
        'name' => 'Gerai '.$code,
        'is_active' => true,
    ]);

    return Counter::create([
        'gerai_id' => $gerai->id,
        'code' => $code,
        'name' => 'Loket '.$code,
        'is_active' => true,
    ]);
}

function assignLoket(Counter $counter, User $user): void
{
    CounterUser::create([
        'counter_id' => $counter->id,
        'user_id' => $user->id,
        'is_active' => true,
    ]);
}

function enablerPetugasMpp(): User
{
    $role = Role::where('slug', 'petugasfrontoffice')->firstOrFail();

    return User::factory()->create(['role_id' => $role->id]);
}

function createUserWithoutMppPermission(): User
{
    $permission = Permission::updateOrCreate(
        ['slug' => 'audit.view'],
        ['name' => 'Lihat Log Audit', 'description' => 'x'],
    );

    $role = Role::create([
        'name' => 'Staf Arsip Tanpa MPP',
        'slug' => 'stafarsip',
    ]);
    $role->permissions()->sync([$permission->id]);

    return User::factory()->create(['role_id' => $role->id]);
}

test('superadmin dapat menugaskan petugas ke loket yang kosong', function () {
    $counter = geraiLoket($this->opd, '1');
    $user = User::factory()->create();

    $this->actingAs($this->superAdmin)
        ->post(route('counter-users.store'), [
            'counter_id' => $counter->id,
            'user_id' => $user->id,
            'is_active' => 1,
        ])
        ->assertRedirect(route('counter-users.index'));

    $this->assertDatabaseHas('mpp_counter_user', [
        'counter_id' => $counter->id,
        'user_id' => $user->id,
        'is_active' => true,
    ]);
});

test('penugasan ditolak jika petugas tidak memiliki permission pengelolaan MPP', function () {
    $counter = geraiLoket($this->opd, '1');
    $user = createUserWithoutMppPermission();

    $this->actingAs($this->superAdmin)
        ->post(route('counter-users.store'), [
            'counter_id' => $counter->id,
            'user_id' => $user->id,
            'is_active' => 1,
        ])
        ->assertSessionHasErrors('user_id');

    expect(CounterUser::where('counter_id', $counter->id)->where('user_id', $user->id)->exists())->toBeFalse();
});

test('halaman create hanya menampilkan petugas dengan permission pengelolaan MPP', function () {
    $counter = geraiLoket($this->opd, '1');

    $eligible = enablerPetugasMpp();
    $inelegible = createUserWithoutMppPermission();

    $response = $this->actingAs($this->superAdmin)
        ->get(route('counter-users.create'));

    $response->assertStatus(200)
        ->assertSee($eligible->email)
        ->assertDontSee($inelegible->email);
});

test('penugasan ke loket yang sudah terisi ditolak', function () {
    $counter = geraiLoket($this->opd, '1');
    $existing = User::factory()->create();
    $newUser = User::factory()->create();
    assignLoket($counter, $existing);

    $this->actingAs($this->superAdmin)
        ->post(route('counter-users.store'), [
            'counter_id' => $counter->id,
            'user_id' => $newUser->id,
        ])
        ->assertSessionHasErrors('counter_id');

    expect(CounterUser::where('counter_id', $counter->id)->where('user_id', $newUser->id)->exists())->toBeFalse();
});

test('petugas yang sudah memiliki loket tidak bisa ditugaskan ke loket lain', function () {
    $counterA = geraiLoket($this->opd, '1');
    $counterB = geraiLoket($this->opd, '2');
    $user = User::factory()->create();
    assignLoket($counterA, $user);

    $this->actingAs($this->superAdmin)
        ->post(route('counter-users.store'), [
            'counter_id' => $counterB->id,
            'user_id' => $user->id,
        ])
        ->assertSessionHasErrors('user_id');

    expect(CounterUser::where('counter_id', $counterB->id)->exists())->toBeFalse();
});

test('tukar loket antara dua petugas yang sudah memiliki loket', function () {
    $counterA = geraiLoket($this->opd, '1');
    $counterB = geraiLoket($this->opd, '2');
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    assignLoket($counterA, $userA);
    assignLoket($counterB, $userB);

    $assignmentA = CounterUser::where('user_id', $userA->id)->first();

    $this->actingAs($this->superAdmin)
        ->put(route('counter-users.update', $assignmentA), [
            'swap_user_id' => $userB->id,
        ])
        ->assertRedirect(route('counter-users.index'));

    expect(CounterUser::where('user_id', $userA->id)->first()->counter_id)->toBe($counterB->id);
    expect(CounterUser::where('user_id', $userB->id)->first()->counter_id)->toBe($counterA->id);
});

test('tukar loket ditolak jika petugas target belum memiliki loket', function () {
    $counterA = geraiLoket($this->opd, '1');
    $counterB = geraiLoket($this->opd, '2');
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    assignLoket($counterA, $userA);
    assignLoket($counterB, $userB);

    $userB->activeCounterAssignments()->delete();

    $assignmentA = CounterUser::where('user_id', $userA->id)->first();

    $this->actingAs($this->superAdmin)
        ->put(route('counter-users.update', $assignmentA), [
            'swap_user_id' => $userB->id,
        ])
        ->assertSessionHasErrors('swap_user_id');

    expect(CounterUser::where('user_id', $userA->id)->first()->counter_id)->toBe($counterA->id);
});

test('halaman edit hanya menampilkan petugas yang sudah memiliki loket', function () {
    $counterA = geraiLoket($this->opd, '1');
    $counterB = geraiLoket($this->opd, '2');
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $withoutLoket = User::factory()->create();
    assignLoket($counterA, $userA);
    assignLoket($counterB, $userB);

    $assignmentA = CounterUser::where('user_id', $userA->id)->first();

    $response = $this->actingAs($this->superAdmin)
        ->get(route('counter-users.edit', $assignmentA));

    $response->assertStatus(200)
        ->assertSee($userB->name)
        ->assertDontSee($withoutLoket->name);
});
