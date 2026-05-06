<?php

namespace App\Rules;

use App\Support\Security\ExternalUrl;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedExternalUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        if (! ExternalUrl::isAllowed((string) $value)) {
            $fail('URL harus memakai HTTPS dan host yang diizinkan.');
        }
    }
}
