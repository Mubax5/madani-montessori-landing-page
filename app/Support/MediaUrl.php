<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUrl
{
    public static function resolve(?string $path = null, ?string $manualUrl = null, ?string $disk = null): ?string
    {
        $manualUrl = self::normalizeManualUrl($manualUrl);

        if ($manualUrl) {
            return $manualUrl;
        }

        $path = self::normalizePath($path);

        if (! $path || self::isTemporaryPath($path)) {
            return null;
        }

        if (self::isRemoteUrl($path)) {
            return $path;
        }

        if (self::publicAssetExists($path)) {
            return asset($path);
        }

        try {
            return Storage::disk($disk ?: self::defaultDisk())->url($path);
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    public static function defaultDisk(): string
    {
        return (string) config('filesystems.uploads_disk', config('filesystems.default', 'public'));
    }

    public static function normalizeManualUrl(?string $url): ?string
    {
        $url = is_string($url) ? trim($url) : null;

        if (! $url) {
            return null;
        }

        return self::isRemoteUrl($url) ? $url : null;
    }

    public static function normalizePath(?string $path): ?string
    {
        $path = is_string($path) ? trim(str_replace('\\', '/', $path)) : null;

        if (! $path) {
            return null;
        }

        if (self::isRemoteUrl($path)) {
            return $path;
        }

        $path = preg_replace('#^/?storage/#', '', $path) ?: $path;
        $path = ltrim($path, '/');
        $path = preg_replace('#/+#', '/', $path) ?: $path;

        return $path ?: null;
    }

    public static function isRemoteUrl(?string $value): bool
    {
        $value = is_string($value) ? trim($value) : '';

        return Str::startsWith($value, ['http://', 'https://'])
            && filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    public static function isTemporaryPath(?string $path): bool
    {
        $path = self::normalizePath($path);

        return $path ? Str::startsWith($path, 'livewire-tmp/') : false;
    }

    public static function publicAssetExists(?string $path): bool
    {
        $path = self::normalizePath($path);

        return $path
            && ! self::isRemoteUrl($path)
            && ! self::isTemporaryPath($path)
            && is_file(public_path($path));
    }

    public static function placeholderDataUri(string $label = 'Madani'): string
    {
        $safeLabel = e(Str::limit($label, 18, ''));
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160" role="img" aria-label="{$safeLabel}">
  <rect width="160" height="160" rx="28" fill="#fff7e6"/>
  <circle cx="46" cy="42" r="28" fill="#f5c542" fill-opacity=".45"/>
  <path d="M28 112c20-28 34-34 50-18 10 10 18 12 30 0 10-10 18-12 24-6 6 6 12 14 18 24v20H28z" fill="#0a1f5c" fill-opacity=".88"/>
  <rect x="42" y="128" width="76" height="8" rx="4" fill="#f5c542"/>
</svg>
SVG;

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
