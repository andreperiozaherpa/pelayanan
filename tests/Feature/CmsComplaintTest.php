<?php

use App\Models\CmsComplaint;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('public user can see complaint form', function () {
    $response = $this->get('/kontak/pengaduan');
    $response->assertStatus(200);
    $response->assertSee('Pengaduan Masyarakat');
});

test('public user can submit a complaint successfully with attachment', function () {
    Storage::fake('public');
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true]),
    ]);

    $response = $this->post('/kontak/pengaduan', [
        'name' => 'Warga Peduli',
        'email' => 'warga@email.com',
        'phone' => '08123456789',
        'subject' => 'Lampu jalan padam',
        'content' => 'Lampu jalan di jalan merdeka padam sejak seminggu lalu.',
        'attachment' => UploadedFile::fake()->image('bukti.jpg'),
        'g-recaptcha-response' => 'mock-token',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('cms_complaints', [
        'name' => 'Warga Peduli',
        'email' => 'warga@email.com',
        'subject' => 'Lampu jalan padam',
        'status' => 'pending',
    ]);

    $complaint = CmsComplaint::first();
    expect($complaint->attachment)->not->toBeNull();
    Storage::disk('public')->assertExists($complaint->attachment);
});

test('public user fails submission if recaptcha is missing', function () {
    $response = $this->post('/kontak/pengaduan', [
        'name' => 'Warga Peduli',
        'email' => 'warga@email.com',
        'phone' => '08123456789',
        'subject' => 'Lampu jalan padam',
        'content' => 'Lampu jalan di jalan merdeka padam sejak seminggu lalu.',
    ]);

    $response->assertSessionHasErrors('g-recaptcha-response');
});

test('public user fails submission if recaptcha is invalid', function () {
    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false]),
    ]);

    $response = $this->post('/kontak/pengaduan', [
        'name' => 'Warga Peduli',
        'email' => 'warga@email.com',
        'phone' => '08123456789',
        'subject' => 'Lampu jalan padam',
        'content' => 'Lampu jalan di jalan merdeka padam sejak seminggu lalu.',
        'g-recaptcha-response' => 'invalid-token',
    ]);

    $response->assertSessionHasErrors('g-recaptcha-response');
});

test('non-authenticated user cannot access cms complaints list', function () {
    $response = $this->get(route('cms-complaints.index'));
    $response->assertRedirect(route('login'));
});

test('user without view permission cannot access cms complaints list', function () {
    $role = Role::create(['name' => 'Staf', 'slug' => 'staf']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $response = $this->actingAs($user)->get(route('cms-complaints.index'));
    $response->assertStatus(403);
});

test('admin with view permission can view complaints list and detail', function () {
    $role = Role::create(['name' => 'Manager', 'slug' => 'manager']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $viewPerm = Permission::create(['name' => 'View', 'slug' => 'cms.complaints.view', 'description' => 'View complaints']);
    $role->permissions()->attach($viewPerm->id);

    $complaint = CmsComplaint::create([
        'name' => 'Pengadu',
        'email' => 'pengadu@mail.com',
        'phone' => '12345',
        'subject' => 'Masalah sampah',
        'content' => 'Sampah menumpuk',
    ]);

    $response = $this->actingAs($user)->get(route('cms-complaints.index'));
    $response->assertStatus(200);
    $response->assertSee('Masalah sampah');

    $responseDetail = $this->actingAs($user)->get(route('cms-complaints.show', $complaint->id));
    $responseDetail->assertStatus(200);
    $responseDetail->assertSee('Sampah menumpuk');
});

test('admin with reply permission can reply to complaints and update status', function () {
    $role = Role::create(['name' => 'Manager', 'slug' => 'manager']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $replyPerm = Permission::create(['name' => 'Reply', 'slug' => 'cms.complaints.reply', 'description' => 'Reply complaints']);
    $role->permissions()->attach($replyPerm->id);

    $complaint = CmsComplaint::create([
        'name' => 'Pengadu',
        'email' => 'pengadu@mail.com',
        'phone' => '12345',
        'subject' => 'Masalah sampah',
        'content' => 'Sampah menumpuk',
    ]);

    $response = $this->actingAs($user)->put(route('cms-complaints.update', $complaint->id), [
        'status' => 'processed',
        'reply' => 'Akan segera diangkut oleh petugas kebersihan.',
    ]);

    $response->assertRedirect(route('cms-complaints.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('cms_complaints', [
        'id' => $complaint->id,
        'status' => 'processed',
        'reply' => 'Akan segera diangkut oleh petugas kebersihan.',
        'replied_by' => $user->id,
    ]);
});

test('admin with delete permission can delete complaints', function () {
    $role = Role::create(['name' => 'Manager', 'slug' => 'manager']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $deletePerm = Permission::create(['name' => 'Delete', 'slug' => 'cms.complaints.delete', 'description' => 'Delete complaints']);
    $role->permissions()->attach($deletePerm->id);

    $complaint = CmsComplaint::create([
        'name' => 'Pengadu',
        'email' => 'pengadu@mail.com',
        'phone' => '12345',
        'subject' => 'Masalah sampah',
        'content' => 'Sampah menumpuk',
    ]);

    $response = $this->actingAs($user)->delete(route('cms-complaints.destroy', $complaint->id));
    $response->assertRedirect(route('cms-complaints.index'));

    $this->assertDatabaseMissing('cms_complaints', ['id' => $complaint->id]);
});
