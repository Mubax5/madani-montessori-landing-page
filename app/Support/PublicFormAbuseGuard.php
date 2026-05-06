<?php

namespace App\Support;

use App\Models\AgendaRegistration;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class PublicFormAbuseGuard
{
    public static function honeypotField(): string
    {
        return (string) config('security.public_forms.honeypot_field', 'website');
    }

    public static function hasHoneypotValue(Request $request): bool
    {
        return filled($request->input(self::honeypotField()));
    }

    public static function ensureLeadAllowed(Request $request, string $normalizedPhone): void
    {
        self::ensureRateLimitAllowed($request, 'lead', $normalizedPhone);
        self::ensureDatabaseLimitAllowed(
            Lead::query()->where('whatsapp_normalized', $normalizedPhone)->where('created_at', '>=', now()->subHour())->count(),
        );
    }

    public static function ensureAgendaRegistrationAllowed(Request $request, string $normalizedPhone): void
    {
        self::ensureRateLimitAllowed($request, 'agenda', $normalizedPhone);
        self::ensureDatabaseLimitAllowed(
            AgendaRegistration::query()->where('whatsapp_normalized', $normalizedPhone)->where('created_at', '>=', now()->subHour())->count(),
        );
    }

    public static function hit(Request $request, string $form, string $normalizedPhone): void
    {
        RateLimiter::hit(self::rateLimitKey($request, $form, $normalizedPhone), self::decaySeconds());
    }

    private static function ensureRateLimitAllowed(Request $request, string $form, string $normalizedPhone): void
    {
        $key = self::rateLimitKey($request, $form, $normalizedPhone);

        if (RateLimiter::tooManyAttempts($key, self::maxAttempts())) {
            throw new TooManyRequestsHttpException(RateLimiter::availableIn($key), 'Terlalu banyak percobaan.');
        }
    }

    private static function ensureDatabaseLimitAllowed(int $existingSubmissions): void
    {
        if ($existingSubmissions >= self::maxAttempts()) {
            throw new TooManyRequestsHttpException(self::decaySeconds(), 'Terlalu banyak percobaan.');
        }
    }

    private static function rateLimitKey(Request $request, string $form, string $normalizedPhone): string
    {
        return 'public-form:'.$form.':'.($request->ip() ?: 'unknown').':'.hash('sha256', $normalizedPhone);
    }

    private static function maxAttempts(): int
    {
        return max(1, (int) config('security.public_forms.max_submissions_per_number_per_hour', 3));
    }

    private static function decaySeconds(): int
    {
        return max(60, (int) config('security.public_forms.rate_limit_decay_seconds', 3600));
    }
}
