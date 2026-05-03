<?php

namespace App\Models;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaAsset extends Model
{
    protected $fillable = [
        'uploaded_by',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'alt_text',
        'caption',
    ];

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(AdminUser::class, 'uploaded_by');
    }

    public function galleryItems(): HasMany
    {
        return $this->hasMany(GalleryItem::class, 'media_id');
    }

    public function getUrlAttribute(): string
    {
        if (Str::startsWith($this->file_path, ['http://', 'https://', '/'])) {
            return $this->file_path;
        }

        $disk = Storage::disk(config('filesystems.default', 'public'));

        return $disk instanceof FilesystemAdapter ? $disk->url($this->file_path) : $this->file_path;
    }

    protected static function booted(): void
    {
        static::saving(function (MediaAsset $asset): void {
            if ($asset->file_path) {
                $path = parse_url($asset->file_path, PHP_URL_PATH) ?: $asset->file_path;
                $fileName = basename($path) ?: 'media';

                if ($asset->isDirty('file_path')) {
                    $asset->file_name = $fileName;
                    $asset->mime_type = null;
                    $asset->file_size = null;
                } else {
                    $asset->file_name = $asset->file_name ?: $fileName;
                }

                if (Str::startsWith($asset->file_path, ['http://', 'https://'])) {
                    $asset->mime_type = $asset->mime_type ?: 'image/remote';

                    return;
                }

                $disk = Storage::disk(config('filesystems.default', 'public'));

                if (! $asset->mime_type && $disk->exists($asset->file_path)) {
                    $asset->mime_type = $disk->mimeType($asset->file_path) ?: 'application/octet-stream';
                }

                if (! $asset->file_size && $disk->exists($asset->file_path)) {
                    $asset->file_size = $disk->size($asset->file_path);
                }
            }

            $asset->mime_type = $asset->mime_type ?: 'application/octet-stream';
            $asset->file_name = $asset->file_name ?: 'media';
        });
    }
}
