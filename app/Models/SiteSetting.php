<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    protected static function booted(): void
    {
        static::saved(fn (): bool => Cache::forget('site_settings'));
        static::deleted(fn (): bool => Cache::forget('site_settings'));
    }
}
