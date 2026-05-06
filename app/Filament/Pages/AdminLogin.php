<?php

namespace App\Filament\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        $key = $this->emailIpRateLimitKey($data);

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($key);

            $this->getRateLimitedNotification(new TooManyRequestsException(
                static::class,
                'authenticate',
                request()->ip(),
                $seconds,
            ))?->send();

            return null;
        }

        try {
            $response = parent::authenticate();
        } catch (ValidationException $exception) {
            RateLimiter::hit($key, decaySeconds: 300);

            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::auth/pages/login.messages.failed'),
            ]);
        }

        if ($response instanceof LoginResponse) {
            RateLimiter::clear($key);
        }

        return $response;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function emailIpRateLimitKey(array $data): string
    {
        $email = Str::lower(trim((string) ($data['email'] ?? '')));

        return 'admin-login:'.(request()->ip() ?: 'unknown').':'.hash('sha256', $email);
    }
}
