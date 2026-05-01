<?php

namespace App\Filament\Resources\Concerns;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait AdminResourceAccess
{
    protected static function adminUser(): ?AdminUser
    {
        $user = Auth::guard('admin')->user();

        return $user instanceof AdminUser ? $user : null;
    }

    protected static function permissionScope(): string
    {
        return property_exists(static::class, 'permissionScope')
            ? static::$permissionScope
            : 'content';
    }

    protected static function canManageResource(): bool
    {
        $user = static::adminUser();

        if (! $user) {
            return false;
        }

        return match (static::permissionScope()) {
            'admin' => $user->isSuperAdmin(),
            'audit' => $user->isSuperAdmin(),
            'settings' => $user->canManageSettings(),
            'leads' => $user->canUpdateLeads(),
            default => $user->canManageContent(),
        };
    }

    public static function canAccess(): bool
    {
        $user = static::adminUser();

        if (! $user) {
            return false;
        }

        return match (static::permissionScope()) {
            'admin', 'audit' => $user->isSuperAdmin(),
            'leads' => $user->canViewLeads(),
            'settings' => $user->canManageSettings(),
            default => $user->canManageContent(),
        };
    }

    public static function canViewAny(): bool
    {
        return static::canAccess();
    }

    public static function canView(Model $record): bool
    {
        return static::canAccess();
    }

    public static function canCreate(): bool
    {
        return static::canManageResource();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canManageResource();
    }

    public static function canDelete(Model $record): bool
    {
        return static::canDeleteAny();
    }

    public static function canDeleteAny(): bool
    {
        $user = static::adminUser();

        if (! $user) {
            return false;
        }

        if (static::permissionScope() === 'leads') {
            return $user->isSuperAdmin();
        }

        return static::canManageResource() && static::permissionScope() !== 'audit';
    }
}
