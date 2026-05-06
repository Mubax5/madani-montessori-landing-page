<?php

use App\Support\PhoneNumber;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table): void {
            if (! Schema::hasColumn('leads', 'whatsapp_normalized')) {
                $table->string('whatsapp_normalized', 30)->nullable()->after('whatsapp_number')->index();
            }
        });

        Schema::table('agenda_registrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('agenda_registrations', 'whatsapp_normalized')) {
                $table->string('whatsapp_normalized', 30)->nullable()->after('whatsapp_number')->index();
            }
        });

        $this->backfill('leads');
        $this->backfill('agenda_registrations');
    }

    public function down(): void
    {
        Schema::table('agenda_registrations', function (Blueprint $table): void {
            if (Schema::hasColumn('agenda_registrations', 'whatsapp_normalized')) {
                $table->dropColumn('whatsapp_normalized');
            }
        });

        Schema::table('leads', function (Blueprint $table): void {
            if (Schema::hasColumn('leads', 'whatsapp_normalized')) {
                $table->dropColumn('whatsapp_normalized');
            }
        });
    }

    private function backfill(string $table): void
    {
        DB::table($table)
            ->whereNull('whatsapp_normalized')
            ->orderBy('id')
            ->get(['id', 'whatsapp_number'])
            ->each(function (object $record) use ($table): void {
                DB::table($table)
                    ->where('id', $record->id)
                    ->update(['whatsapp_normalized' => PhoneNumber::normalizeIndonesianWhatsapp($record->whatsapp_number)]);
            });
    }
};
