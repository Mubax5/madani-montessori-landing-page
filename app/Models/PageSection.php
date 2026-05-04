<?php

namespace App\Models;

use App\Models\Concerns\HasFileUrls;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSection extends Model
{
    use HasFileUrls;

    protected $fillable = [
        'page_id',
        'image_id',
        'section_key',
        'section_name',
        'heading',
        'subheading',
        'body',
        'payload',
        'cta_label',
        'cta_url',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'image_id');
    }

    protected function imageFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->attributes['image_path'] ?? null,
            manualUrl: $this->attributes['image_url'] ?? null,
        ) ?: $this->image?->image_final_url);
    }

    protected function backgroundImageFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->resolveFileUrl(
            path: $this->attributes['background_image_path'] ?? ($this->payload['background_image_path'] ?? null),
            manualUrl: $this->attributes['background_image_url'] ?? ($this->payload['background_image_url'] ?? null),
        ) ?: $this->image_final_url);
    }
}
