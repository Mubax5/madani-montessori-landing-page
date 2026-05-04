<?php

namespace App\Console\Commands;

use App\Models\AdminUser;
use App\Models\Agenda;
use App\Models\MediaAsset;
use App\Models\SiteSetting;
use App\Support\MediaUrl;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuditMediaPathsCommand extends Command
{
    protected $signature = 'media:audit-paths {--fix : Apply safe fixes for URL and /storage/ path variants}';

    protected $description = 'Audit media path fields for livewire temporary paths, raw URLs, storage prefixes, and missing files.';

    public function handle(): int
    {
        $fix = (bool) $this->option('fix');
        $rows = [];

        foreach ($this->targets() as $target) {
            $rows = array_merge($rows, $this->auditTarget($target, $fix));
        }

        if ($rows === []) {
            $this->info('No suspicious media paths found.');

            return self::SUCCESS;
        }

        $this->table(['Model', 'ID', 'Field', 'Value', 'Issues', 'Fix'], $rows);

        if (! $fix) {
            $this->comment('Run with --fix to move full URLs into manual URL fields and strip /storage/ prefixes.');
        }

        return self::SUCCESS;
    }

    /**
     * @return array<int, array{model: class-string<Model>, path: string, manual?: string, label: string, where?: callable(Builder): Builder}>
     */
    private function targets(): array
    {
        return [
            [
                'model' => Agenda::class,
                'label' => 'Agenda cover',
                'path' => 'cover_image_path',
                'manual' => 'cover_image_url',
            ],
            [
                'model' => MediaAsset::class,
                'label' => 'Media asset',
                'path' => 'file_path',
                'manual' => 'file_url',
            ],
            [
                'model' => AdminUser::class,
                'label' => 'Admin avatar',
                'path' => 'avatar_path',
                'manual' => 'avatar_url',
            ],
            [
                'model' => SiteSetting::class,
                'label' => 'Site setting image',
                'path' => 'setting_value',
                'where' => fn (Builder $query): Builder => $query->where('setting_type', 'image'),
            ],
        ];
    }

    /**
     * @param  array{model: class-string<Model>, path: string, manual?: string, label: string, where?: callable(Builder): Builder}  $target
     * @return array<int, array<int, string>>
     */
    private function auditTarget(array $target, bool $fix): array
    {
        /** @var Model $model */
        $model = new $target['model'];
        $table = $model->getTable();
        $pathField = $target['path'];
        $manualField = $target['manual'] ?? null;

        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $pathField)) {
            return [];
        }

        $query = $target['model']::query()->whereNotNull($pathField)->where($pathField, '!=', '');

        if (isset($target['where'])) {
            $query = $target['where']($query);
        }

        return $query
            ->orderBy($model->getKeyName())
            ->get()
            ->flatMap(function (Model $record) use ($target, $pathField, $manualField, $fix): array {
                $value = trim((string) $record->getAttribute($pathField));
                $issues = $this->issuesFor($value);

                if ($issues === []) {
                    return [];
                }

                $fixResult = $fix ? $this->fixRecord($record, $pathField, $manualField, $value) : 'not run';

                return [[
                    $target['label'],
                    (string) $record->getKey(),
                    $pathField,
                    Str::limit($value, 80),
                    implode(', ', $issues),
                    $fixResult,
                ]];
            })
            ->all();
    }

    /**
     * @return array<int, string>
     */
    private function issuesFor(string $value): array
    {
        $issues = [];
        $normalized = MediaUrl::normalizePath($value);

        if (MediaUrl::isTemporaryPath($value)) {
            $issues[] = 'livewire temporary path';
        }

        if (MediaUrl::isRemoteUrl($value)) {
            $issues[] = 'full URL stored in path field';
        }

        if (Str::startsWith($value, ['/storage/', 'storage/'])) {
            $issues[] = 'storage prefix stored in path field';
        }

        if (
            $normalized
            && ! MediaUrl::isRemoteUrl($normalized)
            && ! MediaUrl::isTemporaryPath($normalized)
            && ! MediaUrl::publicAssetExists($normalized)
            && ! $this->storageFileExists($normalized)
        ) {
            $issues[] = 'file not found on configured disk';
        }

        return $issues;
    }

    private function fixRecord(Model $record, string $pathField, ?string $manualField, string $value): string
    {
        if (MediaUrl::isTemporaryPath($value)) {
            return 'skipped: re-upload required';
        }

        if (MediaUrl::isRemoteUrl($value)) {
            if (! $manualField || ! Schema::hasColumn($record->getTable(), $manualField)) {
                return 'skipped: no manual URL field';
            }

            $manualValue = $record->getAttribute($manualField);

            if (filled($manualValue) && $manualValue !== $value) {
                return 'skipped: manual URL already filled';
            }

            $record->forceFill([
                $manualField => $value,
                $pathField => null,
            ])->save();

            return "moved to {$manualField}";
        }

        if (Str::startsWith($value, ['/storage/', 'storage/'])) {
            $record->forceFill([$pathField => MediaUrl::normalizePath($value)])->save();

            return 'normalized storage prefix';
        }

        return 'skipped: inspect manually';
    }

    private function storageFileExists(string $path): bool
    {
        try {
            return Storage::disk(MediaUrl::defaultDisk())->exists($path);
        } catch (\Throwable) {
            return false;
        }
    }
}
