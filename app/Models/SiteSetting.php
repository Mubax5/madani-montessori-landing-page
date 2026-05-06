<?php

namespace App\Models;

use App\Models\Concerns\HasFileUrls;
use App\Support\Security\ExternalUrl;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    use HasFileUrls;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    protected static function booted(): void
    {
        static::saving(function (SiteSetting $setting): void {
            if ($setting->setting_type === 'url') {
                $setting->setting_value = ExternalUrl::normalizeInternalOrAllowed($setting->setting_value);
            }
        });

        static::saved(fn (): bool => Cache::forget('site_settings'));
        static::deleted(fn (): bool => Cache::forget('site_settings'));
    }

    protected function settingValueFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->setting_type === 'image'
            ? $this->resolveFileUrl(path: $this->setting_value, manualUrl: $this->setting_value)
            : null);
    }

    protected function imageFinalUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->setting_value_final_url);
    }
}
