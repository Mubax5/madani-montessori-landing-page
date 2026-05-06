<?php

namespace App\Support\Security;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use RuntimeException;

class AdminPassword
{
    public static function rules(): array
    {
        $minLength = (int) config('security.admin_password_min_length', 16);

        return [
            'nullable',
            'string',
            'min:'.$minLength,
            'max:72',
            Password::min($minLength)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
        ];
    }

    public static function assertValid(string $password): void
    {
        $validator = Validator::make([
            'password' => $password,
        ], [
            'password' => ['required', ...self::rules()],
        ]);

        if ($validator->fails()) {
            throw new RuntimeException('ADMIN_INITIAL_PASSWORD must be at least 16 characters, include mixed case, numbers, symbols, and be no longer than 72 characters.');
        }
    }
}
