<?php

namespace App\Support;

use App\Models\AdminUser;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditLogger
{
    private const SENSITIVE_KEY_PARTS = [
        'password',
        'token',
        'secret',
        'recovery',
        'remember',
        'session',
        'app_key',
    ];

    public static function write(
        string $action,
        string $module,
        int|string|null $recordId = null,
        ?array $oldData = null,
        ?array $newData = null,
        ?AdminUser $actor = null,
    ): void {
        $actor ??= Auth::guard('admin')->user();

        AuditLog::create([
            'admin_user_id' => $actor instanceof AdminUser ? $actor->id : null,
            'action' => $action,
            'module' => $module,
            'record_id' => is_numeric($recordId) ? (int) $recordId : null,
            'old_data' => self::sanitize($oldData),
            'new_data' => self::sanitize($newData),
            'ip_address' => app()->bound('request') ? request()->ip() : null,
            'user_agent' => app()->bound('request') ? substr((string) request()->userAgent(), 0, 2000) : null,
            'created_at' => now(),
        ]);
    }

    public static function sanitize(?array $data): ?array
    {
        if ($data === null) {
            return null;
        }

        $sanitized = [];

        foreach ($data as $key => $value) {
            $keyString = (string) $key;

            if (self::isSensitiveKey($keyString)) {
                $sanitized[$keyString] = '[redacted]';

                continue;
            }

            $sanitized[$keyString] = is_array($value) ? self::sanitize($value) : $value;
        }

        return $sanitized;
    }

    private static function isSensitiveKey(string $key): bool
    {
        $key = Str::lower($key);

        foreach (self::SENSITIVE_KEY_PARTS as $part) {
            if (str_contains($key, $part)) {
                return true;
            }
        }

        return false;
    }
}
