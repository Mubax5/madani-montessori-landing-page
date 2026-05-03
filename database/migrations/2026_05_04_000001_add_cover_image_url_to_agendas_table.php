<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table): void {
            if (! Schema::hasColumn('agendas', 'cover_image_url')) {
                $table->string('cover_image_url', 2048)->nullable()->after('cover_image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table): void {
            if (Schema::hasColumn('agendas', 'cover_image_url')) {
                $table->dropColumn('cover_image_url');
            }
        });
    }
};
