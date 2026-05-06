<?php

namespace App\Observers;

use App\Models\AdminUser;
use App\Support\AuditLogger;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->write('created', $model, null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $this->write('updated', $model, $model->getOriginal(), $model->getChanges());

        if (! $model instanceof AdminUser) {
            return;
        }

        if ($model->wasChanged('password_hash')) {
            $this->write('password_changed', $model, null, ['email' => $model->email]);
        }

        if ($model->wasChanged('role_id')) {
            $this->write('role_changed', $model, [
                'role_id' => $model->getOriginal('role_id'),
            ], [
                'role_id' => $model->role_id,
            ]);
        }

        if ($model->wasChanged('is_active')) {
            $this->write($model->is_active ? 'reactivated' : 'deactivated', $model, [
                'is_active' => $model->getOriginal('is_active'),
            ], [
                'is_active' => $model->is_active,
            ]);
        }

        if ($model->wasChanged('app_authentication_secret')) {
            $oldEnabled = filled($model->getOriginal('app_authentication_secret'));
            $newEnabled = filled($model->app_authentication_secret);

            $this->write(match (true) {
                ! $oldEnabled && $newEnabled => '2fa_enabled',
                $oldEnabled && ! $newEnabled => '2fa_disabled',
                default => '2fa_secret_rotated',
            }, $model, null, ['email' => $model->email]);
        }

        if ($model->wasChanged('app_authentication_recovery_codes') && ! $model->wasChanged('app_authentication_secret')) {
            $this->write('2fa_recovery_regenerated', $model, null, ['email' => $model->email]);
        }
    }

    public function deleted(Model $model): void
    {
        $this->write('deleted', $model, $model->getOriginal(), null);
    }

    private function write(string $action, Model $model, ?array $oldData, ?array $newData): void
    {
        if (! auth()->guard('admin')->check()) {
            return;
        }

        AuditLogger::write($action, $model->getTable(), $model->getKey(), $oldData, $newData);
    }
}
