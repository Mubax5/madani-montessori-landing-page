<?php

namespace App\Support;

class PhoneNumber
{
    public static function normalizeIndonesianWhatsapp(?string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (! $digits) {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        return $digits;
    }
}
