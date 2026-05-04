<?php

namespace App\Models;

use App\Models\Concerns\HasFileUrls;
use App\Support\MediaUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaAsset extends Model
{
    use HasFileUrls;

    protected $fillable = [
        'uploaded_by',
        'file_name',
        'file_path',
        'file_url',
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

    protected function fileFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->file_path,
            manualUrl: $this->file_url,
        ));
    }

    protected function imageFinalUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->isImage()) {
                return $this->file_final_url;
            }

            return null;
        });
    }

    protected function url(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->file_final_url);
    }

    public function isImage(): bool
    {
        if (Str::startsWith((string) $this->mime_type, 'image/')) {
            return true;
        }

        return in_array(Str::lower(pathinfo((string) ($this->file_path ?: $this->file_url), PATHINFO_EXTENSION)), [
            'jpg',
            'jpeg',
            'png',
            'webp',
        ], true);
    }

    protected static function booted(): void
    {
        static::saving(function (MediaAsset $asset): void {
            $asset->file_url = MediaUrl::normalizeManualUrl($asset->file_url);

            if (MediaUrl::isRemoteUrl($asset->file_path)) {
                $asset->file_url ??= $asset->file_path;
                $asset->file_path = null;
            } else {
                $asset->file_path = MediaUrl::isTemporaryPath($asset->file_path)
                    ? null
                    : MediaUrl::normalizePath($asset->file_path);
            }

            $source = $asset->file_path ?: $asset->file_url;

            if ($source) {
                $path = parse_url($source, PHP_URL_PATH) ?: $source;
                $fileName = basename($path) ?: 'media';

                if ($asset->isDirty('file_path') || $asset->isDirty('file_url')) {
                    $asset->file_name = $fileName;
                    $asset->mime_type = null;
                    $asset->file_size = null;
                } else {
                    $asset->file_name = $asset->file_name ?: $fileName;
                }

                if ($asset->file_url) {
                    $asset->mime_type = $asset->mime_type ?: 'image/remote';

                    return;
                }

                $disk = Storage::disk(MediaUrl::defaultDisk());

                try {
                    if (! $asset->mime_type && $disk->exists($asset->file_path)) {
                        $asset->mime_type = $disk->mimeType($asset->file_path) ?: 'application/octet-stream';
                    }

                    if (! $asset->file_size && $disk->exists($asset->file_path)) {
                        $asset->file_size = $disk->size($asset->file_path);
                    }
                } catch (\Throwable) {
                    $asset->mime_type = $asset->mime_type ?: 'application/octet-stream';
                }
            }

            $asset->mime_type = $asset->mime_type ?: 'application/octet-stream';
            $asset->file_name = $asset->file_name ?: 'media';
        });
    }
}
