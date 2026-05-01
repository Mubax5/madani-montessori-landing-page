<?php

namespace App\Support;

use App\Models\SiteSetting;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SiteContent
{
    public static function settings(): Collection
    {
        return Cache::remember('site_settings', 60, fn () => SiteSetting::query()
            ->pluck('setting_value', 'setting_key'));
    }

    public static function setting(string $key, ?string $default = null): ?string
    {
        return self::settings()->get($key, $default);
    }

    public static function whatsappUrl(string $templateKey = 'konsultasi_umum', array $replacements = []): string
    {
        $phone = preg_replace('/\D+/', '', self::setting('whatsapp_number', '6282123576275'));
        $template = WhatsappTemplate::query()
            ->where('template_key', $templateKey)
            ->where('is_active', true)
            ->value('message')
            ?: 'Assalamualaikum, saya ingin konsultasi pendaftaran Madani Montessori Islamic School.';

        foreach ($replacements as $key => $value) {
            $template = str_replace('{' . $key . '}', (string) $value, $template);
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($template);
    }
}
