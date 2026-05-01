<?php

namespace App\Models;

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

        return Storage::disk('public')->url($this->file_path);
    }

    protected static function booted(): void
    {
        static::saving(function (MediaAsset $asset): void {
            if ($asset->file_path) {
                $asset->file_name = $asset->file_name ?: basename($asset->file_path);

                if (! $asset->mime_type && Storage::disk('public')->exists($asset->file_path)) {
                    $asset->mime_type = Storage::disk('public')->mimeType($asset->file_path) ?: 'application/octet-stream';
                }

                if (! $asset->file_size && Storage::disk('public')->exists($asset->file_path)) {
                    $asset->file_size = Storage::disk('public')->size($asset->file_path);
                }
            }

            $asset->mime_type = $asset->mime_type ?: 'application/octet-stream';
            $asset->file_name = $asset->file_name ?: 'media';
        });
    }
}
