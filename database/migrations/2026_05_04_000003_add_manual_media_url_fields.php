<?php

use App\Support\MediaUrl;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_users', function (Blueprint $table): void {
            if (! Schema::hasColumn('admin_users', 'avatar_url')) {
                $table->string('avatar_url', 2048)->nullable()->after('avatar_path');
            }
        });

        Schema::table('media_assets', function (Blueprint $table): void {
            if (! Schema::hasColumn('media_assets', 'file_url')) {
                $table->string('file_url', 2048)->nullable()->after('file_path');
            }

            $table->string('file_path')->nullable()->change();
        });

        $this->moveRemotePathToUrl('agendas', 'cover_image_path', 'cover_image_url');
        $this->moveRemotePathToUrl('admin_users', 'avatar_path', 'avatar_url');
        $this->moveRemotePathToUrl('media_assets', 'file_path', 'file_url');

        $this->normalizeStoragePrefix('agendas', 'cover_image_path');
        $this->normalizeStoragePrefix('admin_users', 'avatar_path');
        $this->normalizeStoragePrefix('media_assets', 'file_path');
    }

    public function down(): void
    {
        Schema::table('media_assets', function (Blueprint $table): void {
            if (Schema::hasColumn('media_assets', 'file_url')) {
                $table->dropColumn('file_url');
            }

            $table->string('file_path')->nullable(false)->change();
        });

        Schema::table('admin_users', function (Blueprint $table): void {
            if (Schema::hasColumn('admin_users', 'avatar_url')) {
                $table->dropColumn('avatar_url');
            }
        });
    }

    private function moveRemotePathToUrl(string $table, string $pathField, string $urlField): void
    {
        if (! Schema::hasColumn($table, $pathField) || ! Schema::hasColumn($table, $urlField)) {
            return;
        }

        DB::table($table)
            ->where(function ($query) use ($pathField): void {
                $query
                    ->where($pathField, 'like', 'http://%')
                    ->orWhere($pathField, 'like', 'https://%');
            })
            ->where(function ($query) use ($urlField): void {
                $query->whereNull($urlField)->orWhere($urlField, '');
            })
            ->orderBy('id')
            ->get(['id', $pathField])
            ->each(function (object $record) use ($table, $pathField, $urlField): void {
                DB::table($table)
                    ->where('id', $record->id)
                    ->update([
                        $urlField => $record->{$pathField},
                        $pathField => null,
                    ]);
            });
    }

    private function normalizeStoragePrefix(string $table, string $pathField): void
    {
        if (! Schema::hasColumn($table, $pathField)) {
            return;
        }

        DB::table($table)
            ->where(function ($query) use ($pathField): void {
                $query
                    ->where($pathField, 'like', '/storage/%')
                    ->orWhere($pathField, 'like', 'storage/%');
            })
            ->orderBy('id')
            ->get(['id', $pathField])
            ->each(function (object $record) use ($table, $pathField): void {
                DB::table($table)
                    ->where('id', $record->id)
                    ->update([$pathField => MediaUrl::normalizePath($record->{$pathField})]);
            });
    }
};
