<?php

namespace App\Models;

use App\Models\Concerns\HasFileUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryItem extends Model
{
    use HasFileUrls;

    protected $fillable = [
        'media_id',
        'category',
        'title',
        'description',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_id');
    }

    protected function imageFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->attributes['image_path'] ?? null,
            manualUrl: $this->attributes['image_url'] ?? null,
        ) ?: $this->media?->image_final_url);
    }

    protected function thumbnailFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->attributes['thumbnail_path'] ?? null,
            manualUrl: $this->attributes['thumbnail_url'] ?? null,
        ) ?: $this->image_final_url);
    }

    protected function mediaFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->image_final_url);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
