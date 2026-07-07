<?php

use App\Models\CmsSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
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
        ];

        foreach ($settings as $setting) {
            CmsSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        CmsSetting::whereIn('key', [
            'head_office_name',
            'head_office_title',
            'head_office_photo',
        ])->delete();
    }
};
