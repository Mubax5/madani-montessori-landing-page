<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const MAPS_URL = 'https://www.google.com/maps/place/National+Education+Centre/@-6.3526697,106.6283245,17z/data=!3m1!4b1!4m6!3m5!1s0x2e69e3874490ef33:0x64684f18b41459a0!8m2!3d-6.3526697!4d106.6283245!16s%2Fg%2F11c47z02h_?hl=en&entry=ttu&g_ep=EgoyMDI2MDQyOS4wIKXMDSoASAFQAw%3D%3D';

    private const MAPS_EMBED_URL = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3194840022015!2d106.6283245!3d-6.352669700000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e3874490ef33%3A0x64684f18b41459a0!2sNational%20Education%20Centre!5e0!3m2!1sen!2sid!4v1777834803428!5m2!1sen!2sid';

    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('site_settings')->updateOrInsert(
            ['setting_key' => 'maps_url'],
            [
                'setting_value' => self::MAPS_URL,
                'setting_type' => 'url',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('site_settings')->updateOrInsert(
            ['setting_key' => 'maps_embed_url'],
            [
                'setting_value' => self::MAPS_EMBED_URL,
                'setting_type' => 'url',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        //
    }
};
