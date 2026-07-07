<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CmsRBACSeeder extends Seeder
{
    public function run(): void
    {
        // Define CMS Permissions
        $permissions = [
            // Articles
            [
                'name' => 'Lihat Artikel CMS',
                'slug' => 'cms.articles.view',
                'description' => 'Akses untuk melihat artikel blog.',
            ],
            [
                'name' => 'Buat Artikel CMS',
                'slug' => 'cms.articles.create',
                'description' => 'Akses untuk membuat artikel blog baru.',
            ],
            [
                'name' => 'Ubah Artikel CMS',
                'slug' => 'cms.articles.edit',
                'description' => 'Akses untuk mengubah artikel blog.',
            ],
            [
                'name' => 'Hapus Artikel CMS',
                'slug' => 'cms.articles.delete',
                'description' => 'Akses untuk menghapus artikel blog.',
            ],
            [
                'name' => 'Publish Artikel CMS',
                'slug' => 'cms.articles.publish',
                'description' => 'Akses untuk mempublikasikan artikel blog secara langsung.',
            ],

            // Pages
            [
                'name' => 'Lihat Halaman CMS',
                'slug' => 'cms.pages.view',
                'description' => 'Akses untuk melihat halaman statis.',
            ],
            [
                'name' => 'Buat Halaman CMS',
                'slug' => 'cms.pages.create',
                'description' => 'Akses untuk membuat halaman statis baru.',
            ],
            [
                'name' => 'Ubah Halaman CMS',
                'slug' => 'cms.pages.edit',
                'description' => 'Akses untuk mengubah halaman statis.',
            ],
            [
                'name' => 'Hapus Halaman CMS',
                'slug' => 'cms.pages.delete',
                'description' => 'Akses untuk menghapus halaman statis.',
            ],

            // Banners
            [
                'name' => 'Lihat Banner CMS',
                'slug' => 'cms.banners.view',
                'description' => 'Akses untuk melihat banner/slider.',
            ],
            [
                'name' => 'Buat Banner CMS',
                'slug' => 'cms.banners.create',
                'description' => 'Akses untuk membuat banner/slider baru.',
            ],
            [
                'name' => 'Ubah Banner CMS',
                'slug' => 'cms.banners.edit',
                'description' => 'Akses untuk mengubah banner/slider.',
            ],
            [
                'name' => 'Hapus Banner CMS',
                'slug' => 'cms.banners.delete',
                'description' => 'Akses untuk menghapus banner/slider.',
            ],

            // FAQs
            [
                'name' => 'Lihat FAQ CMS',
                'slug' => 'cms.faqs.view',
                'description' => 'Akses untuk melihat FAQ.',
            ],
            [
                'name' => 'Buat FAQ CMS',
                'slug' => 'cms.faqs.create',
                'description' => 'Akses untuk membuat FAQ baru.',
            ],
            [
                'name' => 'Ubah FAQ CMS',
                'slug' => 'cms.faqs.edit',
                'description' => 'Akses untuk mengubah FAQ.',
            ],
            [
                'name' => 'Hapus FAQ CMS',
                'slug' => 'cms.faqs.delete',
                'description' => 'Akses untuk menghapus FAQ.',
            ],

            // Testimonials
            [
                'name' => 'Lihat Testimoni CMS',
                'slug' => 'cms.testimonials.view',
                'description' => 'Akses untuk melihat testimoni.',
            ],
            [
                'name' => 'Buat Testimoni CMS',
                'slug' => 'cms.testimonials.create',
                'description' => 'Akses untuk membuat testimoni baru.',
            ],
            [
                'name' => 'Ubah Testimoni CMS',
                'slug' => 'cms.testimonials.edit',
                'description' => 'Akses untuk mengubah testimoni.',
            ],
            [
                'name' => 'Hapus Testimoni CMS',
                'slug' => 'cms.testimonials.delete',
                'description' => 'Akses untuk menghapus testimoni.',
            ],

            // Teams
            [
                'name' => 'Lihat Struktur Organisasi CMS',
                'slug' => 'cms.teams.view',
                'description' => 'Akses untuk melihat struktur organisasi.',
            ],
            [
                'name' => 'Buat Struktur Organisasi CMS',
                'slug' => 'cms.teams.create',
                'description' => 'Akses untuk membuat struktur organisasi baru.',
            ],
            [
                'name' => 'Ubah Struktur Organisasi CMS',
                'slug' => 'cms.teams.edit',
                'description' => 'Akses untuk mengubah data struktur organisasi.',
            ],
            [
                'name' => 'Hapus Struktur Organisasi CMS',
                'slug' => 'cms.teams.delete',
                'description' => 'Akses untuk menghapus data struktur organisasi.',
            ],

            // Settings
            [
                'name' => 'Lihat Setting CMS',
                'slug' => 'cms.settings.view',
                'description' => 'Akses untuk melihat pengaturan website.',
            ],
            [
                'name' => 'Ubah Setting CMS',
                'slug' => 'cms.settings.edit',
                'description' => 'Akses untuk mengubah pengaturan website.',
            ],

            // Media
            [
                'name' => 'Kelola Media CMS',
                'slug' => 'cms.media.manage',
                'description' => 'Akses untuk mengunggah dan mengelola berkas media gambar/dokumen.',
            ],

            // Menus
            [
                'name' => 'Lihat Menu CMS',
                'slug' => 'cms.menus.view',
                'description' => 'Akses untuk melihat menu navigasi.',
            ],
            [
                'name' => 'Buat Menu CMS',
                'slug' => 'cms.menus.create',
                'description' => 'Akses untuk membuat menu navigasi baru.',
            ],
            [
                'name' => 'Ubah Menu CMS',
                'slug' => 'cms.menus.edit',
                'description' => 'Akses untuk mengubah menu navigasi.',
            ],
            [
                'name' => 'Hapus Menu CMS',
                'slug' => 'cms.menus.delete',
                'description' => 'Akses untuk menghapus menu navigasi.',
            ],
            // Complaints
            [
                'name' => 'Lihat Pengaduan CMS',
                'slug' => 'cms.complaints.view',
                'description' => 'Akses untuk melihat pengaduan masyarakat.',
            ],
            [
                'name' => 'Balas Pengaduan CMS',
                'slug' => 'cms.complaints.reply',
                'description' => 'Akses untuk merespons/membalas pengaduan masyarakat.',
            ],
            [
                'name' => 'Hapus Pengaduan CMS',
                'slug' => 'cms.complaints.delete',
                'description' => 'Akses untuk menghapus pengaduan masyarakat.',
            ],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles & Assign Permissions
        $cmsManagerPerms = [
            'cms.articles.view', 'cms.articles.create', 'cms.articles.edit', 'cms.articles.delete', 'cms.articles.publish',
            'cms.pages.view', 'cms.pages.create', 'cms.pages.edit', 'cms.pages.delete',
            'cms.menus.view', 'cms.menus.create', 'cms.menus.edit', 'cms.menus.delete',
            'cms.banners.view', 'cms.banners.create', 'cms.banners.edit', 'cms.banners.delete',
            'cms.faqs.view', 'cms.faqs.create', 'cms.faqs.edit', 'cms.faqs.delete',
            'cms.testimonials.view', 'cms.testimonials.create', 'cms.testimonials.edit', 'cms.testimonials.delete',
            'cms.teams.view', 'cms.teams.create', 'cms.teams.edit', 'cms.teams.delete',
            'cms.settings.view', 'cms.settings.edit',
            'cms.media.manage',
            'cms.complaints.view', 'cms.complaints.reply', 'cms.complaints.delete',
        ];

        $cmsEditorPerms = [
            'cms.articles.view', 'cms.articles.create', 'cms.articles.edit',
            'cms.pages.view', 'cms.pages.create', 'cms.pages.edit',
            'cms.menus.view', 'cms.menus.create', 'cms.menus.edit',
            'cms.banners.view', 'cms.banners.create', 'cms.banners.edit',
            'cms.faqs.view', 'cms.faqs.create', 'cms.faqs.edit',
            'cms.media.manage',
        ];

        // 1. Create/Update Content Manager Role
        $managerRole = Role::updateOrCreate(
            ['slug' => 'cmsmanager'],
            ['name' => 'Content Manager', 'description' => 'Pengelola Utama Konten CMS']
        );
        $managerPermIds = Permission::whereIn('slug', $cmsManagerPerms)->pluck('id');
        $managerRole->permissions()->sync($managerPermIds);

        // 2. Create/Update Editor Role
        $editorRole = Role::updateOrCreate(
            ['slug' => 'cmseditor'],
            ['name' => 'Editor', 'description' => 'Staf Redaksi dan Pembuat Konten CMS']
        );
        $editorPermIds = Permission::whereIn('slug', $cmsEditorPerms)->pluck('id');
        $editorRole->permissions()->sync($editorPermIds);

        // 3. Assign all CMS permissions to Super Admin too
        $superAdmin = Role::where('slug', 'superadmin')->first();
        if ($superAdmin) {
            // Get all current permissions + new CMS permissions
            $allPerms = Permission::pluck('id');
            $superAdmin->permissions()->sync($allPerms);
        }
    }
}
