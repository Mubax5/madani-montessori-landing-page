<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'admin_users';

    protected $authPasswordName = 'password_hash';

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password_hash',
        'avatar_path',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->is_active;
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }

    public function hasAnyRole(array $slugs): bool
    {
        return in_array($this->role?->slug, $slugs, true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function canManageContent(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_konten']);
    }

    public function canViewLeads(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_konten', 'admin_pendaftaran', 'viewer']);
    }

    public function canUpdateLeads(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_pendaftaran']);
    }

    public function canManageSettings(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin_konten']);
    }
}
