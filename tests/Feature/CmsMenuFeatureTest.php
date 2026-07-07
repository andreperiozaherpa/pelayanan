<?php

use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->roleSuperAdmin = Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
    $this->roleEditor = Role::create(['name' => 'Editor', 'slug' => 'editor']);
    $this->roleViewer = Role::create(['name' => 'Viewer', 'slug' => 'viewer']);

    $this->viewerUser = User::factory()->create(['role_id' => $this->roleViewer->id]);
    $this->editorUser = User::factory()->create(['role_id' => $this->roleEditor->id]);
    $this->superAdminUser = User::factory()->create(['role_id' => $this->roleSuperAdmin->id]);

    $permissions = [
        'cms.menus.view', 'cms.menus.create', 'cms.menus.edit', 'cms.menus.delete',
        'cms.pages.view', 'cms.pages.create', 'cms.pages.edit',
    ];

    foreach ($permissions as $slug) {
        $perm = Permission::create(['name' => ucwords(str_replace('.', ' ', $slug)), 'slug' => $slug]);
        $this->roleEditor->permissions()->attach($perm->id);
    }
});

test('guests are redirected to login for menu management', function () {
    $this->get(route('cms-menus.index'))->assertRedirect(route('login'));
});

test('unauthorized users without cms.menus.* permission receive 403', function () {
    $this->actingAs($this->viewerUser);
    $this->get(route('cms-menus.index'))->assertStatus(403);
});

test('authorized editor can view menu list and create form', function () {
    $this->actingAs($this->editorUser);

    $this->get(route('cms-menus.index'))->assertStatus(200);
    $this->get(route('cms-menus.create'))->assertStatus(200);
});

test('authorized editor can store new menu with custom URL', function () {
    $this->actingAs($this->editorUser);

    $response = $this->post(route('cms-menus.store'), [
        'title' => 'Google Link',
        'url' => 'https://google.com',
        'description' => 'Ini tautan menuju google search',
        'target' => '_self',
        'order' => 1,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));
    $this->assertDatabaseHas('cms_menus', [
        'title' => 'Google Link',
        'url' => 'https://google.com',
        'description' => 'Ini tautan menuju google search',
        'order' => 1,
    ]);
});

test('authorized editor can store new menu linked to page and auto-generate URL', function () {
    $this->actingAs($this->editorUser);

    $page = CmsPage::create([
        'title' => 'Hubungi Kami',
        'slug' => 'hubungi-kami',
        'content' => 'Kontak kami di sini.',
        'status' => 'published',
    ]);

    // Create a parent menu first (e.g. Profil)
    $parent = CmsMenu::create([
        'title' => 'Profil Instansi',
        'url' => '/profil',
        'target' => '_self',
        'order' => 1,
    ]);

    $response = $this->post(route('cms-menus.store'), [
        'title' => 'Kontak',
        'parent_id' => $parent->id,
        'cms_page_id' => $page->id,
        'target' => '_self',
        'order' => 2,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));

    // Check if the URL is dynamically resolved
    $this->assertDatabaseHas('cms_menus', [
        'title' => 'Kontak',
        'parent_id' => $parent->id,
        'cms_page_id' => $page->id,
        'url' => '/profil/hubungi-kami',
    ]);
});

test('authorized editor can edit menu and update parent and page relationship', function () {
    $this->actingAs($this->editorUser);

    $page1 = CmsPage::create([
        'title' => 'Visi Misi',
        'slug' => 'visi-misi',
        'content' => 'Visi misi kami.',
        'status' => 'published',
    ]);

    $page2 = CmsPage::create([
        'title' => 'Struktur Organisasi',
        'slug' => 'struktur',
        'content' => 'Struktur organisasi kami.',
        'status' => 'published',
    ]);

    // Parent menu
    $parent = CmsMenu::create([
        'title' => 'Profil',
        'url' => '/profil',
        'target' => '_self',
        'order' => 1,
    ]);

    $menu = CmsMenu::create([
        'title' => 'Visi Misi',
        'parent_id' => $parent->id,
        'cms_page_id' => $page1->id,
        'url' => '/profil/visi-misi',
        'target' => '_self',
        'order' => 1,
    ]);

    // Update to change target page to page2
    $response = $this->put(route('cms-menus.update', $menu->id), [
        'title' => 'Struktur Organisasi',
        'parent_id' => $parent->id,
        'cms_page_id' => $page2->id,
        'description' => 'Menu untuk struktur organisasi instansi',
        'target' => '_self',
        'order' => 2,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));
    $this->assertDatabaseHas('cms_menus', [
        'id' => $menu->id,
        'title' => 'Struktur Organisasi',
        'cms_page_id' => $page2->id,
        'description' => 'Menu untuk struktur organisasi instansi',
        'url' => '/profil/struktur',
        'order' => 2,
    ]);
});

test('authorized editor can delete menu item', function () {
    $this->actingAs($this->editorUser);

    $menu = CmsMenu::create([
        'title' => 'About Us',
        'url' => '/about-us',
        'order' => 1,
    ]);

    $response = $this->delete(route('cms-menus.destroy', $menu->id));
    $response->assertRedirect(route('cms-menus.index'));
    $this->assertDatabaseMissing('cms_menus', [
        'id' => $menu->id,
    ]);
});

test('authorized editor can store top-level menu linked to page and fallback to profil prefix', function () {
    $this->actingAs($this->editorUser);

    $page = CmsPage::create([
        'title' => 'PPID Info',
        'slug' => 'info-berkala',
        'content' => 'PPID info berkala.',
        'status' => 'published',
    ]);

    // Store a top-level menu
    $response = $this->post(route('cms-menus.store'), [
        'title' => 'PPID Utama',
        'cms_page_id' => $page->id,
        'target' => '_self',
        'order' => 1,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));

    $this->assertDatabaseHas('cms_menus', [
        'title' => 'PPID Utama',
        'parent_id' => null,
        'cms_page_id' => $page->id,
        'url' => '/profil/info-berkala',
    ]);
});

test('authorized editor can store child menu and inherit URL prefix directly from parent menu URL', function () {
    $this->actingAs($this->editorUser);

    $parent = CmsMenu::create([
        'title' => 'Custom Parent',
        'url' => '/check',
        'target' => '_self',
        'order' => 1,
    ]);

    $page = CmsPage::create([
        'title' => 'List Item',
        'slug' => 'kode-etik',
        'content' => 'Content here',
        'status' => 'published',
    ]);

    $response = $this->post(route('cms-menus.store'), [
        'title' => 'List',
        'parent_id' => $parent->id,
        'cms_page_id' => $page->id,
        'target' => '_self',
        'order' => 2,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));

    $this->assertDatabaseHas('cms_menus', [
        'title' => 'List',
        'parent_id' => $parent->id,
        'cms_page_id' => $page->id,
        'url' => '/check/kode-etik',
    ]);
});

test('storing menu with title Struktur Organisasi auto-generates url', function () {
    $this->actingAs($this->editorUser);

    $response = $this->post(route('cms-menus.store'), [
        'title' => 'Struktur Organisasi',
        'target' => '_self',
        'order' => 5,
        'is_active' => true,
    ]);

    $response->assertRedirect(route('cms-menus.index'));

    $this->assertDatabaseHas('cms_menus', [
        'title' => 'Struktur Organisasi',
        'url' => '/profil/struktur-organisasi',
        'order' => 5,
    ]);
});
