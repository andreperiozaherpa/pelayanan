<?php

namespace Database\Seeders;

use App\Models\CmsSetting;
use Illuminate\Database\Seeder;

class CmsSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            [
                'group' => 'general',
                'key' => 'site_name',
                'value' => 'SIBERUGO Enterprise',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'site_tagline',
                'value' => 'Portal Pelayanan Publik & Informasi Terintegrasi',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'site_logo',
                'value' => '',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'site_favicon',
                'value' => '',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'head_office_name',
                'value' => 'Drs. H. Syahrul, M.IP.',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'head_office_title',
                'value' => 'Kepala Dinas DPMPTSP',
                'type' => 'string',
            ],
            [
                'group' => 'general',
                'key' => 'head_office_photo',
                'value' => '',
                'type' => 'string',
            ],

            // Contact
            [
                'group' => 'contact',
                'key' => 'contact_email',
                'value' => 'info@siberugo.go.id',
                'type' => 'string',
            ],
            [
                'group' => 'contact',
                'key' => 'contact_phone',
                'value' => '+62 812-3456-7890',
                'type' => 'string',
            ],
            [
                'group' => 'contact',
                'key' => 'contact_address',
                'value' => 'Jl. Raya Siberugo No. 1, Kota Siberugo',
                'type' => 'string',
            ],
            [
                'group' => 'contact',
                'key' => 'contact_map_iframe',
                'value' => '',
                'type' => 'string',
            ],

            // Social Media
            [
                'group' => 'social',
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/siberugo',
                'type' => 'string',
            ],
            [
                'group' => 'social',
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/siberugo',
                'type' => 'string',
            ],
            [
                'group' => 'social',
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/siberugo',
                'type' => 'string',
            ],
            [
                'group' => 'social',
                'key' => 'social_youtube',
                'value' => 'https://youtube.com/siberugo',
                'type' => 'string',
            ],

            // SEO Default
            [
                'group' => 'seo',
                'key' => 'meta_title_default',
                'value' => 'SIBERUGO - Portal Informasi & Pelayanan Publik',
                'type' => 'string',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_description_default',
                'value' => 'Portal resmi SIBERUGO untuk mempermudah akses informasi, berita, dan layanan publik bagi warga secara digital.',
                'type' => 'string',
            ],
            [
                'group' => 'seo',
                'key' => 'meta_keywords_default',
                'value' => 'siberugo, pelayanan publik, portal desa, informasi',
                'type' => 'string',
            ],
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
