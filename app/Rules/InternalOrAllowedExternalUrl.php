<?php

namespace App\Rules;

use App\Support\Security\ExternalUrl;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InternalOrAllowedExternalUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        if (! ExternalUrl::isInternalOrAllowed((string) $value)) {
            $fail('URL harus berupa path internal atau HTTPS ke host yang diizinkan.');
        }
    }
}
