<?php

use App\Models\MppTicketTemplate;
use App\Models\MppTicketTemplateVersion;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RBACSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(RBACSeeder::class);
    $this->superAdmin = User::factory()->create([
        'role_id' => Role::query()->where('slug', 'superadmin')->value('id'),
    ]);
});

function ticketTemplateLayout(string $title): string
{
    return json_encode([
        'schema' => 1,
        'paper_width_mm' => 58,
        'print_width_mm' => 55,
        'blocks' => [
            ['type' => 'title', 'text' => $title, 'align' => 'center'],
            ['type' => 'queue_number', 'align' => 'center'],
            ['type' => 'service', 'align' => 'center'],
        ],
    ], JSON_THROW_ON_ERROR);
}

test('super admin dapat menyimpan draft dan mempublikasikan versi template tiket', function () {
    $this->actingAs($this->superAdmin)
        ->post(route('mpp-ticket-template.update'), ['layout' => ticketTemplateLayout('MPP Utama')])
        ->assertRedirect();

    $this->actingAs($this->superAdmin)
        ->post(route('mpp-ticket-template.publish'), ['layout' => ticketTemplateLayout('MPP Utama')])
        ->assertRedirect();

    $template = MppTicketTemplate::query()->sole();
    $version = MppTicketTemplateVersion::query()->sole();

    expect($template->draft_layout['blocks'][0]['text'])->toBe('MPP Utama')
        ->and($template->active_version_id)->toBe($version->id)
        ->and($version->version)->toBe(1)
        ->and($version->published_by)->toBe($this->superAdmin->id);
});

test('super admin dapat membuka editor template tiket', function () {
    $this->actingAs($this->superAdmin)
        ->get(route('mpp-ticket-template.edit'))
        ->assertOk()
        ->assertSee('Template Tiket Antrian')
        ->assertSee('Nomor antrian');
});

test('pemulihan mengaktifkan versi lama tanpa mengubah versi yang diterbitkan', function () {
    $this->actingAs($this->superAdmin)->post(route('mpp-ticket-template.publish'), ['layout' => ticketTemplateLayout('Versi Lama')]);
    $oldVersion = MppTicketTemplateVersion::query()->sole();

    $this->actingAs($this->superAdmin)->post(route('mpp-ticket-template.publish'), ['layout' => ticketTemplateLayout('Versi Baru')]);
    $newVersion = MppTicketTemplateVersion::query()->latest('version')->firstOrFail();

    $this->actingAs($this->superAdmin)
        ->post(route('mpp-ticket-template.rollback', $oldVersion))
        ->assertRedirect();

    $template = MppTicketTemplate::query()->sole();
    expect($template->active_version_id)->toBe($oldVersion->id)
        ->and($template->draft_layout['blocks'][0]['text'])->toBe('Versi Lama')
        ->and($newVersion->fresh()->layout['blocks'][0]['text'])->toBe('Versi Baru')
        ->and(MppTicketTemplateVersion::query()->count())->toBe(2);
});

test('template tanpa nomor antrian ditolak', function () {
    $invalidLayout = json_encode(['blocks' => [['type' => 'title', 'text' => 'MPP']]], JSON_THROW_ON_ERROR);

    $this->actingAs($this->superAdmin)
        ->from(route('mpp-ticket-template.edit'))
        ->post(route('mpp-ticket-template.update'), ['layout' => $invalidLayout])
        ->assertRedirect(route('mpp-ticket-template.edit'))
        ->assertSessionHasErrors('layout');
});
