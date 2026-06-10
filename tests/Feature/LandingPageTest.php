<?php

use App\Models\CmsArticle;
use App\Models\CmsCategory;
use App\Models\CmsFaq;
use App\Models\CmsMenu;
use App\Models\CmsPage;
use App\Models\CmsService;
use App\Models\CmsSetting;
use App\Models\CmsStatistic;
use App\Models\CmsTestimonial;
use App\Models\CmsWebsiteSection;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('landing page loads successfully with empty database (no errors)', function () {
    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
});

test('landing page renders hero section when data exists', function () {
    CmsWebsiteSection::create([
        'key' => 'hero',
        'title' => 'Selamat Datang di Portal Kami',
        'subtitle' => 'Layanan terpadu untuk masyarakat.',
        'button_text' => 'Mulai Sekarang',
        'button_url' => '#services',
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Selamat Datang di Portal Kami');
    $response->assertSee('Mulai Sekarang');
});

test('landing page renders statistics section when data exists', function () {
    CmsStatistic::create(['label' => 'Total Klien', 'value' => 500, 'order' => 1]);
    CmsStatistic::create(['label' => 'Proyek Selesai', 'value' => 120, 'order' => 2]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Total Klien');
    $response->assertSee('Proyek Selesai');
});

test('landing page renders services section when data exists', function () {
    CmsService::create([
        'name' => 'Layanan Konsultasi',
        'summary' => 'Konsultasi profesional 24 jam.',
        'order' => 1,
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Layanan Konsultasi');
});

test('landing page renders faqs as accordion when data exists', function () {
    CmsFaq::create([
        'question' => 'Bagaimana cara mendaftar?',
        'answer' => 'Kunjungi halaman pendaftaran.',
        'order' => 1,
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Bagaimana cara mendaftar?');
});

test('landing page renders testimonials carousel when data exists', function () {
    CmsTestimonial::create([
        'name' => 'Budi Santoso',
        'content' => 'Layanan sangat memuaskan!',
        'rating' => 5,
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');
    $response->assertSee('Layanan sangat memuaskan!');
});

test('landing page renders latest blog articles when data exists', function () {
    $role = Role::create(['name' => 'Editor', 'slug' => 'editor']);
    $user = User::factory()->create(['role_id' => $role->id]);
    $category = CmsCategory::create(['name' => 'Berita']);
    CmsArticle::create([
        'title' => 'Artikel Terbaru Kami',
        'category_id' => $category->id,
        'author_id' => $user->id,
        'content' => 'Isi artikel.',
        'status' => 'published',
        'published_at' => now(),
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Artikel Terbaru Kami');
});

test('landing page renders settings in footer when data exists', function () {
    CmsSetting::create(['group' => 'general', 'key' => 'site_name', 'value' => 'Portal Andalan', 'type' => 'string']);
    CmsSetting::create(['group' => 'general', 'key' => 'email', 'value' => 'info@portal.id', 'type' => 'string']);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('info@portal.id');
});

test('cache is flushed when a cms model is updated via observer', function () {
    $service = CmsService::create(['name' => 'Layanan Lama', 'summary' => 'Test.', 'order' => 1]);

    // Manually seed the cache to simulate a warm cache
    Cache::put('landing.services', collect([$service]), 3600);
    expect(Cache::has('landing.services'))->toBeTrue();

    // Updating the model should trigger the observer and flush the cache
    $service->update(['name' => 'Layanan Baru']);

    expect(Cache::has('landing.services'))->toBeFalse();
});

test('landing page renders navigation menu tree correctly', function () {
    $parent = CmsMenu::create([
        'title' => 'Profil',
        'url' => '#',
        'order' => 1,
        'is_active' => true,
    ]);

    $child = CmsMenu::create([
        'title' => 'Visi Misi',
        'url' => '/profil/visi-misi',
        'parent_id' => $parent->id,
        'order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Profil');
    $response->assertSee('/profil/visi-misi');
});

test('landing page renders mega menu details for Pelayanan', function () {
    $parent = CmsMenu::create([
        'title' => 'Pelayanan',
        'url' => '#',
        'order' => 1,
        'is_active' => true,
    ]);

    $child = CmsMenu::create([
        'title' => 'Perizinan Berusaha',
        'url' => '/pelayanan/perizinan-berusaha',
        'parent_id' => $parent->id,
        'description' => 'Sistem perizinan berusaha berbasis resiko',
        'order' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('landing.index'));
    $response->assertStatus(200);
    $response->assertSee('Pelayanan');
    $response->assertSee('Perizinan Berusaha');
    $response->assertSee('Sistem perizinan berusaha berbasis resiko');
});

test('dynamic profile page renders successfully', function () {
    $page = CmsPage::create([
        'title' => 'Visi dan Misi',
        'slug' => 'visi-misi',
        'content' => '<p>Visi Misi DPMPTSP</p>',
        'status' => 'published',
    ]);

    $response = $this->get('/profil/visi-misi');
    $response->assertStatus(200);
    $response->assertSee('Visi dan Misi');
    $response->assertSee('Visi Misi DPMPTSP');
});

test('dynamic page returns 404 for non-existent slug', function () {
    $response = $this->get('/profil/non-existent-slug');
    $response->assertStatus(404);
});
