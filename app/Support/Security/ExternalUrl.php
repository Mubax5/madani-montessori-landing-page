<?php

namespace App\Support\Security;

use Illuminate\Support\Str;

class ExternalUrl
{
    public static function isAllowed(?string $url): bool
    {
        $url = is_string($url) ? trim($url) : '';

        if ($url === '' || ! Str::startsWith($url, 'https://') || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $allowedHosts = config('security.allowed_external_hosts', []);

        if (in_array('*', $allowedHosts, true)) {
            return true;
        }

        $host = strtolower((string) parse_url($url, PHP_URL_HOST));

        foreach ($allowedHosts as $allowedHost) {
            if ($host === $allowedHost || Str::endsWith($host, '.'.$allowedHost)) {
                return true;
            }
        }

        return false;
    }

    public static function isInternalOrAllowed(?string $url): bool
    {
        $url = is_string($url) ? trim($url) : '';

        if ($url === '') {
            return true;
        }

        if (Str::startsWith($url, ['/', '#'])) {
            return ! Str::startsWith($url, ['//', '/\\']);
        }

        if (preg_match('/^[A-Za-z0-9_-]+$/', $url) === 1) {
            return true;
        }

        return self::isAllowed($url);
    }

    public static function normalizeAllowed(?string $url): ?string
    {
        $url = is_string($url) ? trim($url) : null;

        return self::isAllowed($url) ? $url : null;
    }

    public static function normalizeInternalOrAllowed(?string $url): ?string
    {
        $url = is_string($url) ? trim($url) : null;

        return self::isInternalOrAllowed($url) ? ($url ?: null) : null;
    }

    public static function looksLikeExternalUrl(?string $value): bool
    {
        $value = is_string($value) ? trim($value) : '';

        return Str::startsWith($value, ['http://', 'https://', '//']);
    }
}
