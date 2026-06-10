<?php

use App\Models\CmsArticle;
use App\Models\CmsBanner;
use App\Models\CmsCategory;
use App\Models\CmsFaq;
use App\Models\CmsPage;
use App\Models\CmsSetting;
use App\Models\CmsTeam;
use App\Models\CmsTestimonial;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    // 1. Setup default roles and permissions
    $this->roleSuperAdmin = Role::create(['name' => 'SuperAdmin', 'slug' => 'superadmin']);
    $this->roleEditor = Role::create(['name' => 'Editor', 'slug' => 'editor']);
    $this->roleViewer = Role::create(['name' => 'Viewer', 'slug' => 'viewer']);

    // Create standard user (viewer - no write permission)
    $this->viewerUser = User::factory()->create(['role_id' => $this->roleViewer->id]);

    // Create editor user
    $this->editorUser = User::factory()->create(['role_id' => $this->roleEditor->id]);

    // Create superadmin user
    $this->superAdminUser = User::factory()->create(['role_id' => $this->roleSuperAdmin->id]);

    // Setup permissions for Editor role
    $permissions = [
        'cms.articles.view', 'cms.articles.create', 'cms.articles.edit', 'cms.articles.publish',
        'cms.pages.view', 'cms.pages.create', 'cms.pages.edit',
        'cms.banners.view', 'cms.banners.create', 'cms.banners.edit',
        'cms.faqs.view', 'cms.faqs.create', 'cms.faqs.edit',
        'cms.testimonials.view', 'cms.testimonials.create', 'cms.testimonials.edit',
        'cms.teams.view', 'cms.teams.create', 'cms.teams.edit',
        'cms.settings.view', 'cms.settings.edit',
    ];

    foreach ($permissions as $slug) {
        $perm = Permission::create(['name' => ucwords(str_replace('.', ' ', $slug)), 'slug' => $slug]);
        $this->roleEditor->permissions()->attach($perm->id);
    }

    Storage::fake('public');
});

/*
|--------------------------------------------------------------------------
| ACCESS CONTROL TESTS
|--------------------------------------------------------------------------
*/

test('guests are redirected to login', function () {
    $this->get(route('cms-categories.index'))->assertRedirect(route('login'));
    $this->get(route('cms-articles.index'))->assertRedirect(route('login'));
    $this->get(route('cms-pages.index'))->assertRedirect(route('login'));
    $this->get(route('cms-banners.index'))->assertRedirect(route('login'));
    $this->get(route('cms-faqs.index'))->assertRedirect(route('login'));
    $this->get(route('cms-testimonials.index'))->assertRedirect(route('login'));
    $this->get(route('cms-teams.index'))->assertRedirect(route('login'));
    $this->get(route('cms-settings.index'))->assertRedirect(route('login'));
});

test('unauthorized users without cms permissions receive 403', function () {
    $this->actingAs($this->viewerUser);

    $this->get(route('cms-categories.index'))->assertStatus(403);
    $this->get(route('cms-articles.index'))->assertStatus(403);
    $this->get(route('cms-pages.index'))->assertStatus(403);
    $this->get(route('cms-banners.index'))->assertStatus(403);
    $this->get(route('cms-faqs.index'))->assertStatus(403);
    $this->get(route('cms-testimonials.index'))->assertStatus(403);
    $this->get(route('cms-teams.index'))->assertStatus(403);
    $this->get(route('cms-settings.index'))->assertStatus(403);
});

/*
|--------------------------------------------------------------------------
| CATEGORY CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD category', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-categories.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-categories.create'))->assertStatus(200);

    // Store
    $response = $this->post(route('cms-categories.store'), [
        'name' => 'Informasi Umum',
    ]);
    $response->assertRedirect(route('cms-categories.index'));
    $this->assertDatabaseHas('cms_categories', ['name' => 'Informasi Umum', 'slug' => 'informasi-umum']);

    $category = CmsCategory::where('slug', 'informasi-umum')->first();

    // Edit View
    $this->get(route('cms-categories.edit', $category->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-categories.update', $category->id), [
        'name' => 'Informasi Publik',
    ]);
    $response->assertRedirect(route('cms-categories.index'));
    $this->assertDatabaseHas('cms_categories', ['id' => $category->id, 'name' => 'Informasi Publik']);
});

/*
|--------------------------------------------------------------------------
| ARTICLE CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD article', function () {
    $this->actingAs($this->editorUser);
    $category = CmsCategory::create(['name' => 'Berita']);

    // List
    $this->get(route('cms-articles.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-articles.create'))->assertStatus(200);

    // Store
    $file = UploadedFile::fake()->image('test_article.jpg', 800, 600);
    $response = $this->post(route('cms-articles.store'), [
        'title' => 'Pengumuman Pelayanan Baru',
        'category_id' => $category->id,
        'content' => 'Pelayanan digital terbaru diresmikan mulai hari ini.',
        'status' => 'draft',
        'featured_image_file' => $file,
        'meta_title' => 'SEO Pengumuman',
        'meta_description' => 'Deskripsi SEO Pengumuman',
        'meta_keywords' => 'pelayanan, baru, digital',
    ]);

    $response->assertRedirect(route('cms-articles.index'));
    $this->assertDatabaseHas('cms_articles', [
        'title' => 'Pengumuman Pelayanan Baru',
        'status' => 'draft',
    ]);

    $article = CmsArticle::latest()->first();
    $this->assertNotNull($article->featured_image);
    Storage::disk('public')->assertExists($article->featured_image);

    // Assert SEO tags were created
    $this->assertDatabaseHas('cms_seos', [
        'seoable_type' => CmsArticle::class,
        'seoable_id' => $article->id,
        'meta_title' => 'SEO Pengumuman',
        'meta_description' => 'Deskripsi SEO Pengumuman',
    ]);

    // Edit View
    $this->get(route('cms-articles.edit', $article->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-articles.update', $article->id), [
        'title' => 'Pengumuman Pelayanan Digital',
        'category_id' => $category->id,
        'content' => 'Pelayanan digital terbaru telah resmi diresmikan mulai hari ini.',
        'status' => 'published',
        'meta_title' => 'SEO Pengumuman Updated',
    ]);

    $response->assertRedirect(route('cms-articles.index'));
    $this->assertDatabaseHas('cms_articles', [
        'id' => $article->id,
        'title' => 'Pengumuman Pelayanan Digital',
        'status' => 'published',
    ]);

    $this->assertDatabaseHas('cms_seos', [
        'seoable_type' => CmsArticle::class,
        'seoable_id' => $article->id,
        'meta_title' => 'SEO Pengumuman Updated',
    ]);
});

/*
|--------------------------------------------------------------------------
| PAGE CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD page', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-pages.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-pages.create'))->assertStatus(200);

    // Store
    $response = $this->post(route('cms-pages.store'), [
        'title' => 'Tentang Aplikasi Siberugo',
        'content' => 'Aplikasi Siberugo adalah portal pelayanan terintegrasi.',
        'status' => 'published',
        'meta_title' => 'SEO Tentang',
    ]);

    $response->assertRedirect(route('cms-pages.index'));
    $this->assertDatabaseHas('cms_pages', [
        'title' => 'Tentang Aplikasi Siberugo',
        'status' => 'published',
    ]);

    $page = CmsPage::latest()->first();

    // Edit View
    $this->get(route('cms-pages.edit', $page->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-pages.update', $page->id), [
        'title' => 'Tentang Kami',
        'content' => 'Tentang portal pelayanan terintegrasi.',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('cms-pages.index'));
    $this->assertDatabaseHas('cms_pages', [
        'id' => $page->id,
        'title' => 'Tentang Kami',
    ]);
});

/*
|--------------------------------------------------------------------------
| BANNER CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD banner', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-banners.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-banners.create'))->assertStatus(200);

    // Store
    $file = UploadedFile::fake()->image('banner.jpg', 1920, 800);
    $response = $this->post(route('cms-banners.store'), [
        'title' => 'Selamat Datang',
        'subtitle' => 'Portal Informasi Resmi',
        'order' => 1,
        'image_file' => $file,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-banners.index'));
    $banner = CmsBanner::latest()->first();
    $this->assertNotNull($banner->image_path);
    Storage::disk('public')->assertExists($banner->image_path);

    // Edit View
    $this->get(route('cms-banners.edit', $banner->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-banners.update', $banner->id), [
        'title' => 'Selamat Datang Kembali',
        'subtitle' => 'Portal Informasi Resmi Warga',
        'order' => 2,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-banners.index'));
    $this->assertDatabaseHas('cms_banners', [
        'id' => $banner->id,
        'title' => 'Selamat Datang Kembali',
        'order' => 2,
    ]);
});

/*
|--------------------------------------------------------------------------
| FAQ CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD faq', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-faqs.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-faqs.create'))->assertStatus(200);

    // Store
    $response = $this->post(route('cms-faqs.store'), [
        'question' => 'Bagaimana cara melapor?',
        'answer' => 'Silakan klik menu Hubungi Kami.',
        'order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-faqs.index'));
    $this->assertDatabaseHas('cms_faqs', [
        'question' => 'Bagaimana cara melapor?',
        'is_active' => true,
    ]);

    $faq = CmsFaq::latest()->first();

    // Edit View
    $this->get(route('cms-faqs.edit', $faq->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-faqs.update', $faq->id), [
        'question' => 'Bagaimana cara mengajukan permohonan?',
        'answer' => 'Melalui menu permohonan.',
        'order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-faqs.index'));
    $this->assertDatabaseHas('cms_faqs', [
        'id' => $faq->id,
        'question' => 'Bagaimana cara mengajukan permohonan?',
    ]);
});

/*
|--------------------------------------------------------------------------
| TESTIMONIAL CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD testimonial', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-testimonials.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-testimonials.create'))->assertStatus(200);

    // Store
    $avatar = UploadedFile::fake()->image('avatar.jpg', 150, 150);
    $response = $this->post(route('cms-testimonials.store'), [
        'name' => 'Budi',
        'position' => 'Pengusaha',
        'company' => 'Mandiri Corp',
        'rating' => 5,
        'content' => 'Pelayanan cepat dan transparan.',
        'avatar_file' => $avatar,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-testimonials.index'));
    $testimonial = CmsTestimonial::latest()->first();
    $this->assertNotNull($testimonial->avatar);
    Storage::disk('public')->assertExists($testimonial->avatar);

    // Edit View
    $this->get(route('cms-testimonials.edit', $testimonial->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-testimonials.update', $testimonial->id), [
        'name' => 'Budi Santoso',
        'position' => 'Pengusaha Sukses',
        'company' => 'Mandiri Corp',
        'rating' => 4,
        'content' => 'Pelayanan sangat cepat.',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-testimonials.index'));
    $this->assertDatabaseHas('cms_testimonials', [
        'id' => $testimonial->id,
        'name' => 'Budi Santoso',
        'rating' => 4,
    ]);
});

/*
|--------------------------------------------------------------------------
| TEAM CRUD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can CRUD team member', function () {
    $this->actingAs($this->editorUser);

    // List
    $this->get(route('cms-teams.index'))->assertStatus(200);

    // Create View
    $this->get(route('cms-teams.create'))->assertStatus(200);

    // Store
    $photo = UploadedFile::fake()->image('team_member.jpg', 400, 500);
    $response = $this->post(route('cms-teams.store'), [
        'name' => 'Andi',
        'position' => 'Kepala Bidang',
        'order' => 1,
        'image_file' => $photo,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-teams.index'));
    $member = CmsTeam::latest()->first();
    $this->assertNotNull($member->image);
    Storage::disk('public')->assertExists($member->image);

    // Edit View
    $this->get(route('cms-teams.edit', $member->id))->assertStatus(200);

    // Update
    $response = $this->put(route('cms-teams.update', $member->id), [
        'name' => 'Andi Wijaya',
        'position' => 'Sekretaris Dinas',
        'order' => 1,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-teams.index'));
    $this->assertDatabaseHas('cms_teams', [
        'id' => $member->id,
        'name' => 'Andi Wijaya',
        'position' => 'Sekretaris Dinas',
    ]);
});

/*
|--------------------------------------------------------------------------
| WEBSITE SETTINGS TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can view and update settings', function () {
    $this->actingAs($this->editorUser);

    // Seed a couple of settings first
    CmsSetting::create(['group' => 'general', 'key' => 'site_name', 'value' => 'Old Name', 'type' => 'string']);
    CmsSetting::create(['group' => 'social', 'key' => 'social_facebook', 'value' => 'Old FB', 'type' => 'string']);

    // List Settings
    $this->get(route('cms-settings.index'))->assertStatus(200);

    // Update Settings
    $response = $this->put(route('cms-settings.update'), [
        'settings' => [
            'site_name' => 'New Site Name',
            'social_facebook' => 'https://facebook.com/new',
            'new_key_dynamic' => 'dynamic value',
        ],
    ]);

    $response->assertRedirect(route('cms-settings.index'));

    $this->assertDatabaseHas('cms_settings', ['key' => 'site_name', 'value' => 'New Site Name']);
    $this->assertDatabaseHas('cms_settings', ['key' => 'social_facebook', 'value' => 'https://facebook.com/new']);

    // Dynamic settings fall back to group 'general'
    $this->assertDatabaseHas('cms_settings', ['key' => 'new_key_dynamic', 'value' => 'dynamic value', 'group' => 'general']);
});

/*
|--------------------------------------------------------------------------
| DROPZONE MEDIA UPLOAD TESTS
|--------------------------------------------------------------------------
*/

test('authorized user can upload media via dropzone route', function () {
    $this->actingAs($this->editorUser);

    $file = UploadedFile::fake()->image('dropzone_test.jpg', 600, 600);
    $response = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'test-folder',
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure(['path', 'url']);

    $path = $response->json('path');
    $this->assertNotNull($path);
    Storage::disk('public')->assertExists($path);
});

test('unauthorized user cannot upload media via dropzone route', function () {
    $this->actingAs($this->viewerUser);

    $file = UploadedFile::fake()->image('dropzone_test.jpg', 600, 600);
    $response = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'test-folder',
    ]);

    $response->assertStatus(403);
});

test('authorized user can create article with dropzone uploaded path', function () {
    $this->actingAs($this->editorUser);
    $category = CmsCategory::create(['name' => 'Berita']);

    // 1. Upload file via dropzone
    $file = UploadedFile::fake()->image('dropzone_article.jpg', 800, 600);
    $uploadResponse = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'articles',
    ]);
    $path = $uploadResponse->json('path');

    // 2. Submit form with path instead of file
    $response = $this->post(route('cms-articles.store'), [
        'title' => 'Artikel Dropzone',
        'category_id' => $category->id,
        'content' => 'Pelayanan digital terbaru diresmikan mulai hari ini.',
        'status' => 'draft',
        'featured_image' => $path,
        'meta_title' => 'SEO Dropzone',
    ]);

    $response->assertRedirect(route('cms-articles.index'));
    $this->assertDatabaseHas('cms_articles', [
        'title' => 'Artikel Dropzone',
        'featured_image' => $path,
    ]);
    Storage::disk('public')->assertExists($path);
});

test('authorized user can create banner with dropzone uploaded path', function () {
    $this->actingAs($this->editorUser);

    // 1. Upload file via dropzone
    $file = UploadedFile::fake()->image('dropzone_banner.jpg', 1920, 800);
    $uploadResponse = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'banners',
    ]);
    $path = $uploadResponse->json('path');

    // 2. Submit form with path instead of file
    $response = $this->post(route('cms-banners.store'), [
        'title' => 'Banner Dropzone',
        'subtitle' => 'Portal Informasi Resmi',
        'order' => 1,
        'image' => $path,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-banners.index'));
    $this->assertDatabaseHas('cms_banners', [
        'title' => 'Banner Dropzone',
        'image_path' => $path,
    ]);
    Storage::disk('public')->assertExists($path);
});

test('authorized user can create testimonial with dropzone uploaded path', function () {
    $this->actingAs($this->editorUser);

    // 1. Upload file via dropzone
    $file = UploadedFile::fake()->image('dropzone_avatar.jpg', 150, 150);
    $uploadResponse = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'testimonials',
    ]);
    $path = $uploadResponse->json('path');

    // 2. Submit form with path instead of file
    $response = $this->post(route('cms-testimonials.store'), [
        'name' => 'Budi Dropzone',
        'position' => 'Pengusaha',
        'company' => 'Mandiri Corp',
        'rating' => 5,
        'content' => 'Pelayanan cepat dan transparan.',
        'avatar' => $path,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-testimonials.index'));
    $this->assertDatabaseHas('cms_testimonials', [
        'name' => 'Budi Dropzone',
        'avatar' => $path,
    ]);
    Storage::disk('public')->assertExists($path);
});

test('authorized user can create team member with dropzone uploaded path', function () {
    $this->actingAs($this->editorUser);

    // 1. Upload file via dropzone
    $file = UploadedFile::fake()->image('dropzone_team.jpg', 400, 500);
    $uploadResponse = $this->postJson(route('cms-media.upload'), [
        'file' => $file,
        'folder' => 'teams',
    ]);
    $path = $uploadResponse->json('path');

    // 2. Submit form with path instead of file
    $response = $this->post(route('cms-teams.store'), [
        'name' => 'Andi Dropzone',
        'position' => 'Kepala Bidang',
        'order' => 1,
        'image' => $path,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('cms-teams.index'));
    $this->assertDatabaseHas('cms_teams', [
        'name' => 'Andi Dropzone',
        'image' => $path,
    ]);
    Storage::disk('public')->assertExists($path);
});
